<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\ISyriatelRepository;
use App\Interfaces\Application\IMTNRepository;
use App\Interfaces\Application\ISendTeasersRepository;
use App\Interfaces\Domain\ITeasersRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Models\PendingHistoryMTN;

use Illuminate\Support\Facades\Log;
use App\Models\Inbox;
use App\Models\Subscriber;


class SendTeasersRepository implements ISendTeasersRepository
{
    private $_SyRepository;
    private $_MTNRepository;
    private $_TeasersRepository;
    private $_SubscribersRepository;
    public function __construct(
        ISyriatelRepository $SyriatelRepository,
        IMTNRepository $MTNRepository,
        ITeasersRepository $TeasersRepository,
        ISubscribersRepository $SubscribersRepository
    ) {

        $this->_SyRepository = $SyriatelRepository;
        $this->_MTNRepository = $MTNRepository;
        $this->_TeasersRepository = $TeasersRepository;
        $this->_SubscribersRepository = $SubscribersRepository;
    }

    public function sendTeasers()
    {
        $activeGSMs = $this->_SubscribersRepository->get_all_active_mtn_subscribers();
        $teasers = $this->_TeasersRepository->get_teaser_to_send();
        if ($teasers->isNotEmpty()) {
            foreach ($teasers as $teaser) {
                $opId = $teaser->op_id;
                if ($opId == 1 || $opId == 3) {
                    if ($teaser->status_syriatel == 'not send') {
                        $syriatelResponse = $this->_SyRepository->send_bulk($teaser->mtxt);
                        if ($syriatelResponse == 1) {
                            $teaser->update([
                                'status_syriatel' => 'send',
                            ]);
                        } else {
                            //Handle unsuccessful Syriatel API response
                        }
                        Log::channel('Syriatel_Teasers')->info(date("Y-m-d h:i:s"), ['Teaser Text:' . $teaser->mtxt, 'Operator Response :' . $syriatelResponse]);
                    }
                }
                /**************************************************************************************/
                if ($opId == 2 || $opId == 3) {
                    if ($teaser->status_mtn == 'not send') {
                        foreach ($activeGSMs as $gsm) {
                            $mtnResponse = $this->_MTNRepository->send_sms($gsm->gsm, $teaser->mtxt);
                            Log::channel('MTN_Teasers')->info(date("Y-m-d h:i:s"), ['Gsm:' . $gsm->gsm, 'Teaser Text:' . $teaser->mtxt, 'Operator Response :' . $mtnResponse]);
                        }
                        $teaser->update([
                            'status_mtn' => 'send',
                        ]);
                    }
                }
                /***************************************************************************************/
            }

            return true;
        }
    }


    public function sendReport()
    {
        $hour_minute = date('H:i');
        if ($hour_minute == '12:00' || $hour_minute == '14:00'   || $hour_minute == '16:00' || $hour_minute == '18:00' || $hour_minute == '20:00' || $hour_minute == '22:00' || $hour_minute == '23:58') {
            $mtnReport = $this->get_mtn_Report();
            $syriatelReport = $this->get_syriatel_Report();
            return [
                'mtnReport' => $mtnReport,
                'syriatelReport' => $syriatelReport
            ];
        }
        return 'Not Report Time !!';
    }


    private function get_syriatel_Report()
    {
        $sc = '1890';
        $gsm = '963993333633,963993333649,963993333601,963933183688,963993338530';
        $today_date = date('Y-m-d');
        $new_sub_syriatel = Subscriber::where('operator', 1)->where('sub_status', 1)->where('created_at', 'like', "%" . $today_date . "%")->count();
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
        $url = "https://bulk.syriatel.com.sy/mt/mt?orig={$sc}&dest=963993333633;963993333649;963993333601;963933183688;963993338530;963993333621&msg={$final_sms}&res=N&type=1";
        //$url = "https://bulk.syriatel.com.sy/mt/mt?orig={$sc}&dest=963993333621&msg={$final_sms}&res=N&type=1";

        $response = file_get_contents($url);
        Log::channel('report')->info(['Received Gsms :' . $gsm, 'Report Text :' . $sms, 'Syriatel Response : ' . $response]);
        return $response;
    }
    private function get_mtn_Report()
    {
        $sc = '1890';

        $gsm = '963993333633;963993333649;963993333601;963933183688;963993338530;963943920177';
        $technical_status_Array = [7, 2, 6, 5];
        $today_date = date('Y-m-d');
        $new_sub_mtn = Subscriber::where('operator', 2)->where('sub_status', 1)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $all_sub_mtn = Subscriber::where('operator', 2)->where('sub_status', 1)->count();
        $charged_sub_mtn = PendingHistoryMTN::where('command', 'R')->where('status', 1)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $out_of_grace = PendingHistoryMTN::where('command', 'R')->where('status', 3)->where('created_at', 'like', "%" . $today_date . "%")->where('cancel_balance_mt', 1)->count();;
        $in_grace = $all_sub_mtn - $charged_sub_mtn;
        $no_balance_sub_mtn = PendingHistoryMTN::where('command', 'R')->where('status', 3)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $technical_issues_sub_mtn = PendingHistoryMTN::where('command', 'R')->whereIn('status', $technical_status_Array)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $new_traffice_mtn = Inbox::where('operator', 2)->where('short_code', 1890)->where('created_at', 'like', "%" . $today_date . "%")->count();
        $all_traffice_mtn = Inbox::where('operator', 2)->where('short_code', 1890)->count();
        $sms = '90-Minutes MTN report :
';
        $sms .= 'Today Subscribers : ' . $new_sub_mtn . '
';
        $sms .= 'All Subscribers : ' . $all_sub_mtn . '
';
        $sms .= 'Charged  Customers : ' . $charged_sub_mtn . '
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
        $url = "https://bulk.syriatel.com.sy/mt/mt?orig={$sc}&dest=963993333633;963993333649;963993333601;963933183688;963993338530;963993333621&msg={$final_sms}&res=N&type=1";
        //$url = "https://bulk.syriatel.com.sy/mt/mt?orig={$sc}&dest=963993333621&msg={$final_sms}&res=N&type=1";

        $response = file_get_contents($url);
        Log::channel('report')->info(['Received Gsms :' . $gsm, 'Report Text :' . $sms, 'sy Response for mtn report : ' . $response]);


        //$mtnGsms = ['963958810060', '963943920177', '963955222101', '963944222038'];
        $mtnGsms = '963943920177;963958810060;963955222101;963944222038;963945825050';

        $curl = curl_init();
        $sc = '1890';
        $sms = mb_convert_encoding((bin2hex(mb_convert_encoding($sms, 'UCS-2', 'UTF-8'))), 'UCS-2', 'UTF-8');
        $sms = iconv('UCS-2', 'UTF-8', $sms);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://services.mtnsyr.com:7443/General/MTNSERVICES/ConcatenatedSender.aspx?User=Rand12&Pass=Rand12345&From=' . $sc . '&Gsm=' . $mtnGsms . '&Msg=' . $sms . '&Lang=0',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $mtnResponse = curl_exec($curl);
        curl_close($curl);
        Log::channel('report')->info(['Received Gsms :' . $mtnGsms, 'Report Text :' . $sms, 'Mtn Response : ' . $mtnResponse]);
        return $response;
    }
}
