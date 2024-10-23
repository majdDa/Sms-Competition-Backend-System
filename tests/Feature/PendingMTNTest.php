<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Repositories\Domain\PendingMTNRepository;
use Carbon\Carbon;
use App\Models\PendingSmsMTN;

class PendingMTNTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function adding_pending_gsm_request()
    {
        $request = array(
            'gsm' => '9333333333',
            'command' => 'activation',
            'attempt_date' => Carbon::now()
        );


        $repository = new PendingMTNRepository();
        $response = $repository->add_pending_gsm($request, 'A', 'Done');

        if ($response) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function adding_pending_sms_request()
    {
        $request = PendingSmsMTN::create([
            'pending_id' => 1,
            'sc' => 199,
            'sms' => 'hello'
        ]);

        $repository = new PendingMTNRepository();
        $response = $repository->add_pending_sms($request, $request->pending_id);
        $this->assertTrue($response);
    }

    /** @test */
    public function check_if_pending_gsm_exists()
    {
        $repository = new PendingMTNRepository();
        $response = $repository->isExist('9333333333');
        $this->assertTrue($response);
    }

    /** @test */
    public function check_if_pending_gsm_doesnt_exists()
    {
        $repository = new PendingMTNRepository();
        $response = $repository->isExist('9333332233');
        $this->assertTrue(!$response);
    }


    /** @test */
    public function getting_pending_gsm_id()
    {
        $repository = new PendingMTNRepository();
        $response = $repository->get_pending_gsm('9333333333');
        $this->assertTrue(true);
    }

    /** @test */
    public function update_pending_gsm_status()
    {
        $repository = new PendingMTNRepository();
        $response = $repository->update_status('9333333333', 1);
        $this->assertTrue($response);
    }

    /** @test */
    public function adding_another_pending_gsm_request()
    {
        $request = array(
            'gsm' => '9433333333',
            'command' => 'activation',
            'attempt_date' => Carbon::now()
        );


        $repository = new PendingMTNRepository();
        $response = $repository->add_pending_gsm($request, 'A', 'Done');

        if ($response) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function check_deleting_from_pending_sms_and_gsm()
    {
        $repository = new PendingMTNRepository();
        $response = $repository->delete_pending_relatives('9433333333');
        $this->assertTrue($response);
    }
}
