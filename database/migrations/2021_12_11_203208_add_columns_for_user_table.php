<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('remember_token', function ($table) {
                $table->unsignedBigInteger('type_id')->unsigned()->index()->nullable();
                $table->string('surname')->nullable();
                $table->string('phone')->nullable();
                $table->boolean('set_pass')->default(false);
                $table->date('date_set_pass')->nullable();
                $table->boolean('active')->default(true);
                $table->unsignedBigInteger('parent_id')->unsigned()->index()->nullable();

                $table->foreign('type_id')->references('id')->on('user_types')->onDelete('SET NULL');
                $table->foreign('parent_id')->references('id')->on('users')->onDelete('SET NULL');
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
            $table->dropForeign('users_type_id_foreign');
            $table->dropForeign('users_parent_id_foreign');

            $table->dropColumn('type_id');
            $table->dropColumn('surname');
            $table->dropColumn('phone');
            $table->dropColumn('set_pass');
            $table->dropColumn('date_set_pass');
            $table->dropColumn('active');
            $table->dropColumn('parent_id');
        });
    }
}
