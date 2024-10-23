<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Repositories\Domain\KeywordsRepository;
use Tests\TestCase;

class KeywordsTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->repo = new KeywordsRepository();
    }

    /** @test */
    public function adding_new_keyword()
    {
        $response = $this->repo->add_keyword('hello',50000,'balance');
        $this->assertTrue($response);
    }


    /** @test */
    public function check_if_keyword_exists()
    {
        $response = $this->repo->isExist('hello');
        $this->assertTrue($response);
    }

    /** @test */
    public function check_if_keyword_doesnt_exists()
    {
        $response = $this->repo->isExist('hi');
        $this->assertTrue(!$response);
    }


    /** @test */
    public function getting_keyword_attributes()
    {
        $response = $this->repo->get_keyword_data('hello');
        $this->assertTrue(true);
    }


    /** @test */
    public function deactivating_keyword()
    {
        $response = $this->repo->deactivate_keyword('hello');
        $this->assertTrue($response);
    }


    /** @test */
    public function re_activating_keyword()
    {
        $response = $this->repo->activate_keyword('hello');
        $this->assertTrue($response);
    }
}
