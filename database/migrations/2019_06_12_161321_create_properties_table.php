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
            $table->boolean("featured")->default(false);
            $table->integer("image_id")->nullable();
            $table->integer("bedrooms")->nullable();
            $table->integer("bathrooms")->nullable();
            $table->integer("toilets")->nullable();
            $table->boolean("furnished")->default(false);
            $table->boolean("serviced")->default(false);
            $table->integer("parking")->nullable();
            $table->integer("total_area")->nullable();
            $table->integer("covered_area")->nullable();
            $table->integer("state_id");
            $table->integer("locality_id");
            $table->integer("country_id")->default(160);
            $table->integer("views")->default(0);
            $table->string("address")->nullable();
            $table->integer("label_id")->nullable();
            $table->boolean("published")->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE properties AUTO_INCREMENT = 54500;');

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
