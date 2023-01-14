<?php

namespace App\Interfaces;

interface PostRepositoryInterface 
{
    public function getAllPosts();
    public function getPostById($postId);
    public function deletePost($postId);
    public function createPost(array $postDetails);
    public function updatePost($postId, array $newDetails);
    public function updateBodyOrInsertData(array $postDetails);
    public function getUsersIdsWithPosts();
    public function exists($postId);
    public function getTopPosts();
}