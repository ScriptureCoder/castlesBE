<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("user_id");
            $table->string("title");
            $table->integer("price");
            $table->string("slug");
            $table->longText("description");
            $table->integer("property_status_id");
            $table->integer("property_type_id");
            $table->boolean("feature")->default(false);
            $table->integer("image_id")->nullable();
            $table->integer("bedrooms")->nullable();
            $table->integer("bathrooms")->nullable();
            $table->integer("state_id");
            $table->integer("city_id");
            $table->integer("country_id")->default(160);
            $table->string("address")->nullable();
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
        Schema::dropIfExists('properties');
    }
}
