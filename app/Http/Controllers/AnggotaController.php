<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    /**
     * Menampilkan form data anggota untuk pengguna yang sedang login.
     */
    public function create(): View
    {
        $user = request()->user();

        $anggota = $user->anggota;

        return view('anggota.create', compact('anggota'));
    }

    /**
     * Menyimpan atau memperbarui data anggota milik pengguna yang login.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_anggota' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:1000'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'no_hp' => ['required', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], $this->pesanValidasi(), $this->namaAtribut());

        $anggota = $request->user()->anggota;

        if ($request->hasFile('foto')) {
            $fotoBaru = $request->file('foto')->store('anggota', 'public');

            if ($anggota?->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }

            $validated['foto'] = $fotoBaru;
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

    /**
     * Form tambah anggota dari panel admin. Anggota tetap harus terhubung
     * dengan satu akun pengguna karena struktur database mewajibkannya.
     */
    public function adminCreate(): View
    {
        $users = User::query()
            ->whereDoesntHave('anggota')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.anggota.create', compact('users'));
    }

    public function adminStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
                Rule::unique('anggotas', 'user_id'),
            ],
            'nama_anggota' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:1000'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'no_hp' => ['required', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], $this->pesanValidasi(), $this->namaAtribut());

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('anggota', 'public');
        }

        $anggota = Anggota::create(array_merge($validated, [
            'status_verifikasi' => 'pending',
            'catatan_verifikasi' => null,
        ]));

        return redirect()
            ->route('admin.anggota.index')
            ->with(
                'success',
                'Data anggota '.$anggota->nama_anggota.' berhasil ditambahkan dan menunggu verifikasi.'
            );
    }

    /**
     * @return array<string, string>
     */
    private function pesanValidasi(): array
    {
        return [
            'user_id.required' => 'Akun pengguna wajib dipilih.',
            'user_id.exists' => 'Akun pengguna yang dipilih tidak ditemukan.',
            'user_id.unique' => 'Akun tersebut sudah memiliki data anggota.',
            'nama_anggota.required' => 'Nama anggota wajib diisi.',
            'nama_anggota.max' => 'Nama anggota tidak boleh lebih dari 255 karakter.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'alamat.max' => 'Alamat maksimal 1000 karakter.',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melewati hari ini.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
            'foto.image' => 'Foto harus berupa berkas gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function namaAtribut(): array
    {
        return [
            'user_id' => 'akun pengguna',
            'nama_anggota' => 'nama anggota',
            'alamat' => 'alamat',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'no_hp' => 'nomor HP',
            'foto' => 'foto',
        ];
    }
}
