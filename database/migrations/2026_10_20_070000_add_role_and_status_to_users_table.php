<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan role dan status akun tanpa menghapus data pengguna lama.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('user')->after('is_admin')->index();
            $table->string('status', 20)->default('pending')->after('role')->index();
            $table->text('status_note')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('status_note');
            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('reviewed_at')
                ->constrained('users')
                ->nullOnDelete();
        });

        // Akun yang sudah ada sebelumnya tetap aktif dan tidak tiba-tiba terkunci.
        DB::table('users')->update(['status' => 'active']);

        // Admin lama dipetakan ke super_admin agar tetap dapat mengelola role.
        DB::table('users')
            ->where('is_admin', true)
            ->update(['role' => 'super_admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'role',
                'status',
                'status_note',
                'reviewed_at',
                'reviewed_by',
            ]);
        });
    }
};
