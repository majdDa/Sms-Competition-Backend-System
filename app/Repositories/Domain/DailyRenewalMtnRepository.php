<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\IDailyRenewalMtnRepository;
use APP\Interfaces\Domain\ISubscribersRepository;
// use APP\Services\Interfaces\IOperatorServices;
use App\Models\TakeAction;
use App\Models\DailyRenewalMtn;
use App\Models\Subscriber;
use App\Services\Interfaces\IOperatorServices;

class DailyRenewalMtnRepository implements IDailyRenewalMtnRepository
{
    private  ISubscribersRepository $_subscribersRepository;
    private  IOperatorServices $_operatorServices;
    public function __construct(ISubscribersRepository $subscribersRepository, IOperatorServices $operatorServices)
    {
        $this->_subscribersRepository = $subscribersRepository;
        $this->_operatorServices = $operatorServices;
    }

    public function  add_renewal_request(TakeAction $takeAction, $pending_id, $subscriber_id)
    {
        $subscribers = $this->_subscribersRepository->get_all_active_mtn_subscribers();

        foreach ($subscribers as $subscriber) {
            $this->_operatorServices->request_daily_renewal_activation_MTN($subscriber);
        }

        /*$daily_renewal_sy = new DailyRenewalMtn();
        $daily_renewal_sy->gsm = $takeAction->gsm;
        $daily_renewal_sy->status = $takeAction->response;
        $daily_renewal_sy->subscriber_id = $subscriber_id;*/
    }
}
