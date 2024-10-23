<?php

use App\Http\Controllers\KeywordsController;
use App\Http\Controllers\WheelController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NintyMinutesCompetitionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TeaserController;
use App\Http\Controllers\MtnDashboardController;
use App\Http\Controllers\ChartController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('mtnuser/login', [AuthController::class, 'login']);
Route::post('mtnuser/add_user', [AuthController::class, 'add_user']);


Route::group(
    ['middleware' => 'auth:sanctum'],
    function () {
        Route::post('mtnuser/get', [MtnDashboardController::class, 'get_all_mtn_users']);
        Route::post('mtnuser/logout', [AuthController::class, 'logout']);
        Route::post('mtnuser/deactivate', [MtnDashboardController::class, 'deactivate']);
        Route::post('mtnuser/search', [MtnDashboardController::class, 'search']);
        Route::post('mtnuser/get_messages', [MtnDashboardController::class, 'get_gsm_messages']);
        Route::post('mtnuser/report', [ChartController::class, 'report_mtn']);
        Route::post('mtnuser/report_v2', [ChartController::class, 'report_mtn_v2']);

        Route::post('mtnuser/new_get', [MtnDashboardController::class, 'new_get_all_mtn_users']);
        Route::post('mtnuser/new_deactivate', [MtnDashboardController::class, 'new_deactivate']);
        Route::post('mtnuser/new_get_messages', [MtnDashboardController::class, 'new_get_gsm_messages']);
        Route::post('mtnuser/new_report', [ChartController::class, 'new_report_mtn']);
        Route::post('mtnuser/new_report_v2', [ChartController::class, 'new_report_mtn_v2']);
    }
);


Route::post('sendVerificationCode', [WheelController::class, 'sendVerificationCode']);
Route::post('login', [WheelController::class, 'login']);
Route::post('spin', [WheelController::class, 'spin']);


Route::get('/receiveSmsSy', [NintyMinutesCompetitionController::class, 'receive_sms_sy']);
Route::get('/receiveSmsMtn', [NintyMinutesCompetitionController::class, 'receive_sms_mtn']);
Route::get('/takeActionSy', [NintyMinutesCompetitionController::class, 'take_action_sy']);
Route::get('/takeActionMtn', [NintyMinutesCompetitionController::class, 'take_action_mtn']);
Route::get('/renewalMtn', [NintyMinutesCompetitionController::class, 'daily_renewal']);
Route::get('/analysisSms', [NintyMinutesCompetitionController::class, 'analysis_sms']);
Route::get('/act_attemptflow_sy', [NintyMinutesCompetitionController::class, 'activation_syriatel_attempt_flow']);
Route::get('/deact_attemptflow_sy', [NintyMinutesCompetitionController::class, 'deActivation_syriatel_attempt_flow']);
Route::get('/retry_flow_mtn', [NintyMinutesCompetitionController::class, 'retry_flow_mtn']);
Route::get('/sendTesers', [TeaserController::class, 'send']);
Route::get('/sendReport', [TeaserController::class, 'sendReport']);
Route::get('/test', [TeaserController::class, 'testSendReport']);
Route::get('/sendMtCancelation', [NintyMinutesCompetitionController::class, 'balanceEnd']);



Route::post('interface/login', [AuthController::class, 'login']);
Route::group(
    ['middleware' => 'auth:sanctum'],
    function () {

        Route::post('interface/logout', [AuthController::class, 'logout']);


        // Question
        Route::group(['prefix' => 'question'], function () {
            Route::get('get_all', [QuestionController::class, 'get_all_questions']);
            Route::post('add', [QuestionController::class, 'add_question']);
            Route::post('add_file', [QuestionController::class, 'add_file_questions']);
            Route::get('get_by_date', [QuestionController::class, 'get_questions_by_date']);
            Route::post('update', [QuestionController::class, 'update_question']);
            Route::post('delete', [QuestionController::class, 'delete_question']);
        });



        // Teaser
        Route::group(['prefix' => 'teaser'], function () {
            Route::get('get_all', [TeaserController::class, 'get_all']);
            Route::post('add', [TeaserController::class, 'add']);
            Route::post('update', [TeaserController::class, 'update']);
            Route::post('delete', [TeaserController::class, 'delete']);
            Route::post('search', [TeaserController::class, 'search']);
        });

        // Keywords
        Route::group(['prefix' => 'keywords'], function () {
            Route::get('get_keyword_data', [KeywordsController::class, 'get_keyword_data']);
            Route::get('get_all_golden', [KeywordsController::class, 'get_all_golden']);
            Route::get('get_all_fixed', [KeywordsController::class, 'get_all_fixed']);
            Route::post('add', [KeywordsController::class, 'add']);
            Route::post('update', [KeywordsController::class, 'update']);
            Route::post('delete', [KeywordsController::class, 'delete']);
        });



        // end sanctum
    }
);



/*
#php artisan migrate:fresh
#php artisan db:seed

#Example Call APIs:
#http://localhost/90MinutesCompetition/public/api/receiveSmsSy?GSM=963993333601&SC=1890&reqID=6548&MSGtxt=%D8%B5%D8%AD
#http://localhost/90MinutesCompetition/public/api/takeActionSy?gsm=963993333649&status=1
#http://localhost/90MinutesCompetition/public/api/receiveSmsMtn?GSM=963943920177&MSGtxt=0645063306270639062f0629&SC=1890&langID=1&timestamp=26042023120000
#http://localhost/90MinutesCompetition/public/api/takeActionMtn?gsm=963943111609&status=1&category=D&ticketid=470456
#http://localhost/90MinutesCompetition/public/api/takeActionMtn?gsm=963945825050&status=10&category=D&ticketid=123

Our APIs:
#http://localhost/90MinutesCompetition/public/api/analysisSms      #every  10 seconds
http://localhost/90MinutesCompetition/public/api/retry_flow_mtn    #every  10 minutes

http://localhost/90MinutesCompetition/public/api/renewalMtn        #every  5 minutes
http://localhost/90MinutesCompetition/public/api/sendMtCancelation

http://localhost/90MinutesCompetition/public/api/act_attemptflow_sy
http://localhost/90MinutesCompetition/public/api/deact_attemptflow_sy 
http://localhost/90MinutesCompetition/public/api/mtnuser/chart_data
*/