<?php

use App\Models\Berita;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function buatBeritaDetail(string $judul, array $override = []): Berita
{
    return Berita::create(array_merge([
        'judul' => $judul,
        'slug' => Str::slug($judul).'-'.Str::random(6),
        'kategori' => 'Pengajian',
        'ringkasan' => 'Ringkasan berita untuk pengujian halaman detail.',
        'isi' => "Paragraf pertama berita.\n\nParagraf kedua berita.",
        'penulis' => 'Redaksi Pengujian',
        'status' => Berita::STATUS_PUBLISHED,
        'published_at' => now()->subDay(),
    ], $override));
}

test('halaman detail menampilkan judul, metadata, gambar, dan isi berita', function () {
    Storage::fake('public');

    $berita = buatBeritaDetail('Pengajian Rutin MWC NU Ajibarang', [
        'kategori' => 'Pengajian',
        'penulis' => 'Ust. Ahmad Fauzi',
        'published_at' => now()->setDate(2026, 10, 15)->setTime(9, 30),
        'gambar' => 'berita/sampul-detail.jpg',
    ]);

    Storage::disk('public')->put($berita->gambar, 'isi gambar');

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Pengajian Rutin MWC NU Ajibarang')
        ->assertSee($berita->published_at->translatedFormat('d F Y'))
        ->assertSee('Pengajian')
        ->assertSee('Ust. Ahmad Fauzi')
        ->assertSee('Ringkasan berita untuk pengujian halaman detail.')
        ->assertSee('Paragraf pertama berita.')
        ->assertSee('Paragraf kedua berita.')
        ->assertSee('storage/berita/sampul-detail.jpg', false)
        ->assertSee('Kembali ke Daftar Berita')
        ->assertDontSee('Gambar utama belum tersedia');
});

test('halaman detail menampilkan berita terkait dari kategori yang sama', function () {
    $berita = buatBeritaDetail('Berita Utama Pengajian', [
        'published_at' => now(),
    ]);

    $terkaitSatu = buatBeritaDetail('Pengajian Awal Bulan', [
        'published_at' => now()->subHour(),
    ]);
    $terkaitDua = buatBeritaDetail('Pengajian Majelis Ilmu', [
        'published_at' => now()->subHours(2),
    ]);
    $terkaitTiga = buatBeritaDetail('Pengajian Rutin Malam', [
        'published_at' => now()->subHours(3),
    ]);
    $terkaitKeempat = buatBeritaDetail('Pengajian Lama', [
        'published_at' => now()->subDays(4),
    ]);
    $kategoriLain = buatBeritaDetail('Kegiatan Sosial Warga', [
        'kategori' => 'Sosial',
        'published_at' => now()->subMinutes(30),
    ]);

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Berita Terkait')
        ->assertSee($terkaitSatu->judul)
        ->assertSee($terkaitDua->judul)
        ->assertSee($terkaitTiga->judul)
        ->assertDontSee($terkaitKeempat->judul)
        ->assertDontSee($kategoriLain->judul);
});

test('berita terkait memakai berita terbaru saat kategori yang sama tidak tersedia', function () {
    $berita = buatBeritaDetail('Berita Kategori Langka', [
        'kategori' => 'Langka',
        'published_at' => now(),
    ]);

    $beritaLain = buatBeritaDetail('Kegiatan Organisasi Terbaru', [
        'kategori' => 'Kegiatan Organisasi',
        'published_at' => now()->subHour(),
    ]);

    $beritaTidakTerbit = buatBeritaDetail('Berita Draft Tidak Tampil', [
        'kategori' => 'Langka',
        'status' => Berita::STATUS_DRAFT,
        'published_at' => null,
    ]);

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Berita Terkait')
        ->assertSee($beritaLain->judul)
        ->assertDontSee($beritaTidakTerbit->judul);
});

test('halaman detail menangani gambar hilang, tanggal null, dan isi kosong', function () {
    Storage::fake('public');

    $berita = buatBeritaDetail('Berita Tanpa Data Pelengkap', [
        'ringkasan' => null,
        'isi' => '',
        'penulis' => null,
        'published_at' => null,
        'gambar' => 'berita/gambar-tidak-ada.jpg',
    ]);

    DB::table('beritas')->where('id', $berita->id)->update([
        'created_at' => null,
        'updated_at' => null,
    ]);

    $berita->refresh();

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Berita Tanpa Data Pelengkap')
        ->assertSee('Tanggal tidak tersedia')
        ->assertSee('Gambar utama belum tersedia')
        ->assertSee('Isi berita belum tersedia.')
        ->assertDontSee('storage/berita/gambar-tidak-ada.jpg', false);
});
