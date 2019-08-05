<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccreditationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accreditations', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
            $table->string("issuer")->nullable();
            $table->date("issuing_date")->nullable();
            $table->date("expiry_date")->nullable();
            $table->string("id_code")->nullable();
            $table->string("url")->nullable();
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE accreditations AUTO_INCREMENT = 2000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accreditations');
    }
}
