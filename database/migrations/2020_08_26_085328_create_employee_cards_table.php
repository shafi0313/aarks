<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('profession_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('status');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob')->comment(' Date of Birth');
            $table->string('gender');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->integer('post_code');
            $table->string('country');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->date('start_date');
            $table->date('term_date')->comment('Termination Date')->nullable();
            $table->string('emp_basis');
            $table->string('emp_category');
            $table->string('emp_status');
            $table->string('emp_classification');
            // Wages Start
            $table->string('pay_basis');
            $table->double('annual_salary',8,4)->nullable();
            $table->double('hourly_rate',8,4)->nullable();
            $table->string('pay_frequency');
            $table->double('hour_pay_frequency',8,4);
            $table->string('netpay_wages_ac');
            $table->string('tw_exp_ac')->comment('T/Withhold Exp AC');
            $table->string('tw_payable_ac')->comment('T/Withhold Payable AC');
            $table->string('tax_number');
            $table->string('tax_table');
            $table->double('wv_rate',8,4)->comment('Withhold Variations Rate')->nullable();
            $table->string('total_rebate')->nullable();
            $table->string('extra_tax')->nullable();
            $table->longText('wages');
            // Payment
            $table->string('payment_method')->nullable();
            $table->string('payment_ac')->nullable();
            $table->text('payment_note')->nullable();
            // Entatilement
            $table->longText('leave')->nullable();
            // suppper anuation
            $table->longText('superannuation');
            $table->string('sup_fund')->nullable();
            $table->string('sup_exp_ac');
            $table->string('emp_membership')->nullable();
            $table->string('sup_payable_ac');
            // Deduction
            $table->string('deduction')->nullable();
            $table->string('link_dd_ac')->nullable();


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
        Schema::dropIfExists('employee_cards');
    }
}
