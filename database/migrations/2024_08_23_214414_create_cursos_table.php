<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursosTable extends Migration // Cambia el nombre de la clase aquí
{
    public function up() {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade');
            $table->string('institution');
            $table->string('classroom');
            $table->unsignedBigInteger('user_id')->nullable(); // Permitir valores nulos
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('courses');
    }
}
