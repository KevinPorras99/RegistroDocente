<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    public function up() {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->date('due_date');
            $table->unsignedBigInteger('user_id')->nullable(); // Permitir valores nulos
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('exams');
    }
}
