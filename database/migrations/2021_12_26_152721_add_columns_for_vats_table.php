<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForVatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vats', function (Blueprint $table) {
            $table->after('name', function ($table) {
                $table->boolean('default_medicals')->default(false);
                $table->boolean('default_services')->default(false);
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
        Schema::table('vats', function (Blueprint $table) {
            $table->dropIfExists(['default_medicals', 'default_services']);
            //$table->dropColumn('default_services');
        });
    }
}
