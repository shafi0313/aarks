<?php

use App\Models\DepAssetName;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetnameTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assetname_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DepAssetName::class)->constrained()->onDelete('cascade');
            $table->string('tran_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assetname_transaction');
    }
}
