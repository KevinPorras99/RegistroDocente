<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//tabla de estudiantes
class CreateStudentsTable extends Migration
{
    public function up() {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade');
            $table->string('institution');
            $table->string('section');
            $table->unsignedBigInteger('user_id')->nullable(); // Permitir valores nulos
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('students');
    }
}
