<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\IPendingSyRepository;
use App\Models\PendingGsmSy;
use App\Models\PendingSmsSy;
use App\Models\PendingHistorySy;

use Carbon\Carbon;

class PendingSyRepository implements IPendingSyRepository

{

    public function get_pending_msgs($pending_id)
    {
        $pending_msgs = PendingSmsSy::where('pending_id', $pending_id)->get();
        return collect($pending_msgs);
    }

    public function add_to_history($pending_gsm)
    {
        if ($pending_gsm != null) {
            PendingHistorySy::create([
                'gsm' => $pending_gsm->gsm,
                'command' => $pending_gsm->command,
                'response' => $pending_gsm->response,
                'status' => $pending_gsm->status,
                'attempt_number' => $pending_gsm->attempt_number,
                'attemp_date' => Carbon::now()
            ]);
        }
        return true;
    }


    public function get_pending_gsm($gsm)
    {
        $pending_gsm = PendingGsmSy::where('gsm', $gsm)->first();
        return $pending_gsm;
    }



    public function delete_pending_relatives($pending_gsm_id)
    {
        $pending = PendingGsmSy::where('id', $pending_gsm_id)->first();

        if ($pending != NULL) {
            PendingSmsSy::where('pending_id', $pending->id)->delete();
            PendingGsmSy::where('gsm', $pending->gsm)->delete();
        }
        return true;
    }


    public function is_Request_Id_Exist($request_id)
    {
        $request = PendingSmsSy::where('request_id', $request_id)->first();
        if (is_null($request)) {
            return false;
        } else {
            return true;
        }
    }


    public function add_pending_sms($request, $pending_id)
    {
        $pending_sms = new PendingSmsSy();
        $pending_sms->pending_id = $pending_id;
        $pending_sms->short_code = $request->sc;
        $pending_sms->sms = $request->sms;
        $pending_sms->mt = $request->mt;
        $pending_sms->command = $request->command;
        $pending_sms->request_id = $request->reqId;
        $pending_sms->op_response = $request->op_response;
        $pending_sms->is_processed = $request->is_processed;
        $pending_sms->save();
        return true;
    }


    public function add_pending_sms_from_inbox($inboxes, $pending_id)
    {

        foreach ($inboxes as $row) {

            $pending_sms = new PendingSmsSy();
            $pending_sms->pending_id = $pending_id;
            $pending_sms->short_code = $row->short_code;
            $pending_sms->sms = $row->sms;
            $pending_sms->request_id = $row->request_id;

            $pending_sms->save();
        }
    }

    public function isExist($gsm): bool
    {
        $pending_gsm = PendingGsmSy::where('gsm', $gsm)->first();
        if (is_null($pending_gsm)) {
            return false;
        } else {
            return true;
        }
    }



    public function add_pending_gsm($request, $command, $operatorResponse)
    {
        $pending = PendingGsmSy::create([
            'gsm' => $request->gsm,
            'command' => $command,
            'response' => $operatorResponse,
            'attempt_number' => 1,
            'attempt_date' => Carbon::now()
        ]);
        return $pending;
    }


    public function update_status($gsm, $status) #Based On TakeActionSy  
    {
        $pending_gsm = PendingGsmSy::where('gsm', $gsm)->first();
        $pending_gsm->status = $status;
        $pending_gsm->save();
        return true;
    }




    public function update_to_processed($request_id)
    {
        $PendingSmsSy = PendingSmsSy::where('request_id', $request_id)->first();
        $PendingSmsSy->is_processed = 1;
        $PendingSmsSy->save();
        return true;
    }



    public function update_command($pending_id, $command, $response)
    {
        $pendingGsmSy = PendingGsmSy::where('id', $pending_id)->first();
        $pendingGsmSy->command = $command;
        $pendingGsmSy->response =   $response;
        $pendingGsmSy->attempt_number =  1;
        $pendingGsmSy->attempt_date =  Carbon::now();
        $pendingGsmSy->save();
        return true;
    }


    public function get_attempt_activation_request()
    {
        $pendingData = PendingGsmSy::where('command', 'A')
            ->where('response', '!=', 'Done')
            ->whereRaw('TIMESTAMPDIFF(MINUTE, attempt_date, NOW()) > 15')
            ->get();
        return $pendingData;
    }


    public function get_attempt_De_activation_request()
    {
        $pendingData = PendingGsmSy::where('command', 'D')
            ->where('response', '=', 'Done')
            ->where('status', '=', 0)
            ->whereRaw('TIMESTAMPDIFF(HOUR, attempt_date, NOW()) > 4')
            ->get();
        return $pendingData;
    }
}