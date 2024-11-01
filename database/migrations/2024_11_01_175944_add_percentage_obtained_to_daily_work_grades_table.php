<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentageObtainedToDailyWorkGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_work_grades', function (Blueprint $table) {
            $table->decimal('percentage_obtained', 5, 2)->nullable()->after('grade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_work_grades', function (Blueprint $table) {
            $table->dropColumn('percentage_obtained');
        });
    }
}