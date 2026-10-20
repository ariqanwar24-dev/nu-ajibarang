<?php

namespace App\Http\Controllers;

class KartuAnggotaController extends Controller
{
    public function index()
    {
        $anggota = auth()->user()->anggota;

        if (!$anggota) {
            return redirect()
                ->route('anggota.create')
                ->with('info', 'Lengkapi data anggota terlebih dahulu.');
        }

        if ($anggota->status_verifikasi !== 'approved') {
            return view('kartu.status', compact('anggota'));
        }

        return view('kartu.index', compact('anggota'));
    }
}
