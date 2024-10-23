<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('messages')->insert([
            'category' => 'true_Answer',
            'message' => 'مبرووك!!
إجابة صحيحة
تم إضافة ? نقطة لرصيدك 

أجب عن السؤال التالي كي تحصل على ? نقطة',
            'order' => '1',
            'category_id' => '1',
            'category_name' => 'true Answer',
        ]);


        DB::table('messages')->insert([
            'category' => 'false_Answer',
            'message' => 'الإجابة خاطئة
لكنك حصلت على ? نقطة
قد يكون حظك أوفر بالمحاولة القادمة

أجب عن السؤال التالي كي تحصل على ? نقطة',
            'order' => '1',
            'category_id' => '2',
            'category_name' => 'false Answer',
        ]);


        DB::table('messages')->insert([
            'category' => 'keyword',
            'message' => 'مبرووك!!
تم إضافة ? نقطة لرصيدك 
رصيد الكلي هو ? نقطة في جميع السحوبات
أرسل 90 و احصل على 900 فرصة ربح جديدة .',
            'order' => '1',
            'category_id' => '3',
            'category_name' => 'keyword',
        ]);




        DB::table('messages')->insert([
            'category' => 'help',
            'message' => 'من مسابقة 90 دقيقة:
بإمكانك اللعب وحل الأسئلة لزيادة فرصك بربح الجوائز الأسبوعية:
1,000,000ل.س  لرابحين.
1,000,000 ل.س لصاحب أعلى عدد نقاط.
لمعرفة رصيد نقاطك أرسل "نقاط" 
لزيادة فرص ربحك بإمكانك ارسال "90" لـ1890
و لإلغاء المسابقة أرسل "غ" لـ 1490 مجاناً.
الاشتراك اليومي بـ125ل.س / الرسالة لـ1890 بـ 250ل.س',
            'order' => '1',
            'category_id' => '4',
            'category_name' => 'help',
        ]);


        DB::table('messages')->insert([
            'category' => 'balance',
            'message' => 'إن اجمالي نقاطك حتى الآن هو ? نقطة...
استمر في اللعب واقترب من الفوز بالجوائز الأسبوعية.',
            'order' => '1',
            'category_id' => '5',
            'category_name' => 'balance',
        ]);



        DB::table('messages')->insert([
            'category' => 'Welcoming',
            'message' => 'من مسابقة 90 دقيقة: بإمكانك اللعب وحل الأسئلة لزيادة فرصك بربح الجوائز الأسبوعية: 
1,000,000 ل.س لرابح واحد بشكل عشوائي
 والجائزة الكبرى الشهرية 3,000,000 ل.س لرابح واحد بشكل عشوائي
 لمعرفة رصيد نقاطك أرسل "نقاط" لزيادة فرص ربحك بإمكانك ارسال "90" لـ1890 و لإلغاء المسابقة أرسل "غ" لـ 1490 مجاناً. تتجدد يوميا بكلفة بـ125ل.س / الرسالة لـ1890 بـ 250ل.س',
            'order' => '1',
            'category_id' => '6',
            'category_name' => 'welcoming Message',
        ]);




        DB::table('messages')->insert([
            'category' => 'unsubscribe',
            'message' => '  امر خاطئ  .. انت غير مشترك بمسابقة 
90 دقيقة 
للاشتراك ارسل " تفعيل" مجانا للرقم 1490',
            'order' => '1',
            'category_id' => '7',
            'category_name' => 'unsubscribe',
        ]);



        DB::table('messages')->insert([
            'category' => 'pending_activation',
            'message' => 'تم استلام طلبك للمشاركة في مسابقة90 دقيقة، ستصلك رسالة تأكيد عند اتمام العملية بنجاح.. شكراً',
            'order' => '1',
            'category_id' => '8',
            'category_name' => 'pending_activation',
        ]);



        DB::table('messages')->insert([
            'category' => 'pending_deActivation',
            'message' => ' تم استلام طلبك لإلغاء مسابقة 90 دقيقة، ستصلك رسالة تأكيد عند اتمام العملية بنجاح.. شكراً',
            'order' => '1',
            'category_id' => '9',
            'category_name' => 'pending_deActivation',
        ]);

        DB::table('messages')->insert([
            'category' => 'renewal',
            'message' => 'مرحباً بك مجدداً في مسابقة " 90 دقيقة "
رصيد نقاطك هو ? نقطة، استمر بالمشاركة وجمع النقاط لتزيد فرصك بربح  الجوائز  الأسبوعية:
1,000,000ل.س  لرابحين.
1,000,000 ل.س لصاحب أعلى عدد رسائل.
شارك أكثر وزد فرصك في ربح الملايين..
الاشتراك اليومي بـ125 ل.س
والرسالة لـ1890 بـ250ل.س',
            'order' => '1',
            'category_id' => '10',
            'category_name' => 'renewal',
        ]);




        DB::table('messages')->insert([
            'category' => 'invalid',
            'message' => 'لقد أرسلت رسالة غير صحيحة!
لكنك حصلت على ? نقطة.
أنت مشترك مسبقاَ في مسابقة 90 دقيقة، للمساعدة أرسل "م"',
            'order' => '1',
            'category_id' => '11',
            'category_name' => 'invalid',
        ]);



        DB::table('messages')->insert([
            'category' => 'cancelation',
            'message' => ' تم الغاء الاشتراك',
            'order' => '1',
            'category_id' => '12',
            'category_name' => 'cancelation',
        ]);

        DB::table('messages')->insert([
            'category' => 'final_keyword',
            'message' => ' أحسنت!!
حصلت على"500" نقطة إضافية
تابع معنا لتتمكن من الربح بنقاط فورية',
            'order' => '1',
            'category_id' => '13',
            'category_name' => 'final_keyword',
        ]);


        DB::table('messages')->insert([
            'category' => 'invalid_last_answer',
            'message' => 'لا يوجد اسئلة متاحة
لكنك حصلت على "400" نقطة
لتقترب اكثر من الربح أرسل "90" لتحصل على "900"نقطة فورية',
            'order' => '1',
            'category_id' => '14',
            'category_name' => 'invalid_last_answer',
        ]);
    }
}
