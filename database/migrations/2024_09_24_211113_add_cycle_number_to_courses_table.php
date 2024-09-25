<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCycleNumberToCoursesTable extends Migration
{
    public function up() {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('cycle_number')->after('cycle');
        });
    }

    public function down() {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('cycle_number');
        });
    }
}