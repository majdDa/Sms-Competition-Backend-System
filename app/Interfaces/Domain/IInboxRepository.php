<?php

namespace App\Interfaces\Domain;

use App\Models\Mo;

interface IInboxRepository
{
    public function  add_sms(Mo $mo, $subscriber_id, $op);
    public function add_from_subscriber_sy($request, $subscriber_id, $operator); #Syriatel Functions

    public function add_from_pending_mtn($subscriber, $pending_messages, $mt, $type); #MTN Functions
    public function add_from_pending_sy($subscriber_id, $pending_messages, $mt, $type); #Syriatel Functions


    public function getUnProcessSms();
    public function getAllUnProcessSms();
    public function is_Request_Id_Exist($request_id);

    public function get_unprocessed_messages_for_gsm($inbox_id, $subscriber_id);
    public function delete_inboxes($array);
    public function update_keyword_attribute($inbox_id, $keyword_id, $sms_mt, $points, $operator_mt_response);
    public function update_help_type($inbox_id, $sms_mt, $command_id, $operator_mt_response);
    public function update_balance_type($inbox_id, $sms_mt, $command_id, $operator_mt_response);
    public function update_deactivation_type($inbox_id, $pending_id, $sms_mt, $command_id, $operator, $operator_mt_response);
    public function update_question_attribute($inbox_id, $question_id, $sms_mt, $points, $operator_mt_response);
    public function update_invalid_last_Answer_type($inbox_id, $invalid_mt, $points, $operator_mt_response);


    public function update_invalid_type($inbox_id, $invalid_mt,  $points, $operator_mt_response);
    public function get_filtered_messages($gsm, $text, $perPage);
    public function  get_messages_by_gsm($request);


    // ---------- 
    public function  new_get_messages_by_gsm($request);
}
