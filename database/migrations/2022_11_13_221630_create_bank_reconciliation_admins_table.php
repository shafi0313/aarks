<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankReconciliationAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_reconciliation_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('tran_id')->index();
            $table->dateTime('date')->nullable();
            $table->string('chart_id')->nullable();
            $table->string('code_name')->nullable();
            $table->string('narration')->nullable();
            $table->boolean('is_posted')->default(0);
            $table->unsignedDouble('debit', 14, 2)->nullable();
            $table->unsignedDouble('credit', 14, 2)->nullable();
            $table->double('diff', 14, 2)->nullable();
            $table->string('identifier')->nullable();
            $table->boolean('is_check')->default(0);
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
        Schema::dropIfExists('bank_reconciliation_admins');
    }
}
