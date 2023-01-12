<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ExternalApiClient as ExternalApiClient;

class ExternalApiClientTest extends TestCase
{
 
    /**
     * @return void
     */
    public function test_can_get_posts_from_external_api()
    {
        $externalApiClient = new ExternalApiClient();
        $posts = $externalApiClient->getPosts(2);

        $this->assertCount(2, $posts);
        $this->assertArrayHasKey('id', $posts[0]);
        $this->assertArrayHasKey('title', $posts[0]);
        $this->assertArrayHasKey('body', $posts[0]);
    }


    /**
     * @return void
     */
    public function test_can_get_users_from_external_api()
    {
        $externalApiClient = new ExternalApiClient();
        $users = $externalApiClient->getUsers();

        $this->assertArrayHasKey('id', $users[0]);
        $this->assertArrayHasKey('name', $users[0]);
        $this->assertArrayHasKey('username', $users[0]);
        $this->assertArrayHasKey('email', $users[0]);
        $this->assertArrayHasKey('address', $users[0]);
        $this->assertArrayHasKey('phone', $users[0]);
        $this->assertArrayHasKey('website', $users[0]);
        $this->assertArrayHasKey('company', $users[0]);
    }

}
