<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForVisitAdditionalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_additional_services', function (Blueprint $table) {
            $table->after('sum_gross_price', function ($table) {
                // Cena jednostkowa przesłana z formularz
                $table->decimal('net_price')->comment("Net price from form")->change();
                $table->decimal('gross_price')->comment("Gross price from form")->change();
                // Cena sumaryczna obliczania z cen przesłanych z formularza
                $table->decimal('sum_net_price')->comment("Sum net price calc for price from form")->change();
                $table->decimal('sum_gross_price')->comment("Sum gross price calc for price from form")->change();
                // Informacyjnie
                $table->decimal('net_price_std', 10, 2)->comment("Price from additional services")->default(0);
                $table->decimal('gross_price_std', 10, 2)->comment("Price from additional services")->default(0);
                // Marża dla lekarza i dla firmy, obliczona
                $table->decimal('sum_net_margin_company', 10, 2)->comment("Sum net margin for company")->default(0);
                $table->decimal('sum_gross_margin_company', 10, 2)->comment("Sum gross margin for company")->default(0);
                $table->decimal('sum_net_margin_doctor', 10, 2)->comment("Sum net margin for doctor")->default(0);
                $table->decimal('sum_gross_margin_doctor', 10, 2)->comment("Sum gross margin for doctor")->default(0);
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
        Schema::table('visit_additional_services', function (Blueprint $table) {
            $table->dropColumn('net_price_std');
            $table->dropColumn('gross_price_std');

            $table->dropColumn('sum_net_margin_company');
            $table->dropColumn('sum_gross_margin_company');
            $table->dropColumn('sum_net_margin_doctor');
            $table->dropColumn('sum_gross_margin_doctor');
        });
    }
}
