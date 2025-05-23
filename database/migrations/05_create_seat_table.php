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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('seat');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('hostel_id');
            $table->boolean('occupied');

            $table->foreign('hostel_id')
              ->references('id')
              ->on('hostels')
              ->onDelete('cascade');
            
            $table->foreign('room_id')
              ->references('id')
              ->on('rooms')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat');
    }
};
