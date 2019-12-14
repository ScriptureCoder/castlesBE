<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('image_id')->nullable();
            $table->string('email',100)->unique();
            $table->string("provider")->default("direct");
            $table->string("address")->nullable();
            $table->mediumText("bio")->nullable();
            $table->integer("country_id")->default(160);
            $table->integer("state_id")->nullable();
            $table->string("phone")->nullable();
            $table->string("whatsapp")->nullable();
            $table->integer("role_id")->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users AUTO_INCREMENT = 3500;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
