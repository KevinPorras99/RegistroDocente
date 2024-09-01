<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilePathToDailyWorksTable extends Migration
{
    public function up() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('due_date');
        });
    }

    public function down() {
        Schema::table('daily_works', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }
}
