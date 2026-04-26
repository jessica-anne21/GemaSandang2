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
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        $table->string('nama_produk');
        $table->text('deskripsi')->nullable();
        $table->integer('harga');
        $table->string('foto_produk')->nullable();
        $table->integer('stok')->default(1);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
