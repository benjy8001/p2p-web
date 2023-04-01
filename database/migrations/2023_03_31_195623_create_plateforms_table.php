<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plateforms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('name', 64);
            $table->string('slug', 64);
            $table->tinyInteger('order');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('link');
            $table->string('referral_code', 12);
            $table->smallInteger('invested_amount');
            $table->float('percentage', 2, 2);
            $table->string('rank', 1);
            $table->string('country', 2);

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
        Schema::dropIfExists('plateforms');
    }
};
