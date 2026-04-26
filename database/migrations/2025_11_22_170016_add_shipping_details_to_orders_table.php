<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('kurir')->nullable(); // misal: jne, jnt, grab
        $table->string('layanan')->nullable(); // misal: REG, YES, Instant
        $table->integer('ongkir')->default(0); // Biaya pengiriman
        $table->string('nomor_resi')->nullable(); // Untuk tracking
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
