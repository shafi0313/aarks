<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandardDeducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_deducations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('rate', 8, 4)->nullable();
            $table->string('tools')->nullable();
            $table->double('fix_amt', 8, 4)->nullable();
            $table->string('period')->nullable();
            $table->tinyInteger('limit')->nullable();
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
        Schema::dropIfExists('standard_deducations');
    }
}
