<?php

namespace App\Services;

use App\Interfaces\PostRepositoryInterface;
use App\Services\UtilsService;

class ProcessService
{

    private PostRepositoryInterface $postRepository;
    private UtilsService $utilsService;

    public function __construct(
        PostRepositoryInterface $postRepository,
        UtilsService $utilsService
    ) {
        $this->postRepository = $postRepository;
        $this->utilsService = $utilsService;
    }

    public function storeApiPosts(array $posts): void
    {

        foreach ($posts as $post) {

            // prepare the data
            $rating = $this->utilsService->calculatePostRating($post);
            
            $data = [
                'id' => $post['id'],
                'user_id' => $post['userId'],
                'title' => $post['title'],
                'body' => $post['body'],
                'rating' => $rating,
            ];

            // insert or update the data
            $this->postRepository->updateBodyOrInsertData($data);
        }
    }
}
