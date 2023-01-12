<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\UtilsService;
class UtilsServiceTest extends TestCase
{
    public function test_can_rating_post()
    {
        $utilsService = new UtilsService();

        $post = [
            'title' => 'Test Title',
            'body' => 'Test Body'
        ];

        $expectedPopularity = str_word_count($post['title']) * 2 + str_word_count($post['body']);
        
        $this->assertEquals($expectedPopularity, $utilsService->calculatePostRating($post));
    }

    public function test_can_rating_post_with_empty_title()
    {
        $this->expectException(\InvalidArgumentException::class);

        $utilsService = new UtilsService();

        $post = [
            'title' => '',
            'body' => 'Test Body'
        ];

        $utilsService->calculatePostRating($post);
    }

    public function test_can_rating_post_with_empty_body()
    {
        $this->expectException(\InvalidArgumentException::class);

        $utilsService = new UtilsService();

        $post = [
            'title' => 'Test Title',
            'body' => ''
        ];

        $utilsService->calculatePostRating($post);
    }

    public function test_can_rating_post_with_null_title()
    {
        $this->expectException(\InvalidArgumentException::class);

        $utilsService = new UtilsService();

        $post = [
            'title' => null,
            'body' => 'Test Body'
        ];

        $utilsService->calculatePostRating($post);
    }

    public function test_can_rating_post_with_null_body()
    {
        $this->expectException(\InvalidArgumentException::class);

        $utilsService = new UtilsService();

        $post = [
            'title' => 'Test Title',
            'body' => null
        ];

        $utilsService->calculatePostRating($post);
    }
}
