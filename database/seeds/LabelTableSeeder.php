<?php

use Illuminate\Database\Seeder;

class LabelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table("labels")->insert([
            ["name"=>"Hot Offer"],
            ["name"=>"Premium Listing"],
            ["name"=>"Sale"],
            ["name"=>"Sold"]
        ]);
    }
}