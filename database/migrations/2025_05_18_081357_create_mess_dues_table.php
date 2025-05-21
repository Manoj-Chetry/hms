<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessDuesTable extends Migration
{
    public function up()
    {
        Schema::create('mess_dues', function (Blueprint $table) {
            $table->id();

            $table->string('student_id', 8)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();

            $table->foreignId('mess_expense_id')
                ->constrained()
                ->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->integer('present_days');
            $table->integer('absent_days');
            $table->boolean('paid')->default(false);

            $table->foreign('student_id')->references('roll_number')->on('students');

        });
    }

    public function down()
    {
        Schema::dropIfExists('mess_dues');
    }
}
