<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitAdditionalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_additional_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visit_id')->unsigned()->index()->nullable();
            $table->unsignedBigInteger('additional_service_id')->unsigned()->index()->nullable();
            $table->integer('quantity');
            $table->unsignedBigInteger('vat_id')->unsigned()->index()->nullable();
            $table->decimal('net_price', 10, 2)->unsigned();
            $table->decimal('gross_price', 10, 2)->unsigned();
            $table->timestamps();

            $table->foreign('visit_id')->references('id')->on('visits');
            $table->foreign('additional_service_id')->references('id')->on('additional_services');
            $table->foreign('vat_id')->references('id')->on('vats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_additional_services');
    }
}
