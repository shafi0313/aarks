<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditorPaymentReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditor_payment_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("creditor_inv")->nullable();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('customer_card_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('profession_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('source')->index()->comment('1=Services & 2=Items');
            $table->integer('chart_id');
            $table->date('tran_date');
            $table->string('tran_id', 64)->index();
            $table->string('inv_no');
            $table->double('payment_amount', 14, 2)->nullable();
            $table->double('accum_payment_amount', 14, 2)->nullable();
            // $table->foreign("creditor_inv")->references('inv_no')->on('creditors')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditor_payment_receives');
    }
}
