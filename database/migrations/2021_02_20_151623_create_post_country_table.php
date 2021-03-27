<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_country', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('country_id');
            $table->text('product_name');
            $table->integer('product_unit');
            $table->timestamps();
            // foreign keys
            $table->foreign('post_id')->references('id')
                ->on('posts')->onDelete('cascade');
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade');

            $table->unique(['post_id', 'country_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_country');
    }
}
