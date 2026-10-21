<?php

use App\Models\Berita;
use App\Models\User;
use Illuminate\Support\Str;

function adminBerita(): User
{
    return User::factory()->admin()->create();
}

function superAdminBerita(): User
{
    return User::factory()->superAdmin()->create();
}

function dataPublikasiBerita(array $override = []): array
{
    return array_merge([
        'judul' => 'Pengajian Rutin MWC NU Ajibarang',
        'slug' => '',
        'kategori' => 'Pengajian',
        'ringkasan' => 'Ringkasan pengajian rutin.',
        'isi' => "Paragraf pertama berita.\n\nParagraf kedua berita.",
        'penulis' => 'Admin MWC',
        'status' => Berita::STATUS_PENDING,
    ], $override);
}

function buatBerita(User $pemilik, string $status, array $override = []): Berita
{
    return Berita::create(array_merge([
        'user_id' => $pemilik->id,
        'judul' => 'Berita '.$status,
        'slug' => 'berita-'.$status.'-'.Str::random(8),
        'isi' => 'Isi berita untuk pengujian alur publikasi.',
        'penulis' => $pemilik->name,
        'status' => $status,
        'published_at' => $status === Berita::STATUS_PUBLISHED ? now() : null,
    ], $override));
}

test('admin dapat menyimpan draft lalu mengajukannya untuk review', function () {
    $admin = adminBerita();

    $this->actingAs($admin)->post(route('admin.berita.store'), dataPublikasiBerita([
        'status' => Berita::STATUS_DRAFT,
    ]))->assertRedirect(route('admin.berita.index'));

    $berita = Berita::query()->firstOrFail();

    expect($berita->status)->toBe(Berita::STATUS_DRAFT)
        ->and($berita->user_id)->toBe($admin->id)
        ->and($berita->submitted_at)->toBeNull()
        ->and($berita->published_at)->toBeNull();

    $this->actingAs($admin)->put(route('admin.berita.update', $berita), dataPublikasiBerita([
        'judul' => 'Pengajian Rutin yang Diajukan',
        'status' => Berita::STATUS_PENDING,
    ]))->assertRedirect(route('admin.berita.index'));

    $berita->refresh();

    expect($berita->status)->toBe(Berita::STATUS_PENDING)
        ->and($berita->submitted_at)->not->toBeNull()
        ->and($berita->published_at)->toBeNull();
});

test('berita pending tidak tampil publik tetapi dapat dipratinjau pemilik dan super admin', function () {
    $pemilik = adminBerita();
    $adminLain = adminBerita();
    $superAdmin = superAdminBerita();
    $berita = buatBerita($pemilik, Berita::STATUS_PENDING, ['judul' => 'Berita Pending Rahasia']);

    $this->get(route('berita.index'))->assertOk()->assertDontSee($berita->judul);
    $this->get(route('berita.show', $berita))->assertNotFound();

    $this->actingAs($pemilik)->get(route('berita.show', $berita))
        ->assertOk()
        ->assertSee('Berita Pending Rahasia')
        ->assertSee('Pratinjau menunggu review');

    $this->actingAs($adminLain)->get(route('berita.show', $berita))->assertNotFound();
    $this->actingAs($superAdmin)->get(route('berita.show', $berita))->assertOk();
});

test('super admin dapat menyetujui berita pending hingga tampil publik', function () {
    $admin = adminBerita();
    $superAdmin = superAdminBerita();
    $berita = buatBerita($admin, Berita::STATUS_PENDING, [
        'judul' => 'Berita Menunggu Persetujuan',
        'submitted_at' => now(),
    ]);

    $this->actingAs($superAdmin)->get(route('admin.berita.index'))
        ->assertOk()
        ->assertSee('Berita Menunggu Review')
        ->assertSee('Berita Menunggu Persetujuan');

    $this->actingAs($superAdmin)->patch(route('admin.berita.review', $berita), [
        'action' => 'approve',
    ])->assertRedirect();

    $berita->refresh();

    expect($berita->status)->toBe(Berita::STATUS_PUBLISHED)
        ->and($berita->reviewed_by)->toBe($superAdmin->id)
        ->and($berita->reviewed_at)->not->toBeNull()
        ->and($berita->published_at)->not->toBeNull()
        ->and($berita->review_note)->toBeNull();

    $this->get(route('berita.index'))->assertOk()->assertSee($berita->judul);
    $this->get(route('berita.show', $berita))->assertOk()->assertSee($berita->judul);
});

test('penolakan berita wajib memiliki alasan dan alasan terlihat oleh admin', function () {
    $admin = adminBerita();
    $superAdmin = superAdminBerita();
    $berita = buatBerita($admin, Berita::STATUS_PENDING, [
        'judul' => 'Berita yang Akan Ditolak',
        'submitted_at' => now(),
    ]);

    $this->actingAs($superAdmin)->patch(route('admin.berita.review', $berita), [
        'action' => 'reject',
    ])->assertSessionHasErrors('review_note');

    expect($berita->fresh()->status)->toBe(Berita::STATUS_PENDING);

    $this->actingAs($superAdmin)->patch(route('admin.berita.review', $berita), [
        'action' => 'reject',
        'review_note' => 'Sumber berita belum dicantumkan.',
    ])->assertRedirect();

    $berita->refresh();

    expect($berita->status)->toBe(Berita::STATUS_REJECTED)
        ->and($berita->review_note)->toBe('Sumber berita belum dicantumkan.')
        ->and($berita->reviewed_by)->toBe($superAdmin->id)
        ->and($berita->published_at)->toBeNull();

    $this->actingAs($admin)->get(route('admin.berita.index'))
        ->assertOk()
        ->assertSee('Alasan ditolak: Sumber berita belum dicantumkan.');

    $this->app['auth']->forgetGuards();

    $this->get(route('berita.show', $berita))->assertNotFound();
});

test('admin tidak dapat menyetujui atau menolak berita', function () {
    $admin = adminBerita();
    $berita = buatBerita($admin, Berita::STATUS_PENDING);

    $this->actingAs($admin)->patch(route('admin.berita.review', $berita), [
        'action' => 'approve',
    ])->assertForbidden();

    expect($berita->fresh()->status)->toBe(Berita::STATUS_PENDING);
});

test('admin biasa tidak memiliki kontrol untuk menerbitkan berita', function () {
    $admin = adminBerita();
    $berita = buatBerita($admin, Berita::STATUS_PENDING, [
        'submitted_at' => now(),
    ]);

    $this->actingAs($admin)->get(route('admin.berita.index'))
        ->assertOk()
        ->assertSee('ajukan review kepada super admin')
        ->assertDontSee('Setujui &amp; Terbitkan', false);

    $this->actingAs($admin)->get(route('admin.berita.edit', $berita))
        ->assertOk()
        ->assertSee('Perlu persetujuan super admin')
        ->assertSee('Admin biasa tidak dapat menerbitkan berita langsung')
        ->assertDontSee('Setujui &amp; Terbitkan', false);
});

test('admin hanya dapat mengelola berita miliknya sendiri', function () {
    $admin = adminBerita();
    $adminLain = adminBerita();
    $superAdmin = superAdminBerita();
    $beritaLain = buatBerita($adminLain, Berita::STATUS_DRAFT, ['judul' => 'Berita Admin Lain']);

    $this->actingAs($admin)->get(route('admin.berita.index'))
        ->assertOk()
        ->assertDontSee('Berita Admin Lain');

    $this->actingAs($admin)->get(route('admin.berita.edit', $beritaLain))->assertForbidden();
    $this->actingAs($admin)->put(route('admin.berita.update', $beritaLain), dataPublikasiBerita())->assertForbidden();
    $this->actingAs($admin)->delete(route('admin.berita.destroy', $beritaLain))->assertForbidden();

    $this->actingAs($superAdmin)->get(route('admin.berita.index'))
        ->assertOk()
        ->assertSee('Berita Admin Lain');

    $this->actingAs($superAdmin)->get(route('admin.berita.edit', $beritaLain))->assertOk();
});

test('status terbit tidak dapat disuntikkan melalui request', function () {
    $admin = adminBerita();

    $this->actingAs($admin)->post(route('admin.berita.store'), dataPublikasiBerita([
        'judul' => 'Berita Curang',
        'status' => Berita::STATUS_PUBLISHED,
    ]))->assertSessionHasErrors('status');

    $this->assertDatabaseMissing('beritas', ['judul' => 'Berita Curang']);

    $berita = buatBerita($admin, Berita::STATUS_DRAFT, ['judul' => 'Tetap Draft']);

    $this->actingAs($admin)->put(route('admin.berita.update', $berita), dataPublikasiBerita([
        'judul' => 'Tetap Draft',
        'status' => Berita::STATUS_PUBLISHED,
    ]))->assertSessionHasErrors('status');

    expect($berita->fresh()->status)->toBe(Berita::STATUS_DRAFT);
});

test('revisi berita terbit harus melalui review ulang sebelum tampil publik', function () {
    $admin = adminBerita();
    $superAdmin = superAdminBerita();
    $berita = buatBerita($admin, Berita::STATUS_PUBLISHED, [
        'judul' => 'Judul Sebelum Revisi',
        'isi' => 'Isi sebelum revisi.',
    ]);

    $this->actingAs($admin)->put(route('admin.berita.update', $berita), dataPublikasiBerita([
        'judul' => 'Judul Setelah Revisi',
        'isi' => 'Isi setelah revisi.',
        'status' => Berita::STATUS_PENDING,
    ]))->assertRedirect(route('admin.berita.index'));

    $berita->refresh();

    expect($berita->status)->toBe(Berita::STATUS_PENDING)
        ->and($berita->published_at)->toBeNull();

    $this->app['auth']->forgetGuards();

    $this->get(route('berita.index'))->assertOk()->assertDontSee('Judul Setelah Revisi');
    $this->get(route('berita.show', $berita))->assertNotFound();

    $this->actingAs($superAdmin)->patch(route('admin.berita.review', $berita), [
        'action' => 'approve',
    ])->assertRedirect();

    $this->app['auth']->forgetGuards();

    $this->get(route('berita.show', $berita))->assertOk()->assertSee('Judul Setelah Revisi');
});

test('menyimpan berita terbit sebagai draft menariknya dari publikasi', function () {
    $admin = adminBerita();
    $berita = buatBerita($admin, Berita::STATUS_PUBLISHED, ['judul' => 'Berita Akan Ditarik']);

    $this->actingAs($admin)->put(route('admin.berita.update', $berita), dataPublikasiBerita([
        'judul' => 'Berita Akan Ditarik',
        'status' => Berita::STATUS_DRAFT,
    ]))->assertRedirect(route('admin.berita.index'));

    $berita->refresh();

    expect($berita->status)->toBe(Berita::STATUS_DRAFT)
        ->and($berita->published_at)->toBeNull();

    $this->app['auth']->forgetGuards();

    $this->get(route('berita.show', $berita))->assertNotFound();
});

test('dashboard menampilkan jumlah berita per status sesuai kewenangan', function () {
    $admin = adminBerita();
    $adminLain = adminBerita();
    $superAdmin = superAdminBerita();

    buatBerita($admin, Berita::STATUS_DRAFT);
    buatBerita($admin, Berita::STATUS_PENDING);
    buatBerita($admin, Berita::STATUS_PUBLISHED);
    buatBerita($adminLain, Berita::STATUS_REJECTED);
    buatBerita($adminLain, Berita::STATUS_PUBLISHED);

    $ringkasanAdmin = $this->actingAs($admin)->get(route('admin.index'))
        ->assertOk()
        ->viewData('ringkasanBerita');

    expect($ringkasanAdmin)->toMatchArray([
        'total' => 3,
        'draft' => 1,
        'pending' => 1,
        'published' => 1,
        'rejected' => 0,
    ]);

    $ringkasanSuperAdmin = $this->actingAs($superAdmin)->get(route('admin.index'))
        ->assertOk()
        ->viewData('ringkasanBerita');

    expect($ringkasanSuperAdmin)->toMatchArray([
        'total' => 5,
        'draft' => 1,
        'pending' => 1,
        'published' => 2,
        'rejected' => 1,
    ]);
});
