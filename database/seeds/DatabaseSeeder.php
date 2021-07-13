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
        $this->call(UsersTabelSeeder::class);
        $this->call(AdminTabelSeeder::class);
        $this->call(PostsTabelSeeder::class);
    }
}
