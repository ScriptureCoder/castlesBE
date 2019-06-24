<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("bedrooms")->nullable();
            $table->integer("bathrooms")->nullable();
            $table->integer("property_type_id")->nullable();
            $table->integer("locality_id")->nullable();
            $table->integer("state_id")->nullable();
            $table->integer("min_price")->nullable();
            $table->integer("max_price")->nullable();
            $table->integer("property_status_id")->nullable();
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
        Schema::dropIfExists('searches');
    }
}
