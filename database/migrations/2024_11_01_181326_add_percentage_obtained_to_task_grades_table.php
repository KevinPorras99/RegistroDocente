<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentageObtainedToTaskGradesTable extends Migration
{
    public function up()
    {
        Schema::table('task_grades', function (Blueprint $table) {
            $table->decimal('percentage_obtained', 5, 2)->nullable()->after('grade');
        });
    }

    public function down()
    {
        Schema::table('task_grades', function (Blueprint $table) {
            $table->dropColumn('percentage_obtained');
        });
    }
}