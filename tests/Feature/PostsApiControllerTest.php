<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;

class PostsApiControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $userRepository;
    private $postRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postRepository = new PostRepository();
        $this->userRepository = new UserRepository();
    }

    
    public function testShowReturnsCorrectData()
    {
        // Arrange
        $postData = [
            'id' => $this->faker->randomNumber(),
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'user_id' => 1,
            'rating' => $this->faker->randomDigit,
        ];

        $userDetails = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'city' => 'New York',
        ];

        $this->userRepository->insertIfNotExist($userDetails);
        $post = $this->postRepository->createPost($postData);

        $expectedData = [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'username' => 'John Doe',
        ];

        // Act
        $response = $this->get("/api/post/{$post->id}");

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['data' => $expectedData]);
    }

    public function testShowReturns404ForInvalidId()
    {
        // Arrange
        // use a random id that is not in the database
        $invalidId = 999;

        // Act
        $response = $this->get("/api/post/{$invalidId}");

        // Assert
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Post not found']);
    }
}
