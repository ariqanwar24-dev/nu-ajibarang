<?php

use App\Models\Pengurus;
use App\Models\User;
use Database\Seeders\PengurusSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function pengurusAdmin(): User
{
    return User::factory()->admin()->create();
}

function dataPengurusBaru(array $override = []): array
{
    return array_merge([
        'nama' => 'Ahmad Fauzan Demonstrasi',
        'jabatan' => 'Ketua Bidang',
        'tingkatan' => 'tanfidziyah',
        'urutan' => 4,
    ], $override);
}

function fotoPengurusPng(): string
{
    return base64_decode(
        'iVBORw0KGgoAAAANSUhEUgAAAZAAAAGQCAYAAACAvzbMAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAWaSURBVHhe7dUhAQAhAMBAWpDkS9C/D/hXMH3izApszPVtAHg1/gEAbhgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAYCACJgQCQGAgAiYEAkBgIAImBAJAcAyn+5YPQ1twAAAAASUVORK5CYII='
    );
}

test('halaman publik mengelompokkan pengurus dari database dan menandai data demo', function () {
    $mustasyar = Pengurus::create([
        'nama' => 'KH. Contoh Mustasyar',
        'jabatan' => 'Mustasyar',
        'tingkatan' => 'mustasyar',
        'urutan' => 1,
        'is_demo' => true,
    ]);

    $banom = Pengurus::create([
        'nama' => 'Contoh Ketua Banom',
        'jabatan' => 'Ketua GP Ansor',
        'tingkatan' => 'GP Ansor',
        'urutan' => 2,
        'is_demo' => true,
    ]);

    $this->get(route('pengurus.index'))
        ->assertOk()
        ->assertSee('Dewan Mustasyar')
        ->assertSee('Pengurus Syuriyah')
        ->assertSee('Pengurus Tanfidziyah')
        ->assertSee('Badan Otonom (Banom NU)')
        ->assertSee($mustasyar->nama)
        ->assertSee($banom->nama)
        ->assertSee('GP Ansor')
        ->assertSee('Data demonstrasi')
        ->assertSee('bukan susunan pengurus resmi');
});

test('halaman detail pengurus menampilkan data database dan fallback foto', function () {
    $pengurus = Pengurus::create([
        'nama' => 'Hj. Contoh Pengurus',
        'jabatan' => 'Wakil Ketua',
        'tingkatan' => 'tanfidziyah',
        'is_demo' => true,
    ]);

    $this->get(route('pengurus.show', $pengurus))
        ->assertOk()
        ->assertSee($pengurus->nama)
        ->assertSee($pengurus->jabatan)
        ->assertSee('Foto belum tersedia')
        ->assertSee('Data contoh')
        ->assertSee('bukan data pengurus resmi');
});

test('admin dapat menambah pengurus beserta foto yang valid', function () {
    Storage::fake('public');

    $admin = pengurusAdmin();
    $foto = UploadedFile::fake()->createWithContent('foto-pengurus.png', fotoPengurusPng());

    $this->actingAs($admin)->post(route('admin.pengurus.store'), dataPengurusBaru([
        'foto' => $foto,
        'is_demo' => true,
    ]))->assertRedirect(route('admin.pengurus.index'));

    $pengurus = Pengurus::query()->where('nama', 'Ahmad Fauzan Demonstrasi')->firstOrFail();

    expect($pengurus->foto)->not->toBeNull()
        ->and($pengurus->is_demo)->toBeFalse()
        ->and($pengurus->urutan)->toBe(4);

    Storage::disk('public')->assertExists($pengurus->foto);
});

test('foto pengurus yang bukan gambar ditolak', function () {
    Storage::fake('public');

    $admin = pengurusAdmin();

    $this->actingAs($admin)->post(route('admin.pengurus.store'), dataPengurusBaru([
        'foto' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
    ]))->assertSessionHasErrors('foto');

    $this->assertDatabaseMissing('penguruses', [
        'nama' => 'Ahmad Fauzan Demonstrasi',
    ]);
});

test('admin dapat memperbarui pengurus dan mengganti foto lama secara aman', function () {
    Storage::fake('public');
    Storage::disk('public')->put('pengurus/foto-lama.png', fotoPengurusPng());

    $admin = pengurusAdmin();
    $pengurus = Pengurus::create([
        'nama' => 'Nama Awal',
        'jabatan' => 'Sekretaris',
        'tingkatan' => 'tanfidziyah',
        'foto' => 'pengurus/foto-lama.png',
    ]);

    $fotoBaru = UploadedFile::fake()->createWithContent('foto-baru.png', fotoPengurusPng());

    $this->actingAs($admin)->put(route('admin.pengurus.update', $pengurus), dataPengurusBaru([
        'nama' => 'Nama Setelah Diperbarui',
        'jabatan' => 'Wakil Sekretaris',
        'foto' => $fotoBaru,
    ]))->assertRedirect(route('admin.pengurus.index'));

    $pengurus->refresh();

    expect($pengurus->nama)->toBe('Nama Setelah Diperbarui')
        ->and($pengurus->jabatan)->toBe('Wakil Sekretaris')
        ->and($pengurus->foto)->not->toBe('pengurus/foto-lama.png');

    Storage::disk('public')->assertMissing('pengurus/foto-lama.png');
    Storage::disk('public')->assertExists($pengurus->foto);
});

test('admin dapat menghapus pengurus beserta fotonya', function () {
    Storage::fake('public');
    Storage::disk('public')->put('pengurus/foto-hapus.png', fotoPengurusPng());

    $admin = pengurusAdmin();
    $pengurus = Pengurus::create([
        'nama' => 'Pengurus Akan Dihapus',
        'jabatan' => 'Anggota',
        'tingkatan' => 'syuriyah',
        'foto' => 'pengurus/foto-hapus.png',
    ]);

    $this->actingAs($admin)->delete(route('admin.pengurus.destroy', $pengurus))
        ->assertRedirect(route('admin.pengurus.index'));

    $this->assertDatabaseMissing('penguruses', ['id' => $pengurus->id]);
    Storage::disk('public')->assertMissing('pengurus/foto-hapus.png');
});

test('pengguna biasa tidak dapat mengelola pengurus', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.pengurus.index'))->assertForbidden();
    $this->actingAs($user)->post(route('admin.pengurus.store'), dataPengurusBaru())->assertForbidden();
});

test('halaman admin menampilkan data pengurus dengan pagination', function () {
    $this->seed(PengurusSeeder::class);

    $admin = pengurusAdmin();

    $this->actingAs($admin)->get(route('admin.pengurus.index'))
        ->assertOk()
        ->assertSee('Kelola Pengurus')
        ->assertSee('data demonstrasi')
        ->assertSee('Demo')
        ->assertSee('Lihat ↗')
        ->assertSee('?page=2', false);
});

test('seeder pengurus dapat dijalankan ulang tanpa membuat duplikat', function () {
    $this->seed(PengurusSeeder::class);

    $jumlahPertama = Pengurus::query()->where('is_demo', true)->count();

    $this->seed(PengurusSeeder::class);

    expect($jumlahPertama)->toBeGreaterThan(0)
        ->and(Pengurus::query()->where('is_demo', true)->count())->toBe($jumlahPertama)
        ->and(Pengurus::query()->where('seed_key', 'demo-mustasyar-01')->count())->toBe(1);
});
