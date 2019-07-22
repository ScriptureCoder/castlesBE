<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title")->nullable();
            $table->longText("description")->nullable();
            $table->longText("days")->nullable();
            $table->string("image")->nullable();
            $table->date("expired_at")->nullable();
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE adverts AUTO_INCREMENT = 2000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adverts');
    }
}
