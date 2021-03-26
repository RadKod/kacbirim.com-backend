<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryWage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_wage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->date('year');
            $table->integer('wage');
            $table->timestamps();
            // foreign keys
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade');

            $table->unique(['country_id', 'year', 'wage']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_wage');
    }
}
