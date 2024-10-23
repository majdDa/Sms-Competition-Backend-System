<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\IActivationMTNRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Interfaces\Domain\IPendingMTNRepository;
use App\Interfaces\Domain\IInboxRepository;
use App\Interfaces\Application\ISendMtRepository;
use App\Interfaces\Application\IMTNRepository;
use App\Models\TakeAction;
use App\Models\ReturnType;


use Carbon\Carbon;
use Faker\Core\Number;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivationMTNRepository implements IActivationMTNRepository
{

	private $response_array = array(
		"7" => "عذراً لقد حدثت مشكلة تقنية",
		"3" => "عذراً , لم يتم تجديد الاشتراك بمسابقة 90 دقيقة لعدم وجود رصيد كافٍ ,لإلغاء الاشتراك يرجى ارسال غ إلى الرقم 1490 بشكل مجاني",
		"2" => "عذراً لقد حدثت مشكلة تقنية",
		"6" => "عذراً لقد حدثت مشكلة تقنية",
		"1" => "تم تفعيل مسابقة 90 دقيقة لديك بنجاح",
		"4" => "عذراً لقد حدثت مشكلة في إضافة العرض المطلوب",
		"30" => "عذراً لقد حدثت مشكلة في حذف العرض",
		"20" => "عذراً العرض المطلوب غير متوفر حالياً",
		"10" => "تم إلغاء مسابقة 90 دقيقة بنجاح",
		"5" => "عذراً لا يمكن تفعيل المسابقة بنجاح",
	);

	private $_pendingMTNRepository;
	private $_subscribersRepository;
	private $_inboxRepository;
	private $_sendMtRepository;
	private $_mtnRepository;

	public function __construct(IMTNRepository $mtnRepository, ISendMtRepository $sendMtRepository, IPendingMTNRepository $pendingMTNRepository, ISubscribersRepository $subscribersRepository, IInboxRepository $inboxRepository)
	{
		$this->_pendingMTNRepository = $pendingMTNRepository;
		$this->_subscribersRepository = $subscribersRepository;
		$this->_inboxRepository = $inboxRepository;
		$this->_sendMtRepository = $sendMtRepository;
		$this->_mtnRepository = $mtnRepository;
	}


	public function check_response_status($request)
	{
		$take_action = new TakeAction($request);
		//success 1
		if ($take_action->status == 1) {

			$this->activation($take_action);
		}

		//deactivate 10
		else if ($take_action->status == 10) {

			$this->deactivation($take_action);
		} else {

			$this->others($take_action);
		}
	}



	public function activation($request)
	{
		$response = new ReturnType('', '');
		$user = 'Mtn'; #'activated_by' = $user
		$operator = 2;
		$subscriber = null;

		$pending_gsm = $this->_pendingMTNRepository->get_pending_gsm($request->gsm, $request->response);

		if ($this->_subscribersRepository->is_not_active($request->gsm)) { #GSM exist and canceled (sub_status=0)
			$this->_subscribersRepository->renewal_subscribtion($request->gsm, 'Mtn');
			$subscriber = $this->_subscribersRepository->get_subscriber_info($request->gsm);
			$response = $this->_sendMtRepository->send_renewal_message_mtn($request->gsm, $subscriber->score, $operator);
		}
		if (!$this->_subscribersRepository->isExist($request->gsm)) { # GSM NOT_exist 
			$score = 90;
			$sc = $pending_gsm != null ? $pending_gsm->PendingSmsMTN()->where('is_processed', 1)->first()->short_code : 1480;

			$subscriber = $this->_subscribersRepository->add_subscriber($request->gsm, $operator, $score, $sc, $user);
			$response = $this->_sendMtRepository->send_Activation_mt_mtn($request->gsm, $score, $operator);
		}

		if ($pending_gsm) {
			$pending_messages = $this->_pendingMTNRepository->get_pending_msgs($pending_gsm->id);
			if ($pending_messages != NULL) {
				$subscriber = $this->_subscribersRepository->get_subscriber_info($request->gsm);
				//dd($subscriber);
				$this->_inboxRepository->add_from_pending_mtn($subscriber, $pending_messages, $response, 'Activation'); #($subscriber, $pending_messages,[$mt , op_response],$type)
			}
			$pending_gsm->status = $request->status;
			$pending_gsm->mt = $response->mt;
			$pending_gsm->op_response = $response->op_response;
			$this->_pendingMTNRepository->add_to_history($pending_gsm);
			$this->_pendingMTNRepository->delete_pending_relatives($pending_gsm->id);
		}
		return true;
	}


	public function deactivation($request)
	{
		$response = new ReturnType('', '');
		$user = 'Mtn';
		$pending_gsm = $this->_pendingMTNRepository->get_pending_gsm($request->gsm, $request->response);
		$sub = null;
		$op_response = '';
		$response_mt = '';

		if (!$this->_subscribersRepository->isExist($request->gsm) || $this->_subscribersRepository->is_not_active($request->gsm)) {
			$sub = $this->_subscribersRepository->get_subscriber_info($request->gsm);
			$response_mt = 'امر خاطئ  .. انت غير مشترك بمسابقة 
90 دقيقة 
للاشتراك ارسل " تفعيل" مجانا للرقم 1490';
			//$op_response =  $this->_sendMtRepository->send_unsubscribe_mt($request->gsm, 2);
			$op_response = $this->_mtnRepository->send_sms($request->gsm, $response_mt);
		} else if ($this->_subscribersRepository->is_active($request->gsm)) {
			$sub = $this->_subscribersRepository->get_subscriber_info($request->gsm);
			$this->_subscribersRepository->cancel_subscribtion($request->gsm, $user); //$subscriber->canceled_by = $user
			$response_mt = $this->response_array[$request->status];
			$op_response = $this->_mtnRepository->send_sms($request->gsm, $response_mt);
		}


		if ($pending_gsm != NULL) {
			$pending_gsm->status = $request->status;
			$pending_gsm->op_response = $op_response;
			$pending_gsm->mt = $response_mt;

			$pending_messages = $this->_pendingMTNRepository->get_pending_msgs($pending_gsm->id);
			if ($pending_messages != NULL) {
				if ($sub != null) {
					$this->_inboxRepository->add_from_pending_mtn($sub, $pending_messages, $response, 'DeActivation');
				}
			}
			if ($this->_pendingMTNRepository->add_to_history($pending_gsm)) {
				$this->_pendingMTNRepository->delete_pending_relatives($pending_gsm->id);
			}
			return true;
		}
		return true;
	}


	public function others($request)
	{
		$response_mt = '';
		$pending_gsm = $this->_pendingMTNRepository->get_pending_gsm($request->gsm, $request->response);
		if ($pending_gsm != NULL) {
			$pending_gsm->status = $request->status;
			if (in_array($request->status, array_keys($this->response_array))) {
				if (in_array($request->gsm, $this->get_gsms_to_canceled())) {
					$user = 'RAND Insufficient_balance';
					$response_mt = 'تم الغاء طلبك لعدم توفر رصيد لتفعيل مسابقة 90 دقيقة 
يرجى تعبئة رصيد واعادة الاشتراك بارسال " تفعيل " للرقم المجاني 1490';

					if ($this->_subscribersRepository->is_active($request->gsm)) {
						$this->_subscribersRepository->cancel_subscribtion($request->gsm, $user); //$subscriber->canceled_by = $user
						$pending_gsm->cancel_balance_mt = 1;
					}
				} else {
					$response_mt = $this->response_array[$request->status];
				}
				$op_response = $this->_mtnRepository->send_sms($request->gsm, $response_mt);
			}

			if ($this->_pendingMTNRepository->add_other_to_history($pending_gsm, $response_mt, $op_response)) {
				$this->_pendingMTNRepository->delete_pending_relatives($pending_gsm->id);
			}
			return true;
		}
	}

	public function get_gsms_to_canceled()
	{
		$array = [];
		$gsms = DB::select(
			'SELECT (`gsm`) ,Min(created_at) from  pending_history_mtn as s
			 WHERE s.status=3 
			 and DATEDIFF(CURRENT_DATE ,s.created_at) = 2 
			  and (SELECT gsm from subscribers where sub_status=1 AND gsm = s.gsm )
			  GROUP BY gsm'
		);
		foreach ($gsms as $gsm) {
			$array[] = $gsm->gsm;
		}
		return $array;
	}
}
