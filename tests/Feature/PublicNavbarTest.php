<?php

use App\Models\User;

test('navbar publik tampil konsisten di beranda berita dan struktur organisasi', function () {
    foreach ([route('home'), route('berita.index'), route('pengurus.index')] as $url) {
        $this->get($url)
            ->assertOk()
            ->assertSee('MWC NU Ajibarang')
            ->assertSee('Banyumas, Jawa Tengah')
            ->assertSee('Beranda')
            ->assertSee('Berita')
            ->assertSee('Struktur Organisasi')
            ->assertSee('Kartu Anggota')
            ->assertSee('Masuk')
            ->assertSee('Daftar')
            ->assertSee('aria-current="page"', false);
    }
});

test('navbar menampilkan menu akun untuk pengguna aktif', function () {
    $user = User::factory()->create(['name' => 'Siti Aminah']);

    $this->actingAs($user)->get(route('home'))
        ->assertOk()
        ->assertSee('Siti Aminah')
        ->assertSee('Dashboard')
        ->assertSee('Profil Saya')
        ->assertSee('Keluar')
        ->assertDontSee('Panel Admin');
});

test('navbar menampilkan panel admin hanya untuk admin dan super admin', function () {
    $admin = User::factory()->admin()->create(['name' => 'Admin MWC']);

    $this->actingAs($admin)->get(route('home'))
        ->assertOk()
        ->assertSee('Admin MWC')
        ->assertSee('Panel Admin');

    $superAdmin = User::factory()->superAdmin()->create(['name' => 'Super Admin MWC']);

    $this->actingAs($superAdmin)->get(route('home'))
        ->assertOk()
        ->assertSee('Super Admin MWC')
        ->assertSee('Super Admin')
        ->assertSee('Panel Admin');
});

test('navbar menampilkan status akun untuk pengguna yang belum aktif', function () {
    $pending = User::factory()->pending()->create(['name' => 'Pendaftar Baru']);

    $this->actingAs($pending)->get(route('home'))
        ->assertOk()
        ->assertSee('Pendaftar Baru')
        ->assertSee('Status Akun')
        ->assertSee('Menunggu Persetujuan')
        ->assertDontSee('Dashboard');
});

test('navbar menampilkan tombol masuk dan daftar untuk tamu', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Masuk')
        ->assertSee('Daftar')
        ->assertDontSee('Profil Saya')
        ->assertDontSee('action="'.route('logout').'"', false);
});
