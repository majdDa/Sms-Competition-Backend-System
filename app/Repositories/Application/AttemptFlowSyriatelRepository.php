<?php

namespace App\Repositories\Application;

use Exception;
use App\Interfaces\Application\IAttemptFlowSyriatelRepository;
use App\Interfaces\Application\ISyriatelRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Interfaces\Domain\IPendingSyRepository;
use App\Models\PendingGsmSy;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class AttemptFlowSyriatelRepository implements IAttemptFlowSyriatelRepository
{
    private $_pendingSyRepository;
    private $_SyriatelRepository;
    private $_SubscribersRepository;
    public function __construct(IPendingSyRepository $pendingSyRepository, ISyriatelRepository $SyriatelRepository, ISubscribersRepository $SubscribersRepository)
    {
        $this->_pendingSyRepository = $pendingSyRepository;
        $this->_SyriatelRepository = $SyriatelRepository;
        $this->_SubscribersRepository = $SubscribersRepository;
    }




    public function attempt_activation_flow()
    {
        $pendingData = $this->_pendingSyRepository->get_attempt_activation_request();
        // dd($pendingData);
        foreach ($pendingData as $data) {
            if ($data->attempt_number == 5) {
                if ($this->_pendingSyRepository->add_to_history($data)) {
                    PendingGsmSy::where('id', $data->id)->delete();
                }
            } else {
                $this->_pendingSyRepository->update_command($data->id, 'A', $this->_SyriatelRepository->call_activation_api($data->gsm));
                $data->attempt_number++;
                $data->attempt_date = Carbon::now();
                $data->save();
            }
        }
    }




    public function attempt_Deactivation_flow()
    {
        $pendingData = $this->_pendingSyRepository->get_attempt_De_activation_request();
        // dd($pendingData);
        foreach ($pendingData as $data) {
            if ($data->attempt_number == 3) {
                if ($this->_SubscribersRepository->isExist($data->gsm)) {
                    $this->_SubscribersRepository->cancel_subscribtion($data->gsm, 'de_Activation_attempt_flow');
                }

                if ($this->_pendingSyRepository->add_to_history($data)) {
                    $this->_pendingSyRepository->delete_pending_relatives($data->id);
                }
            } else {
                $this->_pendingSyRepository->update_command($data->id, 'D', $this->_SyriatelRepository->call_deActivation_api($data->gsm));
                $data->attempt_number++;
                $data->attempt_date = Carbon::now();
                $data->save();
            }
        }
        return true;
    }
}
