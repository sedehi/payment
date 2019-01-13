<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionLogsTable extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up(){

        Schema::create('transaction_logs', function(Blueprint $table){

            $table->increments('id');
            $table->integer('transaction_id')->unsigned()->index();
            $table->string('code');
            $table->string('message');
            $table->timestamps();
            $table->foreign('transaction_id')
                  ->references('id')
                  ->on('transactions')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(){

        Schema::dropIfExists('transaction_logs');
    }

}
