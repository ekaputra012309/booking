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
        Schema::create('lantai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lantai');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('meja', function (Blueprint $table) {
            $table->id();
            $table->string('nama_meja');
            $table->decimal('harga', 10, 2);
            $table->unsignedBigInteger('lantai_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('lantai_id')->references('id')->on('lantai')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status_booking')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('status_booking', function (Blueprint $table) {
            $table->id();
            $table->string('nama_status');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lantai');
        Schema::dropIfExists('meja');
        Schema::dropIfExists('status_booking');
    }
};
