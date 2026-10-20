<?php

use App\Models\Anggota;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function adminUser(): User
{
    return User::factory()->create(['is_admin' => true]);
}

function dataBerita(array $override = []): array
{
    return array_merge([
        'judul' => 'Pengajian Rutin MWC NU Ajibarang',
        'slug' => '',
        'kategori' => 'Pengajian',
        'ringkasan' => 'Ringkasan pengajian rutin.',
        'isi' => "Paragraf pertama berita.\n\nParagraf kedua berita.",
        'penulis' => 'Admin MWC',
        'status' => 'terbit',
    ], $override);
}

test('tamu tidak dapat membuka panel admin', function () {
    $this->get(route('admin.index'))->assertRedirect(route('login'));
});

test('pengguna non-admin tidak dapat membuka panel admin', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)->get(route('admin.index'))->assertForbidden();
});

test('admin dapat membuka dashboard dan halaman form', function () {
    $admin = adminUser();

    $this->actingAs($admin)->get(route('admin.index'))
        ->assertOk()
        ->assertSee('Dashboard Admin');

    $this->actingAs($admin)->get(route('admin.berita.index'))
        ->assertOk()
        ->assertSee('Kelola Berita');

    $this->actingAs($admin)->get(route('admin.berita.create'))
        ->assertOk()
        ->assertSee('Tulis Berita Baru');

    $this->actingAs($admin)->get(route('admin.anggota.index'))
        ->assertOk()
        ->assertSee('Kelola Anggota');

    $this->actingAs($admin)->get(route('admin.anggota.create'))
        ->assertOk()
        ->assertSee('Tambah Data Anggota');
});

test('admin dapat membuka halaman edit berita dan halaman pengurus', function () {
    $admin = adminUser();

    $berita = Berita::create([
        'judul' => 'Berita untuk Diedit',
        'slug' => 'berita-untuk-diedit',
        'isi' => 'Isi berita.',
        'status' => 'draft',
    ]);

    $this->actingAs($admin)->get(route('admin.berita.edit', $berita))
        ->assertOk()
        ->assertSee('Edit Berita');

    $this->actingAs($admin)->get(route('admin.pengurus.index'))
        ->assertOk()
        ->assertSee('Kelola Pengurus');

    $this->actingAs($admin)->get(route('admin.pengurus.create'))
        ->assertOk()
        ->assertSee('Tambah Pengurus');
});

test('admin dapat memperbarui berita', function () {
    $admin = adminUser();

    $berita = Berita::create([
        'judul' => 'Judul Awal',
        'slug' => 'judul-awal',
        'isi' => 'Isi awal.',
        'status' => 'draft',
    ]);

    $this->actingAs($admin)->put(route('admin.berita.update', $berita), dataBerita([
        'judul' => 'Judul Setelah Diperbarui',
        'slug' => '',
        'status' => 'draft',
    ]))->assertRedirect(route('admin.berita.index'));

    $berita->refresh();

    expect($berita->judul)->toBe('Judul Setelah Diperbarui')
        ->and($berita->slug)->toBe('judul-setelah-diperbarui')
        ->and($berita->status)->toBe('draft');
});

test('admin dapat menyimpan berita beserta lokasi acara', function () {
    $admin = adminUser();

    $response = $this->actingAs($admin)->post(route('admin.berita.store'), dataBerita([
        'lokasi_nama' => 'Masjid Baitul Muttaqin',
        'lokasi_alamat' => 'Jl. Raya Ajibarang No. 1',
        'lokasi_desa' => 'Ajibarang Kulon',
        'latitude' => -7.4093,
        'longitude' => 109.0736,
        'acara_mulai' => '2026-10-25T19:00',
        'acara_selesai' => '2026-10-25T21:00',
    ]));

    $response->assertRedirect(route('admin.berita.index'));

    $this->assertDatabaseHas('beritas', [
        'judul' => 'Pengajian Rutin MWC NU Ajibarang',
        'slug' => 'pengajian-rutin-mwc-nu-ajibarang',
        'status' => 'terbit',
        'user_id' => $admin->id,
        'lokasi_nama' => 'Masjid Baitul Muttaqin',
    ]);
});

test('slug otomatis tetap unik ketika judul sama', function () {
    $admin = adminUser();

    Berita::create([
        'judul' => 'Pengajian Rutin MWC NU Ajibarang',
        'slug' => 'pengajian-rutin-mwc-nu-ajibarang',
        'isi' => 'Isi lama.',
        'status' => 'terbit',
    ]);

    $this->actingAs($admin)->post(route('admin.berita.store'), dataBerita())
        ->assertRedirect(route('admin.berita.index'));

    $this->assertDatabaseHas('beritas', [
        'slug' => 'pengajian-rutin-mwc-nu-ajibarang-2',
    ]);
});

test('slug manual yang duplikat ditolak dengan pesan validasi', function () {
    $admin = adminUser();

    Berita::create([
        'judul' => 'Berita Lama',
        'slug' => 'slug-dipakai',
        'isi' => 'Isi lama.',
        'status' => 'terbit',
    ]);

    $this->actingAs($admin)->post(route('admin.berita.store'), dataBerita([
        'slug' => 'slug-dipakai',
    ]))->assertSessionHasErrors('slug');
});

test('latitude tanpa longitude gagal divalidasi', function () {
    $admin = adminUser();

    $this->actingAs($admin)->post(route('admin.berita.store'), dataBerita([
        'latitude' => -7.4,
    ]))->assertSessionHasErrors('longitude');
});

test('gambar sampul yang valid disimpan ke disk publik', function () {
    Storage::fake('public');

    $admin = adminUser();
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl2nXQAAAAASUVORK5CYII=');

    $this->actingAs($admin)->post(route('admin.berita.store'), dataBerita([
        'gambar' => UploadedFile::fake()->createWithContent('sampul.png', $png),
    ]))->assertRedirect(route('admin.berita.index'));

    $berita = Berita::query()->firstOrFail();

    expect($berita->gambar)->not->toBeNull();
    Storage::disk('public')->assertExists($berita->gambar);
});

test('berkas sampul yang bukan gambar ditolak', function () {
    Storage::fake('public');

    $admin = adminUser();

    $this->actingAs($admin)->post(route('admin.berita.store'), dataBerita([
        'gambar' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
    ]))->assertSessionHasErrors('gambar');
});

test('draft tidak tampil di halaman publik', function () {
    $draft = Berita::create([
        'judul' => 'Berita Draft Rahasia',
        'slug' => 'berita-draft-rahasia',
        'isi' => 'Isi draft.',
        'status' => 'draft',
    ]);

    $this->get(route('berita.index'))->assertOk()->assertDontSee($draft->judul);
    $this->get(route('berita.show', $draft))->assertNotFound();
});

test('berita dapat diakses memakai slug dan tautan lama berbasis id', function () {
    $berita = Berita::create([
        'judul' => 'Berita dengan Slug',
        'slug' => 'berita-dengan-slug',
        'isi' => 'Isi berita.',
        'status' => 'terbit',
    ]);

    expect(route('berita.show', $berita))->toEndWith('/berita/berita-dengan-slug');

    $this->get('/berita/berita-dengan-slug')
        ->assertOk()
        ->assertSee('Berita dengan Slug');

    $this->get('/berita/'.$berita->id)
        ->assertOk()
        ->assertSee('Berita dengan Slug');
});

test('berita terbit dengan lokasi menampilkan peta dan petunjuk arah', function () {
    $berita = Berita::create([
        'judul' => 'Pengajian dengan Lokasi',
        'slug' => 'pengajian-dengan-lokasi',
        'isi' => 'Isi berita.',
        'status' => 'terbit',
        'lokasi_nama' => 'Masjid Agung',
        'lokasi_alamat' => 'Ajibarang',
        'latitude' => -7.4093,
        'longitude' => 109.0736,
    ]);

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Lokasi Acara')
        ->assertSee('Petunjuk Arah')
        ->assertSee('Masjid Agung');
});

test('admin dapat menambahkan anggota untuk akun yang belum terhubung', function () {
    $admin = adminUser();
    $user = User::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.anggota.store'), [
        'user_id' => $user->id,
        'nama_anggota' => 'Ahmad Fauzi',
        'alamat' => 'Ajibarang Kulon',
        'tanggal_lahir' => '1995-05-10',
        'jenis_kelamin' => 'L',
        'no_hp' => '081234567890',
    ]);

    $response->assertRedirect(route('admin.anggota.index'));

    $this->assertDatabaseHas('anggotas', [
        'user_id' => $user->id,
        'nama_anggota' => 'Ahmad Fauzi',
        'status_verifikasi' => 'pending',
    ]);
});

test('akun yang sudah memiliki anggota tidak dapat dipakai dua kali', function () {
    $admin = adminUser();
    $user = User::factory()->create();

    Anggota::create([
        'user_id' => $user->id,
        'nama_anggota' => 'Anggota Lama',
        'alamat' => 'Ajibarang',
        'jenis_kelamin' => 'L',
        'no_hp' => '081234567890',
        'status_verifikasi' => 'pending',
    ]);

    $this->actingAs($admin)->post(route('admin.anggota.store'), [
        'user_id' => $user->id,
        'nama_anggota' => 'Anggota Baru',
        'alamat' => 'Ajibarang',
        'jenis_kelamin' => 'P',
        'no_hp' => '081298765432',
    ])->assertSessionHasErrors('user_id');
});

test('admin dapat menyetujui dan menolak pengajuan anggota', function () {
    $admin = adminUser();
    $user = User::factory()->create();

    $anggota = Anggota::create([
        'user_id' => $user->id,
        'nama_anggota' => 'Siti Aminah',
        'alamat' => 'Ajibarang',
        'jenis_kelamin' => 'P',
        'no_hp' => '081234567890',
        'status_verifikasi' => 'pending',
    ]);

    $this->actingAs($admin)->patch(route('admin.anggota.verifikasi', $anggota), [
        'status_verifikasi' => 'approved',
    ])->assertRedirect(route('admin.anggota.index'));

    expect($anggota->fresh()->status_verifikasi)->toBe('approved');

    $this->actingAs($admin)->patch(route('admin.anggota.verifikasi', $anggota), [
        'status_verifikasi' => 'rejected',
        'catatan_verifikasi' => 'Foto kurang jelas.',
    ])->assertRedirect(route('admin.anggota.index'));

    $anggota->refresh();

    expect($anggota->status_verifikasi)->toBe('rejected')
        ->and($anggota->catatan_verifikasi)->toBe('Foto kurang jelas.');
});
