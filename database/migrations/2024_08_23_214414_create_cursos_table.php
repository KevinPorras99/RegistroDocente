<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursosTable extends Migration
{
    public function up() {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade');
            $table->string('institution');
            $table->string('classroom');
            $table->unsignedBigInteger('user_id')->nullable(); // Permitir valores nulos
            $table->integer('daily_work_percentage')->nullable();
            $table->integer('exam_percentage')->nullable();
            $table->integer('assignment_percentage')->nullable();
            $table->integer('conduct_percentage')->nullable();
            $table->integer('attendance_percentage')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('courses');
    }
}
