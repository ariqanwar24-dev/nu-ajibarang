<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Desa;
use App\Models\Pengurus;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $jumlahBerita = Berita::count();
        $jumlahBeritaTerbit = Berita::query()->terbit()->count();
        $jumlahBeritaDraft = Berita::query()->where('status', 'draft')->count();
        $jumlahPengurus = Pengurus::count();
        $jumlahAnggota = Anggota::count();
        $anggotaMenunggu = Anggota::query()->where('status_verifikasi', 'pending')->count();
        $anggotaDisetujui = Anggota::query()->where('status_verifikasi', 'approved')->count();

        $desas = Desa::query()->orderBy('nama')->get();
        $totalAnggotaDesa = $desas->sum('jumlah_anggota');
        $totalKegiatanDesa = $desas->sum('jumlah_kegiatan');
        $totalRantingDesa = $desas->sum('jumlah_ranting');

        $beritaTerbaru = Berita::query()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $anggotaTerbaru = Anggota::query()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'jumlahBerita',
            'jumlahBeritaTerbit',
            'jumlahBeritaDraft',
            'jumlahPengurus',
            'jumlahAnggota',
            'anggotaMenunggu',
            'anggotaDisetujui',
            'desas',
            'totalAnggotaDesa',
            'totalKegiatanDesa',
            'totalRantingDesa',
            'beritaTerbaru',
            'anggotaTerbaru',
        ));
    }
}
