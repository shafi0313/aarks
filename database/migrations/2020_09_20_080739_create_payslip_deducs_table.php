<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayslipDeducsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payslip_deducs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->string('name');
            $table->string('tran_id', 64)->index();
            $table->double('amount', 10, 4)->default(0);
            $table->double('rate', 10, 4)->nullable();
            $table->double('fix_amt', 10, 4)->nullable();
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
        Schema::dropIfExists('payslip_deducs');
    }
}
