<?php

use App\Models\User;

test('akun baru yang pending dapat login tetapi diarahkan ke halaman status', function () {
    $user = User::factory()->pending()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('account.status', absolute: false));
});

test('akun pending tidak dapat mengakses fitur anggota yang memerlukan akun aktif', function () {
    $user = User::factory()->pending()->create();

    $this->actingAs($user)->get(route('dashboard'))
        ->assertRedirect(route('account.status'));

    $this->actingAs($user)->get(route('anggota.create'))
        ->assertRedirect(route('account.status'));

    $this->actingAs($user)->post(route('anggota.store'), [])
        ->assertRedirect(route('account.status'));

    $this->actingAs($user)->get(route('kartu.index'))
        ->assertRedirect(route('account.status'));
});

test('akun pending tidak dapat membuka panel admin', function () {
    $user = User::factory()->pending()->create();

    $this->actingAs($user)->get(route('admin.index'))
        ->assertRedirect(route('account.status'));
});

test('akun pending tidak dapat menyetujui akunnya sendiri lewat request langsung', function () {
    $user = User::factory()->pending()->create();

    $this->actingAs($user)
        ->from(route('account.status'))
        ->patch(route('admin.pengguna.approve', $user))
        ->assertRedirect(route('account.status'));

    expect($user->fresh()->status)->toBe(User::STATUS_PENDING);
});

test('akun yang ditolak dapat login dan melihat alasan penolakan', function () {
    $user = User::factory()->rejected()->create([
        'status_note' => 'Data belum lengkap.',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('account.status'));

    $this->actingAs($user)->get(route('account.status'))
        ->assertOk()
        ->assertSee('Pendaftaran Ditolak')
        ->assertSee('Data belum lengkap.');
});

test('admin dapat melihat daftar pendaftar yang menunggu persetujuan', function () {
    $admin = User::factory()->admin()->create();
    $pending = User::factory()->pending()->create(['name' => 'Pendaftar Baru']);

    $this->actingAs($admin)->get(route('admin.pengguna.index'))
        ->assertOk()
        ->assertSee('Pendaftar Baru')
        ->assertSee($pending->email)
        ->assertSee('Setujui Akun');
});

test('admin dapat menyetujui pendaftaran pengguna', function () {
    $admin = User::factory()->admin()->create();
    $pending = User::factory()->pending()->create();

    $this->actingAs($admin)
        ->from(route('admin.pengguna.index'))
        ->patch(route('admin.pengguna.approve', $pending))
        ->assertRedirect(route('admin.pengguna.index'))
        ->assertSessionHas('success');

    $pending->refresh();

    expect($pending->status)->toBe(User::STATUS_ACTIVE)
        ->and($pending->reviewed_by)->toBe($admin->id)
        ->and($pending->reviewed_at)->not->toBeNull();
});

test('admin dapat menolak pendaftaran dengan alasan wajib', function () {
    $admin = User::factory()->admin()->create();
    $pending = User::factory()->pending()->create();

    $this->actingAs($admin)
        ->from(route('admin.pengguna.index'))
        ->patch(route('admin.pengguna.reject', $pending), [])
        ->assertSessionHasErrors('status_note');

    expect($pending->fresh()->status)->toBe(User::STATUS_PENDING);

    $this->actingAs($admin)
        ->from(route('admin.pengguna.index'))
        ->patch(route('admin.pengguna.reject', $pending), [
            'status_note' => 'Identitas tidak dapat diverifikasi.',
        ])
        ->assertRedirect(route('admin.pengguna.index'))
        ->assertSessionHas('success');

    $pending->refresh();

    expect($pending->status)->toBe(User::STATUS_REJECTED)
        ->and($pending->status_note)->toBe('Identitas tidak dapat diverifikasi.')
        ->and($pending->reviewed_by)->toBe($admin->id);
});

test('admin tidak dapat menyetujui akun admin lain', function () {
    $admin = User::factory()->admin()->create();
    $pendingAdmin = User::factory()->admin()->pending()->create();

    $this->actingAs($admin)
        ->patch(route('admin.pengguna.approve', $pendingAdmin))
        ->assertForbidden();

    expect($pendingAdmin->fresh()->status)->toBe(User::STATUS_PENDING);
});

test('admin tidak dapat mengubah role pengguna', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.pengguna.role', $user), [
            'role' => User::ROLE_SUPER_ADMIN,
        ])
        ->assertForbidden();

    expect($user->fresh()->role)->toBe(User::ROLE_USER);
});

test('super admin dapat mengelola role pengguna', function () {
    $superAdmin = User::factory()->superAdmin()->create();
    $user = User::factory()->create();

    $this->actingAs($superAdmin)
        ->from(route('admin.pengguna.index'))
        ->patch(route('admin.pengguna.role', $user), [
            'role' => User::ROLE_ADMIN,
        ])
        ->assertRedirect(route('admin.pengguna.index'))
        ->assertSessionHas('success');

    $user->refresh();

    expect($user->role)->toBe(User::ROLE_ADMIN)
        ->and($user->is_admin)->toBeTrue();

    $this->actingAs($superAdmin)
        ->from(route('admin.pengguna.index'))
        ->patch(route('admin.pengguna.role', $user), [
            'role' => User::ROLE_USER,
        ])
        ->assertRedirect(route('admin.pengguna.index'));

    $user->refresh();

    expect($user->role)->toBe(User::ROLE_USER)
        ->and($user->is_admin)->toBeFalse();
});

test('super admin tidak dapat mengubah role miliknya sendiri', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    $this->actingAs($superAdmin)
        ->patch(route('admin.pengguna.role', $superAdmin), [
            'role' => User::ROLE_ADMIN,
        ])
        ->assertForbidden();

    expect($superAdmin->fresh()->role)->toBe(User::ROLE_SUPER_ADMIN);
});

test('admin tidak dapat menyetujui akunnya sendiri lewat request langsung', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->patch(route('admin.pengguna.approve', $admin))
        ->assertForbidden();

    expect($admin->fresh()->status)->toBe(User::STATUS_ACTIVE);
});

test('role dan status tidak dapat dinaikkan melalui update profil', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->patch('/profile', [
        'name' => 'Nama Diperbarui',
        'email' => $user->email,
        'role' => User::ROLE_SUPER_ADMIN,
        'status' => User::STATUS_ACTIVE,
        'is_admin' => true,
    ])->assertSessionHasNoErrors();

    $user->refresh();

    expect($user->name)->toBe('Nama Diperbarui')
        ->and($user->role)->toBe(User::ROLE_USER)
        ->and($user->status)->toBe(User::STATUS_ACTIVE)
        ->and($user->is_admin)->toBeFalse();
});

test('super admin dapat menyetujui pendaftaran pengguna', function () {
    $superAdmin = User::factory()->superAdmin()->create();
    $pending = User::factory()->pending()->create();

    $this->actingAs($superAdmin)
        ->from(route('admin.pengguna.index'))
        ->patch(route('admin.pengguna.approve', $pending))
        ->assertRedirect(route('admin.pengguna.index'));

    expect($pending->fresh()->status)->toBe(User::STATUS_ACTIVE);
});
