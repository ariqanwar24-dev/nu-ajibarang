<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Menampilkan form data anggota.
     */
    public function create()
    {
        $user = request()->user();

        $anggota = $user->anggota;

        return view('anggota.create', compact('anggota'));
    }

    /**
     * Menyimpan atau memperbarui data anggota.
     */
   
public function store(Request $request)
{
    $validated = $request->validate([
        'nama_anggota' => 'required|string|max:255',
        'alamat' => 'required|string',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'required|in:L,P',
        'no_hp' => 'required|string|max:20',
        'foto' => 'nullable|image|max:2048',
    ]);

    $anggota = $request->user()->anggota;

    if ($request->hasFile('foto')) {
        $validated['foto'] = $request->file('foto')
            ->store('anggota', 'public');
    }

    if ($anggota) {
        $anggota->update(array_merge($validated, [
            'status_verifikasi' => 'pending',
            'catatan_verifikasi' => null,
        ]));
    } else {
        Anggota::create(array_merge($validated, [
            'user_id' => $request->user()->id,
            'status_verifikasi' => 'pending',
            'catatan_verifikasi' => null,
        ]));
    }

    return redirect()
        ->route('kartu.index')
        ->with(
            'success',
            'Data anggota berhasil dikirim dan menunggu verifikasi admin.'
        );
}

}
