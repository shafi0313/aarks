<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPaymentListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_payment_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->double('amount', 10, 2);
            $table->string('duration');
            $table->string('message')->nullable();
            $table->text('rcpt');
            $table->boolean('status')->default(0);
            $table->boolean('is_expire')->default(0);

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

            $table->dateTime('started_at')->nullable();
            $table->dateTime('expire_at')->nullable();

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
        Schema::dropIfExists('client_payment_lists');
    }
}
