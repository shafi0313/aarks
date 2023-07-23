<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profession_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('chart_id')->index();
            $table->date('date')->index();
            $table->string('tran_id')->index();
            $table->boolean('is_bank')->default(false)->index();
            $table->double('physical_balance', 14, 4)->nullable();
            $table->double('ledger_balance', 14, 4)->nullable();
            $table->double('reconciled_dr', 14, 4)->nullable();
            $table->double('reconciled_cr', 14, 4)->nullable();
            $table->double('unreconciled_dr', 14, 4)->nullable();
            $table->double('unreconciled_cr', 14, 4)->nullable();
            $table->double('tran_ust', 14, 4)->nullable();
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
        Schema::dropIfExists('bank_reconciliations');
    }
}
