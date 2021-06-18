<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class ImagesTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_404_if_you_attempt_to_produce_an_image_with_an_empty_string()
    {
        $response = $this->get('/i/');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function it_returns_a_success_response_if_you_attempt_to_produce_an_image_with_a_string()
    {
        $response = $this->get('/i/test');
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_returns_a_success_response_if_you_attempt_to_produce_an_image_with_a_string_starting_with_😆()
    {
        $response = $this->get('/i/😆');
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_returns_a_success_response_if_you_attempt_to_produce_an_image_with_a_string_starting_with_汉()
    {
        $response = $this->get('/i/汉');
        $response->assertStatus(Response::HTTP_OK);
    }
}
