<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 190)->unique();
            $table->bigInteger('user_id')->unsigned()->index()->references('id')->on('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('telephone')->nullable();
            $table->string('work_email')->nullable();
            $table->string('bvn_number')->nullable();
            $table->string('bvn_phone_number')->nullable();
            $table->string('gender')->nullable();
            $table->string('employment_number')->nullable();
            $table->string('employer_address')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('employment_status')->nullable();
            $table->string('ippis_number')->nullable();
            $table->string('nok_full_name')->nullable();
            $table->string('nok_email')->nullable();
            $table->string('nok_address')->nullable();
            $table->string('nok_relationship')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('recipient_code')->nullable();
            $table->string('no_of_children')->nullable();
            $table->string('date_of_employment')->nullable();
            $table->double('gross_earnings')->nullable();
            $table->double('net_earnings')->nullable();
            $table->string('nok_phone_number')->nullable();
            $table->string('employer_sector')->nullable();
            $table->string('employer_industry')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('employer_state')->nullable();
            $table->string('employer_lga')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
