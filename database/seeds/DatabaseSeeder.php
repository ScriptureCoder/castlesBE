<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UsersTableSeeder::class);
         $this->call(RolesTableSeeder::class);
         $this->call(CountryTableSeeder::class);
         $this->call(StateTableSeeder::class);
         $this->call(CityTableSeeder::class);
         $this->call(FeaturesTableSeeder::class);
         $this->call(LabelTableSeeder::class);
         $this->call(PropertyStatusTableSeeder::class);
         $this->call(PropertyTypeTableSeeder::class);
    }
}
