<?php

namespace App\Interfaces\Domain;

interface IPendingSyRepository
{
    public function is_Request_Id_Exist($request_id);
    public function add_pending_sms($request, $pending_id);
    public function add_pending_sms_from_inbox($inboxes, $pending_id);
    public function isExist($gsm): bool;
    public function update_status($gsm, $status);
    public function update_to_processed($request_id);
    public function get_pending_gsm($gsm);
    public function get_pending_msgs($pending_id);
    public function add_to_history($request);
    public function delete_pending_relatives($gsm);
    public function add_pending_gsm($request, $command, $operatorResponse);
    public function update_command($gsm, $command, $response);
    public function get_attempt_activation_request();
    public function get_attempt_De_activation_request();
}