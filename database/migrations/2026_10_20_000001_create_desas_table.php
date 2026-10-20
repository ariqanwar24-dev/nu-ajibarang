<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->unsignedInteger('jumlah_anggota')->default(0);
            $table->unsignedInteger('jumlah_kegiatan')->default(0);
            $table->unsignedInteger('jumlah_ranting')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('desas'); }
};
