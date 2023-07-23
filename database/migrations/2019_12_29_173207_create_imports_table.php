<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_statement_imports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_code')->nullable();
            $table->date('date');
            $table->string('narration');
            $table->float('debit');
            $table->float('credit');
            $table->tinyInteger('is_posted')->default(0);
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('profession_id')->unsigned();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->foreign('profession_id')
                ->references('id')
                ->on('professions')
                ->onDelete('cascade');
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
        Schema::dropIfExists('bank_statement_imports');
    }
}
