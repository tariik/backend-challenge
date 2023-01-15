<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;


class UserRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    public function test_can_not_insert_if_user_exist()
    {
        //Arrange
        // Create a new user
        $userDetails = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'city' => 'New York',
        ];

        //Act
        // Insert the user into the database
        $this->userRepository->insertIfNotExist($userDetails);

        //Assert
        // Check that the user was inserted into the database
        $this->assertDatabaseHas('users', $userDetails);

        // Try to insert the same user again
        $this->userRepository->insertIfNotExist($userDetails);

        // Check that the user was not inserted again
        $this->assertEquals(1, User::where($userDetails)->count());
    }
}