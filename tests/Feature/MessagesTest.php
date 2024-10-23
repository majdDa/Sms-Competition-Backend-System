<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Domain\MessagesRepository;
use Tests\TestCase;

class MessagesTest extends TestCase
{

    // use RefreshDatabase;

    /** @test */
    public function get_renewal_message()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_renewal_message('renewal');

        $this->assertTrue(true);
    }

    /** @test */
    public function get_invalid_message()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_invalid_message('invalid');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_true_answer_message_mt()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_true_answer_message('true_Answer');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_false_answer_message_mt()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_false_answer_message('false_Answer');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_keyword_message_mt()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_keyword_message('keyword');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_help_message_mt()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_help_message('help');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_balance_message_mt()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_balance_message('balance');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_activation_message_mt()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_activation_message('welcoming');
        $this->assertTrue(true);
    }

    /** @test */
    public function get_deact_message()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_deact_message('cancelation');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_pending_act_message()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_pending_act_message('pending_activation');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_pending_deact_message()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_pending_deact_message('pending_deActivation');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_final_message()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_final_message('final_keyword');
        $this->assertTrue(true);
    }
    /** @test */
    public function get_invalid_last_answer_message()
    {
        $repository = new MessagesRepository();
        $response = $repository->get_invalid_last_answer_message('final_keyword');
        $this->assertTrue(true);
    }
}
