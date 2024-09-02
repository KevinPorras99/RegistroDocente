<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCycleToDailyWorksTable extends Migration
{
    public function up() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->string('cycle')->after('due_date'); // Añadir la columna cycle después de due_date
        });
    }

    public function down() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->dropColumn('cycle');
        });
    }
}
