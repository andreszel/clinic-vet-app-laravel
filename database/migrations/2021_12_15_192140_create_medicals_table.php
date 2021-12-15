<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('vat_buy_id')->unsigned()->index()->nullable();
            $table->unsignedBigInteger('vat_sell_id')->unsigned()->index()->nullable();
            $table->decimal('net_price_buy', 10, 2)->unsigned();
            $table->decimal('gross_price_buy', 10, 2)->unsigned();
            $table->decimal('net_price_sell', 10, 2)->unsigned();
            $table->decimal('gross_price_sell', 10, 2)->unsigned();
            $table->decimal('net_margin', 10, 2)->unsigned();
            $table->decimal('gross_margin', 10, 2)->unsigned();
            $table->unsignedBigInteger('unit_measure_id')->unsigned()->index()->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('unit_measure_id')->references('id')->on('unit_measures');
            $table->foreign('vat_buy_id')->references('id')->on('unit_measures');
            $table->foreign('vat_sell_id')->references('id')->on('unit_measures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicals');
    }
}
