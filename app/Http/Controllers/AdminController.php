<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Desa;
use App\Models\Pengurus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $beritaQuery = Berita::query();

        if (! $user->isSuperAdmin()) {
            $beritaQuery->where('user_id', $user->id);
        }

        $ringkasanBerita = [
            'total' => (clone $beritaQuery)->count(),
            'draft' => (clone $beritaQuery)->where('status', Berita::STATUS_DRAFT)->count(),
            'pending' => (clone $beritaQuery)->where('status', Berita::STATUS_PENDING)->count(),
            'published' => (clone $beritaQuery)->where('status', Berita::STATUS_PUBLISHED)->count(),
            'rejected' => (clone $beritaQuery)->where('status', Berita::STATUS_REJECTED)->count(),
        ];

        $jumlahPengurus = Pengurus::count();
        $anggotaDisetujui = Anggota::query()
            ->where('status_verifikasi', Anggota::STATUS_APPROVED)
            ->count();
        $anggotaMenunggu = Anggota::query()
            ->where('status_verifikasi', Anggota::STATUS_PENDING)
            ->count();
        $penggunaMenunggu = User::query()->where('status', User::STATUS_PENDING)->count();

        $desas = Desa::query()
            ->withCount([
                'anggotas as approved_count' => fn ($query) => $query
                    ->where('status_verifikasi', Anggota::STATUS_APPROVED),
                'anggotas as pending_count' => fn ($query) => $query
                    ->where('status_verifikasi', Anggota::STATUS_PENDING),
            ])
            ->orderBy('nama')
            ->get();

        $desaDenganAnggota = $desas
            ->filter(fn (Desa $desa): bool => $desa->approved_count > 0)
            ->count();
        $desaTanpaAnggota = max(0, $desas->count() - $desaDenganAnggota);
        $anggotaTanpaDesa = Anggota::query()
            ->where('status_verifikasi', Anggota::STATUS_APPROVED)
            ->whereNull('desa_id')
            ->count();

        $beritaTerbaru = (clone $beritaQuery)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $anggotaTerbaru = Anggota::query()
            ->with(['user', 'desa'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'ringkasanBerita',
            'jumlahPengurus',
            'anggotaMenunggu',
            'anggotaDisetujui',
            'penggunaMenunggu',
            'desas',
            'desaDenganAnggota',
            'desaTanpaAnggota',
            'anggotaTanpaDesa',
            'beritaTerbaru',
            'anggotaTerbaru',
        ));
    }
}
