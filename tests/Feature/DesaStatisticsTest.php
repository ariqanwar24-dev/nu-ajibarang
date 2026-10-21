<?php

use App\Models\Anggota;
use App\Models\Desa;
use App\Models\User;

function desaStatistik(string $nama): Desa
{
    return Desa::query()->where('nama', $nama)->firstOrFail();
}

function anggotaStatistik(User $user, Desa $desa, string $status, array $override = []): Anggota
{
    return Anggota::create(array_merge([
        'user_id' => $user->id,
        'desa_id' => $desa->id,
        'nama_anggota' => 'Anggota '.$desa->nama,
        'rt' => '01',
        'rw' => '02',
        'jenis_kelamin' => 'L',
        'no_hp' => '081234567890',
        'status_verifikasi' => $status,
    ], $override));
}

test('dashboard menampilkan statistik resmi seluruh desa dari database', function () {
    $admin = User::factory()->admin()->create();
    $kulon = desaStatistik('Ajibarang Kulon');
    $wetan = desaStatistik('Ajibarang Wetan');
    $banjarsari = desaStatistik('Banjarsari');
    $ciberung = desaStatistik('Ciberung');

    anggotaStatistik(User::factory()->create(), $kulon, Anggota::STATUS_APPROVED);
    anggotaStatistik(User::factory()->create(), $kulon, Anggota::STATUS_APPROVED);
    anggotaStatistik(User::factory()->create(), $wetan, Anggota::STATUS_APPROVED);
    anggotaStatistik(User::factory()->create(), $kulon, Anggota::STATUS_PENDING);
    anggotaStatistik(User::factory()->create(), $banjarsari, Anggota::STATUS_PENDING);
    anggotaStatistik(User::factory()->create(), $ciberung, Anggota::STATUS_REJECTED);
    anggotaStatistik(User::factory()->create(), $kulon, Anggota::STATUS_APPROVED, [
        'desa_id' => null,
        'nama_anggota' => 'Anggota Lama Tanpa Desa',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.index'));

    $response->assertOk()
        ->assertViewHas('anggotaDisetujui', 4)
        ->assertViewHas('anggotaMenunggu', 2)
        ->assertViewHas('desaDenganAnggota', 2)
        ->assertViewHas('desaTanpaAnggota', 13)
        ->assertViewHas('anggotaTanpaDesa', 1)
        ->assertSee('Statistik Anggota per Desa')
        ->assertSee('1 anggota resmi lama belum memiliki data desa')
        ->assertSee('Sudah ada anggota')
        ->assertSee('Menunggu verifikasi')
        ->assertSee('Belum ada anggota')
        ->assertSee('data-status="ada"', false)
        ->assertSee('data-status="pending"', false)
        ->assertSee('data-status="kosong"', false);

    $desas = $response->viewData('desas');

    expect($desas)->toHaveCount(15);

    $kulonStat = $desas->firstWhere('nama', 'Ajibarang Kulon');
    $wetanStat = $desas->firstWhere('nama', 'Ajibarang Wetan');
    $banjarsariStat = $desas->firstWhere('nama', 'Banjarsari');

    expect((int) $kulonStat->approved_count)->toBe(2)
        ->and((int) $kulonStat->pending_count)->toBe(1)
        ->and((int) $wetanStat->approved_count)->toBe(1)
        ->and((int) $banjarsariStat->approved_count)->toBe(0)
        ->and((int) $banjarsariStat->pending_count)->toBe(1);

    foreach ($desas as $desa) {
        $response->assertSee($desa->nama);
    }
});

test('persetujuan anggota memperbarui statistik desa', function () {
    $admin = User::factory()->admin()->create();
    $desa = desaStatistik('Ajibarang Kulon');
    $anggota = anggotaStatistik(User::factory()->create(), $desa, Anggota::STATUS_PENDING);

    $this->actingAs($admin)->get(route('admin.index'))
        ->assertViewHas('anggotaDisetujui', 0)
        ->assertViewHas('anggotaMenunggu', 1)
        ->assertViewHas('desaDenganAnggota', 0)
        ->assertViewHas('desaTanpaAnggota', 15);

    $this->actingAs($admin)
        ->patch(route('admin.anggota.verifikasi', $anggota), [
            'status_verifikasi' => Anggota::STATUS_APPROVED,
        ])
        ->assertRedirect(route('admin.anggota.index'));

    $response = $this->actingAs($admin)->get(route('admin.index'));

    $response->assertViewHas('anggotaDisetujui', 1)
        ->assertViewHas('anggotaMenunggu', 0)
        ->assertViewHas('desaDenganAnggota', 1)
        ->assertViewHas('desaTanpaAnggota', 14);

    $desaStat = $response->viewData('desas')->firstWhere('nama', 'Ajibarang Kulon');

    expect((int) $desaStat->approved_count)->toBe(1)
        ->and((int) $desaStat->pending_count)->toBe(0);
});

test('penolakan anggota tidak menambah jumlah anggota resmi maupun desa terisi', function () {
    $admin = User::factory()->admin()->create();
    $desa = desaStatistik('Pancasan');
    $anggota = anggotaStatistik(User::factory()->create(), $desa, Anggota::STATUS_PENDING);

    $this->actingAs($admin)
        ->patch(route('admin.anggota.verifikasi', $anggota), [
            'status_verifikasi' => Anggota::STATUS_REJECTED,
            'catatan_verifikasi' => 'Data desa perlu diperbaiki.',
        ])
        ->assertRedirect(route('admin.anggota.index'));

    $response = $this->actingAs($admin)->get(route('admin.index'));

    $response->assertViewHas('anggotaDisetujui', 0)
        ->assertViewHas('anggotaMenunggu', 0)
        ->assertViewHas('desaDenganAnggota', 0)
        ->assertViewHas('desaTanpaAnggota', 15);

    $desaStat = $response->viewData('desas')->firstWhere('nama', 'Pancasan');

    expect((int) $desaStat->approved_count)->toBe(0)
        ->and((int) $desaStat->pending_count)->toBe(0)
        ->and($anggota->fresh()->status_verifikasi)->toBe(Anggota::STATUS_REJECTED);
});

test('pemindahan desa melalui pembaruan profil memperbarui statistik', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $kulon = desaStatistik('Ajibarang Kulon');
    $wetan = desaStatistik('Ajibarang Wetan');

    anggotaStatistik($user, $kulon, Anggota::STATUS_APPROVED);

    $this->actingAs($user)
        ->post(route('anggota.store'), [
            'nama_anggota' => 'Anggota Pindah Desa',
            'desa_id' => $wetan->id,
            'rt' => '04',
            'rw' => '05',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
        ])
        ->assertRedirect(route('kartu.index'));

    $response = $this->actingAs($admin)->get(route('admin.index'));

    $response->assertViewHas('anggotaDisetujui', 0)
        ->assertViewHas('anggotaMenunggu', 1)
        ->assertViewHas('desaDenganAnggota', 0)
        ->assertViewHas('desaTanpaAnggota', 15);

    $desas = $response->viewData('desas');

    expect((int) $desas->firstWhere('nama', 'Ajibarang Kulon')->approved_count)->toBe(0)
        ->and((int) $desas->firstWhere('nama', 'Ajibarang Wetan')->approved_count)->toBe(0)
        ->and((int) $desas->firstWhere('nama', 'Ajibarang Wetan')->pending_count)->toBe(1);
});
