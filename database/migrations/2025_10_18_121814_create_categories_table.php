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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Kriteria 3: Setiap kategori milik satu user)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            $table->string('name', 50); // Nama Kategori (Makanan, Gaji, dll.)
            $table->enum('type', ['income', 'expense']); // Tipe: Pemasukan atau Pengeluaran
            $table->string('icon', 50)->nullable(); // Opsional: untuk ikon tampilan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
