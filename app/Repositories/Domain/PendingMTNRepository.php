<?php

namespace App\Repositories\Domain;

use Illuminate\Support\Facades\DB;
use App\Interfaces\Domain\IPendingMTNRepository;
use App\Models\PendingGsmMTN;
use App\Models\PendingSmsMTN;
use App\Models\PendingHistoryMTN;
use Carbon\Carbon;

class PendingMTNRepository implements IPendingMTNRepository
{

    public function isExist($gsm)
    {
        $pending_gsm = PendingGsmMTN::where('gsm', $gsm)->first();

        if (is_null($pending_gsm)) {
            return false;
        } else {
            return true;
        }
    }


    public function add_pending_gsm($request, $command, $operatorResponse)
    {

        $pending = PendingGsmMTN::create([
            'gsm' => $request->gsm,
            'command' => $command,
            'response' => $operatorResponse,
            'attempt_date' => Carbon::now(),
            'renewal_by' => $request->renewal_by != null ? $request->renewal_by : '',
        ]);

        return $pending;
    }


    # read sms and insert into pending_sms_mtn
    public function add_pending_sms($request, $pending_id)
    {
        $pending_sms = new PendingSmsMTN();
        $pending_sms->sms = $request->sms;
        /*  if ($request->operator === 2 && $request->langId == 2) {  #2 = language is Arabic , 1=language is English
            $pending_sms->sms = $this->read_sms($request->sms);
        } else {
            $pending_sms->sms = $request->sms;
        }*/
        $pending_sms->pending_id = $pending_id;
        $pending_sms->short_code = $request->sc;
        $pending_sms->op_timestamp = $request->op_timestamp;
        $pending_sms->lang_id = $request->langId;
        $pending_sms->is_processed = $request->is_processed;
        $pending_sms->mt = $request->mt;
        $pending_sms->op_response = $request->op_response;
        $pending_sms->command = $request->command;
        $pending_sms->save();
        return true;
    }

    #convert hexa to binary
    public function read_sms($str)
    {
        $str = $this->bin2utf8($str);
        $str = str_replace("'", "", $str);
        return $str;
    }
    public function bin2utf8($str)
    {
        $ucs2string = $this->local_hex2bin($str);
        $utf8string = mb_convert_encoding($ucs2string, 'UTF-8', 'UCS-2');
        return $utf8string;
    }
    public function local_hex2bin($h)
    {
        if (!is_string($h)) {
            return null;
        }
        $r = '';
        $length = strlen($h);

        for ($a = 0; $a < $length; $a += 2) {
            $r .= chr(hexdec($h[$a] . $h[($a + 1)]));
        }
        return $r;
    }

    public function add_pending_sms_from_inbox($inboxes, $pending_id)
    {

        foreach ($inboxes as $row) {

            $pending_sms = new PendingSmsMTN();
            $pending_sms->pending_id = $pending_id;
            $pending_sms->short_code = $row->short_code;
            $pending_sms->sms = $row->sms;
            $pending_sms->save();
        }
    }
    public function get_pending_gsm($gsm, $ticket_id)
    {

        $pending_gsm = PendingGsmMTN::where([['gsm', '=', $gsm], ['response', '=', $ticket_id]])->first();

        return $pending_gsm;
    }

    public function get_pending_gsm_byGsm($gsm)
    {

        $pending_gsm = PendingGsmMTN::where('gsm', $gsm)->first();

        return $pending_gsm;
    }

    public function get_pending_msgs($pending_id)
    {
        $pending_msgs = PendingGsmMTN::where('id', $pending_id)->first()->PendingSmsMTN()->get();
        return $pending_msgs;
    }

    public function update_status($gsm, $status)
    {

        $pending_gsm = PendingGsmMTN::where('gsm', $gsm)->first();

        $pending_gsm->status = $status;

        $pending_gsm->save();

        return true;
    }


    public function update_to_processed($sms_id)
    {
        $pending_sms = PendingSmsMTN::where('id', $sms_id)->first();

        $pending_sms->is_processed = 1;

        $pending_sms->save();

        return true;
    }



    public function delete_pending_relatives($pending_gsm_id)
    {
        $pending = PendingGsmMTN::where('id', $pending_gsm_id)->first();
        if ($pending != NULL) {
            PendingSmsMTN::where('pending_id', $pending->id)->delete();
            PendingGsmMTN::where('gsm', $pending->gsm)->delete();
        }
        return true;
    }


    public function add_to_history($pending_gsm)
    {
        if ($pending_gsm != null) {
            PendingHistoryMTN::create([
                'gsm' =>  $pending_gsm->gsm,
                'command' => $pending_gsm->command,
                'response' => $pending_gsm->response,
                'status' => $pending_gsm->status,
                'renewal_by' => $pending_gsm->renewal_by != null ? $pending_gsm->renewal_by : '',
                'attempt_date' => $pending_gsm->attempt_date,
                'mt' =>  $pending_gsm->mt,
                'op_response' => $pending_gsm->op_response
            ]);
        }
        return true;
    }


    public function add_other_to_history($pending_gsm, $mt, $op_response)
    {
        if ($pending_gsm != null) {
            PendingHistoryMTN::create([
                'gsm' =>  $pending_gsm->gsm,
                'command' => $pending_gsm->command,
                'response' => $pending_gsm->response,
                'status' => $pending_gsm->status,
                'renewal_by' => $pending_gsm->renewal_by != null ? $pending_gsm->renewal_by : '',
                'mt' => $mt,
                'cancel_balance_mt' => $pending_gsm->cancel_balance_mt != null ? $pending_gsm->cancel_balance_mt : 0,
                'op_response' => $op_response,
                'attempt_date' => $pending_gsm->attempt_date
            ]);
        }
        return true;
    }


    public function get_attempt_request()
    {
        $pendingData = PendingGsmMTN::where('response', '=', -1)->get();
        //$pendingData = PendingGsmMTN::whereRaw('SIGN(response) = -1')->get();
        return $pendingData;
    }


    public function get_renewal_requests()
    {
        $pendingData = PendingGsmMTN::where(
            [['command', '=', 'R'], ['renewal_by', '=', 'RAND'], ['is_processed', '=', '0']]
        )->get();
        return $pendingData;
    }

    public function get_pending_requests_to_renewal()
    {
        $pendingData = DB::table('pending_renewal_mtn')
        ->where([
            ['command', '=', 'R'],
            ['renewal_by', '=', 'RAND'],
            ['is_processed', '=', '0']
        ])
            ->get();

        return $pendingData;
    }



    public function update_is_renewaled($request_id, $ticketid)
    {
        $pending_gsm = PendingGsmMTN::where('id', $request_id)->first();

        $pending_gsm->is_processed = 1;
        $pending_gsm->response = $ticketid;
        $pending_gsm->attempt_date = Carbon::now();
        $pending_gsm->save();
        return true;
    }
}