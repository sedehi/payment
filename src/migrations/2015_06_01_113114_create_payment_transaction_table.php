<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTransactionTable extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create(config('payment.table'), function(Blueprint $table){
            $table->increments('id');
            $table->string('reference', 255)->nullable()->default(null)->index();
            $table->string('authority', 255)->nullable()->default(null)->index();
            $table->integer('amount')->unsigned()->index();
            $table->enum('provider', [
                'zarinpal',
                'mellat',
            ])->index();
            $table->enum('currency', [
                'toman',
                'rial',
            ])->index();
            $table->tinyInteger('status')->default(0)->index();
            $table->string('card_number', 20)->nullable()->default(null)->index();
            $table->text('description')->nullable();
            $table->string('ip', 15)->nullable()->index();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop(config('payment.table'));
    }

}
