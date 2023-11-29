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
        Schema::create('bothers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('summary_id');
            $table->unsignedBigInteger('budget_id');
            $table->unsignedBigInteger('btypes_id');
            $table->unsignedBigInteger('bstypes_id');
            $table->integer('created_by');
            $table->string('others')->nullable();
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
        Schema::dropIfExists('bothers');
    }
};
