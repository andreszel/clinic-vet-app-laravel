<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsComissionsForUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('phone', function ($table) {
                $table->unsignedInteger('commission_services')->default(0);
                $table->unsignedInteger('commission_medicals')->default(50);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropIfExists(['commission_services', 'commission_medicals']);
            //$table->dropColumn('commission_medicals');
        });
    }
}
