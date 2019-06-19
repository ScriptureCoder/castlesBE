<?php

use Illuminate\Database\Seeder;

class PropertyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table("property_types")->insert([
            ["name"=> "Office Space"],
            ["name"=> "Apartment"],
            ["name"=> "Block of Flats"],
            ["name"=> "Boys Quarters"],
            ["name"=> "Bungalow"],
            ["name"=> "Commercial Property"],
            ["name"=> "Duplex"],
            ["name"=> "Estate"],
            ["name"=> "Event Hall"],
            ["name"=> "Factory/Industrial Site/Warehouse"],
            ["name"=> "Farm/Agriculture"],
            ["name"=> "Flat"],
            ["name"=> "Hotel"],
            ["name"=> "House"],
            ["name"=> "Land"],
            ["name"=> "Open Space"],
            ["name"=> "Other Business"],
            ["name"=> "Petrol Station"],
            ["name"=> "School"],
            ["name"=> "Shop (Retail Unit)"],
            ["name"=> "Tank Farm"],
            ["name"=> "Villa"],
            ["name"=> "Water Production"]
        ]);
    }
}
