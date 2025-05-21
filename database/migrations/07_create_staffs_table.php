<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role'); // dsw, warden, caretaker, attender, hod
            $table->unsignedBigInteger('department_id')->nullable(); // Only for HOD
            $table->unsignedBigInteger('hostel_id')->nullable();     // For warden, caretaker, attender
        
            // Optional: Add foreign keys if needed
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('hostel_id')->references('id')->on('hostels');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};

