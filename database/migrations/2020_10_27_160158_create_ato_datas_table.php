<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtoDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ato_datas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            // $table->foreignId('employee_card_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->json('pay_accum_id');
            $table->date('payment_date');
            $table->string('gross');
            $table->string('payg');
            $table->tinyInteger('payrun')->default(0);
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
        Schema::dropIfExists('ato_datas');
    }
}
