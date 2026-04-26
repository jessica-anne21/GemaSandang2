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
        // Ubah jadi string agar fleksibel
        $table->string('status')->default('menunggu_pembayaran')->change();
    });
}

public function down()
{
    // Kembalikan ke enum jika rollback (opsional, sesuaikan dengan enum lama Anda)
    // $table->enum('status', ['pending', '...'])->change();
}
};
