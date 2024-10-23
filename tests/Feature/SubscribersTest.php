<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Repositories\Domain\SubscribersRepository;

class SubscribersTest extends TestCase
{

    use RefreshDatabase;



    /** @test */
    public function adding_new_subscriber()
    {
        $repository = new SubscribersRepository();
        $response = $repository->add_subscriber('9421424243', 1, 199, 1480, 'test');
        $this->assertTrue($response);
    }

    /** @test */
    public function check_if_subscriber_exists()
    {
        $repository = new SubscribersRepository();
        $response = $repository->isExist('9421424243');
        $this->assertTrue($response);
    }

    /** @test */
    public function check_if_subscriber_doesnt_exists()
    {
        $repository = new SubscribersRepository();
        $response = $repository->isExist('9421324243');
        $this->assertTrue(!$response);
    }

    /** @test */
    public function check_if_is_subscribed()
    {
        $repository = new SubscribersRepository();
        $response = $repository->check_status('9421424243');
        $this->assertTrue($response);
    }


    /** @test */
    public function cancel_subscribtion()
    {
        $repository = new SubscribersRepository();
        $response = $repository->cancel_subscribtion('9421424243', 'user');
        $this->assertTrue(true);
    }

    /** @test */
    public function renew_subscribtion()
    {
        $repository = new SubscribersRepository();
        $response = $repository->renew_subscribtion('9421424243', 'operator');
        $this->assertTrue(true);
    }
}
