<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeywordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Key_words')->insert([
            'name' => 'كرة',
            'is_active' => '1',
            'type' => 'fixed',
            'points' => '600',
            'is_fix' => '1',
        ]);
        DB::table('Key_words')->insert([
            'name' => 'مباراة',
            'is_active' => '1',
            'type' => 'fixed',
            'points' => '600',
            'is_fix' => '1',
        ]);
        DB::table('Key_words')->insert([
            'name' => 'ملعب',
            'is_active' => '1',
            'type' => 'fixed',
            'points' => '600',
            'is_fix' => '1',
        ]);
        DB::table('Key_words')->insert([
            'name' => 'فريق',
            'is_active' => '1',
            'type' => 'fixed',
            'points' => '600',
            'is_fix' => '1',
        ]);

        DB::table('Key_words')->insert([
            'name' => 'هدف',
            'is_active' => '1',
            'type' => 'fixed',
            'points' => '600',
            'is_fix' => '1',
        ]);
        DB::table('Key_words')->insert([
            'name' => 'رياضة',
            'is_active' => '1',
            'type' => 'fixed',
            'points' => '600',
            'is_fix' => '1',
        ]);
        DB::table('Key_words')->insert([
            'name' => 'لاعب',
            'is_active' => '1',
            'type' => 'fixed',
            'points' => '600',
            'is_fix' => '1',
        ]);

        DB::table('Key_words')->insert([
            'name' => 'مرمى',
            'is_active' => '1',
            'type' => 'fixed',
            'points' => '600',
            'is_fix' => '1',
        ]);

        DB::table('Key_words')->insert([
            'name' => '90',
            'is_active' => '1',
            'type' => 'fixed',
            'points' => '900',
            'is_fix' => '1',
        ]);
    }
}