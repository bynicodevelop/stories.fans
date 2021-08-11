<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('content')->nullable();
            $table->integer('price_monthly');
            $table->string('price_monthly_id');
            $table->integer('price_quarterly')->nullable();
            $table->string('price_quarterly_id')->nullable();
            $table->integer('price_annually')->nullable();
            $table->string('price_annually_id')->nullable();
            $table->integer('day_trial')->nullable();
            $table->boolean('deleted')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Schema::table('posts', function (Blueprint $table) {
        //     $table->unsignedBigInteger('plan_id')->nullable();

        //     $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
