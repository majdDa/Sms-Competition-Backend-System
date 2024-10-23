<?php

namespace App\Interfaces\Application;

interface IAttemptFlowMTNRepository
{
    public function retry_flow();
}
