<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        // Nama desa diverifikasi dari situs resmi Kecamatan Ajibarang.
        // Angka anggota/kegiatan/ranting di bawah hanya data DEMO, bukan statistik resmi.
        $data = [
            ['Ajibarang Kulon', 128, 8, 6],
            ['Ajibarang Wetan', 116, 7, 5],
            ['Banjarsari', 84, 5, 4],
            ['Ciberung', 73, 4, 3],
            ['Darmakradenan', 102, 6, 5],
            ['Jingkang', 91, 6, 4],
            ['Kalibenda', 62, 3, 2],
            ['Karangbawang', 79, 5, 3],
            ['Kracak', 110, 7, 5],
            ['Lesmana', 88, 5, 4],
            ['Pancasan', 96, 6, 4],
            ['Pancurendang', 68, 4, 3],
            ['Pandansari', 82, 5, 3],
            ['Sawangan', 75, 4, 3],
            ['Tipar Kidul', 105, 6, 5],
        ];
        foreach ($data as [$nama, $anggota, $kegiatan, $ranting]) {
            Desa::updateOrCreate(['nama' => $nama], [
                'jumlah_anggota' => $anggota,
                'jumlah_kegiatan' => $kegiatan,
                'jumlah_ranting' => $ranting,
            ]);
        }
    }
}
