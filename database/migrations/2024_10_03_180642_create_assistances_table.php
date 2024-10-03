<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistancesTable extends Migration
{
    public function up()
    {
        Schema::create('assistances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('status');
            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'date']); // Asegurar unicidad
        });
    }

    public function down()
    {
        Schema::dropIfExists('assistances');
    }
}