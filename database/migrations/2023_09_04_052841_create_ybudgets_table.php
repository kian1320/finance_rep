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
        Schema::create('ybudgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ysummary_id');
            $table->unsignedBigInteger('btypes_id');
            $table->unsignedBigInteger('bstypes_id');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('created_by');
            $table->timestamps();
            $table->foreign('ysummary_id')->references('id')->on('ysummary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ybudgets');
    }
};
