<?php

namespace App\Repositories\Domain;

use App\Interfaces\Application\ISendSms;
use App\Interfaces\Domain\ISpinningWheelRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Models\SpinningWheel;
use App\Models\Subscriber;
use App\Services\Interfaces\IOperatorServices;

class SpinningWheelRepository implements ISpinningWheelRepository
{

    protected $_subscriberRepository;
    protected $_operatorRepository;
    protected $_SendSms;
    protected $_gsmTypes = ['Syriatel' => 1, 'MTN' => 2];

    public function __construct(ISubscribersRepository $subscriberRepository, IOperatorServices $operatorRepository, ISendSms $SendSms)
    {
        $this->_subscriberRepository = $subscriberRepository;
        $this->_operatorRepository = $operatorRepository;
        $this->_SendSms = $SendSms;
    }

    public function checkIsSubscriber(string $gsm): bool
    {
        $is_active = $this->_subscriberRepository->is_active($gsm);
        if ($is_active) {
            return 1;
        } else {
            return 0;
        }
    }

    public function checkIsPlayerToday(string $gsm): bool
    {
        $checkIsPlayer = SpinningWheel::where('gsm', $gsm)->where('created_at', 'LIKE', '%' . \Carbon\Carbon::now()->format('Y-m-d') . '%')->first();
        return !is_null($checkIsPlayer);
    }


    public function createPlayer(string $gsm): bool
    {
        //$checkInSpinningWheel = SpinningWheel::where('gsm', $gsm)->first();
        if (!($this->checkIsPlayerToday($gsm))) {
            $subscribeId = Subscriber::where('gsm', $gsm)->value('id');
            SpinningWheel::create(['gsm' => $gsm, 'points' => 0, 'verification_code' => 0, 'subscriber_id' => $subscribeId, 'status' => 0]);
            return 1;
        } else {
            SpinningWheel::where([
                ['gsm', '=', $gsm],
                ['status', '=', 0],
                ['created_at', 'LIKE', '%' . \Carbon\Carbon::now()->format('Y-m-d') . '%']
            ])->update(['created_at' => \Carbon\Carbon::now()]);
            return 1;
        }
    }

    public function checkSpinToday(string $gsm): bool
    {
        $checkSpinToday = SpinningWheel::where('gsm', $gsm)->where('created_at', 'LIKE', '%' . \Carbon\Carbon::now()->format('Y-m-d') . '%')->where('status', 1)->first();
        return !is_null($checkSpinToday);
    }

    public function verifyCode(string $gsm, string $code): int
    {

        /*       $verifyCode = SpinningWheel::where([
            ['gsm', '=', $gsm],
            ['verification_code', '=', $code],
            // ['status', '=', 0],
            ['created_at', 'LIKE', '%' . \Carbon\Carbon::now()->format('Y-m-d') . '%']
        ])->first();

        if ($verifyCode) {
            return !$verifyCode->status;
        }
        $this->createPlayer($gsm);
        return !is_null($verifyCode);
        // return -1;
*/

        $verifyCode = SpinningWheel::where([
            ['gsm', '=', $gsm],
            ['verification_code', '=', $code],
            //['status', '=', 0],
            //['created_at', 'LIKE', '%' . \Carbon\Carbon::now()->format('Y-m-d') . '%']
        ])->first();

        if (!$verifyCode) {
            return -1;
        }



        $verifyCode = SpinningWheel::where([
            ['gsm', '=', $gsm],
            ['verification_code', '=', $code],
            //['status', '=', 0],
            ['created_at', 'LIKE', '%' . \Carbon\Carbon::now()->format('Y-m-d') . '%']
        ])->first();

        if (!$verifyCode) {
            $subscribeId = Subscriber::where('gsm', $gsm)->value('id');
            SpinningWheel::create(['gsm' => $gsm, 'points' => 0, 'verification_code' => $code, 'subscriber_id' => $subscribeId, 'status' => 0]);
        } elseif ($verifyCode->status == 1) {
            return 0;
        }

        return 1;
        // return -1;
    }

    public function spin(string $gsm, int $points, int $counter = 0): int
    {
        SpinningWheel::where([
            ['gsm', '=', $gsm],
            ['status', '=', 0],
            ['created_at', 'LIKE', '%' . \Carbon\Carbon::now()->format('Y-m-d') . '%']
        ])->update(['points' => $points, 'status' => 1, 'counter' => $counter]);
        $subscribeId = SpinningWheel::where('gsm', $gsm)->value('subscriber_id');
        $this->_subscriberRepository->update_points($subscribeId, $points);
        return 1;
    }

    public function sendCode(string $gsm): int
    {
        //$isSpinBefore = SpinningWheel::where('gsm', $gsm)->first();
        //$isSpinBefore = SpinningWheel::where('gsm', $gsm)->orderBy('created_at', 'desc')->first();
        //print_r($isSpinBefore);
        // if ($isSpinBefore) {
        //     return $isSpinBefore->verification_code;
        // }

        //$randomCode = substr(md5(uniqid(rand(), true)), 16, 4);
        $randomCode = (string) ((int)rand(1234, 9999));
        SpinningWheel::where([
            ['gsm', '=', $gsm],
            ['status', '=', 0],
            ['created_at', 'LIKE', '%' . \Carbon\Carbon::now()->format('Y-m-d') . '%']
        ])->update(['verification_code' => $randomCode]);
        $gsmID = SpinningWheel::where('gsm', $gsm)->value('id');
        $gsmType = SpinningWheel::find($gsmID)->subscriber->operator;
        $sms = 'كلمة المرور الخاصة بك في موقع مسابقة 90 دقيقة على الإنترنت هي :
' . $randomCode;
        $this->_SendSms->sendSMS($gsmType, $sms, $gsm);
        return 1;
    }
}
