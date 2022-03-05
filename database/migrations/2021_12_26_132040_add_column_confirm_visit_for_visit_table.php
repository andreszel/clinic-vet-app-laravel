<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnConfirmVisitForVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->after('visit_cleared', function ($table) {
                $table->date('cleared_date')->nullable();
                $table->boolean('confirm_visit')->default(false);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropIfExists(['cleared_date', 'confirm_visit']);
            //$table->dropColumn('confirm_visit');
        });
    }
}
