<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifikasiAnggotaController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::with(['user', 'desa'])
            ->latest()
            ->get();

        $ringkasanStatus = [
            'pending' => $anggotas->where('status_verifikasi', 'pending')->count(),
            'approved' => $anggotas->where('status_verifikasi', 'approved')->count(),
            'rejected' => $anggotas->where('status_verifikasi', 'rejected')->count(),
        ];

        return view('admin.anggota.index', compact('anggotas', 'ringkasanStatus'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        abort_if(
            (int) $anggota->user_id === (int) $request->user()->id,
            403,
            'Anda tidak dapat menyetujui atau menolak pendaftaran Anda sendiri.'
        );

        $validated = $request->validate([
            'status_verifikasi' => 'required|in:approved,rejected',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        if (
            $validated['status_verifikasi'] === 'rejected'
            && empty(trim($validated['catatan_verifikasi'] ?? ''))
        ) {
            return back()
                ->withErrors([
                    'catatan_verifikasi' => 'Alasan penolakan wajib diisi.',
                ])
                ->withInput();
        }

        $anggota->update([
            'status_verifikasi' => $validated['status_verifikasi'],
            'catatan_verifikasi' => $validated['status_verifikasi'] === 'rejected'
                    ? $validated['catatan_verifikasi']
                    : null,
        ]);

        if ($validated['status_verifikasi'] === Anggota::STATUS_APPROVED) {
            $anggota->pastikanNomorAnggota();
        }

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Status verifikasi anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        if ($anggota->foto) {
            Storage::disk('public')->delete($anggota->foto);
        }

        $anggota->delete();

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Permintaan anggota berhasil dihapus.');
    }
}
