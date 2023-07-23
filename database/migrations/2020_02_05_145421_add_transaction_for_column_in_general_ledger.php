<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionForColumnInGeneralLedger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('transaction_for')
                ->after('credit')
                ->comment('main,gst,opposite')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_ledgers', function (Blueprint $table) {
            $table->dropColumn('transaction_for');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('transaction_for');
        });
    }
}
