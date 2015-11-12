<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreatePaymentTransactionTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create(Config::get('payment::table'), function (Blueprint $table) {

            $table->increments('id');
            $table->integer('order_id')->unsigned()->default(null)->index()->nullable();
            $table->string('reference', 255)->nullable()->index();
            $table->string('authority', 255)->index();
            $table->decimal('amount', 15, 0);
            $table->enum('provider', array(
                'zarinpal',
                'payline',
                'jahanpay',
                'mellat',
            ))->index();
            $table->enum('currency', array(
                'toman',
                'rial',
            ))->index();
            $table->tinyInteger('status')->index();
            $table->string('card_number', 20)->nullable()->default(null)->index();
            $table->text('description')->nullable();
            $table->string('ip', 20)->nullable()->index();
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

        Schema::drop(Config::get('payment::table'));
    }

}