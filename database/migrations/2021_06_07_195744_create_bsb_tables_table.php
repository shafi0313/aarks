<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBsbTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsb_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profession_id')->constrained()->cascadeOnDelete();
            $table->string('bsb_number')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('customer_cards', function (Blueprint $table) {
            $table->foreignId('bsb_table_id')->nullable()->after('payment_note')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bsb_tables');

        Schema::table('customer_cards', function (Blueprint $table) {
            $table->dropForeign(['bsb_table_id']);
            $table->dropColumn(['bsb_table_id']);
        });
    }
}
