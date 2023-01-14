<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Faker\Factory as Faker;



class PostRepositoryTest extends TestCase
{

    use RefreshDatabase, WithFaker;
    

    /** @var PostRepository */
    private $postRepository;

   
    public function setUp(): void
    {
        parent::setUp();
        $this->postRepository = new PostRepository();
        // create some test posts
        Post::factory()->count(5)->create();
    }

    public function test_can_get_all_posts()
    {
        $posts = $this->postRepository->getAllPosts();
        $this->assertCount(5, $posts);
    }

    public function test_can_delete_post()
    {
        $this->postRepository->deletePost(1);
        $this->assertDatabaseMissing('posts', ['id' => 1]);
    }

    public function test_can_create_post()
    {
        $postData = [
            'id' => $this->faker->randomNumber(),
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'user_id' => $this->faker->randomDigit,
            'rating' => $this->faker->randomDigit,
        ];
    
        $post = $this->postRepository->createPost($postData);
    
        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($postData['title'], $post->title);
        $this->assertEquals($postData['body'], $post->body);
        $this->assertEquals($postData['user_id'], $post->user_id);
        $this->assertEquals($postData['rating'], $post->rating);
        $this->assertNotNull($post->id);

    }

    public function test_can_update_body_or_insert_post()
    {
        $id = $this->faker->randomDigit;
        $title = $this->faker->sentence;
        $user_id = $this->faker->randomDigit;
        $rating = $this->faker->randomDigit;
        $body = $this->faker->paragraph;
        $bodyModified = $this->faker->paragraph;

        $postData1 = [
            'id' => $id,
            'title' => $title,
            'body' => $body,
            'user_id' => $user_id ,
            'rating' => $rating ,
        ];

        $postData2 = [
            'id' => $id,
            'title' => $title,
            'body' => $bodyModified,
            'user_id' => $user_id,
            'rating' => $rating,
        ];


        $this->postRepository->updateBodyOrInsertData($postData1);
        $post =  $this->postRepository->getPostById($id);

        
        $this->assertEquals($postData1['title'], $post->title);
        $this->assertEquals($postData1['body'], $post->body);
        $this->assertEquals($postData1['user_id'], $post->user_id);
        $this->assertEquals($postData1['rating'], $post->rating);

        $this->postRepository->updateBodyOrInsertData($postData2);
        $post =  $this->postRepository->getPostById($id);

        $this->assertNotEquals($postData1['body'], $post->body);
        $this->assertEquals($postData2['body'], $post->body);

    }

    
}
