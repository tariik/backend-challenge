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

    
    /**
    * Given an array of post, this method will calculate the rating for each post and add the rating to each post.
    *
    * @param array $posts
    *
    * @return array
    */
    public function addRatingToPosts(array $posts): array
    {
        $data = [];
        
        foreach ($posts as $post) {
            
             //calculate the rating for the post
            $rating = $this->utilsService->calculatePostRating($post);
            
            // add the rating to the post
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

    /**
    *
    * Given an array of posts, this method will return an array of user ids that have at least one post   
    *
    * @param array $posts
    *
    * @return array
    */
    public function getUsersIdWithPosts(array $posts): array
    {
        $userIds = [];

        foreach ($posts as $post) {
            //check if the user id is not already in the array of user ids
            if (!in_array($post['userId'], $userIds)) {
                $userIds[] = $post['userId'];
            }
        }

        return $userIds;
    }
}
