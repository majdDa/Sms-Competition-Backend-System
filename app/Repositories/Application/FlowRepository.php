<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\IFlowRepository;
use App\Interfaces\Application\ISendMtRepository;
use App\Interfaces\Domain\IInboxRepository;
use App\Interfaces\Domain\IKeywordsRepository;
use App\Interfaces\Domain\ISubscribersRepository;
use App\Interfaces\Domain\IQuestionsRepository;
use App\Services\Interfaces\IOperatorServices;


class FlowRepository implements IFlowRepository
{
    //get_current_question
    private $_inboxRepository;
    private $_sendingMtRepository;
    private $_keywordsRepository;
    private $_subscribersRepository;
    private $_operatorServicesFactory;
    private $_questionsRepository;

    public function __construct(IQuestionsRepository $questionsRepository, IInboxRepository $inboxRepository, ISendMtRepository $sendingMtRepository, IKeywordsRepository $keywordsRepository, ISubscribersRepository $subscribersRepository, IOperatorServices $operatorServicesFactory)
    {
        $this->_inboxRepository = $inboxRepository;
        $this->_sendingMtRepository = $sendingMtRepository;
        $this->_keywordsRepository = $keywordsRepository;
        $this->_subscribersRepository = $subscribersRepository;
        $this->_operatorServicesFactory = $operatorServicesFactory;
        $this->_questionsRepository = $questionsRepository;
    }



    public function balanceFlow($inbox, $command_id)
    {
        $score = $inbox->subscriber->score;
        $response = $this->_sendingMtRepository->send_balance_mt($inbox->subscriber->gsm, $score, $inbox->operator);
        $response_mt_response = $response->op_response;
        $this->_inboxRepository->update_balance_type($inbox->id, $response->mt, $command_id, $response_mt_response);
    }




    public function helpFlow($inbox, $command_id)
    {
        $response = $this->_sendingMtRepository->send_help_mt($inbox->subscriber->gsm, $inbox->operator);
        $response_mt_response = $response->op_response;
        $this->_inboxRepository->update_help_type($inbox->id, $response->mt, $command_id, $response_mt_response);
    }



    public function questionFlow($inbox, $option) //option(1=صح or 2=خطأ)
    {
        //get subscriber
        $subscriber = $inbox->subscriber;
        //complete all the questions
        if ($inbox->subscriber->last_answer == 1) {
            $response = $this->_sendingMtRepository->send_invalid_last_answer_mt($subscriber->gsm, $subscriber->operator);
            $added_points = 400;
            $response_mt_response = $response->op_response;
            $this->_subscribersRepository->update_points($subscriber->id, $added_points);
            $this->_inboxRepository->update_invalid_last_Answer_type($inbox->id, $response->mt, $added_points, $response_mt_response);
            exit;
        }

        $question = $this->_questionsRepository->get_current_question($inbox->subscriber->question_order);
        if ($option == $question->answer) { #true answer
            if ($inbox->subscriber->question_order == 2) {
                $added_points  = $question->points;
                $response = $this->_sendingMtRepository->send_final_keyword($subscriber->gsm, $subscriber->operator);
                $this->_subscribersRepository->update_points($subscriber->id, $added_points);
                $this->_subscribersRepository->update_last_answer($subscriber->id);
                $response_mt_response = $response->op_response;
                $this->_inboxRepository->update_question_attribute($inbox->id, $question->id, $response->mt, $added_points, $response_mt_response);
            } elseif ($inbox->subscriber->question_order == 1) {
                $added_points  = $question->points;
                $question_order = $subscriber->question_order + 1;
                $response = $this->_sendingMtRepository->send_next_question($subscriber->gsm, $subscriber->question_order, $added_points, $subscriber->score, $subscriber->operator);
                $this->_subscribersRepository->update_points($subscriber->id, $added_points);
                $this->_subscribersRepository->update_question_order($subscriber->id, $question_order);
                $response_mt_response = $response->op_response;
                $this->_inboxRepository->update_question_attribute($inbox->id, $question->id, $response->mt, $added_points, $response->op_response);
            }
        } else { #false answer

            $added_points  = $question->points / 2;
            // dd($question->order);
            $response = $this->_sendingMtRepository->send_false_answer($subscriber->gsm, $question->order,  $added_points, $subscriber->score, $inbox->operator);
            $this->_subscribersRepository->update_points($subscriber->id, $added_points);
            $response_mt_response = $response->op_response;

            $this->_inboxRepository->update_question_attribute($inbox->id, $question->id, $response->mt, $added_points, $response->op_response);
        }
    }



    public function keywordFlow($inbox, $keyword_id)
    {

        $keyword = $this->_keywordsRepository->get_keyword_data($inbox->sms);
        $this->_subscribersRepository->update_points($inbox->subscriber_id, $keyword->points);
        //mt & operator
        $response = $this->_sendingMtRepository->send_keyword_mt($inbox->subscriber->gsm, $keyword->points, $inbox->subscriber->score, $keyword->name, $inbox->operator);
        $response_mt_response = $response->op_response;
        $this->_inboxRepository->update_keyword_attribute($inbox->id, $keyword->id, $response->mt, $keyword->points, $response_mt_response);
    }




    public function deactivationFlow($inbox, $command_id)
    {
        $messages = $this->_inboxRepository->get_unprocessed_messages_for_gsm($inbox->id, $inbox->subscriber_id);

        $inbox->gsm = $inbox->subscriber->gsm;

        $pending_id = $this->_operatorServicesFactory->request_deactivation($inbox->operator, $inbox, $messages, $inbox->subscriber);

        $response = $this->_sendingMtRepository->send_pending_de_activation_mt($inbox->subscriber->gsm, $inbox->operator);
        // $response_mt_response = $response->op_response;
        $response_mt_response = '';
        $response->mt = ''; //was not exist 
        $this->_inboxRepository->delete_inboxes($messages);
        $this->_inboxRepository->update_deactivation_type($inbox->id, $pending_id, $response->mt, $command_id, $inbox->operator, $response_mt_response);
    }



    public function invalidFlow($inbox)
    {
        $subscriber = $inbox->subscriber;
        $added_points = 200;
        $response = $this->_sendingMtRepository->send_invalid_answer($subscriber->gsm, $added_points, $subscriber->operator);
        $this->_subscribersRepository->update_points($subscriber->id, $added_points);
        $response_mt_response = $response->op_response;

        $this->_inboxRepository->update_invalid_type($inbox->id, $response->mt, $added_points, $response_mt_response);
    }
}
