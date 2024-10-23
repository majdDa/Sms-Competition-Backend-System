<?php

namespace Tests\Feature;

use App\Interfaces\Application\IAnalysisRepository;
use App\Interfaces\Domain\ICommandRepository;
use App\Interfaces\Domain\IInboxRepository;
use App\Interfaces\Domain\IKeywordsRepository;
use App\Interfaces\Domain\IPendingSyRepository;
use App\Interfaces\Domain\IPendingMTNRepository;
use  App\Repositories\Application\AnalysisRepository;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;


class CommandTest extends TestCase{

    protected $repo;

    protected $inbox;
    protected $command;
    protected $keyword;
    protected $flow;

    private $commandCategories = ['keyword' => 0, 'question' => 1, 'balance' => 2, 'help' => 3, 'deactivation' => 4, 'question_option1' => 5, 'question_option2' => 6];

    public function setUp(): void
    {
        parent::setUp();


        $this->inbox = app('App\Interfaces\Domain\IInboxRepository');
        $this->command = app('App\Interfaces\Domain\ICommandRepository');
        $this->keyword = app('App\Interfaces\Domain\IKeywordsRepository');
        $this->flow = app('App\Interfaces\Application\IFlowRepository');

        $this->repo = new AnalysisRepository($this->inbox, $this->command,$this->keyword,$this->flow );
    }

    // Success usecases
    public function test_success_usecase_sms_category_keyword()
    {
        $response = $this->repo->checkSms('فوز');
        $this -> assertEquals($this -> commandCategories['keyword'], $response -> _categoryId );
    }

    public function test_success_usecase_sms_category_question_option1()
    {
        $response = $this->repo->checkSms('1');
        $this -> assertEquals($this -> commandCategories['question_option1'], $response -> _categoryId );
    }

    public function test_success_usecase_sms_category_question_option2()
    {
        $response = $this->repo->checkSms('2');
        $this -> assertEquals($this -> commandCategories['question_option2'], $response -> _categoryId );
    }

    public function test_success_usecase_sms_category_balance()
    {
        $response = $this->repo->checkSms('رصيد');
        $this -> assertEquals($this -> commandCategories['balance'], $response -> _categoryId );
    }

    public function test_success_usecase_sms_category_help()
    {
        $response = $this->repo->checkSms('help');
        $this -> assertEquals($this -> commandCategories['help'], $response -> _categoryId );
    }

    public function test_success_usecase_sms_category_deactivation()
    {
        $response = $this->repo->checkSms('cancel');
        $this -> assertEquals($this -> commandCategories['deactivation'], $response -> _categoryId );
    }

    // Failure usecases
    public function test_failure_usecase_sms_category_keyword()
    {
        $response = $this->repo->checkSms('ربح');
        $this -> assertNotEquals($this -> commandCategories['keyword'], $response -> _categoryId );
    }

    public function test_failure_usecase_sms_category_question_option1()
    {
        $response = $this->repo->checkSms('2');
        $this -> assertNotEquals($this -> commandCategories['question_option1'], $response -> _categoryId );
    }

    public function test_failure_usecase_sms_category_question_option2()
    {
        $response = $this->repo->checkSms('1');
        $this -> assertNotEquals($this -> commandCategories['question_option2'], $response -> _categoryId );
    }

    public function test_failure_usecase_sms_category_balance()
    {
        $response = $this->repo->checkSms('دددرصيد');
        $this -> assertNotEquals($this -> commandCategories['balance'], $response -> _categoryId );
    }

    public function test_failure_usecase_sms_category_help()
    {
        $response = $this->repo->checkSms('helppp');
        $this -> assertNotEquals($this -> commandCategories['help'], $response -> _categoryId );
    }

    public function test_failure_usecase_sms_category_deactivation()
    {
        $response = $this->repo->checkSms('cancellll');
        $this -> assertNotEquals($this -> commandCategories['deactivation'], $response -> _categoryId );
    }
}
