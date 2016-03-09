<?php

use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('colors')->insert([
            [
                'name' => 'Straw',
                'start' => 0,
                'end' => 3,
            ],
            [
                'name' => 'Yellow',
                'start' => 3,
                'end' => 4,
            ],
            [
                'name' => 'Gold',
                'start' => 5,
                'end' => 6,
            ],
            [
                'name' => 'Amber',
                'start' => 6,
                'end' => 9,
            ],
            [
                'name' => 'Deep Amber/Light Copper',
                'start' => 10,
                'end' => 14,
            ],
            [
                'name' => 'Copper',
                'start' => 14,
                'end' => 17,
            ],
            [
                'name' => 'Deep Copper/Light Brown',
                'start' => 17,
                'end' => 18,
            ],
            [
                'name' => 'Brown',
                'start' => 19,
                'end' => 22,
            ],
            [
                'name' => 'Dark Brown',
                'start' => 22,
                'end' => 30,
            ],
            [
                'name' => 'Very Dark Brown',
                'start' => 30,
                'end' => 35,
            ],
            [
                'name' => 'Black',
                'start' => 40,
                'end' => 100,
            ]
        ]);
    }
}
