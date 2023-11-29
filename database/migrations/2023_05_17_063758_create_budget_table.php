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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('summary_id');
            $table->unsignedBigInteger('btypes_id');
            $table->unsignedBigInteger('bstypes_id');
            $table->string('others')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('created_by');
            $table->unsignedBigInteger('month');
            $table->unsignedBigInteger('year');
            $table->timestamps();
            $table->foreign('summary_id')->references('id')->on('summary');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget');
    }
};
