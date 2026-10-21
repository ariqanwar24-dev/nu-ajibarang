<?php

namespace Database\Seeders;

use App\Models\Pengurus;
use Illuminate\Database\Seeder;

class PengurusSeeder extends Seeder
{
    /**
     * Data contoh untuk demonstrasi. Nama dan jabatan tidak mewakili
     * susunan pengurus resmi MWC NU Kecamatan Ajibarang.
     */
    public function run(): void
    {
        $data = [
            // Dewan Mustasyar
            ['demo-mustasyar-01', 'KH. Ahmad Fauzi Ridwan', 'Mustasyar', 'mustasyar', 1],
            ['demo-mustasyar-02', 'KH. Muhammad Syarif Hidayat', 'Mustasyar', 'mustasyar', 2],
            ['demo-mustasyar-03', 'KH. Abdul Ghofur Zain', 'Mustasyar', 'mustasyar', 3],

            // Syuriyah
            ['demo-syuriyah-01', 'KH. M. Ridlwan Abdullah', 'Rais Syuriyah', 'syuriyah', 1],
            ['demo-syuriyah-02', 'KH. Ahmad Munir Fadlil', 'Wakil Rais Syuriyah', 'syuriyah', 2],
            ['demo-syuriyah-03', 'KH. Muhammad Anwar Ridwan', 'Wakil Rais Syuriyah', 'syuriyah', 3],
            ['demo-syuriyah-04', 'KH. Abdul Hamid Musthofa', 'Katib Syuriyah', 'syuriyah', 4],
            ['demo-syuriyah-05', 'H. Agus Salim Fauzi', "A'wan Syuriyah", 'syuriyah', 5],

            // Tanfidziyah
            ['demo-tanfidziyah-01', 'H. Muhammad Faiz Najib', 'Ketua Tanfidziyah', 'tanfidziyah', 1],
            ['demo-tanfidziyah-02', 'Hj. Siti Nurhayati', 'Wakil Ketua', 'tanfidziyah', 2],
            ['demo-tanfidziyah-03', 'H. Ahmad Syaifudin', 'Sekretaris', 'tanfidziyah', 3],
            ['demo-tanfidziyah-04', 'Muhammad Rifqi Hidayat', 'Wakil Sekretaris', 'tanfidziyah', 4],
            ['demo-tanfidziyah-05', 'Hj. Umi Kulsum', 'Bendahara', 'tanfidziyah', 5],
            ['demo-tanfidziyah-06', 'Ahmad Fauzan Aziz', 'Wakil Bendahara', 'tanfidziyah', 6],
            ['demo-tanfidziyah-07', 'H. Abdul Rozak', 'Koordinator Organisasi', 'tanfidziyah', 7],
            ['demo-tanfidziyah-08', 'Muhammad Ihsan Maulana', 'Koordinator Dakwah', 'tanfidziyah', 8],

            // Banom NU
            ['demo-banom-01', 'Muhammad Ali Mustofa', 'Ketua GP Ansor', 'GP Ansor', 1],
            ['demo-banom-02', 'Ahmad Rifai', 'Sekretaris GP Ansor', 'GP Ansor', 2],
            ['demo-banom-03', 'Hj. Siti Aminah', 'Ketua Muslimat NU', 'Muslimat NU', 3],
            ['demo-banom-04', 'Hj. Nur Hidayah', 'Sekretaris Muslimat NU', 'Muslimat NU', 4],
            ['demo-banom-05', 'Siti Khodijah', 'Ketua Fatayat NU', 'Fatayat NU', 5],
            ['demo-banom-06', 'Ahmad Farhan', 'Ketua IPNU', 'IPNU', 6],
            ['demo-banom-07', 'Nabila Putri', 'Ketua IPPNU', 'IPPNU', 7],
            ['demo-banom-08', 'Muhammad Yusuf', 'Ketua Pagar Nusa', 'Pagar Nusa', 8],
            ['demo-banom-09', 'Dr. Ahmad Taufik', 'Ketua ISNU', 'ISNU', 9],
            ['demo-banom-10', 'KH. Abdul Ghoni', 'Ketua Pergunu', 'Pergunu', 10],
        ];

        foreach ($data as [$seedKey, $nama, $jabatan, $tingkatan, $urutan]) {
            Pengurus::query()->updateOrCreate(
                ['seed_key' => $seedKey],
                [
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                    'tingkatan' => $tingkatan,
                    'urutan' => $urutan,
                    'is_demo' => true,
                ],
            );
        }
    }
}
