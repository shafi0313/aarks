<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->longText('description')->nullable();
            $table->double('amount', 14, 3);
            $table->integer('interval')->nullable()->index();
            $table->integer('sales_quotation')->nullable();
            $table->integer('purchase_quotation')->nullable();
            $table->integer('invoice')->nullable();
            $table->integer('bill')->nullable();
            $table->integer('receipt')->nullable();
            $table->integer('payment')->nullable();
            $table->integer('payslip')->nullable();
            $table->integer('discount')->nullable()->comment('% discount with focus taxation for tax & Accounting fee.');
            $table->boolean('access_report')->default(0);
            $table->boolean('customer_support')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
