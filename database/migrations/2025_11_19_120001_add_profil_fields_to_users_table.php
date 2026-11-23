<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('warga')->index()->after('password');
            $table->string('no_whatsapp', 20)->nullable()->after('role');
            $table->text('alamat')->nullable()->after('no_whatsapp');
            $table->string('foto')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn(['role', 'no_whatsapp', 'alamat', 'foto']);
        });
    }
};
