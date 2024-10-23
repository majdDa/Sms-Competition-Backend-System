<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Factories\OperatorServicesFactory;
use Tests\TestCase;
use App\Models\Inbox;
use Carbon\Carbon;

class ActivationTest extends TestCase
{


    protected $syriatel;
    protected $mtn;
    protected $pending_sy;
    protected $pending_mtn;
    protected $inbox;

    public function setUp(): void
    {
        parent::setUp();

        $this->pending_sy = app('App\Interfaces\Domain\IPendingSyRepository');
        $this->pending_mtn = app('App\Interfaces\Domain\IPendingMTNRepository');
        $this->inbox = app('App\Interfaces\Domain\IInboxRepository');
        $this->syriatel = app('App\Interfaces\Application\ISyriatelRepository');
        $this->mtn = app('App\Interfaces\Application\IMTNRepository');
    }


    /** @test */
    public function check_activation_flow()
    {
        /*   $request = array(
            'gsm' => '9333333333',
            'command' => 'activation',
            'short_code' => 199,
            'sms' => 'test',
            'attempt_date' => Carbon::now()
        );
        $repo = new OperatorServicesFactory($this->syriatel, $this->mtn, $this->pending_sy, $this->pending_mtn, $this->inbox);
        $response = $repo->request_activation(2, $request);
        $this->assertTrue($response); */
    }


    /** @test */
    public function check_deactivation_flow()
    {
        /*  $request = array(
            'gsm' => '93354543333',
            'attempt_date' => Carbon::now(),
            'command' => 'deactivation'
        );

        $repo = new OperatorServicesFactory($this->syriatel, $this->mtn, $this->pending_sy, $this->pending_mtn, $this->inbox);
        $inboxes = Inbox::all();
        $response = $repo->request_deactivation(2, $request, $inboxes);
        $this->assertTrue($response); */
    }

    /** @test */
    public function test()
    {
        $response = $this->get('/');
        $response->arrertStatus(200);
    }
}
