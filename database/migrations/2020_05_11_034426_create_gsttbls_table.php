<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsttblsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsttbls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->unsignedBigInteger('period_id');
            $table->foreign('period_id')
                ->references('id')
                ->on('periods')
                ->onDelete('cascade');
            $table->string('trn_id', 64)->index();
            $table->date('trn_date');
            $table->integer('chart_code');
            $table->string('source');
            $table->double('gross_amount', 8, 2);
            $table->double('gross_cash_amount', 8, 2);
            $table->double('gst_accrued_amount', 8, 2)->nullable();
            $table->double('gst_cash_amount', 8, 2)->nullable();
            $table->double('net_amount', 8, 2);

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
        Schema::dropIfExists('gsttbls');
    }
}
