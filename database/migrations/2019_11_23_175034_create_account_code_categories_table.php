<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountCodeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_code_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('code');
            $table->text('note')->nullable();
            $table->integer('type')
                ->default(3)
                ->comment('1 = Parent Category, 2 = Sub Category, 3 = Additional Category');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')
                ->references('id')
                ->on('account_code_categories')
                ->onDelete('cascade');
            $table->boolean('is_deletable')->default(true);
            $table->boolean('is_for_all_professions')->default(false);
            modificationFields($table);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('account_code_category_profession', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_code_category_id');
            $table->foreign('account_code_category_id', 'profession_category_id')
                ->references('id')
                ->on('account_code_categories')
                ->onDelete('cascade');
            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')
                ->references('id')
                ->on('professions')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('account_code_category_industry_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_code_category_id');
            $table->foreign('account_code_category_id', 'industry_category_category_id')
                ->references('id')
                ->on('account_code_categories')
                ->onDelete('cascade');
            $table->unsignedBigInteger('industry_category_id');
            $table->foreign('industry_category_id', 'industry_category_id')
                ->references('id')
                ->on('industry_categories')
                ->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('account_code_category_industry_category');
        Schema::dropIfExists('account_code_category_profession');
        Schema::dropIfExists('account_code_categories');
    }
}
