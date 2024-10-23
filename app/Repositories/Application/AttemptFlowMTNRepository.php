<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\IAttemptFlowMTNRepository;
use App\Interfaces\Application\IMTNRepository;
use App\Interfaces\Domain\IPendingMTNRepository;
use App\Models\PendingSmsMTN;
use App\Models\PendingGsmMTN;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class AttemptFlowMTNRepository implements IAttemptFlowMTNRepository
{
    private $_pendingMTNRepository;
    private $_mtnRepository;
    public function __construct(IPendingMTNRepository $pendingMTNRepository, IMTNRepository $mtnRepository)
    {
        $this->_pendingMTNRepository = $pendingMTNRepository;
        $this->_mtnRepository = $mtnRepository;
    }


    public function retry_flow()
    {
        $api_response = '';
        $pendingData = $this->_pendingMTNRepository->get_attempt_request();
        foreach ($pendingData as $data) {
            $sc = PendingSmsMTN::where('pending_id', $data->id)->value('short_code');
            if ($data->command  == 'R' && $data->renewal_by == 'RAND') {
                $api_response = $this->_mtnRepository->call_renewal_api($data->gsm);
            }
            if ($data->command  == 'A' &&  $data->renewal_by == '') {
                if ($sc != NULL) {
                    $data->sc = $sc;
                } else { //no messages in pending_sms for the pending_gsm 
                    $data->sc = 1490;
                }
                $api_response = $this->_mtnRepository->call_activation_api($data);
            }
            if ($data->command  == 'D') {
                $api_response = $this->_mtnRepository->call_deactivation_api($data->gsm);
            }
            $data->response = $api_response;
            $data->attempt++;
            $data->attempt_date = Carbon::now();
            $data->save();
        }

        return true;
    }
}