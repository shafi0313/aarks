<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profession_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->enum('source', ['stock','sales','purchase']);
            $table->string('item_name')->nullable();
            $table->date('date')->default(now());
            $table->double('sales_qty', 14, 2)->nullable();
            $table->double('sales_rate', 14, 2)->nullable();
            $table->double('purchase_qty', 14, 2)->nullable();
            $table->double('purchase_rate', 14, 2)->nullable();
            $table->double('close_qty', 14, 2)->nullable();
            $table->double('close_rate', 14, 2)->nullable();
            $table->double('close_amount', 14, 2)->nullable();
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
        Schema::dropIfExists('inventory_registers');
    }
}
