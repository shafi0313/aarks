<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreparePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prepare_payrolls', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('profession_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('employee_card_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('financial_year')->nullable();
            $table->date('tran_date')->nullable();
            $table->string('tran_id', 64)->index()->nullable();
            $table->string('source')->nullable();
            $table->double('salary', 14, 4)->nullable();
            $table->double('accum_salary', 14, 4)->nullable();
            $table->double('director_fee', 14, 4)->nullable();
            $table->double('accum_director_fee', 14, 4)->nullable();
            $table->double('overtime', 14, 4)->nullable();
            $table->double('accum_overtime', 14, 4)->nullable();
            $table->double('allowence', 14, 4)->nullable();
            $table->double('accum_allowence', 14, 4)->nullable();
            $table->double('bonus', 14, 4)->nullable();
            $table->double('accum_bonus', 14, 4)->nullable();
            $table->double('lump', 14, 4)->nullable();
            $table->double('accum_lump', 14, 4)->nullable();
            $table->double('etp', 14, 4)->nullable();
            $table->double('accum_etp', 14, 4)->nullable();
            $table->double('secriface', 14, 4)->nullable();
            $table->double('accum_secriface', 14, 4)->nullable();
            $table->double('exempt', 14, 4)->nullable();
            $table->double('accum_exempt', 14, 4)->nullable();
            $table->double('cdep', 14, 4)->nullable();
            $table->double('accum_cdep', 14, 4)->nullable();
            $table->double('payg', 14, 4)->nullable();
            $table->double('accum_payg', 14, 4)->nullable();
            $table->double('annual', 14, 4)->comment('Annual Leave')->nullable();
            $table->double('accum_annual', 14, 4)->comment('Annual Leave')->nullable();
            $table->double('personal', 14, 4)->comment('Personal Leave')->nullable();
            $table->double('accum_personal', 14, 4)->comment('Personal Leave')->nullable();
            $table->double('super', 14, 4)->nullable();
            $table->double('accum_super', 14, 4)->nullable();
            $table->double('gross', 14, 4)->nullable();
            $table->double('accum_gross', 14, 4)->nullable();

            $table->double('wages', 10, 4)->nullable();
            $table->double('accum_wages', 10, 4)->nullable();
            $table->double('deduc', 10, 4)->nullable();
            $table->double('accum_deduc', 10, 4)->nullable();
            $table->double('gorss', 10, 4)->nullable();
            $table->double('net_pay', 10, 4)->nullable();

            $table->date('payment_date')->nullable();
            $table->date('payperiod_start')->nullable();
            $table->date('payperiod_end')->nullable();
            $table->string('pay_period')->nullable();
            $table->string('statement')->nullable();
            $table->string('memo')->nullable();
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
        Schema::dropIfExists('prepare_payrolls');
    }
}
