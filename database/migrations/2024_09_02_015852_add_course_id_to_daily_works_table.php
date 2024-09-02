<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseIdToDailyWorksTable extends Migration
{
    public function up() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->after('due_date'); // Añadir la columna course_id después de due_date

            // Si tienes una relación con la tabla courses, puedes añadir la clave foránea
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->dropForeign(['course_id']); // Eliminar la clave foránea si existe
            $table->dropColumn('course_id'); // Eliminar la columna course_id
        });
    }
}
