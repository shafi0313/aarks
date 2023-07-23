<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisposeableLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposeable_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('batch')->index()->nullable();
            $table->foreignId('dep_asset_name_id')->constrained()->cascadeOnDelete();
            $table->foreignId('general_ledger_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disposeable_ledgers');
    }
}
