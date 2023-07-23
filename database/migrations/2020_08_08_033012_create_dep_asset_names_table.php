<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepAssetNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dep_asset_names', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('depreciation_id');
            $table->integer('year')->nullable();
            $table->integer('status')->default(1);
            $table->foreign('depreciation_id')->references('id')->on('depreciations')->cascadeOnDelete();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')->references('id')->on('professions')->onDelete('cascade');
            $table->string('asset_name')->nullable();
            $table->date('owdv_value_date')->nullable();
            $table->double('owdv_value', 8, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->double('purchase_rate', 8, 2)->nullable();
            $table->double('purchase_value', 8, 2)->nullable();
            $table->double('adjust_dep', 8, 2)->default(0);
            $table->date('disposal_date')->nullable();
            $table->double('disposal_value', 8, 2)->nullable();
            $table->double('total_value', 8, 2)->nullable();
            $table->string('dep_method')->nullable();
            $table->integer('dep_rate')->nullable();
            $table->double('dep_amt', 8, 2)->nullable();
            $table->string('cwdv_value')->nullable();
            $table->string('profit')->nullable();
            $table->string('loss')->nullable();
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
        Schema::dropIfExists('dep_asset_names');
    }
}
