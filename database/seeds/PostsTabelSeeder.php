<?php
use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;
class PostsTabelSeeder extends Seeder
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
            $title= $title= $faker->words($nb = 3, $asText = true) ;
            Post::create([
                'title'=>$title,
                'slug'=>Str::slug($title),
                'content'=>$faker->paragraph(),
                'user_id'=>rand(1,100),
            ]);
        }
    }
}
