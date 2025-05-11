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
        Schema::create('hostel_changes', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 8)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->unsignedBigInteger('destination_hostel_id');
            $table->unsignedBigInteger('new_seat_id')->nullable();
            $table->string('status');
            $table->date('created');
            
            $table->foreign('student_id')->references('roll_number')->on('students')->onDelete('cascade');
            $table->foreign('destination_hostel_id')->references('id')->on('hostels')->onDelete('cascade');
            $table->foreign('new_seat_id')->references('id')->on('seats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostel_changes');
    }
};
