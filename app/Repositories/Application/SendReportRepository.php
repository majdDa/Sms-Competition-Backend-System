<?php

namespace App\Repositories\Application;

use App\Models\Inbox;
use App\Models\Subscriber;
use App\Models\PendingHistoryMTN;
use Illuminate\Support\Facades\DB;
use App\Interfaces\Application\ISendReportRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class SendReportRepository implements ISendReportRepository
{

        /*     public static $subscription_price = 125;
    public static $mo_price = 250; */

        public function send_report()
        {
                $hour_minute = date('H:i');
                if ($hour_minute == '12:00' || $hour_minute == '14:00'  || $hour_minute == '16:00'  || $hour_minute == '18:00' || $hour_minute == '20:00' || $hour_minute == '22:00' || $hour_minute == '23:58') {
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


        public function get_chart_data($request)
        {

                $today_date = $request->date;
                $optins = DB::table('subscribers')
                        ->where('operator', 2)
                        ->where('sub_status', 1)
                        ->where('created_at', 'like', "%" . $today_date . "%")
                        ->count();

                $Re_optins = DB::table('subscribers')
                        ->where('operator', 2)
                        ->where('sub_status', 1)
                        ->where('cancel_date', '!=', NULL)
                        ->where('created_at', '!=', 'updated_at')
                        ->where('created_at', '<', Carbon::parse($today_date))
                        ->count();

                $Optout = DB::table('subscribers')
                        ->where('operator', 2)
                        ->where('sub_status', 0)
                        ->count();

                $daily_subscribers = DB::table('pending_history_mtn')
                        ->where('command', 'R')
                        ->where('status', 1)
                        ->where('created_at', 'like', "%" . $today_date . "%")
                        ->count();

                $daily_MO = DB::table('inbox')
                        ->where('operator', 2)
                        ->where('short_code', 1890)
                        ->where('created_at', 'like', "%" . $today_date . "%")
                        ->count();

                $subscription_revenue = $daily_subscribers * 125;
                $MO_revenue = $daily_MO *  250;
                $total_revenue = $MO_revenue + $subscription_revenue;

                $report_data = [
                        'Optins' => $optins,
                        'Re_optins' => $Re_optins,
                        'Optout' => $Optout,
                        'Daily Subscribers' => $daily_subscribers,
                        'Daily MO' => $daily_MO,
                        'Total Revenue' => $total_revenue,
                        'Subscription Revenue' => $subscription_revenue,
                        'MO Revenue' => $MO_revenue
                ];

                return response()->json($report_data);
        }



        public function get_chart_data_v2($request)
        {
                $earliestDate = DB::table('pending_history_mtn')
                        ->select(DB::raw('DATE(created_at) as date'))
                        ->where('created_at', 'like', $request->date . '%')
                        ->orderBy('date', 'asc')
                        ->limit(1)
                        ->value('date');

                $latestDate = DB::table('pending_history_mtn')
                        ->select(DB::raw('DATE(created_at) as date'))
                        ->where('created_at', 'like', $request->date . '%')
                        ->orderBy('date', 'desc')
                        ->limit(1)
                        ->value('date');

                if (!$earliestDate || !$latestDate) {
                        return response()->json(['error' => 'No data available for the selected month']);
                }
                $currentDate = $earliestDate;
                $reportData = [];
                while ($currentDate <= $latestDate) {
                        $optins = DB::table('subscribers')
                                ->where('operator', 2)
                                ->where('sub_status', 1)
                                ->whereDate('created_at', $currentDate)
                                ->count();

                        $Re_optins = DB::table('subscribers')
                                ->where('operator', 2)
                                ->where('sub_status', 1)
                                ->where('cancel_date', '!=', NULL)
                                ->where('created_at', '<', $currentDate)
                                ->count();

                        $Optout = DB::table('subscribers')
                                ->where('operator', 2)
                                ->where('sub_status', 0)
                                ->whereDate('created_at', $currentDate)
                                ->count();

                        $daily_subscribers = DB::table('pending_history_mtn')
                                ->where('command', 'R')
                                ->where('status', 1)
                                ->whereDate('created_at', $currentDate)
                                ->count();

                        $Subscriptions =  DB::table('pending_history_mtn')
                                ->where('command', 'R')
                                ->whereDate('created_at', $currentDate)
                                ->count();

                        $daily_MO = DB::table('inbox')
                                ->where('operator', 2)
                                ->where('short_code', 1890)
                                ->whereDate('created_at', $currentDate)
                                ->count();

                        if ($request->date < '2023-11') {
                                $subscription_revenue = $daily_subscribers * 100;
                                $MO_revenue = $daily_MO * 200;
                        } else {
                                $subscription_revenue = $daily_subscribers * 125;
                                $MO_revenue = $daily_MO * 250;
                        }
                        $total_revenue = $MO_revenue + $subscription_revenue;

                        $oneDay = [
                                'date' => $currentDate,
                                'Optins' => $optins,
                                'Re_optins' => $Re_optins,
                                'Optout' => $Optout,
                                'Charged Unique users' => $daily_subscribers,
                                'Subscriptions' => $Subscriptions,
                                'Daily MO' => $daily_MO,
                                'Total Revenue' => $total_revenue,
                                'Subscription Revenue' => $subscription_revenue,
                                'MO Revenue' => $MO_revenue
                        ];

                        array_push($reportData, $oneDay);
                        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                }

                return response()->json($reportData, 200);
        }


        // ----
        public function new_get_chart_data_v2($request)
        {
                $cacheKey = 'chart_data_' . $request->date;

                // Attempt to retrieve data from cache
                $reportData = Cache::get($cacheKey);

                if (
                        $reportData === null
                ) {
                        // Data not found in cache, perform database queries

                        $dates = PendingHistoryMtn::selectRaw('DISTINCT DATE(created_at) as date')
                                ->where('created_at', 'like', $request->date . '%')
                                ->orderBy('date', 'asc')
                                ->pluck('date')
                                ->toArray();

                        if (empty($dates)) {
                                return response()->json(['status' => 404, 'message' => 'No data available for the selected month', 'data' => [], 'totalCount' => 0]);
                        }

                        $reportData = [];
                        foreach ($dates as $currentDate) {
                                $currentDateCarbon = Carbon::parse($currentDate);

                                // Use Eloquent relationships and eager loading
                                $subscribers = Subscriber::where('operator', 2)
                                        ->where('sub_status', 1)
                                        ->whereDate('created_at', $currentDate)
                                        ->get();

                                $optins = $subscribers->count();

                                $reOptins = $subscribers->whereNotNull('cancel_date')
                                        ->where('created_at', '<', $currentDateCarbon)
                                        ->count();

                                $optout = Subscriber::where('operator', 2)
                                        ->where('sub_status', 0)
                                        ->whereDate('created_at', $currentDate)
                                        ->count();

                                $dailySubscribers = PendingHistoryMtn::where('command', 'R')
                                        ->where('status', 1)
                                        ->whereDate('created_at', $currentDate)
                                        ->count();

                                $subscriptions = PendingHistoryMtn::where('command', 'R')
                                        ->whereDate('created_at', $currentDate)
                                        ->count();

                                $dailyMO = Inbox::where('operator', 2)
                                        ->where('short_code', 1890)
                                        ->whereDate('created_at', $currentDate)
                                        ->count();

                                // Use Carbon for date manipulation
                                $currentDate = $currentDateCarbon->toDateString();

                                // Use selectRaw for calculations
                                $subscriptionRevenue = ($request->date < '2023-11') ? $dailySubscribers * 100 : $dailySubscribers * 125;
                                $MORevenue = $dailyMO * (($request->date < '2023-11') ? 200 : 250);
                                $totalRevenue = $MORevenue + $subscriptionRevenue;

                                $oneDay = [
                                        'date' => $currentDate,
                                        'Optins' => $optins,
                                        'Re_optins' => $reOptins,
                                        'Optout' => $optout,
                                        'Charged Unique users' => $dailySubscribers,
                                        'Subscriptions' => $subscriptions,
                                        'Daily MO' => $dailyMO,
                                        'Total Revenue' => $totalRevenue,
                                        'Subscription Revenue' => $subscriptionRevenue,
                                        'MO Revenue' => $MORevenue
                                ];

                                array_push($reportData, $oneDay);
                        }

                        // Cache the results for future requests
                        Cache::put($cacheKey, $reportData, now()->addHours(1)); // Adjust the expiration time as needed
                }

                return response()->json(['status' => 200, 'message' => '', 'data' => $reportData, 'totalCount' => count($reportData)]);
        }
}
