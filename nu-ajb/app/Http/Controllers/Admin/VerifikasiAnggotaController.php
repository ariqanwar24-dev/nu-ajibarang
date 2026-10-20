<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;

class VerifikasiAnggotaController extends Controller
{
   public function index()
{
    ('Controller verifikasi terpanggil');

    $anggotas = Anggota::with('user')
        ->latest()
        ->get();

    return view('admin.anggota.index', compact('anggotas'));
}

    public function update(Request $request, Anggota $anggota)
    {
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
            'catatan_verifikasi' =>
                $validated['status_verifikasi'] === 'rejected'
                    ? $validated['catatan_verifikasi']
                    : null,
        ]);

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Status verifikasi anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        if ($anggota->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($anggota->foto);
        }

        $anggota->delete();

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Permintaan anggota berhasil dihapus.');
    }
}
