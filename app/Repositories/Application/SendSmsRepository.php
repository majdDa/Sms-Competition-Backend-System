<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\ISyriatelRepository;
use App\Interfaces\Application\IMTNRepository;
use App\Interfaces\Application\ISendSms;

class SendSmsRepository implements ISendSms
{
    private $_SyRepository;
    private $_MTNRepository;

    public function __construct(ISyriatelRepository $SyriatelRepository, IMTNRepository $MTNRepository)
    {

        $this->_SyRepository = $SyriatelRepository;
        $this->_MTNRepository = $MTNRepository;
    }

    public function sendSMS($operator, $sms, $gsm)
    {

        if ($operator == 1) {
            return  $this->_SyRepository->send_sms($gsm, $sms);
        } else {
            return $this->_MTNRepository->send_sms($gsm, $sms);
        }
    }
}
