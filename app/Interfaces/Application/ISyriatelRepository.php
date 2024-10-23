<?php

namespace App\Interfaces\Application;


interface ISyriatelRepository
{
    public function call_activation_api($gsm);
    public function call_deActivation_api($gsm);
    public function send_sms($gsm, $sms);
    public function send_bulk($teserText);
}
