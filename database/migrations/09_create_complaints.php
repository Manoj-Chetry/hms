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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('student_id', 8);
            $table->unsignedBigInteger('hostel_id');
            $table->longText('issue_description');
            $table->string('status');
            $table->date('created_at');
            $table->date('resolved_at')->nullable();


            $table->foreign('student_id')
              ->references('roll_number')
              ->on('students')
              ->onDelete('cascade');

            $table->foreign('hostel_id')
              ->references('id')
              ->on('hostels')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
