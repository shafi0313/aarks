<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profession_id')->constrained()->cascadeOnDelete();
            $table->foreignId('measure_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_account_code_id')->constrained()->cascadeOnDelete();
            $table->string('category');
            $table->string('alige')->nullable();
            $table->bigInteger('item_number')->unsigned();
            $table->string('item_name');
            $table->bigInteger('bin_number')->unsigned()->nullable();
            $table->bigInteger('barcode_number')->unsigned()->nullable();
            $table->enum('type', [1,2,3])->comment('1=Buy,2=Sell,3=Stock');
            $table->enum('status', [0,1])->comment('0=inactive,1=Active');
            $table->double('price', 14, 2);
            $table->string('gst_code');
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
        Schema::dropIfExists('inventory_items');
    }
}
