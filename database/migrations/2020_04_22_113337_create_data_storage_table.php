<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataStorageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_storages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->unsignedBigInteger('period_id');
            $table->foreign('period_id')
                ->references('id')
                ->on('periods')
                ->onDelete('cascade');
            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')
                ->references('id')
                ->on('professions')
                ->onDelete('cascade');
            $table->string('trn_id', 64)->index()->nullable();
            $table->date('trn_date');
            $table->string('source');
            $table->string('narration')->nullable()->default('EMPTY');
            $table->string('chart_id');
            $table->string('gst_code');
            $table->tinyInteger('ac_type')->comment('1=Debit,2=Credit');
            $table->tinyInteger('amt_type')->comment('1=Debit,2=Credit');
            $table->double('amount_debit', 8, 2)->nullable();
            $table->double('amount_credit', 8, 2)->nullable();
            $table->double('gst_accrued_debit', 8, 2)->nullable();
            $table->double('gst_accrued_credit', 8, 2)->nullable();
            $table->double('gst_cash_debit', 8, 2)->nullable();
            $table->double('gst_cash_credit', 8, 2)->nullable();
            $table->double('net_amount_debit', 8, 2)->nullable();
            $table->double('net_amount_credit', 8, 2)->nullable();
            $table->integer('percent')->nullable();
            $table->double('total_inv', 8, 2)->nullable();
            $table->double('balance', 8, 2)->nullable();
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
        Schema::dropIfExists('data_storages');
    }
}
