<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpDesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_desks', function (Blueprint $table) {
            $table->id();
            $table->boolean('parent_id')->default(0)->nullable();
            $table->boolean('type')->default(1)->comment('1 support/help 0 faq');
            // $table->string('category')->index();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();
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
        Schema::dropIfExists('help_desks');
    }
}
