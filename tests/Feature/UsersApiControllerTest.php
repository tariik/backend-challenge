<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersApiControllerTest extends TestCase
{
    /**
     * Test that the API returns a list of users with posts.
     *
     * @return void
     */
    public function test_users()
    {
        //Act
        $response = $this->get('/api/users');

        //Assert
        $response->assertStatus(200);       
    }
}
