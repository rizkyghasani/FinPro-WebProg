<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/..._create_transactions_table.php

    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Kriteria 3: Akses terbatas per user)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Relasi ke Category (Membuat transaksi lebih terstruktur)
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            
            $table->string('description');
            $table->decimal('amount', 10, 2); // Jumlah uang
            $table->string('type'); // 'income' atau 'expense' (Tipe Transaksi)
            $table->date('date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
