<?php
// database/migrations/xxxx_xx_xx_create_daily_work_grades_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//tabla de calificaciones de cotidiano
class CreateDailyWorkGradesTable extends Migration
{
    public function up()
    {
        Schema::create('daily_work_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_work_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('grade')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_work_grades');
    }
}
