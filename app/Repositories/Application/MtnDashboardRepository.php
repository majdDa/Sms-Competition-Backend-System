<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\IMtnDashboardRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Interfaces\Domain\IInboxRepository;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;

class MtnDashboardRepository implements IMtnDashboardRepository
{
    protected $_subscriberRepository;
    protected $_IInboxRepository;
    public function __construct(ISubscribersRepository $subscribersRepository, IInboxRepository $IInboxRepository)
    {
        $this->_subscriberRepository = $subscribersRepository;
        $this->_IInboxRepository = $IInboxRepository;
    }

    public function get_all_mtn_users($filter)
    {
        return $this->_subscriberRepository->get_all_mtn_users($filter);
    }

    public function deactivate($gsm)
    {
        if (strlen($gsm->gsm) != 12 || substr($gsm->gsm, 0, 4) != '9639') {
            return response()->json("InvalidGsmParameter");
        }
        if ($this->_subscriberRepository->is_active($gsm->gsm)) {
            return [
                $this->_subscriberRepository->cancel_subscribtion_by_POS($gsm->gsm, 'MTN POS Team'),
                response()->json("Deactivated")
            ];
        } else {
            return response()->json("AlreadyCancelled");
        }
    }


    public function search($request)
    {
        $type = $request->type;
        $gsm = $request->gsm;
        $text = $request->text;
        $perPage = $request->input('perPage');
        if ($type == 'gsm') {
            if ($this->validateGsm($gsm)) {
                if ($this->_subscriberRepository->isExist($gsm)) {
                    return [$this->_subscriberRepository->get_subscriber_info($gsm)];
                } else {
                    return response()->json("NotExist");
                }
            } else {
                return response()->json("InvalidGsmParameter");
            }
        } elseif ($type == 'text') {
            $messages = $this->_IInboxRepository->get_filtered_messages($gsm, $text, $perPage);
            return $messages;
        }
    }




    private function validateGsm($gsm)
    {
        return strlen($gsm) === 12 && substr($gsm, 0, 4) === '9639';
    }


    public function get_gsm_messages($gsm)
    {
        return $this->_IInboxRepository->get_messages_by_gsm($gsm);
    }


    //  ===================== 

    public function new_get_all_mtn_users($request)
    {
        $status = $request->input('status');
        $pageSize = $request->input('pageSize');
        $page = ($request->input('page') - 1) * $pageSize;
        $gsm = $request->input('gsm');

        $sqlQuery = "SELECT id, gsm, sub_status, sub_date, cancel_date, last_response_date FROM subscribers WHERE operator = 2 ";
        $sqlQueryCount = "SELECT count(*) as count FROM subscribers WHERE operator = 2 ";

        if ($status === 0 || $status === 1) {
            $sqlQuery .= " AND sub_status = $status";
            $sqlQueryCount .= " AND sub_status = $status";
        }

        if ($gsm) {
            $sqlQuery .= " AND gsm = $gsm";
            $sqlQueryCount .= " AND gsm = $gsm";
        }


        $sqlQuery .= " LIMIT " . $pageSize . " OFFSET " . $page;
        $subscribers = DB::select($sqlQuery);

        $subscribersCount = (DB::select($sqlQueryCount))[0]->count;

        return response()->json(['status' => 200, 'data' => $subscribers, 'TotalCount' => $subscribersCount]);
    }


    public function new_deactivate($request)
    {
        $gsm = $request->input('gsm');

        if (strlen($gsm) != 12 || substr($gsm, 0, 4) != '9639') {
            return response()->json(['status' => 400, 'message' => 'InvalidGsmParameter', 'data' => [], 'TotalCount' => 0]);
        }
        if ($this->_subscriberRepository->is_active($gsm)) {
            $this->_subscriberRepository->cancel_subscribtion_by_POS($gsm, 'MTN POS Team');
            return  response()->json(['status' => 200, 'message' => 'Deactivate Success', 'data' => [], 'TotalCount' => 0]);
        } else {
            return response()->json(['status' => 202, 'message' => 'Already Deactivate', 'data' => [], 'TotalCount' => 0]);
        }
    }




    public function new_get_gsm_messages($gsm)
    {
        return $this->_IInboxRepository->new_get_messages_by_gsm($gsm);
    }
}
