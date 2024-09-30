<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// tabla de exámenes
class CreateExamsTable extends Migration
{
    public function up() {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->date('due_date');
            $table->unsignedBigInteger('user_id')->nullable(); // Permitir valores nulos
            $table->unsignedBigInteger('course_id'); // Añadir la columna course_id
            $table->string('cycle'); // Añadir la columna cycle
            $table->string('file_path')->nullable(); // Añadir la columna file_path
            $table->integer('percentage')->default(0); // Añadir la columna percentage
            $table->timestamps();

            // Si tienes una relación con la tabla courses, puedes añadir la clave foránea
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('exams');
    }
}
