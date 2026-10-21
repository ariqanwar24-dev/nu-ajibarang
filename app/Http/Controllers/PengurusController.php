<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PengurusController extends Controller
{
    public function index()
    {
        // Seluruh data pada halaman publik berasal dari tabel penguruses.
        $pengurus = Pengurus::query()->ordered()->get();
        $kategori = Pengurus::KATEGORI;
        $pengurusPerKategori = $pengurus->groupBy(
            fn (Pengurus $item) => $item->kategoriKey()
        );
        $jumlahDemo = $pengurus->where('is_demo', true)->count();

        return view('pengurus.index', compact(
            'pengurus',
            'kategori',
            'pengurusPerKategori',
            'jumlahDemo',
        ));
    }

    public function adminIndex()
    {
        Gate::authorize('viewAny', Pengurus::class);

        $pengurus = Pengurus::query()->ordered()->paginate(15);
        $kategori = Pengurus::KATEGORI;
        $jumlahDemo = Pengurus::query()->where('is_demo', true)->count();

        return view('admin.pengurus.index', compact('pengurus', 'kategori', 'jumlahDemo'));
    }

    public function create()
    {
        Gate::authorize('create', Pengurus::class);

        return view('admin.pengurus.form', [
            'pengurus' => new Pengurus(['urutan' => 0]),
            'mode' => 'create',
            'kategori' => Pengurus::KATEGORI,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Pengurus::class);

        $validated = $this->validatedData($request);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengurus', 'public');
        }

        Pengurus::create($validated);

        return redirect()
            ->route('admin.pengurus.index')
            ->with('success', 'Data pengurus berhasil ditambahkan.');
    }

    public function show(Pengurus $pengurus)
    {
        return view('pengurus.show', compact('pengurus'));
    }

    public function edit(Pengurus $pengurus)
    {
        Gate::authorize('update', $pengurus);

        return view('admin.pengurus.form', [
            'pengurus' => $pengurus,
            'mode' => 'edit',
            'kategori' => Pengurus::KATEGORI,
        ]);
    }

    public function update(Request $request, Pengurus $pengurus)
    {
        Gate::authorize('update', $pengurus);

        $validated = $this->validatedData($request);
        $fotoLama = $pengurus->foto;
        $fotoBaru = null;
        $hapusFoto = $request->boolean('hapus_foto');

        unset($validated['foto']);

        if ($request->hasFile('foto')) {
            $fotoBaru = $request->file('foto')->store('pengurus', 'public');
            $validated['foto'] = $fotoBaru;
        } elseif ($hapusFoto) {
            $validated['foto'] = null;
        }

        try {
            $pengurus->update($validated);
        } catch (\Throwable $exception) {
            $this->deleteFotoFile($fotoBaru);

            throw $exception;
        }

        if ($fotoLama && ($fotoBaru || $hapusFoto)) {
            $this->deleteFotoFile($fotoLama);
        }

        return redirect()
            ->route('admin.pengurus.index')
            ->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(Pengurus $pengurus)
    {
        Gate::authorize('delete', $pengurus);

        $foto = $pengurus->foto;
        $pengurus->delete();

        $this->deleteFotoFile($foto);

        return redirect()
            ->route('admin.pengurus.index')
            ->with('success', 'Data pengurus berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'tingkatan' => ['required', 'string', 'max:100'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                'dimensions:min_width=300,min_height=300,max_width=5000,max_height=5000',
            ],
            'hapus_foto' => ['sometimes', 'boolean'],
        ]);

        $validated['urutan'] = $validated['urutan'] ?? 0;
        unset($validated['hapus_foto']);

        return $validated;
    }

    private function deleteFotoFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $path = ltrim(str_replace('\\', '/', $path), '/');

        if (str_contains($path, '..') || ! str_starts_with($path, 'pengurus/')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
