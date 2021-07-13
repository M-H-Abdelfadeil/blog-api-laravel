<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Carbon\Carbon;
use App\Models\User;
class UsersTabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Factory::create();
        for($i=0 ; $i<100 ; $i++){
            User::create([
                'name'=>$faker->name,
                'email'=>$faker->email,
                'password'=>bcrypt('11221122'),
                'email_verified_at'=>Carbon::now(),

            ]);
        }
    }
}
