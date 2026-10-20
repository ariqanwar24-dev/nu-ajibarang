<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengurusController extends Controller
{
    public function index()
    {
        // Data yang dibuat/diubah admin tetap menjadi sumber halaman publik.
        $pengurus = Pengurus::query()->orderBy('tingkatan')->orderBy('jabatan')->orderBy('nama')->get();
        return view('pengurus.index', compact('pengurus'));
    }

    public function adminIndex()
    {
        $pengurus = Pengurus::query()->orderBy('tingkatan')->orderBy('jabatan')->orderBy('nama')->get();
        return view('admin.pengurus.index', compact('pengurus'));
    }

    public function create()
    {
        return view('admin.pengurus.form', ['pengurus' => new Pengurus(), 'mode' => 'create']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required','string','max:255'],
            'jabatan' => ['required','string','max:255'],
            'tingkatan' => ['required','string','max:100'],
            'foto' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengurus', 'public');
        }
        Pengurus::create($validated);
        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil ditambahkan.');
    }

    public function show(Pengurus $pengurus)
    {
        return view('pengurus.show', compact('pengurus'));
    }

    public function edit(Pengurus $pengurus)
    {
        return view('admin.pengurus.form', ['pengurus' => $pengurus, 'mode' => 'edit']);
    }

    public function update(Request $request, Pengurus $pengurus)
    {
        $validated = $request->validate([
            'nama' => ['required','string','max:255'],
            'jabatan' => ['required','string','max:255'],
            'tingkatan' => ['required','string','max:100'],
            'foto' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
        if ($request->hasFile('foto')) {
            if ($pengurus->foto) Storage::disk('public')->delete($pengurus->foto);
            $validated['foto'] = $request->file('foto')->store('pengurus', 'public');
        }
        $pengurus->update($validated);
        return redirect()->route('admin.pengurus.index')->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(Pengurus $pengurus)
    {
        if ($pengurus->foto) Storage::disk('public')->delete($pengurus->foto);
        $pengurus->delete();
        return redirect()->route('admin.pengurus.index')->with('success', 'Data pengurus berhasil dihapus.');
    }
}
