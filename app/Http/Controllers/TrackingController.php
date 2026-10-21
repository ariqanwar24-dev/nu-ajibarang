<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingController extends Controller
{
    /**
     * Jumlah maksimal kata kunci yang diproses agar pencarian tetap ringan.
     */
    private const MAX_KATA_KUNCI = 6;

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'min:3', 'max:100'],
        ], [
            'q.min' => 'Masukkan minimal 3 karakter untuk mencari berita.',
            'q.max' => 'Kata kunci pencarian maksimal 100 karakter.',
        ], [
            'q' => 'kata kunci berita',
        ]);

        $kataKunci = trim((string) ($validated['q'] ?? ''));
        $kataKunci = preg_replace('/\s+/u', ' ', $kataKunci) ?? '';

        $bolehLihatStatusInternal = $this->bolehLihatStatusInternal($request);

        $berita = null;
        $sudahDicari = $kataKunci !== '';

        if ($sudahDicari) {
            $query = Berita::query()->with('user');
            $this->batasiVisibilitas($query, $request);

            $this->terapkanPencarian($query, $kataKunci);

            $berita = $query
                ->orderByRaw('COALESCE(published_at, created_at) DESC')
                ->orderByDesc('id')
                ->paginate(12)
                ->withQueryString();
        }

        return view('tracking.index', compact(
            'kataKunci',
            'berita',
            'sudahDicari',
            'bolehLihatStatusInternal',
        ));
    }

    /**
     * Status internal hanya terbuka untuk admin aktif yang berhak.
     */
    private function bolehLihatStatusInternal(Request $request): bool
    {
        $user = $request->user();

        return $user !== null && $user->isActive() && $user->isAdmin();
    }

    /**
     * Samakan visibilitas dengan kebijakan berita: publik hanya berita terbit,
     * admin biasa hanya berita terbit dan miliknya, super admin melihat semua.
     */
    private function batasiVisibilitas(Builder $query, Request $request): void
    {
        $user = $request->user();

        if ($user === null || ! $user->isActive() || ! $user->isAdmin()) {
            $query->published();

            return;
        }

        if ($user->isSuperAdmin()) {
            return;
        }

        $query->where(function (Builder $subQuery) use ($user) {
            $subQuery->where('status', Berita::STATUS_PUBLISHED)
                ->orWhere('user_id', $user->id);
        });
    }

    /**
     * Cocokkan setiap kata kunci pada judul, ringkasan, isi, dan metadata berita.
     */
    private function terapkanPencarian(Builder $query, string $kataKunci): void
    {
        $kolom = [
            'judul',
            'ringkasan',
            'isi',
            'kategori',
            'penulis',
            'lokasi_nama',
            'lokasi_desa',
        ];

        $kataKunciList = array_slice(
            preg_split('/\s+/u', $kataKunci, -1, PREG_SPLIT_NO_EMPTY) ?: [],
            0,
            self::MAX_KATA_KUNCI,
        );

        foreach ($kataKunciList as $kata) {
            $pola = '%'.$this->escapeLike($kata).'%';

            $query->where(function (Builder $subQuery) use ($kolom, $pola) {
                foreach ($kolom as $namaKolom) {
                    $subQuery->orWhere($namaKolom, 'like', $pola);
                }
            });
        }
    }

    /**
     * Netralkan karakter khusus LIKE agar pengguna tidak bisa memakai wildcard liar.
     */
    private function escapeLike(string $nilai): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $nilai);
    }
}
