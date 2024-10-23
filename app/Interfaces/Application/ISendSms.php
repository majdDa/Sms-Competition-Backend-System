<?php

namespace App\Interfaces\Application;

interface ISendSms
{
    public function sendSMS($operator, $sms, $gsm);
}
