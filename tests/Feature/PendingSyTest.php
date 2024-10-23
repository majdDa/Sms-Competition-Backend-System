<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Repositories\Domain\PendingSyRepository;
use Carbon\Carbon;

class PendingSyTest extends TestCase
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


        $repository = new PendingSyRepository();
        $response = $repository->add_pending_gsm($request, 'A', 'Done');
        $this->assertTrue($response);
    }

    /** @test */
    public function adding_pending_sms_request()
    {
        $request = array(
            'pending_id' => 1,
            'request_id' => '3212',
            'sc' => 199,
            'sms' => 'hello'
        );

        $repository = new PendingSyRepository();
        $response = $repository->add_pending_sms($request, $request->pending_id);
        $this->assertTrue($response);
    }

    /** @test */
    public function check_if_pending_gsm_exists()
    {
        $repository = new PendingSyRepository();
        $response = $repository->isExist('9333333333');
        $this->assertTrue($response);
    }

    /** @test */
    public function check_if_pending_gsm_doesnt_exists()
    {
        $repository = new PendingSyRepository();
        $response = $repository->isExist('9333332233');
        $this->assertTrue(!$response);
    }


    /** @test */
    public function getting_pending_gsm_id()
    {
        $repository = new PendingSyRepository();
        $response = $repository->get_pending_gsm('9333333333');
        $this->assertTrue(true);
    }

    /** @test */
    public function update_pending_gsm_status()
    {
        $repository = new PendingSyRepository();
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


        $repository = new PendingSyRepository();
        $response = $repository->add_pending_gsm($request, 'A', 'Done');
        $this->assertTrue($response);
    }

    /** @test */
    public function check_deleting_from_pending_sms_and_gsm()
    {
        $repository = new PendingSyRepository();
        $response = $repository->delete_pending_relatives('9433333333');
        $this->assertTrue($response);
    }


    /** @test */
    public function get_pending_msgs_from_pendingSmsSy_Table()
    {
        $repository = new PendingSyRepository();
        $response = $repository->get_pending_msgs('9433333333');
        $this->assertTrue($response);
    }

    /** @test */
    public function update_to_processed()
    {
        $repository = new PendingSyRepository();
        $response = $repository->update_to_processed('1995');
        $this->assertTrue($response);
    }

    /** @test */
    public function is_Request_Id_Exist_in_PendingSy_Table()
    {
        $repository = new PendingSyRepository();
        $response = $repository->is_Request_Id_Exist('1995');
        $this->assertTrue($response);
    }
}
