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
        Schema::create('itempembelians', function (Blueprint $table) {
            $table->id();
            // $table->uuid()->unique();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->nullable();
            $table->unsignedBigInteger('pembelian_id');
            $table->foreign('pembelian_id')->references('id')->on('pembelians')->nullable()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itempembelians');
    }
};
