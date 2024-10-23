<?php

namespace App\Repositories\Application;

use App\Interfaces\Application\IAnalysisRepository;
use App\Interfaces\Application\IFlowRepository;
use App\Interfaces\Domain\ICommandRepository;
use App\Interfaces\Domain\IInboxRepository;
use App\Interfaces\Domain\IKeywordsRepository;
use App\Models\SmsCategory;

class AnalysisRepository implements IAnalysisRepository
{


    private $commandCategories = ['keyword' => 0, 'question' => 1, 'balance' => 2, 'help' => 3, 'deactivation' => 4, 'question_option1' => 5, 'question_option2' => 6];

    private $shortCodes = ['free' => 1490, 'paid' => 1890];

    private $_iInboxRepository;
    private $_iCommandRepository;
    private $_iKeywordsRepository;
    protected $_iFlowRepository;

    public function __construct(IInboxRepository $iInboxRepository, ICommandRepository $iCommandRepository, IKeywordsRepository $iKeywordsRepository, IFlowRepository $iFlowRepository)
    {
        $this->_iInboxRepository = $iInboxRepository;
        $this->_iCommandRepository = $iCommandRepository;
        $this->_iKeywordsRepository = $iKeywordsRepository;
        $this->_iFlowRepository = $iFlowRepository;
    }

    public function paidFlow($commandCategory, $unProcessSms)
    {

        switch ($commandCategory->_categoryId) {
            case $this->commandCategories['keyword']:
                //  go to keyword flow
                return $this->_iFlowRepository->keywordFlow($unProcessSms, $commandCategory->_id);
                break;
            case $this->commandCategories['question_option1']:
                //  go to question flow
                return $this->_iFlowRepository->questionFlow($unProcessSms, 1);
                break;
            case $this->commandCategories['question_option2']:
                //  go to question flow
                return $this->_iFlowRepository->questionFlow($unProcessSms, 2);
                break;
            case $this->commandCategories['balance']:
                //  go to balance flow
                return $this->_iFlowRepository->balanceFlow($unProcessSms, $commandCategory->_id);
                break;
            case $this->commandCategories['help']:
                //  go to help flow
                return $this->_iFlowRepository->helpFlow($unProcessSms, $commandCategory->_id);
                break;
            case $this->commandCategories['deactivation']:
                //  go to deactivation flow
                return $this->_iFlowRepository->deactivationFlow($unProcessSms, $commandCategory->_id);
                break;
            default:
                //  go to invalid flow
                return $this->_iFlowRepository->invalidFlow($unProcessSms);
                break;
        }
    }

    public function freeFlow($commandCategory, $unProcessSms)
    {
        switch ($commandCategory->_categoryId) {
            case $this->commandCategories['balance']:
                //  go to balance flow
                return $this->_iFlowRepository->balanceFlow($unProcessSms, $commandCategory->_id);
                break;
            case $this->commandCategories['deactivation']:
                //  go to deactivation flow
                return $this->_iFlowRepository->deactivationFlow($unProcessSms, $commandCategory->_id);
                break;
            default:
                //  go to help flow
                return $this->_iFlowRepository->helpFlow($unProcessSms, 49);
                break;
        }
    }


    public function checkSMS(string $inboxSms): SmsCategory
    {
        $commandCategory = $this->_iCommandRepository->getCommandByName($inboxSms);
        if ($commandCategory->category_id == 404) {
            if ($this->_iKeywordsRepository->isExist($inboxSms)) {
                return new SmsCategory($this->commandCategories['keyword'], $this->_iKeywordsRepository->get_keyword_id($inboxSms));
            } else {
                return new SmsCategory('-1', '-1');
            }
        }
        return new SmsCategory($commandCategory->category_id, $commandCategory->id);
    }

    public function goToFlowBasedOnSms()
    {
        $unProcessSms = $this->_iInboxRepository->getUnProcessSms();
        if (is_object($unProcessSms)) {
            $inboxSms = $unProcessSms->sms;
            $inboxShortCode = $unProcessSms->short_code;
            $commandCategory =  $this->checkSMS($inboxSms);
            if ($inboxShortCode == $this->shortCodes['paid']) {
                $this->paidFlow($commandCategory, $unProcessSms);
            } else {
                $this->freeFlow($commandCategory, $unProcessSms);
            }
        }
    }

    public function allGoToFlowBasedOnSms()
    {
        /*         $messages = $this->_iInboxRepository->getAllUnProcessSms();
        //$unProcessSms = $this->_iInboxRepository->getUnProcessSms();
        //dd($unProcessSms);
        foreach ($messages as $message) {
            if (is_object($message)) {
                $inboxSms = $message->sms;
                $inboxShortCode = $message->short_code;
                $commandCategory =  $this->checkSMS($inboxSms);
                if ($inboxShortCode == $this->shortCodes['paid']) {
                    $this->paidFlow($commandCategory, $message);
                } else {
                    $this->freeFlow($commandCategory, $message);
                }
            }
        } */
    }
}
