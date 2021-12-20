<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('net_price', 10, 2)->unsigned();
            $table->decimal('gross_price', 10, 2)->unsigned();
            $table->boolean('set_price_in_visit')->default(false);
            $table->unsignedBigInteger('vat_id')->unsigned()->index()->nullable()->default(2); //8% default
            $table->boolean('active')->default(true);
            $table->string('description')->nullable();
            $table->boolean('drive_to_customer')->default(false);
            $table->timestamps();

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
        Schema::dropIfExists('additional_services');
    }
}
