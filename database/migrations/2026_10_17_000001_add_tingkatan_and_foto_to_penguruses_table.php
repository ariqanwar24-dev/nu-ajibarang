<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penguruses', function (Blueprint $table) {
            if (! Schema::hasColumn('penguruses', 'tingkatan')) {
                $table->string('tingkatan')->nullable();
            }
            if (! Schema::hasColumn('penguruses', 'foto')) {
                $table->string('foto')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('penguruses', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('penguruses', 'tingkatan')) {
                $columns[] = 'tingkatan';
            }
            if (Schema::hasColumn('penguruses', 'foto')) {
                $columns[] = 'foto';
            }
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
