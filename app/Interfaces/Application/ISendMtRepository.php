<?php

namespace App\Interfaces\Application;


interface ISendMtRepository
{
    public function send_help_mt($gsm_id, $operator);
    public function send_balance_mt($gsm, $points, $operator);
    public function send_keyword_mt($gsm, $keyword_point, $score, $keyword, $operator);
    public function send_deActivation_mt($gsm, $operator); #MTN
    public function send_Activation_mt($gsm, $score, $operator);
    public function send_Activation_mt_mtn($gsm, $score, $operator);

    public function send_next_question($gsm, $question_id, $points,  $score, $operator);
    public function send_final_keyword($gsm, $operator);
    public function send_false_answer($gsm, $question_id, $points, $score, $operator);

    public function send_invalid_answer($gsm, $added_points, $operator);


    public function send_pending_activation_mt($gsm, $operator);
    public function send_pending_de_activation_mt($gsm, $operator);

    public function send_invalid_last_answer_mt($gsm, $operator);
    public function send_renewal_message($gsm, $score, $operator);
    public function send_renewal_message_mtn($gsm, $score, $operator);
    public function send_unsubscribe_mt($gsm, $operator);
}
