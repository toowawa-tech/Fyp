<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Pengguna yang buat kemaskini
            $table->foreignId('material_id')->constrained()->onDelete('cascade'); // Bahan yang terlibat
            $table->string('action'); // Contoh: 'Stock Added', 'Request Approved / Delivered'
            $table->integer('quantity_change'); // Jumlah (+ atau -)
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};