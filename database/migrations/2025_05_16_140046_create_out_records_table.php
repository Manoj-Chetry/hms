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
        Schema::create('out_records', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 8)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->date('out_date');
            $table->date('in_date')->nullable();

            $table->foreign('student_id')->references('roll_number')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('out_records');
    }
};
