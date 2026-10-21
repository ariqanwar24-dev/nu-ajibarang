<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')
                ->constrained('beritas')
                ->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['berita_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_fotos');
    }
};
