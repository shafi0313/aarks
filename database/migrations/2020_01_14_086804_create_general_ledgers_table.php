<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('chart_id');
            $table->date('date');
            $table->string('narration');
            $table->string('source');
            $table->float('loan')->default(0);
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);
            $table->float('gst')->default(0);
            $table->float('balance')->default(0);
            $table->tinyInteger('balance_type')
                ->nullable()
                ->comment('null = not set yet, 0 = credit, 1 = debit');
            $table->string('transaction_for')
                ->comment('main,gst,opposite')
                ->nullable();
            $table->string('payable_liabilty')
                ->comment('For identify Which code is that!')
                ->nullable();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')
                ->references('id')
                ->on('professions')
                ->onDelete('cascade');
            $table->bigInteger('client_account_code_id');
            $table->string('transaction_id');
            $table->integer('batch')->nullable();
            $table->timestamps();
            $table->softDeletes();
            modificationFields($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_ledgers');
    }
}
