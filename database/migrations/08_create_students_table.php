<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->string('name');
            $table->string('roll_number', 8)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('department');
            $table->unsignedBigInteger('department_id');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('seat');
            $table->unsignedBigInteger('seat_id')->unique();
            $table->string('password');

            $table->foreign('seat_id')
                ->references('id')
                ->on('seats');

            $table->foreign('department_id')->references('id')->on('departments');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
