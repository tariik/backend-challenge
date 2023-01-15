<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\UtilsService;
class UtilsServiceTest extends TestCase
{
    public function test_can_rating_post()
    {
        //Arrange
        $utilsService = new UtilsService();

        $post = [
            'title' => 'Test Title',
            'body' => 'Test Body'
        ];
        //Act
        $expectedPopularity = str_word_count($post['title']) * 2 + str_word_count($post['body']);
        
        //Assert
        $this->assertEquals($expectedPopularity, $utilsService->calculatePostRating($post));
    }

    public function test_can_rating_post_with_empty_title()
    {
        //Arrange
        $this->expectException(\InvalidArgumentException::class);

        $utilsService = new UtilsService();

        $post = [
            'title' => '',
            'body' => 'Test Body'
        ];
        //Assert
        $utilsService->calculatePostRating($post);
    }

    public function test_can_rating_post_with_empty_body()
    {
        //Arrange
        $this->expectException(\InvalidArgumentException::class);

        $utilsService = new UtilsService();

        $post = [
            'title' => 'Test Title',
            'body' => ''
        ];

        //Assert
        $utilsService->calculatePostRating($post);
    }

    public function test_can_rating_post_with_null_title()
    {
        //Arrange
        $this->expectException(\InvalidArgumentException::class);

        $utilsService = new UtilsService();

        $post = [
            'title' => null,
            'body' => 'Test Body'
        ];

        //Assert
        $utilsService->calculatePostRating($post);
    }

    public function test_can_rating_post_with_null_body()
    {
        //Arrange
        $this->expectException(\InvalidArgumentException::class);

        $utilsService = new UtilsService();

        $post = [
            'title' => 'Test Title',
            'body' => null
        ];

        //Assert
        $utilsService->calculatePostRating($post);
    }
}
