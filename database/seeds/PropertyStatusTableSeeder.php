<?php

use Illuminate\Database\Seeder;

class PropertyStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table("property_statuses")->insert([
            ["name"=>"For Sale"],
            ["name"=>"For Rent"],
            ["name"=>"Joint Venture"],
            ["name"=>"Lease"],
            ["name"=>"Short Let"]
        ]);
    }
}
