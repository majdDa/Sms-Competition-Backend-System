<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// use App\Interfaces\Application\IWheelAnalysisRepository;

use App\Repositories\Application\WheelAnalysisRepository;


class WheelController extends Controller
{

    /*protected $_wheel;

    public function __construct(IWheelAnalysisRepository $wheelRepo)
    {
        $this->_wheel = $wheelRepo;
    }
*/

    public function sendVerificationCode(Request $request, WheelAnalysisRepository $_wheel)
    {
        $gsm = $request->gsm;
        Log::channel('sendCode')->info(date("Y-m-d h:i:sa"), $request->all());
        return $_wheel->sendVerificationCode($gsm);
    }


    public function login(Request $request, WheelAnalysisRepository $_wheel)
    {
        $gsm = $request->gsm;
        $code = $request->code;
        Log::channel('login')->info(date("Y-m-d h:i:sa"), $request->all());
        return $_wheel->login($gsm, $code);
    }


    public function spin(Request $request, WheelAnalysisRepository $_wheel)
    {
        $gsm = $request->gsm;
        $points = $request->points;
        $counter = (int)$request->counter;

        Log::channel('spin')->info(date("Y-m-d h:i:sa"), $request->all());
        return $_wheel->spin($gsm, $points, $counter);
    }
}
