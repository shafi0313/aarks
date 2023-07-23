<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('account_code')->nullable();
            $table->date('date')->nullable();
            $table->string('narration');
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);
            $table->float('gst')->default(0);
            $table->float('net_amount')->default(0);
            $table->tinyInteger('is_posted')->default(0);
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('profession_id')->unsigned();
            $table->string('gst_code');
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
        Schema::dropIfExists('journal_entries');
    }
}
