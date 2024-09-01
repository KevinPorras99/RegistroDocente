<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyWorksTable extends Migration
{
    public function up() {
        Schema::create('daily_works', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->date('due_date');
            $table->unsignedBigInteger('user_id')->nullable(); // Permitir valores nulos
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('daily_works');
    }
}
