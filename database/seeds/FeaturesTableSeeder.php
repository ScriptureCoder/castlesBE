<?php

use Illuminate\Database\Seeder;

class FeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table("features")->insert([
            ["name"=>"AC"],
            ["name"=>"Bar"],
            ["name"=>"Bathroom"],
            ["name"=>"Bedroom"],
            ["name"=>"Borehole"],
            ["name"=>"BQ"],
            ["name"=>"Bullet proof doors & windows"],
            ["name"=>"C of O"],
            ["name"=>"CCTV"],
            ["name"=>"Cinema room"],
            ["name"=>"Conference room"],
            ["name"=>"Corner Piece"],
            ["name"=>"Deed Of Assignment"],
            ["name"=>"Elevator"],
            ["name"=>"Fitted Kitchen"],
            ["name"=>"Fridge/Freezer"],
            ["name"=>"Garden"],
            ["name"=>"Generator"],
            ["name"=>"Gym"],
            ["name"=>"Inverter"],
            ["name"=>"Land Certificate"],
            ["name"=>"MicroWave"],
            ["name"=>"Parking Space"],
            ["name"=>"Restaurant"],
            ["name"=>"Sit-out Lounge"],
            ["name"=>"squash Court"],
            ["name"=>"swimming pool"],
            ["name"=>"Toilet"],
            ["name"=>"Transformer"],
            ["name"=>"Wardrobe"],
            ["name"=>"Water Treatment Plant"],
            ["name"=>"Waterfront"]
        ]);
    }
}


