<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
        $table->increments('id');
        $table->string('order_id', 500)->nullable();
        $table->string('token', 500)->nullable();
        $table->string('billingagreementacceptedstatus', 500)->nullable();
        $table->string('checkoutstatus', 500)->nullable();
        $table->string('timestamp', 500)->nullable();
        $table->string('correlationid', 500)->nullable();
        $table->string('ack', 500)->nullable();
        $table->string('email', 500)->nullable();
        $table->string('payerid', 500)->nullable();
        $table->string('payerstatus', 500)->nullable();
        $table->string('firstname', 500)->nullable();
        $table->string('lastname', 500)->nullable();
        $table->string('countrycode', 500)->nullable();
        $table->string('addressstatus', 500)->nullable();
        $table->string('currencycode', 500)->nullable();
        $table->string('amt', 500)->nullable();
        $table->string('itemamt', 500)->nullable();
        $table->string('shippingamt', 500)->nullable();
        $table->string('handlingamt', 500)->nullable();
        $table->string('taxamt', 500)->nullable();
        $table->string('desc', 500)->nullable();
        $table->string('invnum', 500)->nullable();
        $table->string('insuranceamt', 500)->nullable();
        $table->string('shipdiscamt', 500)->nullable();
        $table->string('insuranceoptionoffered', 500)->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
