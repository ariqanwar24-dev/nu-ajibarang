<?php

use App\Models\Berita;
use App\Models\User;
use Illuminate\Support\Str;

function buatBeritaTracking(string $judul, array $override = []): Berita
{
    return Berita::create(array_merge([
        'judul' => $judul,
        'slug' => Str::slug($judul).'-'.Str::random(6),
        'kategori' => 'Kegiatan',
        'ringkasan' => 'Ringkasan berita untuk pengujian pencarian.',
        'isi' => 'Isi berita untuk pengujian pencarian publikasi.',
        'penulis' => 'Redaksi Pengujian',
        'status' => Berita::STATUS_PUBLISHED,
        'published_at' => now()->subDay(),
    ], $override));
}

test('halaman lacak berita menampilkan formulir pencarian tanpa data anggota', function () {
    $this->get(route('tracking.index'))
        ->assertOk()
        ->assertSee('Lacak Berita')
        ->assertSee('Cari Berita')
        ->assertSee('Judul atau kata kunci berita')
        ->assertSee('Status publikasi')
        ->assertSee('Lokasi kegiatan')
        ->assertDontSee('Nomor Anggota')
        ->assertDontSee('Nomor HP')
        ->assertDontSee('Privasi anggota');
});

test('pengunjung publik dapat mencari berita terbit berdasarkan judul', function () {
    $berita = buatBeritaTracking('Pengajian Rutin MWC NU Ajibarang', [
        'kategori' => 'Pengajian',
        'ringkasan' => 'Kegiatan pengajian rutin bersama warga NU.',
    ]);

    $this->get(route('tracking.index', ['q' => 'Pengajian Rutin']))
        ->assertOk()
        ->assertSee('Hasil pencarian')
        ->assertSee('1</span> berita ditemukan', false)
        ->assertSee($berita->judul)
        ->assertSee('Kegiatan pengajian rutin bersama warga NU.')
        ->assertSee('Terbit')
        ->assertSee('Lihat detail')
        ->assertSee(route('berita.show', $berita), false);
});

test('pencarian juga mencakup isi berita dan kata kunci di dalamnya', function () {
    $berita = buatBeritaTracking('Kegiatan Sosial Warga', [
        'isi' => 'Pembagian sembako untuk santri dan duafa di Ajibarang.',
    ]);

    $this->get(route('tracking.index', ['q' => 'santri']))
        ->assertOk()
        ->assertSee($berita->judul)
        ->assertSee('Hasil pencarian');
});

test('pengunjung publik tidak dapat menemukan berita yang belum terbit', function () {
    $draft = buatBeritaTracking('Rapat Koordinasi Internal', [
        'status' => Berita::STATUS_DRAFT,
        'published_at' => null,
    ]);

    $pending = buatBeritaTracking('Laporan Kegiatan Menunggu', [
        'status' => Berita::STATUS_PENDING,
        'submitted_at' => now(),
        'published_at' => null,
    ]);

    $rejected = buatBeritaTracking('Usulan Berita Ditolak', [
        'status' => Berita::STATUS_REJECTED,
        'review_note' => 'Perlu perbaikan data.',
        'published_at' => null,
    ]);

    $this->get(route('tracking.index', ['q' => 'Internal']))
        ->assertOk()
        ->assertSee('Berita tidak ditemukan')
        ->assertDontSee($draft->judul)
        ->assertDontSee('Menunggu Review')
        ->assertDontSee('Ditolak');

    $this->get(route('tracking.index', ['q' => 'Menunggu']))
        ->assertOk()
        ->assertDontSee($pending->judul);

    $this->get(route('tracking.index', ['q' => 'Ditolak']))
        ->assertOk()
        ->assertDontSee($rejected->judul);
});

test('super admin dapat melihat semua status internal berita pada hasil pencarian', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    buatBeritaTracking('Rapat Koordinasi Internal', [
        'status' => Berita::STATUS_DRAFT,
        'published_at' => null,
    ]);
    buatBeritaTracking('Laporan Kegiatan Internal', [
        'status' => Berita::STATUS_PENDING,
        'submitted_at' => now(),
        'published_at' => null,
    ]);
    buatBeritaTracking('Usulan Berita Internal', [
        'status' => Berita::STATUS_REJECTED,
        'review_note' => 'Perlu perbaikan data.',
        'published_at' => null,
    ]);

    $this->actingAs($superAdmin)
        ->get(route('tracking.index', ['q' => 'Internal']))
        ->assertOk()
        ->assertSee('Mode admin')
        ->assertSee('Rapat Koordinasi Internal')
        ->assertSee('Laporan Kegiatan Internal')
        ->assertSee('Usulan Berita Internal')
        ->assertSee('Draft')
        ->assertSee('Menunggu Review')
        ->assertSee('Ditolak');
});

test('admin biasa hanya melihat status internal berita miliknya sendiri', function () {
    $admin = User::factory()->admin()->create();

    buatBeritaTracking('Rapat Koordinasi Milik Sendiri', [
        'user_id' => $admin->id,
        'status' => Berita::STATUS_PENDING,
        'submitted_at' => now(),
        'published_at' => null,
    ]);

    buatBeritaTracking('Rapat Koordinasi Milik Admin Lain', [
        'user_id' => User::factory()->admin()->create()->id,
        'status' => Berita::STATUS_DRAFT,
        'published_at' => null,
    ]);

    $this->actingAs($admin)
        ->get(route('tracking.index', ['q' => 'Rapat Koordinasi']))
        ->assertOk()
        ->assertSee('Rapat Koordinasi Milik Sendiri')
        ->assertSee('Menunggu Review')
        ->assertDontSee('Rapat Koordinasi Milik Admin Lain');
});

test('pengguna biasa tidak memperoleh status internal meskipun sudah login', function () {
    $pengguna = User::factory()->create();

    buatBeritaTracking('Rapat Koordinasi Internal', [
        'status' => Berita::STATUS_DRAFT,
        'published_at' => null,
    ]);

    $this->actingAs($pengguna)
        ->get(route('tracking.index', ['q' => 'Internal']))
        ->assertOk()
        ->assertSee('Berita tidak ditemukan')
        ->assertDontSee('Rapat Koordinasi Internal')
        ->assertDontSee('Mode admin');
});

test('pencarian tanpa hasil menampilkan pesan yang jelas', function () {
    $this->get(route('tracking.index', ['q' => 'Kata Yang Tidak Ada']))
        ->assertOk()
        ->assertSee('Berita tidak ditemukan')
        ->assertSee('Kata Yang Tidak Ada')
        ->assertSee('Coba kata kunci lain')
        ->assertSee('Lihat semua berita')
        ->assertDontSee('Hasil pencarian');
});

test('kata kunci divalidasi sebelum digunakan untuk mencari', function () {
    $this->get(route('tracking.index', ['q' => 'ab']))
        ->assertRedirect()
        ->assertSessionHasErrors('q');

    $this->get(route('tracking.index', ['q' => str_repeat('a', 101)]))
        ->assertRedirect()
        ->assertSessionHasErrors('q');
});

test('kata kunci ditampilkan dengan aman tanpa mengeksekusi HTML', function () {
    $this->get(route('tracking.index', ['q' => '<script>alert(1)</script>']))
        ->assertOk()
        ->assertSee('Berita tidak ditemukan')
        ->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
});

test('detail berita menampilkan tombol Google Maps berdasarkan alamat yang tersimpan', function () {
    $berita = buatBeritaTracking('Pengajian di Pondok Pesantren', [
        'lokasi_nama' => 'Pondok Pesantren Al-Falah',
        'lokasi_alamat' => 'Jalan Raya Ajibarang Nomor 10, Ajibarang Wetan',
        'lokasi_desa' => 'Ajibarang Wetan',
        'latitude' => null,
        'longitude' => null,
    ]);

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Lokasi Acara')
        ->assertSee('Pondok Pesantren Al-Falah')
        ->assertSee('Jalan Raya Ajibarang Nomor 10, Ajibarang Wetan')
        ->assertSee('Lihat Lokasi di Google Maps')
        ->assertSee(
            rawurlencode('Jalan Raya Ajibarang Nomor 10, Ajibarang Wetan'),
            false
        );
});

test('detail berita memakai koordinat asli untuk tautan Google Maps', function () {
    $berita = buatBeritaTracking('Kegiatan dengan Titik Koordinat', [
        'lokasi_nama' => 'Aula MWC NU',
        'latitude' => -7.4123456,
        'longitude' => 109.0123456,
    ]);

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Lihat Lokasi di Google Maps')
        ->assertSee(
            rawurlencode('-7.4123456,109.0123456'),
            false
        )
        ->assertSee('Petunjuk Arah');
});

test('detail berita tanpa lokasi tidak menampilkan tombol Google Maps', function () {
    $berita = buatBeritaTracking('Berita Tanpa Lokasi Kegiatan', [
        'lokasi_nama' => null,
        'lokasi_alamat' => null,
        'lokasi_desa' => null,
        'latitude' => null,
        'longitude' => null,
    ]);

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertDontSee('Lihat Lokasi di Google Maps')
        ->assertDontSee('Lokasi Acara');
});

test('alamat yang terlalu pendek tidak dibuatkan tautan Google Maps', function () {
    $berita = buatBeritaTracking('Kegiatan dengan Alamat Belum Lengkap', [
        'lokasi_nama' => 'Tempat Kegiatan',
        'lokasi_alamat' => 'RT 3',
        'latitude' => null,
        'longitude' => null,
    ]);

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Lokasi Acara')
        ->assertSee('Lokasi belum tersedia')
        ->assertDontSee('Lihat Lokasi di Google Maps');
});

test('hasil pencarian menampilkan lokasi ringkas dan tautan peta bila tersedia', function () {
    $berita = buatBeritaTracking('Kajian Akbar di Ajibarang', [
        'lokasi_nama' => 'Masjid Agung Ajibarang',
        'lokasi_desa' => 'Ajibarang Kulon',
        'lokasi_alamat' => 'Jalan Raya Ajibarang, Ajibarang Kulon',
        'latitude' => null,
        'longitude' => null,
    ]);

    $this->get(route('tracking.index', ['q' => 'Kajian Akbar']))
        ->assertOk()
        ->assertSee($berita->judul)
        ->assertSee('Masjid Agung Ajibarang')
        ->assertSee('Peta')
        ->assertSee(
            rawurlencode('Jalan Raya Ajibarang, Ajibarang Kulon'),
            false
        );
});
