<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    /**
     * Get all posts from the database
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPosts()
    {
        return Post::all();
    }

    /**
     * Get a post by its id
     *
     * @param int $postId
     * @return \App\Models\Post
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getPostById($postId)
    {
        return Post::findOrFail($postId);
    }

    /**
     * Delete a post by its id
     *
     * @param int $postId
     * @return void
     */
    public function deletePost($postId)
    {
        Post::destroy($postId);
    }
    
    /**
     * Create a new post
     *
     * @param array $postDetails
     * @return \App\Models\Post
     */
    public function createPost(array $postDetails)
    {
        return Post::create($postDetails);
    }

    /**
     * Update an existing post
     *
     * @param int $postId
     * @param array $newDetails
     * @return int
     */
    public function updatePost($postId, array $newDetails)
    {
        return Post::whereId($postId)->update($newDetails);
    }

    /**
     * Get all distinct user ids with posts
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUsersIdsWithPosts()
    {
        return  Post::select('uesr_id')->distinct()->get();
    }

    /**
     * Check if a post with a specific id exists
     *
     * @param int $postId
     * @return boolean
     */
    public function exists($postId)
    {
        return  Post::where('id', $postId)->exists();
    }

    
    /**
     * Update the body of an existing post or insert a new one
     *
     * @param array $postDetails
     * @return void
     */
    public function updateBodyOrInsertData(array $postDetails)
    {
        if ($this->exists($postDetails['id'])) {
            // update rating here?
           $this->updatePost
            (
                $postDetails['id'], 
                [
                    'body' => $postDetails['body'],
                    'rating' => $postDetails['rating']
                ]
            );
        } else {
            $this->createPost(
                [
                    'id' => $postDetails['id'],
                    'user_id' => $postDetails['user_id'],
                    'title' => $postDetails['title'],
                    'body' => $postDetails['body'],
                    'rating' => $postDetails['rating']
                ]
            );
        }
    }

    /**
     * Retrieve the top rated posts by user
     *
     * @return Collection
     */
    public function getTopPosts()
    {
        $topPosts = Post::with('user')->orderBy('rating', 'desc')->get()
            ->groupBy('user_id')
            ->map(function($posts) {
                return $posts->first();
            });
        
        return $topPosts;
    }
}
