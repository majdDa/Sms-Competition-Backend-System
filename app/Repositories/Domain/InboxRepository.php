<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\IInboxRepository;
use App\Interfaces\Domain\IPendingSyRepository;
use App\Interfaces\Domain\IPendingMTNRepository;
use App\Models\Inbox;
use App\Models\Mo;
use App\Models\Messages;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class InboxRepository implements IInboxRepository
{

    public function is_Request_Id_Exist($request_id)
    {
        $request = Inbox::where('request_id', $request_id)->first();
        if (is_null($request)) {
            return false;
        } else {
            return true;
        }
    }

    public function add_sms(Mo $mo, $subscriber_id, $op)
    {
        $inbox = new Inbox;
        $inbox->gsm = $mo->gsm;
        $inbox->subscriber_id = $subscriber_id;
        /*if ($op === 2 && $mo->langId == 2) { #2 = language is Arabic , 1=language is English
            $inbox->sms = $this->read_sms($mo->sms);
        } else {
            $inbox->sms = $mo->sms;
        }*/
        $inbox->sms = $mo->sms;
        $inbox->short_code =  $mo->sc;
        $inbox->operator = $op;
        $inbox->lang_id = $mo->langId;
        $inbox->op_timestamp = $mo->op_timestamp;
        $inbox->sms_date = Carbon::now();
        $inbox->request_id = $mo->reqId;
        $inbox->save();
        return $inbox;
    }

    public  function read_sms($str)
    {
        $str = $this->bin2utf8($str);
        $str = str_replace("'", "", $str);
        return $str;
    }
    public  function bin2utf8($str)
    {
        $ucs2string = $this->local_hex2bin($str);
        $utf8string = mb_convert_encoding($ucs2string, 'UTF-8', 'UCS-2');
        return $utf8string;
    }
    public  function local_hex2bin($h)
    {
        if (!is_string($h)) return null;
        $r = '';
        for ($a = 0; $a < strlen($h); $a += 2) {
            $r .= chr(hexdec($h[$a] . $h[($a + 1)]));
        }
        return $r;
    }
    #add_from_subscriber_sy function
    public function add_from_subscriber_sy($inbox, $subscriber_id, $operator)
    {
        Inbox::create([
            'subscriber_id' => $subscriber_id,
            'operator' => $operator,
            'request_id' => $inbox['request_id'],
            'sms' => $inbox['sms'],
            'status' => 0,
            'short_code' => $inbox['sc'],
            'sms_date' => Carbon::now()
        ]);
    }

    public function add_from_pending_sy($subscriber, $pending_messages, $response, $type)
    {

        foreach ($pending_messages as $row) {
            if ($row->is_processed == 1) {
                Inbox::create([
                    'subscriber_id' => $subscriber->id,
                    'gsm' => $subscriber->gsm,
                    'pending_id_sy' => $row->pending_id,
                    'request_id' => $row->request_id,
                    'sms' => $row->sms,
                    'status' => 1,
                    'points' => $subscriber->score,
                    'short_code' =>   $row->short_code,
                    'sms_mt' =>   $response->mt,
                    'operator_mt_response' =>   $response->op_response,
                    'sms_date' => $row->created_at,
                    'type' =>  $type,
                    'operator' =>   1,
                ]);
            } else {
                Inbox::create([
                    'subscriber_id' => $subscriber->id,
                    'gsm' => $subscriber->gsm,
                    'request_id' => $row->request_id,
                    'sms' => $row->sms,
                    'status' => 0,
                    'points' => $subscriber->score,
                    'short_code' =>   $row->short_code,
                    'sms_date' =>   $row->created_at,
                    'type' =>  $type,
                    'operator' =>   1,
                ]);
            }
        }

        return true;
    }


    public function add_from_pending_mtn($subscriber, $pending_messages, $response, $type)
    {
        foreach ($pending_messages as $row) {
            if ($row->is_processed == 1) {
                Inbox::create([
                    'subscriber_id' => $subscriber->id,
                    'gsm' => $subscriber->gsm,
                    'pending_id_mtn' => $row->pending_id,
                    'request_id' => $row->request_id,
                    'sms' => $row->sms,
                    'status' => 1,
                    'points' => 0,
                    'short_code' => $row->short_code,
                    'sms_mt' =>   $response->mt,   //notwork
                    'operator_mt_response' => $response->op_response, //notwork
                    'sms_date' =>   $row->created_at, //notwork
                    'type' =>  $type,
                    'lang_id' =>   $row->lang_id,
                    'operator' =>   2,

                ]);
            } else {
                Inbox::create([
                    'subscriber_id' => $subscriber->id,
                    'gsm' => $subscriber->gsm,
                    'request_id' => $row->request_id,
                    'sms' => $row->sms,
                    'status' => 0,
                    'points' => $subscriber->score,
                    'short_code' =>   $row->short_code,
                    'sms_date' =>   $row->created_at,
                    'type' =>  $type,
                    'lang_id' => $row->lang_id,
                    'operator' =>   2,
                ]);
            }
        }

        return true;
    }



    public function getUnProcessSms()
    {
        $inbox = Inbox::where('status', '0')->first();
        return $inbox;
    }

    public function getAllUnProcessSms()
    {
        $inbox = Inbox::where('status', '0')->get();
        return $inbox;
    }



    public function update_keyword_attribute($inbox_id, $keyword_id, $sms_mt, $points, $operator_mt_response)
    {
        $inbox = Inbox::where('id', $inbox_id)->first();
        $inbox->keyword_id  = $keyword_id;
        $inbox->sms_mt  = $sms_mt;
        $inbox->points  = $points;
        $inbox->status  = 1;
        $inbox->operator_mt_response  = $operator_mt_response;
        $inbox->type  = 'keyowrd';
        $inbox->save();

        return true;
    }


    public function update_question_attribute($inbox_id, $question_id, $sms_mt, $points, $operator_mt_response)
    {

        $inbox = Inbox::where('id', $inbox_id)->first();

        $inbox->question_id  = $question_id;
        $inbox->sms_mt  = $sms_mt;
        $inbox->points  = $points;
        $inbox->status  = 1;
        $inbox->operator_mt_response  = $operator_mt_response;
        $inbox->type  = 'question';
        $inbox->save();
        return true;
    }



    public function update_balance_type($inbox_id, $sms_mt, $command_id, $operator_mt_response)
    {
        $inbox = Inbox::where('id', $inbox_id)->first();
        $inbox->command_id  = $command_id;
        $inbox->sms_mt  = $sms_mt;
        $inbox->status  = 1;
        $inbox->operator_mt_response  =  $operator_mt_response;
        $inbox->type  = 'balance';
        $inbox->save();

        return true;
    }




    public function update_deactivation_type($inbox_id, $pending_id, $sms_mt, $command_id, $operator, $operator_mt_response)
    {

        $inbox = Inbox::where('id', $inbox_id)->first();

        $inbox->operator == 1 ? ($inbox->pending_id_sy = $pending_id) : ($inbox->pending_id_mtn = $pending_id);
        $inbox->command_id  = $command_id;
        $inbox->sms_mt  = $sms_mt;
        $inbox->status  = 1;
        $inbox->operator_mt_response  =  $operator_mt_response;
        $inbox->type  = 'deactivation';
        $inbox->save();


        return true;
    }


    public function update_help_type($inbox_id, $sms_mt, $command_id, $operator_mt_response)
    {
        $inbox = Inbox::where('id', $inbox_id)->first();

        $inbox->command_id  = $command_id;
        $inbox->sms_mt  = $sms_mt;
        $inbox->status  = 1;
        $inbox->operator_mt_response  =  $operator_mt_response;
        $inbox->type  = 'help';
        $inbox->save();

        return true;
    }


    public function get_unprocessed_messages_for_gsm($inbox_id, $subscriber_id)
    {
        $inboxes = Inbox::where([['subscriber_id', '=', $subscriber_id], ['id', '!=', $inbox_id], ['status', '=', 0]])->get();
        return $inboxes;
    }


    public function delete_inboxes($inboxes)
    {

        foreach ($inboxes as $inbox) {

            Inbox::where('id', $inbox->id)->delete();
        }

        return true;
    }




    public function  update_invalid_type($inbox_id, $invalid_mt, $points, $operator_mt_response)
    {
        $inbox = Inbox::where('id', $inbox_id)->first();
        $inbox->sms_mt  = $invalid_mt;
        $inbox->points  = $points;
        $inbox->status  = 1;
        $inbox->operator_mt_response  =  $operator_mt_response;
        $inbox->type  = 'invalid';
        $inbox->save();

        return true;
    }


    public function  update_invalid_last_Answer_type($inbox_id, $invalid_mt, $points, $operator_mt_response)
    {

        $inbox = Inbox::where('id', $inbox_id)->first();

        $inbox->sms_mt  = $invalid_mt;
        $inbox->points  = $points;
        $inbox->status  = 1;
        $inbox->operator_mt_response  =  $operator_mt_response;
        $inbox->type  = 'invalid_last_answer';
        $inbox->save();

        return true;
    }

    public function  get_filtered_messages($gsm, $text, $perPage)
    {
        $inbox = Inbox::where([['gsm', '=', $gsm], ['sms', '=', $text]])->paginate($perPage);
        return response()->json($inbox);
    }



    public function get_messages_by_gsm($request)
    {
        $gsm = $request->input('gsm');
        $perPage = $request->input('perPage');
        $start_date = Carbon::parse($request->input('start_date'));
        $end_date = Carbon::parse($request->input('end_date') . ' 23:59:59');
        $inbox = Inbox::where('gsm', '=', $gsm)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->paginate($perPage);
        if ($inbox->count() > 0) {
            return response()->json($inbox);
        } else {
            return response()->json("NotFound");
        }
    }


    //  -----------
    public function new_get_messages_by_gsm($request)
    {
        $gsm = $request->input('gsm');
        $smsFilter = $request->input('smsFilter');
        $pageSize = $request->input('pageSize');
        $page = ($request->input('page') - 1) * $pageSize;
        $startDate = ($request->input('startDate')) ? Carbon::parse($request->input('startDate')) :  date("Y-m-d");
        $endDate =  ($request->input('endDate')) ?  Carbon::parse($request->input('endDate') . ' 23:59:59') :  date("Y-m-d 23:59:59");

        $sqlQuery = "SELECT gsm, sms as mo, created_at as mo_date, sms_mt as mt, short_code FROM inbox WHERE gsm = $gsm 
        AND created_at >= " . "'" . $startDate . "'" . " AND  created_at <=" . "'" . $endDate . "'";

        $sqlQueryCount = "SELECT count(*) as count FROM inbox WHERE gsm = $gsm 
        AND created_at >= " . "'" . $startDate . "'" . " AND  created_at <=" . "'" . $endDate . "'";



        if ($smsFilter && $smsFilter != "") {
            $sqlQuery .= "AND sms =" . "'" . $smsFilter . "'";
            $sqlQueryCount .= "AND sms =" . "'" . $smsFilter . "'";
        }


        $sqlQuery .= " LIMIT " . $pageSize . " OFFSET " . $page;

        $inbox = DB::select($sqlQuery);
        $inboxCount = (DB::select($sqlQueryCount))[0]->count;

        return response()->json(['status' => 200, 'data' => $inbox, 'TotalCount' => $inboxCount]);
    }
}
