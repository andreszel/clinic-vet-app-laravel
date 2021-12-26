<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned()->index()->nullable();
            $table->unsignedBigInteger('customer_id')->unsigned()->index()->nullable();
            $table->integer('visit_number');
            $table->date('visit_date')->nullable();
            $table->decimal('net_price', 10, 2)->unsigned()->nullable();
            $table->decimal('gross_price', 10, 2)->unsigned()->nullable();
            $table->unsignedBigInteger('pay_type_id')->unsigned()->index()->nullable();
            $table->boolean('visit_cleared')->default(false)->comment('wizyta rozliczona, nie uwzględniamy jej przy rozliczeniu miesięcznym dla lekarza, uwzględniamy w raportach dla firmy');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('pay_type_id')->references('id')->on('pay_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits');
    }
}
