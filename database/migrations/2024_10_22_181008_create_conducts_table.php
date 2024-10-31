<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conducts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('cycle');
            $table->string('cycle_number')->nullable(); // Agregar la columna cycle_number
            $table->string('student_name');
            $table->enum('conduct', ['good', 'average', 'poor'])->nullable();
            $table->integer('grade')->nullable();
            $table->text('observations')->nullable();
            $table->string('justification_status')->default('not_justified');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conducts');
    }
}