<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function getAllPosts()
    {
        return Post::all();
    }

    public function getPostById($postId)
    {
        return Post::findOrFail($postId);
    }

    public function deletePost($postId)
    {
        Post::destroy($postId);
    }

    public function createPost(array $postDetails)
    {
        return Post::create($postDetails);
    }

    public function updatePost($postId, array $newDetails)
    {
        return Post::whereId($postId)->update($newDetails);
    }

    public function getUsersIdsWithPosts()
    {
        return  Post::select('uesr_id')->distinct()->get();
    }

    public function exists($postId)
    {
        return  Post::where('id', $postId)->exists();
    }

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
