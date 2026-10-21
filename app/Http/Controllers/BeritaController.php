<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\BeritaFoto;
use App\Models\Desa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(): View
    {
        $berita = Berita::query()
            ->published()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return view('berita.index', compact('berita'));
    }

    public function adminIndex(Request $request): View
    {
        Gate::authorize('viewAny', Berita::class);

        $user = $request->user();
        $query = Berita::query()->with('user')->withCount('galeri');

        if (! $user->isSuperAdmin()) {
            $query->where('user_id', $user->id);
        }

        $berita = $query->latest()->get();

        $ringkasanStatus = [
            'total' => $berita->count(),
            'draft' => $berita->where('status', Berita::STATUS_DRAFT)->count(),
            'pending' => $berita->where('status', Berita::STATUS_PENDING)->count(),
            'published' => $berita->where('status', Berita::STATUS_PUBLISHED)->count(),
            'rejected' => $berita->where('status', Berita::STATUS_REJECTED)->count(),
        ];

        $beritaMenunggu = $user->isSuperAdmin()
            ? Berita::query()
                ->with('user')
                ->where('status', Berita::STATUS_PENDING)
                ->orderBy('submitted_at')
                ->latest()
                ->get()
            : collect();

        return view('admin.berita.index', compact('berita', 'ringkasanStatus', 'beritaMenunggu'));
    }

    public function create(): View
    {
        Gate::authorize('create', Berita::class);

        return view('berita.create', [
            'desas' => Desa::query()->orderBy('nama')->pluck('nama'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Berita::class);

        $validated = $request->validate($this->aturanValidasi(), $this->pesanValidasi());
        $this->pastikanJumlahGaleriValid($request);

        $data = $this->siapkanData($request, $validated);
        $data['user_id'] = $request->user()->id;

        if ($validated['status'] === Berita::STATUS_PENDING) {
            $data['submitted_at'] = now();
        }

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita = Berita::create($data);
        $this->simpanGaleri($request, $berita);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', $this->pesanBerhasil($berita));
    }

    public function show(Request $request, Berita $berita): View
    {
        if (! $berita->isPublished()) {
            $user = $request->user();

            abort_unless(
                $user && Gate::forUser($user)->allows('preview', $berita),
                404
            );
        }

        $berita->load([
            'galeri' => fn ($query) => $query->limit(Berita::MAX_GALERI),
            'user',
        ]);

        $beritaTerkait = Berita::query()
            ->published()
            ->whereKeyNot($berita->getKey())
            ->when(filled($berita->kategori), fn ($query) => $query->where('kategori', $berita->kategori))
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        if ($beritaTerkait->isEmpty() && filled($berita->kategori)) {
            $beritaTerkait = Berita::query()
                ->published()
                ->whereKeyNot($berita->getKey())
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->limit(3)
                ->get();
        }

        return view('berita.show', compact('berita', 'beritaTerkait'));
    }

    public function edit(Berita $berita): View
    {
        Gate::authorize('update', $berita);

        $berita->load('galeri');

        return view('berita.edit', [
            'berita' => $berita,
            'desas' => Desa::query()->orderBy('nama')->pluck('nama'),
        ]);
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        Gate::authorize('update', $berita);

        $validated = $request->validate(
            $this->aturanValidasi($berita),
            $this->pesanValidasi()
        );
        $this->pastikanJumlahGaleriValid($request, $berita);

        $data = $this->siapkanData($request, $validated, $berita);
        $data['user_id'] = $berita->user_id ?? $request->user()->id;

        if ($validated['status'] === Berita::STATUS_PENDING) {
            // Setiap revisi harus ditinjau ulang sebelum tampil publik.
            $data['submitted_at'] = now();
            $data['reviewed_at'] = null;
            $data['reviewed_by'] = null;
            $data['review_note'] = null;
            $data['published_at'] = null;
        } else {
            // Menyimpan sebagai draft berarti menarik berita dari publikasi.
            $data['submitted_at'] = null;
            $data['published_at'] = null;

            if (! $berita->isRejected()) {
                $data['review_note'] = null;
            }
        }

        if ($request->hasFile('gambar')) {
            $gambarBaru = $request->file('gambar')->store('berita', 'public');

            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }

            $data['gambar'] = $gambarBaru;
        }

        $berita->update($data);
        $this->simpanGaleri($request, $berita);

        $pesan = $berita->fresh()->isPending()
            ? 'Berita berhasil diajukan untuk review. Berita hanya tampil publik setelah disetujui super admin.'
            : $this->pesanBerhasil($berita);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', $pesan);
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        Gate::authorize('delete', $berita);

        $gambarSampul = $berita->gambar;
        $fotoGaleri = $berita->galeri()->pluck('path')->all();

        $berita->delete();

        if ($gambarSampul) {
            Storage::disk('public')->delete($gambarSampul);
        }

        Storage::disk('local')->delete($fotoGaleri);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    public function destroyFoto(Berita $berita, BeritaFoto $foto): RedirectResponse
    {
        Gate::authorize('update', $berita);

        abort_unless((int) $foto->berita_id === (int) $berita->id, 404);

        $path = $foto->path;
        $ditarikDariPublikasi = $berita->isPublished();

        $foto->delete();
        Storage::disk('local')->delete($path);
        $this->tarikDariPublikasi($berita, $ditarikDariPublikasi);

        return back()->with(
            'success',
            $ditarikDariPublikasi
                ? 'Foto galeri dihapus. Karena berita sudah terbit, berita ditarik dari publikasi dan menunggu review ulang.'
                : 'Foto galeri berhasil dihapus.'
        );
    }

    public function replaceFoto(Request $request, Berita $berita, BeritaFoto $foto): RedirectResponse
    {
        Gate::authorize('update', $berita);

        abort_unless((int) $foto->berita_id === (int) $berita->id, 404);

        $request->validate([
            'foto' => ['required', ...$this->aturanFotoGaleri()],
        ], [
            'foto.required' => 'Pilih foto pengganti terlebih dahulu.',
            'foto.image' => 'Berkas pengganti harus berupa gambar.',
            'foto.mimes' => 'Foto galeri harus berformat JPG, JPEG, PNG, atau WebP.',
            'foto.max' => 'Ukuran setiap foto galeri maksimal '.Berita::MAX_GALERI_SIZE_MB.' MB.',
        ]);

        $file = $request->file('foto');
        $pathLama = $foto->path;
        $pathBaru = $file->store('berita/galeri', 'local');

        if (! $pathBaru) {
            throw ValidationException::withMessages([
                'foto' => 'Foto galeri gagal disimpan. Silakan coba lagi.',
            ]);
        }

        $ditarikDariPublikasi = $berita->isPublished();

        try {
            DB::transaction(function () use ($berita, $file, $foto, $pathBaru, $ditarikDariPublikasi) {
                $foto->update([
                    'path' => $pathBaru,
                    'original_name' => Str::limit($file->getClientOriginalName(), 255, ''),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);

                $this->tarikDariPublikasi($berita, $ditarikDariPublikasi);
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($pathBaru);

            throw $exception;
        }

        if (filled($pathLama)) {
            Storage::disk('local')->delete($pathLama);
        }

        return back()->with(
            'success',
            $ditarikDariPublikasi
                ? 'Foto galeri berhasil diganti. Karena berita sudah terbit, berita ditarik dari publikasi dan menunggu review ulang.'
                : 'Foto galeri berhasil diganti.'
        );
    }

    public function galeri(Request $request, Berita $berita, BeritaFoto $foto)
    {
        abort_unless((int) $foto->berita_id === (int) $berita->id, 404);

        if (! $berita->isPublished()) {
            $user = $request->user();

            abort_unless(
                $user && Gate::forUser($user)->allows('preview', $berita),
                404
            );
        }

        $disk = Storage::disk('local');

        abort_unless($disk->exists($foto->path), 404);

        return $disk->response($foto->path, $foto->original_name, [
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function review(Request $request, Berita $berita): RedirectResponse
    {
        Gate::authorize('publish', $berita);

        $validated = $request->validate([
            'action' => ['required', Rule::in(['approve', 'reject'])],
            'review_note' => ['nullable', 'string', 'max:1000', 'required_if:action,reject'],
        ], [
            'action.required' => 'Tindakan review wajib dipilih.',
            'action.in' => 'Tindakan review tidak valid.',
            'review_note.required_if' => 'Alasan penolakan wajib diisi.',
            'review_note.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        if ($validated['action'] === 'approve') {
            $berita->forceFill([
                'status' => Berita::STATUS_PUBLISHED,
                'review_note' => null,
                'reviewed_at' => now(),
                'reviewed_by' => $request->user()->id,
                'published_at' => now(),
            ])->save();

            return back()->with('success', 'Berita berhasil disetujui dan diterbitkan.');
        }

        $berita->forceFill([
            'status' => Berita::STATUS_REJECTED,
            'review_note' => $validated['review_note'],
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
            'published_at' => null,
        ])->save();

        return back()->with('success', 'Berita ditolak. Alasan penolakan sudah disimpan untuk penulis.');
    }

    /**
     * @return array<int, string>
     */
    private function aturanFotoGaleri(): array
    {
        return [
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:'.(Berita::MAX_GALERI_SIZE_MB * 1024),
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function aturanValidasi(?Berita $berita = null): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('beritas', 'slug')->ignore($berita?->id),
            ],
            'kategori' => ['nullable', 'string', 'max:100'],
            'ringkasan' => ['nullable', 'string', 'max:600'],
            'isi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'galeri' => ['nullable', 'array', 'max:'.Berita::MAX_GALERI_PER_UPLOAD],
            'galeri.*' => $this->aturanFotoGaleri(),
            'penulis' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in([Berita::STATUS_DRAFT, Berita::STATUS_PENDING])],
            'lokasi_nama' => ['nullable', 'string', 'max:255'],
            'lokasi_alamat' => ['nullable', 'string', 'max:1000'],
            'lokasi_desa' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
            'lokasi_keterangan' => ['nullable', 'string', 'max:1000'],
            'acara_mulai' => ['nullable', 'date'],
            'acara_selesai' => ['nullable', 'date', 'after_or_equal:acara_mulai'],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function pesanValidasi(): array
    {
        return [
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul berita tidak boleh lebih dari 255 karakter.',
            'slug.unique' => 'Slug tersebut sudah dipakai berita lain. Silakan gunakan slug lain.',
            'kategori.max' => 'Nama kategori maksimal 100 karakter.',
            'ringkasan.max' => 'Ringkasan maksimal 600 karakter.',
            'isi.required' => 'Isi berita wajib diisi.',
            'gambar.image' => 'Berkas sampul harus berupa gambar.',
            'gambar.mimes' => 'Gambar sampul harus berformat JPG, JPEG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran gambar sampul maksimal 2 MB.',
            'galeri.array' => 'Foto galeri tidak valid.',
            'galeri.max' => 'Maksimal '.Berita::MAX_GALERI_PER_UPLOAD.' foto dapat diunggah dalam satu waktu.',
            'galeri.*.image' => 'Setiap berkas galeri harus berupa gambar.',
            'galeri.*.mimes' => 'Foto galeri harus berformat JPG, JPEG, PNG, atau WebP.',
            'galeri.*.max' => 'Ukuran setiap foto galeri maksimal '.Berita::MAX_GALERI_SIZE_MB.' MB.',
            'penulis.max' => 'Nama penulis maksimal 255 karakter.',
            'status.required' => 'Status publikasi wajib dipilih.',
            'status.in' => 'Status publikasi hanya boleh draft atau menunggu review.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'latitude.between' => 'Latitude harus berada di antara -90 dan 90.',
            'latitude.required_with' => 'Latitude dan longitude harus diisi bersama-sama.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'longitude.between' => 'Longitude harus berada di antara -180 dan 180.',
            'longitude.required_with' => 'Latitude dan longitude harus diisi bersama-sama.',
            'acara_mulai.date' => 'Waktu mulai acara tidak valid.',
            'acara_selesai.date' => 'Waktu selesai acara tidak valid.',
            'acara_selesai.after_or_equal' => 'Waktu selesai acara tidak boleh sebelum waktu mulai.',
        ];
    }

    /**
     * Menyusun data yang benar-benar akan disimpan. Hanya field yang tervalidasi
     * yang dipakai agar mass assignment tetap aman.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function siapkanData(Request $request, array $validated, ?Berita $berita = null): array
    {
        $punyaKoordinat = filled($validated['latitude'] ?? null)
            && filled($validated['longitude'] ?? null);

        return [
            'judul' => $validated['judul'],
            'slug' => $this->slugUnik($validated['slug'] ?? null, $validated['judul'], $berita?->id),
            'kategori' => filled($validated['kategori'] ?? null) ? $validated['kategori'] : null,
            'ringkasan' => filled($validated['ringkasan'] ?? null) ? $validated['ringkasan'] : null,
            'penulis' => filled($validated['penulis'] ?? null)
                ? $validated['penulis']
                : ($berita?->penulis ?: $request->user()->name),
            'status' => $validated['status'],
            'isi' => $validated['isi'],
            'lokasi_nama' => filled($validated['lokasi_nama'] ?? null) ? $validated['lokasi_nama'] : null,
            'lokasi_alamat' => filled($validated['lokasi_alamat'] ?? null) ? $validated['lokasi_alamat'] : null,
            'lokasi_desa' => filled($validated['lokasi_desa'] ?? null) ? $validated['lokasi_desa'] : null,
            'latitude' => $punyaKoordinat ? $validated['latitude'] : null,
            'longitude' => $punyaKoordinat ? $validated['longitude'] : null,
            'lokasi_keterangan' => filled($validated['lokasi_keterangan'] ?? null) ? $validated['lokasi_keterangan'] : null,
            'acara_mulai' => $validated['acara_mulai'] ?? null,
            'acara_selesai' => $validated['acara_selesai'] ?? null,
        ];
    }

    private function slugUnik(?string $slugInput, string $judul, ?int $abaikanId = null): string
    {
        $dasar = Str::slug($slugInput ?: $judul);

        if ($dasar === '') {
            $dasar = 'berita';
        }

        $slug = $dasar;
        $nomor = 2;

        while (
            Berita::query()
                ->where('slug', $slug)
                ->when($abaikanId, fn ($query) => $query->whereKeyNot($abaikanId))
                ->exists()
        ) {
            $slug = $dasar.'-'.$nomor++;
        }

        return $slug;
    }

    private function tarikDariPublikasi(Berita $berita, bool $ditarikDariPublikasi): void
    {
        if (! $ditarikDariPublikasi) {
            return;
        }

        $berita->forceFill([
            'status' => Berita::STATUS_PENDING,
            'submitted_at' => now(),
            'reviewed_at' => null,
            'reviewed_by' => null,
            'review_note' => null,
            'published_at' => null,
        ])->save();
    }

    private function pastikanJumlahGaleriValid(Request $request, ?Berita $berita = null): void
    {
        $files = $request->file('galeri', []);
        $jumlahBaru = is_array($files) ? count($files) : 0;

        if ($jumlahBaru === 0) {
            return;
        }

        $jumlahTersimpan = $berita?->galeri()->count() ?? 0;

        if ($jumlahTersimpan + $jumlahBaru > Berita::MAX_GALERI) {
            $sisa = max(0, Berita::MAX_GALERI - $jumlahTersimpan);

            throw ValidationException::withMessages([
                'galeri' => 'Galeri berita maksimal '.Berita::MAX_GALERI." foto. Saat ini tersimpan {$jumlahTersimpan} foto, sehingga hanya {$sisa} foto baru yang dapat ditambahkan.",
            ]);
        }
    }

    private function simpanGaleri(Request $request, Berita $berita): void
    {
        $files = $request->file('galeri', []);

        if (! is_array($files) || $files === []) {
            return;
        }

        $urutan = (int) $berita->galeri()->max('urutan');
        $pathTersimpan = [];

        try {
            DB::transaction(function () use ($files, $berita, &$urutan, &$pathTersimpan) {
                foreach ($files as $file) {
                    $path = $file->store('berita/galeri', 'local');

                    if (! $path) {
                        throw new \RuntimeException('Foto galeri gagal disimpan.');
                    }

                    $pathTersimpan[] = $path;

                    $berita->galeri()->create([
                        'path' => $path,
                        'original_name' => Str::limit($file->getClientOriginalName(), 255, ''),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'urutan' => ++$urutan,
                    ]);
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($pathTersimpan);

            throw $exception;
        }
    }

    private function pesanBerhasil(Berita $berita): string
    {
        return match ($berita->status) {
            Berita::STATUS_PENDING => 'Berita berhasil diajukan untuk review. Berita hanya tampil publik setelah disetujui super admin.',
            Berita::STATUS_PUBLISHED => 'Berita berhasil diterbitkan.',
            Berita::STATUS_REJECTED => 'Berita ditolak dan menunggu perbaikan.',
            default => 'Berita berhasil disimpan sebagai draft.',
        };
    }
}
