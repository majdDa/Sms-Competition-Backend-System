<?php

namespace App\Interfaces\Application;

interface IMTNRepository
{
    public function call_activation_api($request);
    public function call_deactivation_api($gsm);
    public function send_sms($gsm, $sms);
    public function formatSMS($sms);
    public function hexaSMS($sms);
    public function call_renewal_api($gsm);
}
