<?php

namespace App\Http\Controllers;

use App\Models\Anggota;

class KartuAnggotaController extends Controller
{
    public function index()
    {
        $anggota = auth()->user()->anggota()->with('desa')->first();

        if (! $anggota) {
            return redirect()
                ->route('anggota.create')
                ->with('info', 'Lengkapi data anggota terlebih dahulu.');
        }

        if ($anggota->status_verifikasi !== Anggota::STATUS_APPROVED) {
            return view('kartu.status', compact('anggota'));
        }

        $nomorAnggota = $anggota->pastikanNomorAnggota();

        return view('kartu.index', compact('anggota', 'nomorAnggota'));
    }
}
