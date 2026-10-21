<?php

use App\Models\Berita;
use App\Models\BeritaFoto;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function adminGaleri(): User
{
    return User::factory()->admin()->create();
}

function isiGambarGaleri(): string
{
    return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl2nXQAAAAASUVORK5CYII=');
}

function unggahGambarGaleri(string $nama = 'galeri.png'): UploadedFile
{
    return UploadedFile::fake()->createWithContent($nama, isiGambarGaleri());
}

function dataGaleriBerita(array $override = []): array
{
    return array_merge([
        'judul' => 'Kegiatan MWC NU Ajibarang',
        'slug' => '',
        'kategori' => 'Kegiatan Organisasi',
        'ringkasan' => 'Ringkasan kegiatan.',
        'isi' => 'Isi berita kegiatan.',
        'penulis' => 'Admin MWC',
        'status' => Berita::STATUS_DRAFT,
    ], $override);
}

function buatBeritaGaleri(User $pemilik, string $status = Berita::STATUS_DRAFT): Berita
{
    return Berita::create([
        'user_id' => $pemilik->id,
        'judul' => 'Berita galeri '.Str::random(6),
        'slug' => 'berita-galeri-'.Str::random(10),
        'isi' => 'Isi berita untuk pengujian galeri.',
        'penulis' => $pemilik->name,
        'status' => $status,
        'published_at' => $status === Berita::STATUS_PUBLISHED ? now() : null,
    ]);
}

function tambahFotoGaleri(Berita $berita, string $nama = 'foto.png', int $urutan = 1): BeritaFoto
{
    $path = 'berita/galeri/'.Str::random(12).'.png';

    Storage::disk('local')->put($path, isiGambarGaleri());

    return $berita->galeri()->create([
        'path' => $path,
        'original_name' => $nama,
        'mime_type' => 'image/png',
        'size' => strlen(isiGambarGaleri()),
        'urutan' => $urutan,
    ]);
}

test('admin dapat memilih dan mengunggah tiga foto galeri sekaligus saat membuat berita', function () {
    Storage::fake('local');
    Storage::fake('public');

    $admin = adminGaleri();

    $this->actingAs($admin)
        ->post(route('admin.berita.store'), dataGaleriBerita([
            'galeri' => [
                unggahGambarGaleri('pertama.png'),
                unggahGambarGaleri('kedua.png'),
                unggahGambarGaleri('ketiga.png'),
            ],
        ]))
        ->assertRedirect(route('admin.berita.index'));

    $berita = Berita::query()->firstOrFail();

    expect($berita->galeri)->toHaveCount(3)
        ->and($berita->galeri->pluck('urutan')->all())->toBe([1, 2, 3])
        ->and($berita->galeri->pluck('original_name')->all())->toBe([
            'pertama.png',
            'kedua.png',
            'ketiga.png',
        ]);

    foreach ($berita->galeri as $foto) {
        Storage::disk('local')->assertExists($foto->path);
        Storage::disk('public')->assertMissing($foto->path);

        expect($foto->mime_type)->toBe('image/png')
            ->and($foto->size)->toBeGreaterThan(0)
            ->and($foto->original_name)->not->toBeEmpty();
    }
});

test('foto galeri dapat ditambahkan saat edit tanpa menghapus foto lama', function () {
    Storage::fake('local');
    Storage::fake('public');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin);
    $fotoLama = tambahFotoGaleri($berita, 'lama.png');

    $this->actingAs($admin)
        ->put(route('admin.berita.update', $berita), dataGaleriBerita([
            'judul' => 'Kegiatan yang Diperbarui',
            'galeri' => [unggahGambarGaleri('baru.png')],
        ]))
        ->assertRedirect(route('admin.berita.index'));

    $berita->refresh();

    expect($berita->galeri)->toHaveCount(2)
        ->and($berita->galeri->pluck('id'))->toContain($fotoLama->id);

    Storage::disk('local')->assertExists($fotoLama->path);
});

test('admin dapat mengganti foto galeri tanpa menambah jumlah foto', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin);
    $foto = tambahFotoGaleri($berita, 'lama.png', 1);
    $fotoLain = tambahFotoGaleri($berita, 'lain.png', 2);
    tambahFotoGaleri($berita, 'ketiga.png', 3);
    $idFoto = $foto->id;
    $pathLama = $foto->path;

    $this->actingAs($admin)
        ->patch(route('admin.berita.galeri.replace', [$berita, $foto]), [
            'foto' => unggahGambarGaleri('pengganti.png'),
        ])
        ->assertRedirect();

    $foto->refresh();

    expect($foto->id)->toBe($idFoto)
        ->and($foto->original_name)->toBe('pengganti.png')
        ->and($foto->path)->not->toBe($pathLama)
        ->and($foto->urutan)->toBe(1)
        ->and($berita->galeri()->count())->toBe(3);

    Storage::disk('local')->assertMissing($pathLama);
    Storage::disk('local')->assertExists($foto->path);
    Storage::disk('local')->assertExists($fotoLain->path);
});

test('mengganti foto galeri berita terbit menarik berita dari publikasi untuk review ulang', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin, Berita::STATUS_PUBLISHED);
    $foto = tambahFotoGaleri($berita);

    $this->actingAs($admin)
        ->patch(route('admin.berita.galeri.replace', [$berita, $foto]), [
            'foto' => unggahGambarGaleri('pengganti.png'),
        ])
        ->assertRedirect();

    $berita->refresh();

    expect($berita->status)->toBe(Berita::STATUS_PENDING)
        ->and($berita->published_at)->toBeNull()
        ->and($berita->submitted_at)->not->toBeNull();

    auth()->logout();

    $this->get(route('berita.galeri.show', [$berita, $foto]))
        ->assertNotFound();
});

test('foto pengganti yang melebihi delapan megabita ditolak dan foto lama tetap tersimpan', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin);
    $foto = tambahFotoGaleri($berita);
    $pathLama = $foto->path;

    $this->actingAs($admin)
        ->patch(route('admin.berita.galeri.replace', [$berita, $foto]), [
            'foto' => unggahGambarGaleri('besar.png')->size(Berita::MAX_GALERI_SIZE_MB * 1024 + 1),
        ])
        ->assertSessionHasErrors([
            'foto' => 'Ukuran setiap foto galeri maksimal 8 MB.',
        ]);

    $foto->refresh();

    expect($foto->path)->toBe($pathLama)
        ->and($berita->galeri()->count())->toBe(1);

    Storage::disk('local')->assertExists($pathLama);
});

test('validasi galeri menolak berkas bukan gambar dan unggahan lebih dari tiga foto', function () {
    Storage::fake('local');

    $admin = adminGaleri();

    $this->actingAs($admin)
        ->post(route('admin.berita.store'), dataGaleriBerita([
            'galeri' => [
                UploadedFile::fake()->create('dokumen.txt', 10, 'text/plain'),
            ],
        ]))
        ->assertSessionHasErrors([
            'galeri.0' => 'Setiap berkas galeri harus berupa gambar.',
        ]);

    expect(Berita::query()->count())->toBe(0);

    $empatFoto = collect(range(1, 4))
        ->map(fn (int $nomor) => unggahGambarGaleri("foto-{$nomor}.png"))
        ->all();

    $this->actingAs($admin)
        ->post(route('admin.berita.store'), dataGaleriBerita([
            'galeri' => $empatFoto,
        ]))
        ->assertSessionHasErrors([
            'galeri' => 'Maksimal 3 foto dapat diunggah dalam satu waktu.',
        ]);

    expect(Berita::query()->count())->toBe(0);
});

test('foto galeri berukuran tepat delapan megabita diterima', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $foto = unggahGambarGaleri('delapan-megabita.png')->size(Berita::MAX_GALERI_SIZE_MB * 1024);

    $this->actingAs($admin)
        ->post(route('admin.berita.store'), dataGaleriBerita([
            'galeri' => [$foto],
        ]))
        ->assertRedirect(route('admin.berita.index'));

    $berita = Berita::query()->firstOrFail();

    expect($berita->galeri)->toHaveCount(1);
    Storage::disk('local')->assertExists($berita->galeri->first()->path);
});

test('validasi galeri menolak foto yang melebihi delapan megabita', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $fotoBesar = unggahGambarGaleri('besar.png')->size(Berita::MAX_GALERI_SIZE_MB * 1024 + 1);

    $this->actingAs($admin)
        ->post(route('admin.berita.store'), dataGaleriBerita([
            'galeri' => [$fotoBesar],
        ]))
        ->assertSessionHasErrors([
            'galeri.0' => 'Ukuran setiap foto galeri maksimal 8 MB.',
        ]);

    expect(Berita::query()->count())->toBe(0);
});

test('total foto galeri dibatasi tiga foto per berita', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin);

    foreach (range(1, Berita::MAX_GALERI) as $urutan) {
        $berita->galeri()->create([
            'path' => 'berita/galeri/tersimpan-'.$urutan.'.png',
            'original_name' => 'tersimpan-'.$urutan.'.png',
            'mime_type' => 'image/png',
            'size' => 100,
            'urutan' => $urutan,
        ]);
    }

    $this->actingAs($admin)
        ->put(route('admin.berita.update', $berita), dataGaleriBerita([
            'galeri' => [unggahGambarGaleri('tambahan.png')],
        ]))
        ->assertSessionHasErrors([
            'galeri' => 'Galeri berita maksimal 3 foto. Saat ini tersimpan 3 foto, sehingga hanya 0 foto baru yang dapat ditambahkan.',
        ]);

    expect($berita->galeri()->count())->toBe(Berita::MAX_GALERI);
});

test('foto berita terbit dapat diakses publik dan foto berita pending tidak', function () {
    Storage::fake('local');
    Storage::fake('public');

    $pemilik = adminGaleri();
    $beritaTerbit = buatBeritaGaleri($pemilik, Berita::STATUS_PUBLISHED);
    $fotoTerbit = tambahFotoGaleri($beritaTerbit, 'terbit.png');

    $response = $this->get(route('berita.galeri.show', [$beritaTerbit, $fotoTerbit]))
        ->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff');

    expect($response->headers->get('content-type'))->toContain('image/png');

    Storage::disk('public')->assertMissing($fotoTerbit->path);

    $beritaPending = buatBeritaGaleri($pemilik, Berita::STATUS_PENDING);
    $fotoPending = tambahFotoGaleri($beritaPending, 'pending.png');

    $this->get(route('berita.galeri.show', [$beritaPending, $fotoPending]))
        ->assertNotFound();

    $this->actingAs($pemilik)
        ->get(route('berita.galeri.show', [$beritaPending, $fotoPending]))
        ->assertOk();
});

test('admin lain tidak dapat mengakses foto galeri berita draft yang bukan miliknya', function () {
    Storage::fake('local');

    $pemilik = adminGaleri();
    $adminLain = adminGaleri();
    $berita = buatBeritaGaleri($pemilik);
    $foto = tambahFotoGaleri($berita);

    $this->actingAs($adminLain)
        ->get(route('berita.galeri.show', [$berita, $foto]))
        ->assertNotFound();
});

test('foto galeri milik berita lain tidak dapat diakses lewat berita yang salah', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $beritaA = buatBeritaGaleri($admin, Berita::STATUS_PUBLISHED);
    $beritaB = buatBeritaGaleri($admin, Berita::STATUS_PUBLISHED);
    $fotoB = tambahFotoGaleri($beritaB);

    $this->get(route('berita.galeri.show', [$beritaA, $fotoB]))
        ->assertNotFound();
});

test('foto galeri tidak dapat dihapus lewat berita yang salah', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $beritaA = buatBeritaGaleri($admin);
    $beritaB = buatBeritaGaleri($admin);
    $fotoB = tambahFotoGaleri($beritaB);

    $this->actingAs($admin)
        ->delete(route('admin.berita.galeri.destroy', [$beritaA, $fotoB]))
        ->assertNotFound();

    $this->assertDatabaseHas('berita_fotos', ['id' => $fotoB->id]);
});

test('admin dapat menghapus satu foto galeri tanpa menghapus berita', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin);
    $foto = tambahFotoGaleri($berita);

    $this->actingAs($admin)
        ->delete(route('admin.berita.galeri.destroy', [$berita, $foto]))
        ->assertRedirect();

    $this->assertDatabaseMissing('berita_fotos', ['id' => $foto->id]);
    $this->assertDatabaseHas('beritas', ['id' => $berita->id]);

    Storage::disk('local')->assertMissing($foto->path);
});

test('admin lain tidak dapat menghapus foto galeri milik admin lain', function () {
    Storage::fake('local');

    $pemilik = adminGaleri();
    $adminLain = adminGaleri();
    $berita = buatBeritaGaleri($pemilik);
    $foto = tambahFotoGaleri($berita);

    $this->actingAs($adminLain)
        ->delete(route('admin.berita.galeri.destroy', [$berita, $foto]))
        ->assertForbidden();

    $this->assertDatabaseHas('berita_fotos', ['id' => $foto->id]);
});

test('admin lain tidak dapat mengganti foto galeri milik admin lain', function () {
    Storage::fake('local');

    $pemilik = adminGaleri();
    $adminLain = adminGaleri();
    $berita = buatBeritaGaleri($pemilik);
    $foto = tambahFotoGaleri($berita);
    $pathLama = $foto->path;

    $this->actingAs($adminLain)
        ->patch(route('admin.berita.galeri.replace', [$berita, $foto]), [
            'foto' => unggahGambarGaleri('pengganti.png'),
        ])
        ->assertForbidden();

    $foto->refresh();

    expect($foto->path)->toBe($pathLama);
    Storage::disk('local')->assertExists($pathLama);
});

test('foto galeri tidak dapat diganti lewat berita yang salah', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $beritaA = buatBeritaGaleri($admin);
    $beritaB = buatBeritaGaleri($admin);
    $fotoB = tambahFotoGaleri($beritaB);
    $pathLama = $fotoB->path;

    $this->actingAs($admin)
        ->patch(route('admin.berita.galeri.replace', [$beritaA, $fotoB]), [
            'foto' => unggahGambarGaleri('pengganti.png'),
        ])
        ->assertNotFound();

    $fotoB->refresh();

    expect($fotoB->path)->toBe($pathLama);
    Storage::disk('local')->assertExists($pathLama);
});

test('menghapus foto galeri berita terbit menarik berita dari publikasi untuk review ulang', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin, Berita::STATUS_PUBLISHED);
    $foto = tambahFotoGaleri($berita);

    $this->actingAs($admin)
        ->delete(route('admin.berita.galeri.destroy', [$berita, $foto]))
        ->assertRedirect();

    $berita->refresh();

    expect($berita->status)->toBe(Berita::STATUS_PENDING)
        ->and($berita->published_at)->toBeNull()
        ->and($berita->submitted_at)->not->toBeNull();

    $this->get(route('berita.galeri.show', [$berita, $foto]))
        ->assertNotFound();
});

test('menghapus berita menghapus seluruh berkas foto galeri', function () {
    Storage::fake('local');
    Storage::fake('public');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin);
    $fotoPertama = tambahFotoGaleri($berita, 'satu.png', 1);
    $fotoKedua = tambahFotoGaleri($berita, 'dua.png', 2);

    $this->actingAs($admin)
        ->delete(route('admin.berita.destroy', $berita))
        ->assertRedirect(route('admin.berita.index'));

    Storage::disk('local')->assertMissing($fotoPertama->path);
    Storage::disk('local')->assertMissing($fotoKedua->path);
    $this->assertDatabaseCount('berita_fotos', 0);
});

test('halaman edit menampilkan kontrol ganti dan menyembunyikan tambah saat galeri penuh', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin);
    $foto = tambahFotoGaleri($berita, 'satu.png', 1);
    tambahFotoGaleri($berita, 'dua.png', 2);
    tambahFotoGaleri($berita, 'tiga.png', 3);

    $this->actingAs($admin)
        ->get(route('admin.berita.edit', $berita))
        ->assertOk()
        ->assertSee('Galeri Foto')
        ->assertSee('Ganti foto')
        ->assertSee('Galeri sudah penuh.')
        ->assertSee(route('admin.berita.galeri.replace', [$berita, $foto]), false)
        ->assertSee(route('admin.berita.galeri.destroy', [$berita, $foto]), false)
        ->assertDontSee('Tambah Foto Galeri')
        ->assertDontSee('id="galeri"', false);
});

test('halaman detail berita terbit menampilkan grid galeri', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin, Berita::STATUS_PUBLISHED);
    $foto = tambahFotoGaleri($berita);

    $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Galeri Foto')
        ->assertSee(route('berita.galeri.show', [$berita, $foto]), false);
});

test('halaman detail menampilkan maksimal tiga foto dan tidak menghapus foto lama yang berlebih', function () {
    Storage::fake('local');

    $admin = adminGaleri();
    $berita = buatBeritaGaleri($admin, Berita::STATUS_PUBLISHED);
    $foto = collect(range(1, 4))
        ->map(fn (int $nomor) => tambahFotoGaleri($berita, "lama-{$nomor}.png", $nomor));

    $response = $this->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('3 foto')
        ->assertDontSee('4 foto');

    $foto->take(3)->each(function (BeritaFoto $item) use ($response, $berita) {
        $response->assertSee(route('berita.galeri.show', [$berita, $item]), false);
    });

    $response->assertDontSee(route('berita.galeri.show', [$berita, $foto->last()]), false);

    expect($berita->galeri()->count())->toBe(4);

    $foto->each(fn (BeritaFoto $item) => Storage::disk('local')->assertExists($item->path));
});
