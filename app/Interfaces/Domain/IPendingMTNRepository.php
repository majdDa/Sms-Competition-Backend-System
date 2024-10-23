<?php

namespace App\Interfaces\Domain;

use App\Models\PendingSmsMTN;

interface IPendingMTNRepository
{
    public function isExist($gsm);
    public function add_pending_gsm($request, $command, $operatorResponse);
    public function add_pending_sms($request, $pending_id);
    public function delete_pending_relatives($gsm);
    public function get_pending_gsm($gsm, $ticket_id);
    public function get_pending_gsm_byGsm($gsm);
    public function update_status($gsm, $status);
    public function get_pending_msgs($pending_id);
    public function update_to_processed($sms_id);
    public function add_to_history($pending);
    public function  add_other_to_history($pending_gsm, $mt, $op_response);
    public function add_pending_sms_from_inbox($inboxes, $pending_id);
    public function get_attempt_request();
    public function get_renewal_requests();
    public function update_is_renewaled($request_id, $ticketid);
    public function read_sms($sms);
    public function get_pending_requests_to_renewal();
}
