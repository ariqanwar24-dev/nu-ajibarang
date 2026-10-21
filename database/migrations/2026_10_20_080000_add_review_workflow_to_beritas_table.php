<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan alur review berita tanpa menghapus data lama.
     */
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->text('review_note')->nullable()->after('status');
            $table->timestamp('submitted_at')->nullable()->after('review_note');
            $table->timestamp('reviewed_at')->nullable()->after('submitted_at');
            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('reviewed_at')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('published_at')->nullable()->after('reviewed_by');
        });

        // Status lama "terbit" dipetakan ke status baru "published".
        DB::table('beritas')
            ->where('status', 'terbit')
            ->update([
                'status' => 'published',
                'published_at' => DB::raw('created_at'),
            ]);

        Schema::table('beritas', function (Blueprint $table) {
            $table->string('status', 20)->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('beritas')
            ->where('status', 'published')
            ->update(['status' => 'terbit']);

        Schema::table('beritas', function (Blueprint $table) {
            $table->string('status', 20)->default('terbit')->change();
        });

        Schema::table('beritas', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'review_note',
                'submitted_at',
                'reviewed_at',
                'reviewed_by',
                'published_at',
            ]);
        });
    }
};
