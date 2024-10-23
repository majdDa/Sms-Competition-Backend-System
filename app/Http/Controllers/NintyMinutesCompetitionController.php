<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Interfaces\Application\IReceiveSMS;
use App\Interfaces\Application\IActivationMTNRepository;
use App\Interfaces\Application\IActivationSyRepository;
use App\Interfaces\Application\IAttemptFlowSyriatelRepository;
use App\Interfaces\Application\IAttemptFlowMTNRepository;
use App\Services\Interfaces\IOperatorServices;
use App\Interfaces\Application\IAnalysisRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class NintyMinutesCompetitionController extends Controller
{

    private $_receive_sms;
    private $_activationSyRepo;
    private $_activationMTNRepo;
    private $_operatorServices;
    private $_AttemptFlowSyriatelRepository;
    private $_AttemptFlowMTNRepository;
    protected $_analysisRepository;


    public function __construct(
        IOperatorServices $operatorServices,
        IReceiveSMS $receive_sms,
        IActivationMTNRepository $activationMTNRepository,
        IActivationSyRepository $activationSyRepo,
        IAnalysisRepository $AnalysisRepository,
        IAttemptFlowSyriatelRepository $AttemptFlowSyriatelRepository,
        IAttemptFlowMTNRepository $AttemptFlowMTNRepository
    ) {
        $this->_receive_sms = $receive_sms;
        $this->_activationMTNRepo = $activationMTNRepository;
        $this->_activationSyRepo = $activationSyRepo;
        $this->_operatorServices = $operatorServices;
        $this->_analysisRepository = $AnalysisRepository;
        $this->_AttemptFlowSyriatelRepository = $AttemptFlowSyriatelRepository;
        $this->_AttemptFlowMTNRepository = $AttemptFlowMTNRepository;
    }

    public function receive_sms_sy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'GSM' => 'required|regex:/^963\d{9}$/',
            'SC' => 'required|digits:4',
            'reqID' => 'required|integer',
            'MSGtxt' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }
        if (!$this->_receive_sms->check_duplicate_request_id($request['reqID'])) {
            $this->_receive_sms->receive_request($request, 1);
        }
        Log::channel('receiveSySms')->info(date("Y-m-d h:i:sa"), $request->all());
        echo 'Ok';
    }


    public function receive_sms_mtn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'GSM' => 'required|regex:/^963\d{9}$/',
            'SC' => 'required|digits:4',
            'langID' => 'required|in:1,2',
            'timestamp' => 'required|string',
        ]);

        $request['MSGtxt'] = $request->MSGtxt ?? ' ';

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }
        $this->_receive_sms->receive_request($request, 2);
        Log::channel('receiveMtnSms')->info(date("Y-m-d h:i:sa"), $request->all());
        return response()->json('Ok', 200);
    }


    public function take_action_sy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gsm' => 'required|regex:/^963\d{9}$/',
            'status' => 'required|in:1,0',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        Log::channel('syriatelTakeAction')->info(date("Y-m-d h:i:sa"), $request->all());
        echo 'Ok';
        return  $this->_activationSyRepo->check_response_status($request);
    }



    public function take_action_mtn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gsm' => 'required|regex:/^963\d{9}$/',
            'status' => 'required',
            'category' => 'required',
            'ticketid' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        Log::channel('mtnTakeAction')->info(date("Y-m-d h:i:sa"), $request->all());
        echo 'Ok';
        return $this->_activationMTNRepo->check_response_status($request);
    }


    public function analysis_sms(Request $request)
    {
        echo 'Ok';
        return $this->_analysisRepository->goToFlowBasedOnSms($request);
    }

    public function activation_syriatel_attempt_flow()
    {
        return $this->_AttemptFlowSyriatelRepository->attempt_activation_flow();
    }

    public function deActivation_syriatel_attempt_flow()
    {
        return $this->_AttemptFlowSyriatelRepository->attempt_Deactivation_flow();
    }
    public function daily_renewal(Request $request)
    {
        return $this->_operatorServices->request_daily_renewal_activation_MTN($request);
    }

    public function retry_flow_mtn()
    {
        return  $this->_AttemptFlowMTNRepository->retry_flow();
    }

    public function balanceEnd()
    {
        return $this->_activationMTNRepo->get_gsms_to_canceled();
    }
}
