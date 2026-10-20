<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Pengurus;

class AdminController extends Controller
{
    public function index()
    {
        $jumlahBerita = Berita::count();
        $jumlahPengurus = Pengurus::count();
        $anggotaMenunggu = Anggota::where(
            'status_verifikasi',
            'pending'
        )->count();

        return view('admin.index', compact(
            'jumlahBerita',
            'jumlahPengurus',
            'anggotaMenunggu'
        ));
    }
}
