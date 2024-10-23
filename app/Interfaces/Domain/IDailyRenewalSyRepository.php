<?php

namespace App\Interfaces\Domain;

use App\Models\TakeAction;

interface IDailyRenewalSyRepository
{
    public function  add_renewal_request(TakeAction $takeAction,$subscriber_id);
}