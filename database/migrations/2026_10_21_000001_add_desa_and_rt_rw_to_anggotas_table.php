<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hubungkan anggota dengan desa dan simpan RT/RW secara terpisah.
     *
     * Data desa yang sudah ada tidak diubah. Baris desa yang belum tersedia
     * ditambahkan dengan angka bawaan nol, bukan angka demo.
     */
    public function up(): void
    {
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

        $sekarang = now();

        DB::table('desas')->insertOrIgnore(
            collect($daftarDesa)
                ->map(fn (string $nama): array => [
                    'nama' => $nama,
                    'jumlah_anggota' => 0,
                    'jumlah_kegiatan' => 0,
                    'jumlah_ranting' => 0,
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ])
                ->all()
        );

        // Alamat lama tetap disimpan, tetapi tidak lagi wajib karena formulir
        // sekarang memakai desa, RT, dan RW.
        Schema::table('anggotas', function (Blueprint $table) {
            $table->text('alamat')->nullable()->change();
        });

        Schema::table('anggotas', function (Blueprint $table) {
            $table->foreignId('desa_id')
                ->nullable()
                ->after('user_id')
                ->constrained('desas')
                ->nullOnDelete();

            $table->string('rt', 3)->nullable()->after('alamat');
            $table->string('rw', 3)->nullable()->after('rt');
        });

        // Isi relasi desa lama hanya jika nama desa benar-benar tertulis pada
        // alamat. RT/RW diambil dari pola yang ada. Nilai yang tidak diketahui
        // dibiarkan kosong, tidak dikarang.
        $desas = DB::table('desas')
            ->select(['id', 'nama'])
            ->orderByRaw('LENGTH(nama) DESC')
            ->get();

        DB::table('anggotas')
            ->select(['id', 'alamat'])
            ->whereNull('desa_id')
            ->orderBy('id')
            ->get()
            ->each(function (object $anggota) use ($desas): void {
                $alamat = trim((string) $anggota->alamat);
                $desaId = null;
                $rt = null;
                $rw = null;

                if ($alamat !== '') {
                    foreach ($desas as $desa) {
                        if (mb_stripos($alamat, $desa->nama) !== false) {
                            $desaId = $desa->id;
                            break;
                        }
                    }

                    if (preg_match('/\bRT\s*[.:\-]?\s*(\d{1,3})\b/iu', $alamat, $cocokRt) === 1) {
                        $rt = $cocokRt[1];
                    }

                    if (preg_match('/\bRW\s*[.:\-]?\s*(\d{1,3})\b/iu', $alamat, $cocokRw) === 1) {
                        $rw = $cocokRw[1];
                    }
                }

                $perubahan = array_filter([
                    'desa_id' => $desaId,
                    'rt' => $rt,
                    'rw' => $rw,
                ], fn ($nilai): bool => $nilai !== null);

                if ($perubahan !== []) {
                    DB::table('anggotas')
                        ->where('id', $anggota->id)
                        ->update($perubahan);
                }
            });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('desa_id');
            $table->dropColumn(['rt', 'rw']);
        });

        DB::table('anggotas')->whereNull('alamat')->update(['alamat' => '']);

        Schema::table('anggotas', function (Blueprint $table) {
            $table->text('alamat')->nullable(false)->change();
        });
    }
};
