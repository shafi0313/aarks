<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayslipWagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payslip_wages', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->string('name');
            $table->string('tran_id', 64)->index();
            $table->double('hours',10,4)->nullable();
            $table->double('rate',10,4)->nullable();
            $table->double('amount',10,4)->nullable();
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
        Schema::dropIfExists('payslip_wages');
    }
}
