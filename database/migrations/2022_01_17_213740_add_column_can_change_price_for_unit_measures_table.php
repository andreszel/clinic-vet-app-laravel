<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColumnCanChangePriceForUnitMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unit_measures', function (Blueprint $table) {
            $table->after('short_name', function ($table) {
                $table->boolean('can_change_price')->default(false);
            });
        });
        DB::table('unit_measures')
            ->where('id', 2)
            ->update([
                'can_change_price' => 1,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unit_measures', function (Blueprint $table) {
            $table->dropColumn('can_change_price');
        });
    }
}
