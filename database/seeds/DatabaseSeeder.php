<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_acc')->insert([
            'game_id' => rand(1, 5),
            'shop_id' => 1,
            'username' => str_random(10),
            'password' => str_random(10),
            'price' => '{"3":"1000", "8":"5000", "20":"15000"}',
            'description' => str_random(100)
        ]);
    }
}
