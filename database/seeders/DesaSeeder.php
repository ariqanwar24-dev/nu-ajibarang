<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        // Nama desa diverifikasi dari situs resmi Kecamatan Ajibarang.
        // Statistik anggota tidak memakai kolom angka pada tabel ini; angka
        // dihitung langsung dari data anggota yang sudah disetujui.
        $daftarDesa = [
            'Ajibarang Kulon',
            'Ajibarang Wetan',
            'Banjarsari',
            'Ciberung',
            'Darmakradenan',
            'Jingkang',
            'Kalibenda',
            'Karangbawang',
            'Kracak',
            'Lesmana',
            'Pancasan',
            'Pancurendang',
            'Pandansari',
            'Sawangan',
            'Tipar Kidul',
        ];

        foreach ($daftarDesa as $nama) {
            Desa::query()->firstOrCreate(['nama' => $nama]);
        }
    }
}
