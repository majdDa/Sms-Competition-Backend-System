<?php

namespace App\Services\Interfaces;

interface IOperatorServices
{
  public function sendSMS($operator, $sms, $gsm);
  public function call_activation_api($operator, $gsm);
  public function call_deactivation_api($operator, $gsm);
  public function request_activation($operator, $request);
  public function request_deactivation($operator, $request,$sub);
  public function request_daily_renewal_activation_MTN($request);
}