<?php

namespace App\Interfaces\Domain;

interface ISubscribersRepository
{
    public function isExist($gsm);
    public function is_active($gsm);
    public function is_not_active($gsm);
    public function check_status($gsm);
    public function add_subscriber($gsm, $operator, $score, $sc, $user);
    public function cancel_subscribtion($gsm, $user);
    public function renewal_subscribtion($gsm, $user);
    public function update_points($subscriber_id, $score);
    public function update_last_answer($subscriber_id);
    public function get_subscriber_info($gsm);
    public function update_question_order($subscriber_id, $question_order);
    public function get_all_active_mtn_subscribers();
    public function get_all_mtn_users($request);
    public function cancel_subscribtion_by_POS($gsm, $user);
}