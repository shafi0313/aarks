<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfessionIdFuelTaxLtrs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fuel_tax_ltrs', function (Blueprint $table) {
            $table->foreignId('profession_id')->constrained()->after('client_id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fuel_tax_ltrs', function (Blueprint $table) {
            $table->dropForeign(['profession_id']);
            $table->dropColumn(['profession_id']);
        });
    }
}
