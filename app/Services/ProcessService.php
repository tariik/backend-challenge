<?php

namespace App\Services;

use App\Services\UtilsService;

class ProcessService
{

    private UtilsService $utilsService;

    public function __construct(
        UtilsService $utilsService
    ) {
        $this->utilsService = $utilsService;
    }

    public function addRatingToPosts(array $posts): array
    {
        $data = [];
        
        foreach ($posts as $post) {
            
            $rating = $this->utilsService->calculatePostRating($post);
           
            $data[] = [
                'id' => $post['id'],
                'user_id' => $post['userId'],
                'title' => $post['title'],
                'body' => $post['body'],
                'rating' => $rating,
            ];   
        }

        return  $data;
    }

    public function getUsersIdWithPosts($posts): array
    {
        $userIds = [];

        foreach ($posts as $post) {
            if (!in_array($post['userId'], $userIds)) {
                $userIds[] = $post['userId'];
            }
        }

        return $userIds;
    }
}
