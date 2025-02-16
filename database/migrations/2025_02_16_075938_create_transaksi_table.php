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
        Schema::create('transaksi_header', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Track the user who created this transaction
            $table->string('invoice_number')->unique();
            $table->string('status_transaksi')->nullable();
            $table->datetime('checkin');
            $table->datetime('checkout');
            $table->datetime('approve_time')->nullable();
            $table->datetime('finish_time')->nullable();
            $table->integer('approveby')->nullable();
            $table->integer('dp')->nullable();
            $table->timestamps();

            // Foreign key to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create the purchase_order_items table
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_header_id'); // Link to purchase_order
            $table->unsignedBigInteger('lantai_id'); 
            $table->unsignedBigInteger('meja_id');
            $table->integer('harga');
            $table->timestamps();

            // Foreign keys
            $table->foreign('transaksi_header_id')->references('id')->on('transaksi_header')->onDelete('cascade');
            $table->foreign('lantai_id')->references('id')->on('lantai')->onDelete('cascade');
            $table->foreign('meja_id')->references('id')->on('meja')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_header');
        Schema::dropIfExists('transaksi_detail');
    }
};
