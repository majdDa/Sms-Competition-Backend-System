<?php

namespace App\Services\Factories;

use App\Services\Interfaces\IOperatorServices;
use App\Interfaces\Application\ISyriatelRepository;
use App\Interfaces\Application\IMTNRepository;
use App\Interfaces\Domain\IPendingMTNRepository;
use App\Interfaces\Domain\IPendingSyRepository;
use App\Interfaces\Domain\IInboxRepository;
use App\Interfaces\Application\ISendMtRepository;
use App\Jobs\ProcessRenewalRequestJob;
use App\Models\ReturnType;

class OperatorServices implements IOperatorServices
{
    private $_SyRepository;
    private $_MTNRepository;
    private $_pendingMTNRepository;
    private $_pendingSyRepository;
    private $_inboxRepository;
    private $_sendingMtRepository;

    public function __construct(ISendMtRepository $sendingMtRepository, ISyriatelRepository $SyRepository, IMTNRepository $MTNRepository, IPendingSyRepository $pendingSyRepository, IPendingMTNRepository $pendingMTNRepository, IInboxRepository $inboxRepository)
    {
        $this->_SyRepository = $SyRepository;
        $this->_MTNRepository = $MTNRepository;
        $this->_pendingMTNRepository = $pendingMTNRepository;
        $this->_pendingSyRepository = $pendingSyRepository;
        $this->_inboxRepository = $inboxRepository;
        $this->_sendingMtRepository = $sendingMtRepository;
    }


    public function sendSMS($operator, $sms, $gsm)
    {

        if ($operator == 1) {
            $this->_SyRepository->send_sms($gsm, $sms);
        } else {
            $this->_MTNRepository->send_sms($gsm, $sms);
        }
        return true;
    }


    #call Activation API
    public function call_activation_api($operator, $gsm)
    {

        if ($operator == 1) {
            return   $this->_SyRepository->call_activation_api($gsm);
        } else {
            return  $this->_MTNRepository->call_activation_api($gsm);
        }
        return true;
    }



    #call DeActivation API 
    public function call_deactivation_api($operator, $gsm)
    {
        if ($operator == 1) {
            return $this->_SyRepository->call_deactivation_api($gsm);
        } else {
            return  $this->_MTNRepository->call_deactivation_api($gsm);
        }
        return true;
    }



    #Request Activation
    public function request_activation($operator, $request)
    {
        if ($operator == 1) {
            return  $this->request_activation_syriatel($request);
        } else {
            //if sub is not null 
            return  $this->request_activation_MTN($request);
        }
    }

    #Request DeActivation
    public function request_deactivation($operator, $request, $inboxes = null, $sub = null)
    {
        if ($operator == 1) {
            return  $this->request_deactivation_syriatel($request, $inboxes, $sub);
        } else {
            return  $this->request_deactivation_MTN($request, $inboxes, $sub);
        }
    }


    ###############  Request Daily Renewal Activation MTN  ###############


    public function request_daily_renewal_activation_MTN($request)
    {
        $renewalRequests = $this->_pendingMTNRepository->get_renewal_requests();
        if ($renewalRequests->isNotEmpty()) {
            foreach ($renewalRequests as $renewalRequests) {
                $ticket_id = $this->_MTNRepository->call_renewal_api($renewalRequests->gsm);
                $this->_pendingMTNRepository->update_is_renewaled($renewalRequests->id, $ticket_id);
            }
        }
        return true;
    }


    #####################################################################################################################
    private function request_activation_syriatel($request)
    {
        $response = new ReturnType('', '');
        if ($this->_pendingSyRepository->isExist($request->gsm)) {
            $pending =   $this->_pendingSyRepository->get_pending_gsm($request->gsm);
            $pending_id = $pending->id;
            $api_response =  $this->_SyRepository->call_activation_api($request->gsm);
            $response = $this->_sendingMtRepository->send_pending_activation_mt($request->gsm, 1);
            $request->mt = $response->mt;
            $request->op_response = $response->op_response;
            $request->command = "A";
            $request->is_processed = 1;
            $this->_pendingSyRepository->update_command($pending_id, 'A', $api_response);
            $this->_pendingSyRepository->add_pending_sms($request, $pending_id);
        } else {
            #call Syriatel Activation API //  ISyriatelRepository
            $operatorResponse =  $this->_SyRepository->call_activation_api($request->gsm);
            $pending_gsm = $this->_pendingSyRepository->add_pending_gsm($request, 'A', $operatorResponse);
            $response = $this->_sendingMtRepository->send_pending_activation_mt($request->gsm, 1);
            $request->is_processed  = 1;
            $request->mt = $response->mt;
            $request->command = "A";
            $request->op_response = $response->op_response;
            $this->_pendingSyRepository->add_pending_sms($request, $pending_gsm->id);
        }
    }


    private function request_activation_MTN($request)
    {
        $response = new ReturnType('', '');
        if ($this->_pendingMTNRepository->isExist($request->gsm)) {
            $pending_id = $this->_pendingMTNRepository->get_pending_gsm_byGsm($request->gsm)->id;
            $response = $this->_sendingMtRepository->send_pending_activation_mt($request->gsm, 2);
            $request->is_processed = 1;
            //$request->mt = $response->mt;
            $request->mt = '';
            //  $request->command = "A";
            $request->command = '';
            // $request->op_response = $response->op_response;
            $request->op_response = '';
            $this->_pendingMTNRepository->add_pending_sms($request, $pending_id);
        } else {
            $operatorResponse = $this->_MTNRepository->call_activation_api($request); // $operatorResponse id tiscketId
            $pending_gsm = $this->_pendingMTNRepository->add_pending_gsm($request, 'A', $operatorResponse);
            $response = $this->_sendingMtRepository->send_pending_activation_mt($request->gsm, 2);
            $request->is_processed  = 1;
            $request->mt = $response->mt;
            $request->command = "A";
            $request->op_response = $response->op_response;
            $this->_pendingMTNRepository->add_pending_sms($request, $pending_gsm->id);
        }
    }










    private function request_deactivation_syriatel($request, $inboxes, $sub)
    {
        $response = new ReturnType('', '');
        $pending_id = 0;
        if ($this->_pendingSyRepository->isExist($request->gsm)) {
            $pending_id =  $this->_pendingSyRepository->get_pending_gsm($request->gsm)->id;
            $api_response = $this->_SyRepository->call_deactivation_api($request->gsm);
            $response = $this->_sendingMtRepository->send_pending_de_activation_mt($request->gsm, 1);
            $request->mt = $response->mt;
            $request->op_response = $response->op_response;
            $request->command = "D";
            $request->is_processed = 1;
            $this->_pendingSyRepository->update_command($pending_id, 'D', $api_response);
            $this->_pendingSyRepository->add_pending_sms($request,  $pending_id);
        } else {
            $operatorResponse = $this->_SyRepository->call_deactivation_api($request->gsm);
            $response = $this->_sendingMtRepository->send_pending_de_activation_mt($request->gsm, 1);
            $request->mt = $response->mt;
            $request->op_response = $response->op_response;
            $request->command = "D";
            $request->is_processed = 1;
            if (!is_null($sub) &&  $sub->sub_status == 1) {
                $pending_gsm = $this->_pendingSyRepository->add_pending_gsm($request, 'D', $operatorResponse);
                $pending_id = $pending_gsm->id;
                if (!is_null($inboxes)) { #the incoming DeActivation Command is Coming from Inbox
                    //     $this->_pendingSyRepository->add_pending_sms_from_inbox($inboxes, $pending_gsm->id);
                } else { #incoming DeActivation Command is Coming from receive_sms API
                    $this->_pendingSyRepository->add_pending_sms($request, $pending_gsm->id);
                }
            }
        }
        return $pending_id;
    }


    private function request_deactivation_MTN($request, $inboxes, $sub)
    {
        $response = new ReturnType('', '');
        $pending_id = 0;
        if ($this->_pendingMTNRepository->isExist($request->gsm)) {
            $pending_id =  $this->_pendingMTNRepository->get_pending_gsm_byGsm($request->gsm)->id;
            $request->is_processed = 1;
            //  $response = $this->_sendingMtRepository->send_pending_de_activation_mt($request->gsm, 2);
            ///$request->mt = $response->mt;
            $request->mt = '';
            //  $request->command = "D";
            $request->command = '';
            //  $request->op_response = $response->op_response;
            $request->op_response = '';
            $this->_pendingMTNRepository->add_pending_sms($request,  $pending_id);
        } else {
            $operatorResponse = $this->_MTNRepository->call_deactivation_api($request->gsm);
            $pending_gsm = $this->_pendingMTNRepository->add_pending_gsm($request, 'D', $operatorResponse);
            $pending_id = $pending_gsm->id;
            $request->is_processed = 1;
            $response = $this->_sendingMtRepository->send_pending_de_activation_mt($request->gsm, 2);
            $request->mt = $response->mt;
            $request->command = "D";
            $request->op_response = $response->op_response;
            if (!is_null($inboxes)) { #the incoming DeActivation Command is Coming from Inbox
                // $this->_pendingMTNRepository->add_pending_sms_from_inbox($inboxes, $pending_gsm->id);
            } else {
                $this->_pendingMTNRepository->add_pending_sms($request, $pending_gsm->id);
            }
        }
        return $pending_id;
    }
}
