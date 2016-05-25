<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentLogTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('payment.table').'_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned()->index();
            $table->string('code');
            $table->string('message');
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('payment.table').'_log');
    }

}
