<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Desa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(): View
    {
        $berita = Berita::query()
            ->terbit()
            ->latest()
            ->get();

        return view('berita.index', compact('berita'));
    }

    public function adminIndex(): View
    {
        $berita = Berita::query()
            ->with('user')
            ->latest()
            ->get();

        $ringkasanStatus = [
            'total' => $berita->count(),
            'terbit' => $berita->where('status', 'terbit')->count(),
            'draft' => $berita->where('status', 'draft')->count(),
        ];

        return view('admin.berita.index', compact('berita', 'ringkasanStatus'));
    }

    public function create(): View
    {
        return view('berita.create', [
            'desas' => Desa::query()->orderBy('nama')->pluck('nama'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->aturanValidasi(), $this->pesanValidasi());

        $data = $this->siapkanData($request, $validated);
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita = Berita::create($data);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', $this->pesanBerhasil($berita));
    }

    public function show(Berita $berita): View
    {
        // Draft hanya boleh dilihat oleh admin yang sedang login.
        if (! $berita->sudahTerbit()) {
            $user = auth()->user();

            abort_unless($user && $user->is_admin, 404);
        }

        return view('berita.show', compact('berita'));
    }

    public function edit(Berita $berita): View
    {
        return view('berita.edit', [
            'berita' => $berita,
            'desas' => Desa::query()->orderBy('nama')->pluck('nama'),
        ]);
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $validated = $request->validate(
            $this->aturanValidasi($berita),
            $this->pesanValidasi()
        );

        $data = $this->siapkanData($request, $validated, $berita);
        $data['user_id'] = $berita->user_id ?? $request->user()->id;

        if ($request->hasFile('gambar')) {
            $gambarBaru = $request->file('gambar')->store('berita', 'public');

            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }

            $data['gambar'] = $gambarBaru;
        }

        $berita->update($data);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', $this->pesanBerhasil($berita));
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()
            ->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
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
            'penulis' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'terbit'])],
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
            'penulis.max' => 'Nama penulis maksimal 255 karakter.',
            'status.required' => 'Status publikasi wajib dipilih.',
            'status.in' => 'Status publikasi harus draft atau terbit.',
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

    private function pesanBerhasil(Berita $berita): string
    {
        return $berita->sudahTerbit()
            ? 'Berita berhasil diterbitkan.'
            : 'Berita berhasil disimpan sebagai draft.';
    }
}
