<?php

namespace App\Http\Controllers;

use App\Models\Teaser;
use App\Interfaces\Domain\ITeasersRepository;
use App\Interfaces\Application\ISendTeasersRepository;
use App\Models\Inbox;
use App\Models\PendingHistoryMTN;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class TeaserController extends Controller
{
    protected $_TeasersRepository;
    protected $_SendTeasersRepository;

    public function __construct(ITeasersRepository $TeasersRepository, ISendTeasersRepository $SendTeasersRepository)
    {
        $this->_TeasersRepository = $TeasersRepository;
        $this->_SendTeasersRepository = $SendTeasersRepository;
    }
    public function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mtxt' => 'required',
            'op_id' => 'required|max:3|min:1',
            'sending_date' => 'required|after:' . date('Y-m-d H:i:s'),
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        return $this->_TeasersRepository->add($request);
    }

    public function get_all(Request $request)
    {
        return $this->_TeasersRepository->get_all($request);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mtxt' => 'required',
            'op_id' => 'required|max:3|min:1',
            'sending_date' => 'required|after:' . date('Y-m-d H:i:s'),
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        return $this->_TeasersRepository->update($request);
    }


    public function delete(Request $request)
    {
        return $this->_TeasersRepository->delete($request);
    }

    public function search(Request $request)
    {
        return $this->_TeasersRepository->search($request);
    }



    public function send()
    {
        return $this->_SendTeasersRepository->sendTeasers();
    }



    public function sendReport()
    {
        return $this->_SendTeasersRepository->sendReport();
    }












    public function testSendReport()
    {
        return $this->get_mtn_Report();
        $hour_minute = date('H:i');
        if ($hour_minute == '12:00') {
            $mtnReport = $this->get_mtn_Report();
            $syriatelReport = $this->get_syriatel_Report();
            return [
                'mtnReport' => $mtnReport,
                'syriatelReport' => $syriatelReport
            ];
        }
        return 'NotReportTime';
    }

    private function get_syriatel_Report()
    {
        $sc = '1890';
        $gsm = '963993333649';
        $today_date = date('Y-m-d');
        $new_sub_syriatel = Subscriber::where('operator', 1)->where('sub_status', 1)->where('sub_date', $today_date)->count();
        $all_sub_syriatel = Subscriber::where('operator', 1)->where('sub_status', 1)->count();
        $new_traffice_syriatel = Inbox::where('operator', 1)->where('short_code', 1890)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $all_traffice_syriatel = Inbox::where('operator', 1)->where('short_code', 1890)->count();
        $sms = '90-Minutes Syriatel report :
';
        $sms .= 'Today Subscribers : ' . $new_sub_syriatel . '
';
        $sms .= 'All Subscribers : ' . $all_sub_syriatel . '
';
        $sms .= 'Today Traffic : ' . $new_traffice_syriatel . '
';
        $sms .= 'All Traffic : ' . $all_traffice_syriatel . '
';
        $m1 = mb_convert_encoding((bin2hex(mb_convert_encoding($sms, 'UCS-2', 'UTF-8'))), 'UCS-2', 'UTF-8');
        $final_sms = iconv('UCS-2', 'UTF-8', $m1);
        $url = "https://bulk.syriatel.com.sy/mt/mt?orig={$sc}&dest=963993333649&msg={$final_sms}&res=N&type=1";
        $response = file_get_contents($url);
        Log::channel('report')->info(['Received Gsms :' . $gsm, 'Report Text :' . $sms, 'Mtn Response : ' . $response]);
        return $response;
    }
    private function get_mtn_Report()
    {
        $sc = '1890';
        $gsm = '963993333649';
        $statusArray = [7, 2, 6, 5];
        $today_date = date('Y-m-d');
        $new_sub_mtn = Subscriber::where('operator', 2)->where('sub_status', 1)->where('sub_date', $today_date)->count();
        $all_sub_mtn = Subscriber::where('operator', 2)->where('sub_status', 1)->count();
        $charged_sub_mtn = PendingHistoryMTN::where('command', 'R')->where('status', 1)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $out_of_grace = PendingHistoryMTN::where('command', 'R')->where('status', 3)->where('created_at', 'like', "%" . $today_date . "%")->where('cancel_balance_mt', 1)->count();;
        $in_grace = $all_sub_mtn - $charged_sub_mtn;
        $no_balance_sub_mtn = PendingHistoryMTN::where('command', 'R')->where('status', 3)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $technical_issues_sub_mtn = PendingHistoryMTN::where('command', 'R')->whereIn('status', $statusArray)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $new_traffice_mtn = Inbox::where('operator', 2)->where('short_code', 1890)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $all_traffice_mtn = Inbox::where('operator', 2)->where('short_code', 1890)->count();
        $sms = '90-Minutes MTN report :
';
        $sms .= 'Today Subscribers : ' . $new_sub_mtn . '
';
        $sms .= 'All Subscribers : ' . $all_sub_mtn . '
';
        $sms .= 'Caharged Customers : ' . $charged_sub_mtn . '
';
        $sms .= 'Canceled Customers(Out of grace-period)  : ' . $out_of_grace . '
';
        $sms .= 'Pending Customers (in grace-period) : ' . $in_grace . '
';
        $sms .= 'No Balance Customers : ' . $no_balance_sub_mtn . '
';
        $sms .= 'Technical Issues : ' . $technical_issues_sub_mtn . '
';
        $sms .= 'Today Traffic : ' . $new_traffice_mtn . '
';
        $sms .= 'All Traffic : ' . $all_traffice_mtn . '
';

        $m1 = mb_convert_encoding((bin2hex(mb_convert_encoding($sms, 'UCS-2', 'UTF-8'))), 'UCS-2', 'UTF-8');
        $final_sms = iconv('UCS-2', 'UTF-8', $m1);
        $url = "https://bulk.syriatel.com.sy/mt/mt?orig={$sc}&dest=963993333649&msg={$final_sms}&res=N&type=1";
        $response = file_get_contents($url);
        Log::channel('report')->info(['Received Gsms :' . $gsm, 'Report Text :' . $sms, 'Mtn Response : ' . $response]);
        return $response;
    }
}
