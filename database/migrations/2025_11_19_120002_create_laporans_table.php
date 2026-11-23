<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelapor_id')->constrained('users')->restrictOnDelete();
            $table->string('kategori', 30)->default('lainnya')->index();
            $table->string('judul', 120);
            $table->text('detail');
            $table->date('tanggal')->index();
            $table->string('foto')->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->text('tanggapan')->nullable();
            $table->timestamps();

            $table->index(['pelapor_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
