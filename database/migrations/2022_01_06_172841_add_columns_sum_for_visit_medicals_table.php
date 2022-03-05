<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsSumForVisitMedicalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_medicals', function (Blueprint $table) {
            $table->after('gross_margin', function ($table) {
                $table->decimal('sum_net_price', 10, 2)->default(0);
                $table->decimal('sum_gross_price', 10, 2)->default(0);
                $table->decimal('sum_net_margin', 10, 2)->default(0);
                $table->decimal('sum_gross_margin', 10, 2)->default(0);
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
        Schema::table('visit_medicals', function (Blueprint $table) {
            $table->dropIfExists(['sum_net_price', 'sum_gross_price', 'sum_net_margin', 'sum_gross_margin']);
            /* $table->dropColumn('sum_gross_price');
            $table->dropColumn('sum_net_margin');
            $table->dropColumn('sum_gross_margin'); */
        });
    }
}
