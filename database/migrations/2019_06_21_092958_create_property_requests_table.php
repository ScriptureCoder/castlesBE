<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id")->nullable();
            $table->integer("status_id")->default(1);
            $table->integer("budget");
            $table->string("type");
            $table->integer("bedrooms")->nullable();
            $table->integer("state_id");
            $table->integer("locality_id");
            $table->integer("role_id")->default(1);
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->longText("comment")->nullable();
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
        Schema::dropIfExists('property_requests');
    }
}
