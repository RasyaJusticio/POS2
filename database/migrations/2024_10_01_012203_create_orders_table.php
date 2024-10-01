<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // ID otomatis
            $table->string('customer_name'); // Nama pelanggan
            $table->integer('payment_status'); // Status pembayaran (1: belum bayar, 2: berhasil, dll.)
            $table->decimal('total_amount', 10, 2); // Jumlah total
            $table->string('client_key'); // Kunci klien untuk Midtrans
            $table->string('snap_token')->nullable(); // Token snap (jika diperlukan untuk disimpan)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
