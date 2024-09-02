<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGrupoToDailyWorksTable extends Migration
{
    public function up() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->string('grupo')->after('cycle'); // Añadir la columna grupo después de cycle
        });
    }

    public function down() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->dropColumn('grupo');
        });
    }
}
