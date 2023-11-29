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
        Schema::create('summary', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->string('type');
            $table->decimal('beginbal', 10, 2)->nullable();
            $table->decimal('totalstr', 10, 2)->nullable();
            $table->decimal('aftexpenses', 10, 2)->nullable();
            $table->integer('created_by');
            $table->integer('submitted')->default('0'); //1 if it is submitted and 2 if approved
            $table->integer('approved_by')->nullable();
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
        Schema::dropIfExists('summary');
    }
};
