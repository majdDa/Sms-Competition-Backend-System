<?php

namespace App\Interfaces\Application;

interface IAnalysisRepository
{
   public function checkSMS(string $inboxSms);
   public function goToFlowBasedOnSms();
   public function allGoToFlowBasedOnSms();
}
