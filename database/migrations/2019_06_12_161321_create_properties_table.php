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
            $table->integer("property_status_id")->default(1);
            $table->integer("property_type_id")->default(1);
            $table->integer("label_id")->default(1);
            $table->boolean("featured")->default(false);
            $table->integer("image_id")->nullable();
            $table->integer("bedrooms")->nullable()->default(0);
            $table->integer("bathrooms")->nullable()->default(0);
            $table->integer("toilets")->nullable()->default(0);
            $table->boolean("furnished")->default(false);
            $table->boolean("serviced")->default(false);
            $table->integer("parking")->nullable();
            $table->integer("total_area")->nullable()->default(0);
            $table->integer("covered_area")->nullable()->default(0);
            $table->integer("state_id")->nullable()->default(25);
            $table->integer("locality_id")->default(1);
            $table->integer("country_id")->default(160);
            $table->string("address")->nullable();
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
