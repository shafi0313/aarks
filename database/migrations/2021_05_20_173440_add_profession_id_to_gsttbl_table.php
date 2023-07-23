<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfessionIdToGsttblTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gsttbls', function (Blueprint $table) {
            $table->foreignId('profession_id')->nullable()->after('client_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gsttbls', function (Blueprint $table) {
            $table->dropForeign(['profession_id']);
            $table->dropColumn(['profession_id']);
        });
    }
}
