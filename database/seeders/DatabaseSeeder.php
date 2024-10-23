<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    //use WithoutModelEvents;
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\Messages::factory(100)->create();
        $this->call([
            MessagesSeeder::class,
            CommandSeeder::class,
            QuestionSeeder::class,
            KeywordsSeeder::class,
        ]);
    }
}
