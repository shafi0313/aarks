<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('account_name');
            $table->date('transaction_date');
            $table->string('narration');
            $table->float('debit');
            $table->float('credit');

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

            $table->unsignedBigInteger('client_account_code_id');
            $table->foreign('client_account_code_id')
                ->references('id')
                ->on('client_account_codes')
                ->onDelete('cascade');

            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transaction_numbers')
                ->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('transactions');
    }
}
