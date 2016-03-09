<?php

use Illuminate\Database\Seeder;

class BrewerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('breweries')->insert([
            [
                'name' => 'Schlafly',
                'location' => 'St. Louis, Missouri',
            ],
            [
                'name' => 'Blue Moon Brewing Company',
                'location' => 'Golden, Colorado',
            ],
            [
                'name' => 'Boulevard Brewing Co',
                'location' => 'Kansas City, MO',
            ],
            [
                'name' => 'O\'Fallon Brewery',
                'location' => 'O\'Fallon, Missouri',
            ],
            [
                'name' => 'Goose Island Beer Company',
                'location' => 'Chicago, Illinois',
            ],
            [
                'name' => 'Urban Chestnut Brewing Company',
                'location' => 'St. Louis, Missouri',
            ],
            [
                'name' => 'New Belgium Brewing',
                'location' => 'Fort Collins, Colorado',
            ],
            [
                'name' => 'Mother\'s Brewing Company',
                'location' => 'Springfield, Missouri',
            ],
        ]);
    }
}
