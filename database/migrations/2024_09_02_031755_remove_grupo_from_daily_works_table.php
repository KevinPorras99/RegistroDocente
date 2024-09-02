<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveGrupoFromDailyWorksTable extends Migration
{
    public function up() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->dropColumn('grupo'); // Eliminar la columna grupo
        });
    }

    public function down() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->string('grupo')->after('cycle'); // Restaurar la columna grupo
        });
    }
}
