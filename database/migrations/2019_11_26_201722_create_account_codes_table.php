<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('code')->unique();
            $table->string('gst_code');
            $table->text('note')->nullable();
            $table->integer('type')
                ->comment('1 = Debit, 2 = Credit');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                ->references('id')
                ->on('account_code_categories')
                ->onDelete('cascade');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')
                ->references('id')
                ->on('account_code_categories')
                ->onDelete('cascade');
            $table->unsignedBigInteger('additional_category_id')->nullable();
            $table->foreign('additional_category_id')
                ->references('id')
                ->on('account_code_categories')
                ->onDelete('cascade');
            $table->boolean('is_deletable')->default(true);
            $table->boolean('is_for_all_professions')->default(false);
            modificationFields($table);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('account_code_profession', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')->references('id')->on('professions');
            $table->unsignedBigInteger('account_code_id');
            $table->foreign('account_code_id')->references('id')->on('account_codes');
            $table->timestamps();
        });

        Schema::create('account_code_industry_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_code_id');
            $table->foreign('account_code_id')->references('id')->on('account_codes');
            $table->unsignedBigInteger('industry_category_id');
            $table->foreign('industry_category_id')->references('id')->on('industry_categories');
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
        Schema::dropIfExists('account_code_industry_category');
        Schema::dropIfExists('account_code_profession');
        Schema::dropIfExists('account_codes');
    }
}
