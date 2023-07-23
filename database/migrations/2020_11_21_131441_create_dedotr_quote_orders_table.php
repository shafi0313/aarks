<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDedotrQuoteOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dedotr_quote_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('customer_card_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('profession_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('chart_id');
            $table->string('source');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('inv_no');
            $table->string('your_ref')->nullable();
            $table->string('our_ref')->nullable();
            $table->text('quote_terms')->nullable();
            $table->string('job_title')->nullable();
            $table->string('job_des')->nullable();
            $table->string('item_no')->nullable();
            $table->string('item_name')->nullable();
            $table->string('item_quantity')->nullable();
            $table->string('hours')->nullable();
            $table->double('price', 14, 2);
            $table->double('disc_rate', 14, 2)->nullable();
            $table->double('disc_amount', 14, 2)->nullable();
            $table->double('freight_charge', 14, 2)->nullable();
            $table->double('tax_rate', 14, 2)->nullable();
            $table->double('ex_rate', 14, 2)->nullable();
            $table->double('amount', 14, 2)->nullable();
            $table->bigInteger('payment_no')->nullable();
            $table->double('payment_amount', 14, 2)->nullable();
            $table->string('is_tax')->default('no');
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
        Schema::dropIfExists('dedotr_quote_orders');
    }
}
