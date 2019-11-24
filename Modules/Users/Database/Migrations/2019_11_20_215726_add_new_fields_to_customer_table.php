<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('valid_means_of_id')->nullable();
            $table->string('work_id')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('current_city')->nullable();
            $table->string('city_of_residence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('valid_means_of_id');
            $table->dropColumn('work_id');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('current_city');
            $table->dropColumn('city_of_residence');
        });
    }
}
