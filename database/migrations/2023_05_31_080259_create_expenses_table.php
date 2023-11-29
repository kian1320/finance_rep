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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('date_issued');
            $table->string('voucher');
            $table->string('check');
            $table->string('encashment');
            $table->string('description');
            $table->string('type_id');
            $table->string('stype_id');
            $table->decimal('amount', 10, 2);
            $table->string('others')->nullable();
            $table->string('created_by');
            $table->unsignedBigInteger('summary_id')->nullable();
            $table->foreign('summary_id')->references('id')->on('summary');
            $table->integer('late_encash')->default('0'); //1 if it is added from the late encashment
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
        Schema::dropIfExists('expenses');
    }
};
