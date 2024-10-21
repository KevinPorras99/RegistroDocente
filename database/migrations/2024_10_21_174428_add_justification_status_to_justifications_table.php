<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('justifications', function (Blueprint $table) {
            $table->string('justification_status')->default('not_justified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('justifications', function (Blueprint $table) {
            $table->dropColumn('justification_status');
        });
    }
};
