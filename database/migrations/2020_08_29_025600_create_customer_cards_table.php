<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('profession_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('status');
            $table->tinyInteger('type')->comment('1=customer, 2=supplier, 3=personal');
            $table->string('customer_type');
            $table->string('customer_ref');
            $table->string('name');
            $table->text('b_address');
            $table->string('b_city');
            $table->string('b_state');
            $table->string('b_postcode');
            $table->string('b_country');
            $table->text('s_address')->nullable();
            $table->string('s_city')->nullable();
            $table->string('s_state')->nullable();
            $table->string('s_postcode')->nullable();
            $table->string('s_country')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('xxx')->nullable();
            $table->string('greeting')->nullable();
            // Buying Details
            $table->string('inv_layout');
            $table->string('inv_delivery');
            $table->string('barcode_price')->nullable();
            $table->string('shipping_method')->nullable();
            $table->double('inv_rate', 8, 2)->nullable();
            $table->double('card_limit', 8, 2)->nullable();
            $table->string('abn')->nullable();
            $table->string('gst_code')->nullable();
            $table->string('freight_code')->nullable();
            // Buying terms
            $table->string('contact_person')->nullable();
            $table->string('inv_comment')->nullable();
            $table->string('early_discount')->nullable();
            $table->string('late_fee')->nullable();
            $table->string('overall_discount')->nullable();
            $table->string('payment_due')->nullable();
            $table->string('days')->nullable();
            $table->string('by_date')->nullable();
            $table->string('after_date')->nullable();
            // payment
            $table->string('payment_method')->nullable();
            $table->string('payment_note')->nullable();
            $table->string('opening_blnc')->nullable();
            $table->string('opening_blnc_date')->nullable();
            $table->string('credit_account')->nullable();

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
        Schema::dropIfExists('customer_cards');
    }
}
