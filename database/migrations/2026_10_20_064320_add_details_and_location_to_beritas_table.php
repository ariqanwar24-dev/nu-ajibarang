<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan detail publikasi dan lokasi acara tanpa mengubah data lama.
     */
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            $table->string('kategori', 100)->nullable()->after('slug');
            $table->text('ringkasan')->nullable()->after('kategori');
            $table->string('penulis')->nullable()->after('ringkasan');
            $table->string('status', 20)->default('terbit')->after('penulis')->index();

            // Lokasi acara bersifat opsional agar berita biasa tetap bisa terbit.
            $table->string('lokasi_nama')->nullable()->after('gambar');
            $table->text('lokasi_alamat')->nullable()->after('lokasi_nama');
            $table->string('lokasi_desa', 100)->nullable()->after('lokasi_alamat');
            $table->decimal('latitude', 10, 7)->nullable()->after('lokasi_desa');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->text('lokasi_keterangan')->nullable()->after('longitude');
            $table->dateTime('acara_mulai')->nullable()->after('lokasi_keterangan');
            $table->dateTime('acara_selesai')->nullable()->after('acara_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'user_id',
                'kategori',
                'ringkasan',
                'penulis',
                'status',
                'lokasi_nama',
                'lokasi_alamat',
                'lokasi_desa',
                'latitude',
                'longitude',
                'lokasi_keterangan',
                'acara_mulai',
                'acara_selesai',
            ]);
        });
    }
};
