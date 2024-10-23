<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([

            'question_text' => 'جائزة الحذاء الذهبي تمنح للاعب كرة القدم
1-صح
2-خطأ',
            'order' => '1',
            'answer' => '2',
            'points' => '400',
            'question_date' => '2023-05-19',
            'is_active' => '0',
            'created_by' => 'IT Team',
        ]);
        DB::table('questions')->insert([

            'question_text' => 'تطل مدينة جبلة على جبل قاسيون
1- صح
2- خطأ',
            'order' => '2',
            'answer' => '2',
            'points' => '500',
            'question_date' => '2023-05-19',
            'is_active' => '0',
            'created_by' => 'IT Team',
        ]);


        DB::table('questions')->insert([

            'question_text' => 'بيليه هو من  أشهر لاعبي كرة القدم البرازيليين
1-صح
2-خطأ',
            'order' => '1',
            'answer' => '2',
            'points' => '400',
            'question_date' => '2023-05-18',
            'is_active' => '0',
            'created_by' => 'IT Team',
        ]);
        DB::table('questions')->insert([

            'question_text' => 'تتألف لعبة كرة القدم من شوطين أساسيين
1-صح
2-خطأ',
            'order' => '2',
            'answer' => '1',
            'points' => '500',
            'question_date' => '2023-05-18',
            'is_active' => '0',
            'created_by' => 'IT Team',
        ]);


        DB::table('questions')->insert([

            'question_text' => 'ماريا شرابوفا لاعبة كرة قدم
1-صح
2-خطأ',
            'order' => '1',
            'answer' => '2',
            'points' => '400',
            'question_date' => '2023-05-17',
            'is_active' => '0',
            'created_by' => 'IT Team',
        ]);
        DB::table('questions')->insert([

            'question_text' => 'في كرة القدم يطرد اللاعب بالكرت الأحمر
1- صح
2- خطأ',
            'order' => '2',
            'answer' => '1',
            'points' => '500',
            'question_date' => '2023-05-17',
            'is_active' => '0',
            'created_by' => 'ITt Team',
        ]);



        DB::table('questions')->insert([

            'question_text' => 'باولو روسي ‏ لاعب كرة قدم إيطالي سابق، وهداف مونديال 1982
 1-صح
 2-خطأ',
            'order' => '1',
            'answer' => '1',
            'points' => '400',
            'question_date' => '2023-05-16',
            'is_active' => '1',
            'created_by' => 'IT Team',
        ]);
        DB::table('questions')->insert([

            'question_text' => 'شعار فريق روما لكرة القدم هو النمر
1- صح
2- خطأ',
            'order' => '2',
            'answer' => '1',
            'points' => '500',
            'question_date' => '2023-05-16',
            'is_active' => '1',
            'created_by' => 'IT Team',
        ]);
    }
}
