<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfessionAccountCodeIndustryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry_category_profession_account_code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('profession_account_code_id');
            $table->foreign('profession_account_code_id', 'account_code_id')
                ->references('id')
                ->on('account_code_profession');
            $table->unsignedBigInteger('industry_category_id');
            $table->foreign('industry_category_id', 'industry_category')->references('id')->on('industry_categories');
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
        Schema::dropIfExists('industry_category_profession_account_code');
    }
}
