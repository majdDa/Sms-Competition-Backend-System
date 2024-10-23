<?php

namespace App\Interfaces\Domain;

use App\Models\TakeAction;

interface IDailyRenewalMtnRepository
{
    public function  add_renewal_request(TakeAction $takeAction, $pending_id, $subscriber_id);
}
