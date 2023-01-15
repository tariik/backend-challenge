<?php

namespace Tests\Unit;

use App\Services\ProcessService;
use App\Services\UtilsService;
use Tests\TestCase;

class ProcessServiceTest extends TestCase
{
    private ProcessService $processService;
    private UtilsService $utilsService;

    public function setUp(): void
    {
        $this->utilsService =new UtilsService();
        $this->processService = new ProcessService($this->utilsService);
    }

    public function testAddRatingToPosts()
    {
        //Arrange
        $posts = [
            [
                'id' => 1,
                'userId' => 1,
                'title' => 'Test Title 1',
                'body' => 'Test Body 1',
            ],
            [
                'id' => 2,
                'userId' => 2,
                'title' => 'Test Title 2',
                'body' => 'Test Body Body 2',
            ],
        ];

        $expected = [
            [
                'id' => 1,
                'user_id' => 1,
                'title' => 'Test Title 1',
                'body' => 'Test Body 1',
                'rating' => 6,
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'title' => 'Test Title 2',
                'body' => 'Test Body Body 2',
                'rating' => 7,
            ],
        ];

        //Act
        $result = $this->processService->addRatingToPosts($posts);

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function testGetUsersIdWithPosts()
    {
         //Arrange
        $posts = [
            [
                'id' => 1,
                'userId' => 1,
                'title' => 'Test Title 1',
                'body' => 'Test Body 1',
            ],
            [
                'id' => 2,
                'userId' => 2,
                'title' => 'Test Title 2',
                'body' => 'Test Body 2',
            ],
            [
                'id' => 3,
                'userId' => 1,
                'title' => 'Test Title 3',
                'body' => 'Test Body 3',
            ],
        ];

        $expected = [1, 2];

        //Act
        $result = $this->processService->getUsersIdWithPosts($posts);

        // Assert
        $this->assertEquals($expected, $result);
    }
}