<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penguruses', function (Blueprint $table) {
            if (! Schema::hasColumn('penguruses', 'urutan')) {
                $table->unsignedSmallInteger('urutan')->default(0);
            }

            if (! Schema::hasColumn('penguruses', 'is_demo')) {
                $table->boolean('is_demo')->default(false);
            }

            if (! Schema::hasColumn('penguruses', 'seed_key')) {
                $table->string('seed_key')->nullable()->unique();
            }
        });
    }

    public function down(): void
    {
        Schema::table('penguruses', function (Blueprint $table) {
            if (Schema::hasColumn('penguruses', 'seed_key')) {
                $table->dropUnique(['seed_key']);
                $table->dropColumn('seed_key');
            }

            if (Schema::hasColumn('penguruses', 'is_demo')) {
                $table->dropColumn('is_demo');
            }

            if (Schema::hasColumn('penguruses', 'urutan')) {
                $table->dropColumn('urutan');
            }
        });
    }
};
