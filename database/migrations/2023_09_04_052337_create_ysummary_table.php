<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ysummary', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('type');
            $table->decimal('beginbal', 10, 2)->nullable();
            $table->decimal('totalstr', 10, 2)->nullable();
            $table->decimal('aftexpenses', 10, 2)->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ysummary');
    }
};
