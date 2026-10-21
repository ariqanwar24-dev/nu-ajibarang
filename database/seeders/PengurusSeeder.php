<?php

namespace Database\Seeders;

use App\Models\Pengurus;
use Illuminate\Database\Seeder;

class PengurusSeeder extends Seeder
{
    /**
     * Data berdasarkan gambar susunan pengurus yang diberikan.
     *
     * Catatan:
     * - Ejaan nama mengikuti teks yang terbaca pada gambar.
     * - Periode kepengurusan tidak tercantum pada gambar.
     * - Data Banom tidak dimasukkan karena tidak terdapat
     *   dalam gambar sumber.
     */
    public function run(): void
    {
        $data = [
            // ==========================================
            // MUSTASYAR
            // ==========================================
            ['asli-mustasyar-01', 'Kiai Kholid Abdul Rosyid', 'Mustasyar', 'mustasyar', 1],
            ['asli-mustasyar-02', 'KH. Habib Muhammad Al Khasni', 'Mustasyar', 'mustasyar', 2],
            ['asli-mustasyar-03', 'KH. Munawar Kholil', 'Mustasyar', 'mustasyar', 3],
            ['asli-mustasyar-04', 'Kiai Abu Bakar', 'Mustasyar', 'mustasyar', 4],
            ['asli-mustasyar-05', 'KH. Turkiman', 'Mustasyar', 'mustasyar', 5],
            ['asli-mustasyar-06', 'KH. Hamam Sholeh Mustofa', 'Mustasyar', 'mustasyar', 6],
            ['asli-mustasyar-07', 'Kiai Bahrun Imron Rosyadi', 'Mustasyar', 'mustasyar', 7],
            ['asli-mustasyar-08', 'Kiai Masruri Zen', 'Mustasyar', 'mustasyar', 8],
            ['asli-mustasyar-09', 'KH. Ahmad Sofyan, S.Pd.', 'Mustasyar', 'mustasyar', 9],

            // ==========================================
            // SYURIYAH - RAIS DAN WAKIL RAIS
            // ==========================================
            ['asli-syuriyah-rais-01', 'KH. Zulfa Mohammad Nur', 'Rais Syuriyah', 'syuriyah', 1],

            ['asli-syuriyah-wakil-01', 'Kiai Akhmad Tohirin', 'Wakil Rais Syuriyah', 'syuriyah', 2],
            ['asli-syuriyah-wakil-02', 'Kiai Mahful Fauzi', 'Wakil Rais Syuriyah', 'syuriyah', 3],
            ['asli-syuriyah-wakil-03', 'Dr. Kiai Nur Kholis, M.S.I.', 'Wakil Rais Syuriyah', 'syuriyah', 4],
            ['asli-syuriyah-wakil-04', 'Kiai Agus Syarifudin', 'Wakil Rais Syuriyah', 'syuriyah', 5],
            ['asli-syuriyah-wakil-05', 'Kiai Slamet Faozi', 'Wakil Rais Syuriyah', 'syuriyah', 6],

            // ==========================================
            // SYURIYAH - KATIB DAN WAKIL KATIB
            // ==========================================
            ['asli-syuriyah-katib-01', 'Kiai Mujaroib, S.Pd.', 'Katib Syuriyah', 'syuriyah', 7],

            ['asli-syuriyah-wakil-katib-01', 'Kiai Zaenudin, S.Pd., M.Si.', 'Wakil Katib Syuriyah', 'syuriyah', 8],
            ['asli-syuriyah-wakil-katib-02', 'Kiai Jamaludin, S.Ag.', 'Wakil Katib Syuriyah', 'syuriyah', 9],
            ['asli-syuriyah-wakil-katib-03', 'KH. Afifudin Idrus, M.Ag.', 'Wakil Katib Syuriyah', 'syuriyah', 10],

            // ==========================================
            // SYURIYAH - A'WAN
            // ==========================================
            ['asli-syuriyah-awan-01', 'Kiai Ahmad Masduki', "A'wan Syuriyah", 'syuriyah', 11],
            ['asli-syuriyah-awan-02', 'Kiai Nur Arifin', "A'wan Syuriyah", 'syuriyah', 12],
            ['asli-syuriyah-awan-03', 'Kiai Riswanto Maftuh Fauzi', "A'wan Syuriyah", 'syuriyah', 13],
            ['asli-syuriyah-awan-04', 'Kiai Chudori', "A'wan Syuriyah", 'syuriyah', 14],
            ['asli-syuriyah-awan-05', 'Kiai Ahmad Riyadi', "A'wan Syuriyah", 'syuriyah', 15],
            ['asli-syuriyah-awan-06', 'Kiai Shobirin', "A'wan Syuriyah", 'syuriyah', 16],
            ['asli-syuriyah-awan-07', 'Kiai Ali Muhadi Masykur', "A'wan Syuriyah", 'syuriyah', 17],
            ['asli-syuriyah-awan-08', 'KH. Imam Wasingun', "A'wan Syuriyah", 'syuriyah', 18],
            ['asli-syuriyah-awan-09', 'KH. Wartoyo', "A'wan Syuriyah", 'syuriyah', 19],
            ['asli-syuriyah-awan-10', 'Kiai Abu Masykur', "A'wan Syuriyah", 'syuriyah', 20],
            ['asli-syuriyah-awan-11', "Kiai M. Amin Ma'mun", "A'wan Syuriyah", 'syuriyah', 21],
            ['asli-syuriyah-awan-12', 'Kiai Bushrol Kahfi, S.P.', "A'wan Syuriyah", 'syuriyah', 22],
            ['asli-syuriyah-awan-13', 'Kiai Misbahul Huda', "A'wan Syuriyah", 'syuriyah', 23],

            // ==========================================
            // TANFIDZIYAH - KETUA DAN WAKIL KETUA
            // ==========================================
            ['asli-tanfidziyah-ketua-01', 'H. Slamet Ibnu Ansori, S.AP', 'Ketua', 'tanfidziyah', 1],

            ['asli-tanfidziyah-wakil-01', 'Nisful Adji, S.Ag.', 'Wakil Ketua', 'tanfidziyah', 2],
            ['asli-tanfidziyah-wakil-02', 'Akhmad Syaibatul Khamdi', 'Wakil Ketua', 'tanfidziyah', 3],
            ['asli-tanfidziyah-wakil-03', 'H. Suripto, S.Pd.', 'Wakil Ketua', 'tanfidziyah', 4],

            // ==========================================
            // TANFIDZIYAH - SEKRETARIS DAN WAKIL
            // ==========================================
            ['asli-tanfidziyah-sekretaris-01', 'Rofiqdin, S.Sos.', 'Sekretaris', 'tanfidziyah', 5],

            ['asli-tanfidziyah-wakil-sekretaris-01', 'Agus Waluyo, S.Pd.I., M.Pd.', 'Wakil Sekretaris', 'tanfidziyah', 6],
            ['asli-tanfidziyah-wakil-sekretaris-02', 'Abdul Aziz', 'Wakil Sekretaris', 'tanfidziyah', 7],
            ['asli-tanfidziyah-wakil-sekretaris-03', 'Endro Aprilian Budi', 'Wakil Sekretaris', 'tanfidziyah', 8],
        ];

        foreach ($data as [$seedKey, $nama, $jabatan, $tingkatan, $urutan]) {
            Pengurus::query()->updateOrCreate(
                ['seed_key' => $seedKey],
                [
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                    'tingkatan' => $tingkatan,
                    'urutan' => $urutan,
                    'is_demo' => false,
                ],
            );
        }
    }
}
