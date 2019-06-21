<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table("roles")->insert([
            ["name"=>"Individual"],
            ["name"=>"Estate Agent"],
            ["name"=>"Admin"],
            ["name"=>"Super Admin"]
        ]);

        \Illuminate\Support\Facades\DB::table("property_advices")->insert([
            ["name"=>"Agency Practice", "slug"=> "agency-practice"],
            ["name"=>"Construction", "slug"=> "construction"],
            ["name"=>"Decor &  Architecture", "slug"=> "architecture"],
            ["name"=>"Highbrow Living", "slug"=> "highbrow-living"],
            ["name"=>"Property Rental Guide", "slug"=> "property-rental-guide"],
            ["name"=>"Property Buyers Guide", "slug"=> "property-buyers-guide"],
        ]);
    }
}
