<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique(); // Contoh: PIL-RC-001
            $table->string('name');              // Contoh: RC Pile 300mm x 300mm
            $table->string('category');          // Contoh: Concrete Pile, Steel Casing, Rebar
            $table->integer('quantity_in_stock')->default(0);
            $table->string('unit');              // Contoh: Pcs, Meters, Tonnes
            $table->integer('minimum_stock')->default(5); // Alert stok rendah
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};