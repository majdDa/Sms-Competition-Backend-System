<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\IAnalysisRepository;
use App\Interfaces\Application\IReceiveSMS;
use App\Models\Mo;
use App\Interfaces\Application\ISendMtRepository;
use App\Interfaces\Domain\IInboxRepository;
use App\Interfaces\Domain\ICommandRepository;
use App\Interfaces\Domain\IKeywordsRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Interfaces\Domain\IQuestionsRepository;
use App\Interfaces\Domain\IPendingSyRepository;
use App\Interfaces\Domain\IPendingMTNRepository;

use App\Jobs\AnalysisSmsJob;
use App\Services\Interfaces\IOperatorServices;
use Illuminate\Support\Facades\Artisan;

class ReceiveSMS implements IReceiveSMS
{

    private $_inboxRepository;
    private $_subscribersRepository;
    private $_operatorServicesFactory;
    private $_commandRepository;
    private $_pendingSyRepository;
    private $_pendingMtnRepository;

    public function __construct(
        ICommandRepository $commandRepository,
        IInboxRepository $inboxRepository,
        ISubscribersRepository $subscribersRepository,
        IOperatorServices $operatorServicesFactory,
        IPendingSyRepository $pendingSyRepository,
        IPendingMTNRepository $pendingMtnRepository
    ) {
        $this->_inboxRepository = $inboxRepository;
        $this->_commandRepository = $commandRepository;
        $this->_subscribersRepository = $subscribersRepository;
        $this->_operatorServicesFactory = $operatorServicesFactory;
        $this->_pendingSyRepository = $pendingSyRepository;
        $this->_pendingMtnRepository = $pendingMtnRepository;
    }

    public function check_duplicate_request_id($request_id): bool
    {
        if ($this->_inboxRepository->is_Request_Id_Exist($request_id) || $this->_pendingSyRepository->is_Request_Id_Exist($request_id)) {
            return true;
        }
        return false;
    }


    public function receive_request($request, $op)
    {
        $request['MSGtxt'] = $this->convert_mo($op, $request);
        $mo = new Mo($request, $op);
        $sub = $this->_subscribersRepository->get_subscriber_info($request['GSM']);

        if (!empty($this->_commandRepository->getDeactivationCommandsByName($mo->sms))) {
            return  $this->_operatorServicesFactory->request_deactivation($op, $mo, null, $sub);
        }

        if (!is_null($sub) &&  $sub->sub_status == 1) {
            return $this->_inboxRepository->add_sms($mo, $sub->id, $op);
        }

        if (is_null($sub) && $mo->operator == 2 && $mo->sc == '1890') {
            $mo->sc = '1490';
        }

        return $this->_operatorServicesFactory->request_activation($op, $mo);
    }





    private function convert_mo($op, $request)
    {
        $sms = $request->MSGtxt;
        if ($op == 2 && $request->langID == 2) {
            $sms = $this->_pendingMtnRepository->read_sms($sms);
        }
        return $sms;
    }
}