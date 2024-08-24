<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursosTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id(); // Campo de clave primaria autoincremental
            $table->string('nombre'); // Campo para el nombre del curso
            $table->timestamps(); // Campos para created_at y updated_at
        });
    }

    /**
     * Reversa las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cursos');
    }
}
