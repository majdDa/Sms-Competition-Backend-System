<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommandSeeder extends Seeder
{

    private $commandCategories = ['keyword' => 0, 'question' => 1, 'balance' => 2, 'help' => 3, 'deactivation' => 4, 'question_option1' => 5, 'question_option2' => 6];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        #############################Help#################################
        DB::table('commands')->insert([
            'name' => 'مساعدة',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('commands')->insert([
            'name' => 'مساعده',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'معلومات',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'م',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'help',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'Help',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'HELP',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'H',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'h',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'info',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'Info',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'INFO',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);




        ########################balance######################################
        DB::table('commands')->insert([
            'name' => 'رصيد',
            'category' => 'balance',
            'category_id' => $this->commandCategories['balance'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'نقاط',
            'category' => 'balance',
            'category_id' => $this->commandCategories['balance'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'نقاطي',
            'category' => 'balance',
            'category_id' => $this->commandCategories['balance'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'score',
            'category' => 'balance',
            'category_id' => $this->commandCategories['balance'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('commands')->insert([
            'name' => 'Score',
            'category' => 'balance',
            'category_id' => $this->commandCategories['balance'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'SCORE',
            'category' => 'balance',
            'category_id' => $this->commandCategories['balance'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'Points',
            'category' => 'balance',
            'category_id' => $this->commandCategories['balance'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'points',
            'category' => 'balance',
            'category_id' => $this->commandCategories['balance'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'POINTS',
            'category' => 'balance',
            'category_id' => $this->commandCategories['balance'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        #############################DeActivation####################################

        DB::table('commands')->insert([
            'name' => 'وقف',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'Cancel',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'cancel',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'الغاء',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'إلغاء',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'ألغاء',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'stop',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'غ',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('commands')->insert([
            'name' => 'Stop',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'STOP',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'OFF',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'Off',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'off',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'قف',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'إيقاف',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'أيقاف',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'خروج',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);


        DB::table('commands')->insert([
            'name' => 'c',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'C',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('commands')->insert([
            'name' => '0',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'deact',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'deactivate',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'CANCEL',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        ###########################################################################
        DB::table('commands')->insert([
            'name' => '1',
            'category' => 'question_option1',
            'category_id' => $this->commandCategories['question_option1'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'صح',
            'category' => 'question_option1',
            'category_id' => $this->commandCategories['question_option1'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => '2',
            'category' => 'question_option2',
            'category_id' => $this->commandCategories['question_option2'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'خطأ',
            'category' => 'question_option2',
            'category_id' => $this->commandCategories['question_option2'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'UnKnown',
            'category' => 'help',
            'category_id' => $this->commandCategories['help'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        ###################
        DB::table('commands')->insert([
            'name' => '1صح',
            'category' => 'question_option1',
            'category_id' => $this->commandCategories['question_option1'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => '1.صح',
            'category' => 'question_option1',
            'category_id' => $this->commandCategories['question_option1'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => '1- صح',
            'category' => 'question_option1',
            'category_id' => $this->commandCategories['question_option1'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => '1-',
            'category' => 'question_option1',
            'category_id' => $this->commandCategories['question_option1'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => '2خطأ',
            'category' => 'question_option2',
            'category_id' => $this->commandCategories['question_option2'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => '2.خطأ',
            'category' => 'question_option2',
            'category_id' => $this->commandCategories['question_option2'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => '2- خطأ',
            'category' => 'question_option2',
            'category_id' => $this->commandCategories['question_option2'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => '2-',
            'category' => 'question_option2',
            'category_id' => $this->commandCategories['question_option2'],
            'created_at' =>  \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('commands')->insert([
            'name' => 'الغا',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('commands')->insert([
            'name' => 'ألغا',
            'category' => 'deactivation',
            'category_id' => $this->commandCategories['deactivation'],
            'created_at' =>  \Carbon\Carbon::now(), # new \Datetime()
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
