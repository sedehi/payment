<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up(){

        Schema::create('transactions', function(Blueprint $table){

            $table->increments('id');
            $table->string('reference', 255)->nullable()->index();
            $table->string('authority', 255)->nullable()->index();
            $table->integer('amount')->unsigned()->index();
            $table->string('gateway', 20)->index();
            $table->tinyInteger('status')->default(0);
            $table->string('card_number', 20)->nullable()->index();
            $table->text('description')->nullable();
            $table->string('ip', 15)->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(){

        Schema::dropIfExists('transactions');
    }

}
