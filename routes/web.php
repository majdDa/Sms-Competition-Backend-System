<?php

use Illuminate\Support\Facades\Route;
use Mockery as Mockery;
use App\Models\Inbox;
use App\Models\PendingSmsMTN;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    echo ('welcome');
});

Route::get('/test', function () {

    $request = array(
        'gsm' => '9333333333',
        'sc' => '1999'
    );

    $inboxes = Inbox::all();

    $msgs = [];

    foreach ($inboxes as $row) {
        $pending_sms = new PendingSmsMTN();
        $pending_sms->pending_id = 1;
        $pending_sms->short_code = $row->short_code;
        $pending_sms->sms = $row->sms;
        array_push($msgs, $pending_sms);
    }


    PendingSmsMTN::insert($msgs);
});
