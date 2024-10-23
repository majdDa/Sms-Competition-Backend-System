<?php

namespace App\Repositories\Application;

use App\Models\ReturnType;
use App\Interfaces\Application\ISendMtRepository;
use App\Interfaces\Domain\IMessagesRepository;
use App\Interfaces\Domain\IQuestionsRepository;
use App\Interfaces\Application\ISendSms;
use Illuminate\Support\Str;

class SendMtRepository implements ISendMtRepository
{
    private $_MessageRepository;
    private $_SendSms;
    private $_QuestionsRepository;



    public function __construct(IMessagesRepository $messagesRepository, IQuestionsRepository $questionsRepository, ISendSms $SendSms)
    {
        $this->_MessageRepository = $messagesRepository;
        $op_response = $this->_SendSms = $SendSms;
        $this->_QuestionsRepository = $questionsRepository;
    }


    public function send_help_mt($gsm, $operator)
    {
        $help_mt = $this->_MessageRepository->get_mt('help');
        $op_response = $this->_SendSms->sendSMS($operator, $help_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $help_mt);
        return $ReturnType;
    }


    public function send_balance_mt($gsm, $points, $operator)
    {
        $balance_mt = $this->_MessageRepository->get_mt('balance');
        $balance_mt = Str::replace("?", $points, $balance_mt);
        $op_response = $this->_SendSms->sendSMS($operator, $balance_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $balance_mt);
        return $ReturnType;
    }



    public function send_keyword_mt($gsm, $keyword_point, $score, $keyword, $operator)
    {
        $keyword_mt = $this->_MessageRepository->get_mt('keyword');
        $keyword_mt = Str::replaceArray('?', [$keyword_point, $score], $keyword_mt);
        $op_response = $this->_SendSms->sendSMS($operator, $keyword_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $keyword_mt);
        return $ReturnType;
    }



    public function send_deActivation_mt($gsm, $operator)
    {

        $de_activate_mt = $this->_MessageRepository->get_mt('cancelation');
        $op_response = $this->_SendSms->sendSMS($operator, $de_activate_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $de_activate_mt);
        return $ReturnType;
    }



    public function send_Activation_mt($gsm, $score, $operator)
    {
        $get_current_question = $this->_QuestionsRepository->get_current_question(1);
        $activate_mt = $this->_MessageRepository->get_mt('welcoming');
        $activate_mt = Str::replace('?', $score, $activate_mt);
        $activate_mt .=  "\n" . $get_current_question->question_text;
        $op_response = $this->_SendSms->sendSMS($operator, $activate_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $activate_mt);
        return $ReturnType;
    }



    public function  send_Activation_mt_mtn($gsm, $score, $operator)
    {
        $get_current_question = $this->_QuestionsRepository->get_current_question(1);
        /*      $activate_mt = 'مرحباً بك في مسابقة " 90 دقيقة " لقد حصلت على ? نقطة، بالإضافة إلى دخولك سحوبات الجوائز الأسبوعية: 
1,000,000 ل.س لرابح واحد بشكل عشوائي
والجائزة الكبرى الشهرية 3,000,000 ل.س لرابح واحد بشكل عشوائي
شارك أكثر وزد فرصك في ربح الملايين.. الاشتراك اليومي بـ125 ل.س والرسالة لـ1890 بـ250ل.س'; */
        $activate_mt = $this->_MessageRepository->get_mt('welcoming');
        $activate_mt = Str::replace('?', $score, $activate_mt);
        $activate_mt .=  "\n" . $get_current_question->question_text;
        $op_response = $this->_SendSms->sendSMS($operator, $activate_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $activate_mt);
        return $ReturnType;
    }


    public function send_next_question($gsm, $question_id, $added_points, $score, $operator)
    {
        $get_next_question = $this->_QuestionsRepository->get_next_question($question_id);
        $true_mt = $this->_MessageRepository->get_mt('true_Answer');
        $true_mt = Str::replaceArray('?', [$added_points, $get_next_question->points], $true_mt);
        $true_mt  .= "\n" . $get_next_question->question_text;
        $op_response = $this->_SendSms->sendSMS($operator, $true_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $true_mt);
        return $ReturnType;
    }



    //send_false_answer
    public function send_false_answer($gsm, $question_id, $added_points,  $score, $operator)
    {
        $get_current_question = $this->_QuestionsRepository->get_current_question($question_id);
        //  var_dump($get_current_question);
        $false_mt = $this->_MessageRepository->get_mt('false_Answer');
        $false_mt = Str::replaceArray('?', [$added_points, $get_current_question->points], $false_mt);
        $false_mt .=  "\n" . $get_current_question->question_text;
        $op_response = $this->_SendSms->sendSMS($operator, $false_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $false_mt);
        return $ReturnType;
    }


    //send_final_keyword
    public function send_final_keyword($gsm, $operator)
    {
        $final_mt = $this->_MessageRepository->get_mt('final_keyword');
        $op_response = $this->_SendSms->sendSMS($operator, $final_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $final_mt);
        return $ReturnType;
    }



    //send_invalid_answer
    public function send_invalid_answer($gsm, $added_points, $operator)
    {
        $invalid_mt = $this->_MessageRepository->get_mt('invalid');
        $invalid_mt = Str::replace('?', $added_points, $invalid_mt);
        $op_response = $this->_SendSms->sendSMS($operator, $invalid_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $invalid_mt);
        return $ReturnType;
    }



    //send_pending_activation_mt
    public function send_pending_activation_mt($gsm, $operator)
    {
        $pending_act_mt = $this->_MessageRepository->get_mt('pending_activation');
        $op_response = $this->_SendSms->sendSMS($operator, $pending_act_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $pending_act_mt);
        return $ReturnType;
    }

    //send_pending_de_activation_mt
    public function send_pending_de_activation_mt($gsm, $operator)
    {
        $pending_de_act_mt = $this->_MessageRepository->get_mt('pending_deActivation');
        $op_response = $this->_SendSms->sendSMS($operator, $pending_de_act_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $pending_de_act_mt);
        return $ReturnType;
    }

    public function send_invalid_last_answer_mt($gsm, $operator)
    {
        $invalid_last_answer = $this->_MessageRepository->get_mt('invalid_last_answer');
        $op_response = $this->_SendSms->sendSMS($operator, $invalid_last_answer, $gsm);
        $ReturnType = new ReturnType($op_response, $invalid_last_answer);
        return $ReturnType;
    }


    public function send_renewal_message($gsm, $score, $operator)
    {
        $renewal_mt = $this->_MessageRepository->get_mt('renewal');
        $renewal_mt = Str::replace('?', $score, $renewal_mt);
        $op_response = $this->_SendSms->sendSMS($operator, $renewal_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $renewal_mt);
        return $ReturnType;
    }


    public function  send_renewal_message_mtn($gsm, $score, $operator)
    {
        /*         $renewal_mt = 'مرحباً بك مجدداً في مسابقة " 90 دقيقة " رصيد نقاطك هو ? نقطة، استمر بالمشاركة وجمع النقاط لتزيد فرصك بربح الجوائز الأسبوعية: 
1,000,000 ل.س لرابح واحد بشكل عشوائي
والجائزة الكبرى الشهرية 3,000,000 ل.س لرابح واحد بشكل عشوائي
شارك أكثر وزد فرصك في ربح الملايين.. الاشتراك اليومي بـ125 ل.س والرسالة لـ1890 بـ250ل.س'; */

        $renewal_mt = $this->_MessageRepository->get_mt('renewal');
        $renewal_mt = Str::replace('?', $score, $renewal_mt);
        $op_response = $this->_SendSms->sendSMS($operator, $renewal_mt, $gsm);
        $ReturnType = new ReturnType($op_response, $renewal_mt);
        return $ReturnType;
    }

    //unsubscribe
    public function send_unsubscribe_mt($gsm, $operator)
    {
        $unsubscribe = $this->_MessageRepository->get_mt('unsubscribe');
        $op_response = $this->_SendSms->sendSMS($operator, $unsubscribe, $gsm);
        // $ReturnType = new ReturnType($op_response, $help_mt);
        return $op_response;
    }
}
