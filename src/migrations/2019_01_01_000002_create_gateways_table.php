<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatewaysTable extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up(){

        Schema::create('gateways', function(Blueprint $table){

            $table->increments('id');
            $table->string('title');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(){

        Schema::dropIfExists('gateways');
    }

}
