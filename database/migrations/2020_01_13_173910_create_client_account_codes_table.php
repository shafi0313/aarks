<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientAccountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_account_codes', function (Blueprint $table) {
            $table->string('name');
            $table->integer('code');
            $table->string('gst_code');
            $table->text('note')->nullable();
            $table->integer('type')
                ->comment('1 = Debit, 2 = Credit');
            $table->unsignedBigInteger('category_id')
                ->nullable();
            $table->foreign('category_id')
                ->references('id')
                ->on('account_code_categories')
                ->onDelete('cascade');
            $table->unsignedBigInteger('sub_category_id')
                ->nullable();
            $table->foreign('sub_category_id')
                ->references('id')
                ->on('account_code_categories')
                ->onDelete('cascade');
            $table->unsignedBigInteger('additional_category_id')
                ->nullable();
            $table->foreign('additional_category_id')
                ->references('id')
                ->on('account_code_categories')
                ->onDelete('cascade');
            $table->unsignedBigInteger('account_code_id')
                ->nullable()
                ->change();

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')->references('id')->on('professions')->onDelete('cascade');

            $table->timestamps();
            $table->integer('0')->nullable();
            modificationFields($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_account_codes');
    }
}
