<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mapel_uploads', function (Blueprint $table) {
            $table->string('kode_mapel')->nullable()->after('id');
            $table->string('nama_mapel')->nullable()->after('kode_mapel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapel_uploads', function (Blueprint $table) {
            $table->dropColumn(['kode_mapel', 'nama_mapel']);
        });
    }
};
