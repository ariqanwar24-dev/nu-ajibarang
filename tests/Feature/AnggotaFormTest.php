<?php

use App\Models\Anggota;
use App\Models\Desa;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function idDesaAnggota(string $nama = 'Ajibarang Kulon'): int
{
    return (int) Desa::query()->where('nama', $nama)->value('id');
}

function dataAnggotaForm(array $override = []): array
{
    return array_merge([
        'nama_anggota' => 'Siti Aminah',
        'desa_id' => idDesaAnggota(),
        'rt' => '03',
        'rw' => '02',
        'tanggal_lahir' => '1998-04-12',
        'jenis_kelamin' => 'P',
        'no_hp' => '081234567890',
    ], $override);
}

function buatAnggotaForm(User $user, string $status = Anggota::STATUS_PENDING, array $override = []): Anggota
{
    return Anggota::create(array_merge([
        'user_id' => $user->id,
        'desa_id' => idDesaAnggota(),
        'nama_anggota' => 'Siti Aminah',
        'rt' => '03',
        'rw' => '02',
        'tanggal_lahir' => '1998-04-12',
        'jenis_kelamin' => 'P',
        'no_hp' => '081234567890',
        'status_verifikasi' => $status,
    ], $override));
}

test('formulir anggota menampilkan bagian, field, dan penanda wajib', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('anggota.create'));

    $response->assertOk()
        ->assertSee('Informasi Pribadi')
        ->assertSee('Alamat')
        ->assertSee('Kontak')
        ->assertSee('Foto Anggota')
        ->assertSee('name="nama_anggota"', false)
        ->assertSee('name="desa_id"', false)
        ->assertSee('name="rt"', false)
        ->assertSee('name="rw"', false)
        ->assertSee('name="tanggal_lahir"', false)
        ->assertSee('name="jenis_kelamin"', false)
        ->assertSee('name="no_hp"', false)
        ->assertSee('name="foto"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('Pilih desa')
        ->assertSee('Ajibarang Kulon')
        ->assertSee('Tipar Kidul')
        ->assertSee('Wajib')
        ->assertSee('Pendaftaran baru')
        ->assertDontSee('name="alamat"', false)
        ->assertDontSee('name="status_verifikasi"', false)
        ->assertDontSee('name="user_id"', false);
});

test('formulir anggota menampilkan status menunggu verifikasi', function () {
    $user = User::factory()->create();
    buatAnggotaForm($user);

    $this->actingAs($user)->get(route('anggota.create'))
        ->assertOk()
        ->assertSee('Menunggu Verifikasi')
        ->assertSee('Status hanya dapat diubah oleh admin.')
        ->assertDontSee('Lihat Kartu Anggota');
});

test('pengguna yang disetujui melihat kartu resmi dengan nomor anggota', function () {
    $user = User::factory()->create();
    $anggota = buatAnggotaForm($user, Anggota::STATUS_APPROVED, [
        'alamat' => 'Jl. Rahasia No. 10, Ajibarang Kulon',
    ]);

    $nomor = 'NU-AJB-'.str_pad((string) $anggota->id, 6, '0', STR_PAD_LEFT);

    $this->actingAs($user)->get(route('kartu.index'))
        ->assertOk()
        ->assertSee('Kartu Anggota Resmi')
        ->assertSee('Nahdlatul Ulama')
        ->assertSee('Kecamatan Ajibarang')
        ->assertSee($anggota->nama_anggota)
        ->assertSee($nomor)
        ->assertSee('Ajibarang Kulon')
        ->assertSee('03 / 02')
        ->assertSee('Cetak / Simpan PDF')
        ->assertSee('85,6 × 54 mm')
        ->assertDontSee($anggota->alamat)
        ->assertDontSee('name="status_verifikasi"', false);

    expect($anggota->fresh()->nomor_anggota)->toBe($nomor);
});

test('nomor anggota unik dan konsisten saat kartu dibuka ulang', function () {
    $userSatu = User::factory()->create();
    $userDua = User::factory()->create();

    $anggotaSatu = buatAnggotaForm($userSatu, Anggota::STATUS_APPROVED);
    $anggotaDua = buatAnggotaForm($userDua, Anggota::STATUS_APPROVED);

    $this->actingAs($userSatu)->get(route('kartu.index'))
        ->assertOk()
        ->assertSee($anggotaSatu->fresh()->nomor_anggota);

    $this->actingAs($userDua)->get(route('kartu.index'))
        ->assertOk()
        ->assertSee($anggotaDua->fresh()->nomor_anggota);

    $nomorSatu = $anggotaSatu->fresh()->nomor_anggota;
    $nomorDua = $anggotaDua->fresh()->nomor_anggota;

    expect($nomorSatu)->not->toBe($nomorDua);

    $this->actingAs($userSatu)->get(route('kartu.index'))
        ->assertOk()
        ->assertSee($nomorSatu);

    expect($anggotaSatu->fresh()->nomor_anggota)->toBe($nomorSatu);
});

test('anggota pending tidak mendapatkan kartu resmi', function () {
    $user = User::factory()->create();
    $anggota = buatAnggotaForm($user);

    $this->actingAs($user)->get(route('kartu.index'))
        ->assertOk()
        ->assertSee('Menunggu Verifikasi')
        ->assertDontSee('Kartu Anggota Resmi')
        ->assertDontSee('Cetak / Simpan PDF');

    expect($anggota->fresh()->nomor_anggota)->toBeNull();
});

test('nomor anggota yang sudah ada tidak ditimpa', function () {
    $user = User::factory()->create();
    $anggota = buatAnggotaForm($user, Anggota::STATUS_APPROVED, [
        'nomor_anggota' => 'NU-2026-0001',
    ]);

    $this->actingAs($user)->get(route('kartu.index'))
        ->assertOk()
        ->assertSee('NU-2026-0001');

    expect($anggota->fresh()->nomor_anggota)->toBe('NU-2026-0001');
});

test('persetujuan admin membuat nomor anggota resmi', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $anggota = buatAnggotaForm($user);

    $this->actingAs($admin)
        ->patch(route('admin.anggota.verifikasi', $anggota), [
            'status_verifikasi' => Anggota::STATUS_APPROVED,
        ])
        ->assertRedirect(route('admin.anggota.index'));

    $anggota->refresh();

    expect($anggota->status_verifikasi)->toBe(Anggota::STATUS_APPROVED)
        ->and($anggota->nomor_anggota)->toBe('NU-AJB-'.str_pad((string) $anggota->id, 6, '0', STR_PAD_LEFT));
});

test('halaman status menampilkan catatan penolakan dari admin', function () {
    $user = User::factory()->create();
    buatAnggotaForm($user, Anggota::STATUS_REJECTED, [
        'catatan_verifikasi' => 'Foto kurang jelas. Mohon unggah foto wajah yang lebih terang.',
    ]);

    $this->actingAs($user)->get(route('kartu.index'))
        ->assertOk()
        ->assertSee('Perlu Perbaikan')
        ->assertSee('Foto kurang jelas. Mohon unggah foto wajah yang lebih terang.')
        ->assertSee('Perbaiki Data')
        ->assertDontSee('name="status_verifikasi"', false);
});

test('pesan validasi anggota tampil pada field terkait', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->followingRedirects()
        ->from(route('anggota.create'))
        ->post(route('anggota.store'), []);

    $response->assertOk()
        ->assertSee('Nama anggota wajib diisi.')
        ->assertSee('Desa wajib dipilih.')
        ->assertSee('RT wajib diisi.')
        ->assertSee('RW wajib diisi.')
        ->assertSee('Jenis kelamin wajib dipilih.')
        ->assertSee('Nomor HP wajib diisi.');
});

test('formulir tidak dapat mengubah status verifikasi atau identitas pengguna lain', function () {
    $user = User::factory()->create();
    $penggunaLain = User::factory()->create();

    $this->actingAs($user)
        ->post(route('anggota.store'), dataAnggotaForm([
            'status_verifikasi' => Anggota::STATUS_APPROVED,
            'user_id' => $penggunaLain->id,
            'nomor_anggota' => 'NU-CURANG-001',
        ]))
        ->assertRedirect(route('kartu.index'));

    $anggota = Anggota::query()->where('user_id', $user->id)->firstOrFail();

    expect($anggota->status_verifikasi)->toBe(Anggota::STATUS_PENDING)
        ->and($anggota->nomor_anggota)->toBeNull()
        ->and(Anggota::query()->where('user_id', $penggunaLain->id)->exists())->toBeFalse();
});

test('memperbarui data anggota mengajukan verifikasi ulang', function () {
    $user = User::factory()->create();
    $anggota = buatAnggotaForm($user, Anggota::STATUS_APPROVED, [
        'alamat' => 'Alamat lama yang harus dipertahankan',
        'catatan_verifikasi' => null,
    ]);

    $this->actingAs($user)
        ->post(route('anggota.store'), dataAnggotaForm([
            'nama_anggota' => 'Siti Aminah Baru',
            'no_hp' => '081298765432',
        ]))
        ->assertRedirect(route('kartu.index'));

    $anggota->refresh();

    expect($anggota->nama_anggota)->toBe('Siti Aminah Baru')
        ->and($anggota->no_hp)->toBe('081298765432')
        ->and($anggota->alamat)->toBe('Alamat lama yang harus dipertahankan')
        ->and($anggota->desa_id)->toBe(idDesaAnggota())
        ->and($anggota->rt)->toBe('03')
        ->and($anggota->rw)->toBe('02')
        ->and($anggota->status_verifikasi)->toBe(Anggota::STATUS_PENDING)
        ->and($anggota->catatan_verifikasi)->toBeNull();
});

test('foto anggota yang tidak valid ditolak dengan pesan yang jelas', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('anggota.create'))
        ->post(route('anggota.store'), dataAnggotaForm([
            'foto' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]))
        ->assertSessionHasErrors('foto');

    expect(Anggota::query()->where('user_id', $user->id)->exists())->toBeFalse();
});
test('formulir menolak desa yang tidak tersedia di database', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('anggota.create'))
        ->post(route('anggota.store'), dataAnggotaForm([
            'desa_id' => 999999,
            'rt' => '03',
            'rw' => '02',
        ]))
        ->assertSessionHasErrors('desa_id')
        ->assertSessionHasInput('rt', '03')
        ->assertSessionHasInput('rw', '02');

    expect(Anggota::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

test('validasi RT dan RW dilakukan di backend', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('anggota.create'))
        ->post(route('anggota.store'), dataAnggotaForm([
            'rt' => 'A1',
            'rw' => '1234',
        ]))
        ->assertSessionHasErrors(['rt', 'rw']);

    expect(Anggota::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

test('admin tidak dapat menyetujui pendaftarannya sendiri', function () {
    $admin = User::factory()->admin()->create();
    $anggota = buatAnggotaForm($admin);

    $this->actingAs($admin)
        ->patch(route('admin.anggota.verifikasi', $anggota), [
            'status_verifikasi' => Anggota::STATUS_APPROVED,
        ])
        ->assertForbidden();

    expect($anggota->fresh()->status_verifikasi)->toBe(Anggota::STATUS_PENDING);
});
