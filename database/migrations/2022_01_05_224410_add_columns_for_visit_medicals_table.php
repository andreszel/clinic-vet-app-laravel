<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForVisitMedicalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_medicals', function (Blueprint $table) {
            $table->after('gross_price', function ($table) {
                $table->decimal('net_margin', 10, 2)->default(0);
                $table->decimal('gross_margin', 10, 2)->default(0);
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
            $table->dropColumn('net_margin');
            $table->dropColumn('gross_margin');
        });
    }
}
