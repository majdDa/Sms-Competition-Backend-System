<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\IDailyRenewalSyRepository;
use App\Models\TakeAction;
use App\Models\DailyRenewalSy;


class DailyRenewalSyRepository implements IDailyRenewalSyRepository
{
    public function  add_renewal_request(TakeAction $takeAction, $subscriber_id)
    {
        $daily_renewal_sy = new DailyRenewalSy();
        $daily_renewal_sy->gsm = $takeAction->gsm;
        $daily_renewal_sy->status = $takeAction->status;
        $daily_renewal_sy->subscriber_id = $subscriber_id;
        $daily_renewal_sy->save();
    }
}
