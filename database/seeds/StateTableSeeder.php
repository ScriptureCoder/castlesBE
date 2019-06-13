<?php

use Illuminate\Database\Seeder;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table("states")->insert([["name"=>"Abia"], ["name"=>"Abuja Federal Capital Territor"], ["name"=>"Adamawa"], ["name"=>"Akwa Ibom"], ["name"=>"Anambra"], ["name"=>"Bauchi"], ["name"=>"Bayelsa"], ["name"=>"Benue"], ["name"=>"Borno"], ["name"=>"Cross River"], ["name"=>"Delta"], ["name"=>"Ebonyi"], ["name"=>"Edo"], ["name"=>"Ekiti"], ["name"=>"Enugu"], ["name"=>"Gombe"], ["name"=>"Imo"], ["name"=>"Jigawa"], ["name"=>"Kaduna"], ["name"=>"Kano"], ["name"=>"Katsina"], ["name"=>"Kebbi"], ["name"=>"Kogi"], ["name"=>"Kwara"], ["name"=>"Lagos"], ["name"=>"Nassarawa"], ["name"=>"Niger"], ["name"=>"Ogun"], ["name"=>"Ondo"], ["name"=>"Osun"], ["name"=>"Oyo"], ["name"=>"Plateau"], ["name"=>"Rivers"], ["name"=>"Sokoto"], ["name"=>"Taraba"], ["name"=>"Yobe"], ["name"=>"Zamfara"]]);
    }
}
