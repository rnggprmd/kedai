<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_order', 30)->unique();
            $table->foreignId('table_id')->constrained('tables');
            $table->string('nama_pelanggan', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'completed',
                'cancelled',
            ])->default('pending');
            $table->decimal('total_harga', 10, 2)->default(0);
            $table->foreignId('kasir_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
