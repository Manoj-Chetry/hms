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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->longText('reason');
            $table->date('deperature_time');
            $table->date('arrival_time');
            $table->string('status')->default('pending');

            $table->foreign('student_id')
              ->references('roll_number')
              ->on('students')
              ->onDelete('cascade');
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
