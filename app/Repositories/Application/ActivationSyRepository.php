<?php

namespace App\Repositories\Application;

use Illuminate\Http\Request;
use App\Interfaces\Application\IActivationSyRepository;
use App\Interfaces\Application\ISyriatelRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Interfaces\Domain\IPendingSyRepository;
use App\Interfaces\Domain\IInboxRepository;
use App\Interfaces\Application\ISendMtRepository;
use App\Interfaces\Domain\IDailyRenewalSyRepository;
use App\Models\ReturnType;
use App\Models\TakeAction;

class ActivationSyRepository implements IActivationSyRepository
{

    private $_subscribersrepository;
    private $_pendingSyRepository;
    private $_syriatelRepository;
    private $_inboxRepository;
    private $_sendMtRepository;
    private $_dailyRenewalSyRepository;

    public function __construct(
        IPendingSyRepository $pendingSyRepository,
        ISubscribersRepository $subscribersrepository,
        ISyriatelRepository $syriatelRepository,
        IInboxRepository $inboxRepository,
        ISendMtRepository $sendMtRepository,
        IDailyRenewalSyRepository  $dailyRenewalSyRepository
    ) {
        $this->_pendingSyRepository = $pendingSyRepository;
        $this->_subscribersrepository = $subscribersrepository;
        $this->_syriatelRepository = $syriatelRepository;
        $this->_inboxRepository = $inboxRepository;
        $this->_sendMtRepository = $sendMtRepository;
        $this->_dailyRenewalSyRepository = $dailyRenewalSyRepository;
    }


    public function check_response_status($request) #check TakeAction Response
    {
        $take_action = new TakeAction($request);
        if ($take_action->status == 1) {
            $this->activation($take_action);
        } else {
            $this->de_activation($take_action); //cancel_subscribtion
        }
    }


    public function activation($request)
    {
        $response = new ReturnType('', '');
        $pending_gsm = $this->_pendingSyRepository->get_pending_gsm($request->gsm);
        $sub = $this->_subscribersrepository->get_subscriber_info($request->gsm);

        if ($this->_subscribersrepository->is_not_active($request->gsm)) {
            $this->_subscribersrepository->renewal_subscribtion($request->gsm, 'Syriatel');
            $response = $this->_sendMtRepository->send_renewal_message($request->gsm, $sub->score, 1);
        }
        if (!$this->_subscribersrepository->isExist($request->gsm)) {
            $score = 90;
            $user = 'Syriatel';
            $sc = $pending_gsm != null ? $pending_gsm->PendingSmsSy()->where('is_processed', 1)->first()->short_code : 1480;
            $sub = $this->_subscribersrepository->add_subscriber($request->gsm, 1, $score, $sc, $user);
            $response = $this->_sendMtRepository->send_Activation_mt($request->gsm, $score, 1);
        }

        if ($pending_gsm) {
            //  dd($sub);
            $pending_messages = $this->_pendingSyRepository->get_pending_msgs($pending_gsm->id);
            # add pending_Messages(collect) to inbox
            if ($pending_messages != NULL) {
                $this->_inboxRepository->add_from_pending_sy($sub, $pending_messages, $response, 'Activation');
            }
            $pending_gsm->status = $request->status;
            $this->_pendingSyRepository->add_to_history($pending_gsm);
            $this->_pendingSyRepository->delete_pending_relatives($pending_gsm->id);
        } else {
            #TakeAction without Request - add to daily_renewal_sy table 
            $this->_dailyRenewalSyRepository->add_renewal_request($request, $sub->id);
        }
        // echo 'Ok';
        return true;
    }



    public function de_activation($request) //add new parameter $user
    {
        $response = new ReturnType('', '');
        $user = 'Syriatel';
        $pending_gsm = $this->_pendingSyRepository->get_pending_gsm($request->gsm);
        $sub = null;
        if ($this->_subscribersrepository->isExist($request->gsm)) {
            $sub = $this->_subscribersrepository->get_subscriber_info($request->gsm);
            $this->_subscribersrepository->cancel_subscribtion($request->gsm, $user);
        }
        if ($pending_gsm != NULL) {
            $pending_gsm->status = $request->status;
            $pending_messages = $this->_pendingSyRepository->get_pending_msgs($pending_gsm->id); //return collect from 'Pending Sms TB'
            if ($pending_messages != NULL && $sub != null) {

                $sub = $this->_subscribersrepository->get_subscriber_info($request->gsm);
                $response->mt = 'Canceled Message sent By Syriatel';
                $response->op_response = '';

                $this->_inboxRepository->add_from_pending_sy($sub, $pending_messages, $response, 'DeActivation');
            }
            if ($this->_pendingSyRepository->add_to_history($pending_gsm)) {
                $this->_pendingSyRepository->delete_pending_relatives($pending_gsm->id);
            }
        } else {
            #add to renewal table 
            // $sub = $this->_subscribersrepository->get_subscriber_info($request->gsm);
            // $this->_dailyRenewalSyRepository->add_renewal_request($request, $sub->id);
        }
        return true;
    }
}
