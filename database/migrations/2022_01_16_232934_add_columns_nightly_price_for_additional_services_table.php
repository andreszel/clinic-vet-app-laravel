<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsNightlyPriceForAdditionalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_services', function (Blueprint $table) {
            $table->after('gross_price', function ($table) {
                $table->decimal('nightly_net_price', 10, 2)->default(0);
                $table->decimal('nightly_gross_price', 10, 2)->default(0);
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
        Schema::table('additional_services', function (Blueprint $table) {
            $table->dropIfExists(['nightly_net_price', 'nightly_gross_price']);
            //$table->dropColumn('nightly_gross_price');
        });
    }
}
