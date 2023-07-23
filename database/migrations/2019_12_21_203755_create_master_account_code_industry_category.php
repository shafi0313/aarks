<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterAccountCodeIndustryCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_account_code_industry_category', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('master_account_code_id');
//            $table->foreign('master_account_code_id')
//                ->references('id')
//                ->on('master_account_code')
//                ->onDelete('cascade');

            $table->unsignedBigInteger('industry_category_id');
//            $table->foreign('industry_category_id')
//                ->references('id')
//                ->on('industry_categories')
//                ->onDelete('cascade');

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
        Schema::dropIfExists('master_account_code_industry_category');
    }
}
