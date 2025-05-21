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
        Schema::create('mess_expenses', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('hostel_id');
            $table->date('starting_date');
            $table->date('end_date');
            $table->integer('expense');
            $table->boolean('done')->nullable();

            $table->foreign('hostel_id')->references('id')->on('hostels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
