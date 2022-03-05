<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsNightlyPaidForVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->after('confirm_visit', function ($table) {
                $table->boolean('nightly_visit')->default(false);
                $table->decimal('paid_net_price', 10, 2)->default(0);
                $table->decimal('paid_gross_price', 10, 2)->default(0);
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
            $table->dropIfExists(['nightly_visit', 'paid_net_price', 'paid_gross_price']);
            /* $table->dropColumn('paid_net_price');
            $table->dropColumn('paid_gross_price'); */
        });
    }
}
