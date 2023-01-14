<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Interfaces\PostRepositoryInterface;
use App\Services\ExternalApiClient;
use App\Services\ProcessService;


class PostsApiController extends Controller
{
    
    private PostRepositoryInterface $postRepository;
    private ExternalApiClient $externalApiClient;
    private ProcessService $processService;

    public function __construct(
        PostRepositoryInterface $postRepository,
        ExternalApiClient $externalApiClient,
        ProcessService $processService
    ) 
    {
        $this->postRepository = $postRepository;
        $this->externalApiClient = $externalApiClient;
        $this->processService = $processService;
    }


    public function inserApiPosts()
    {
        $apiPosts = $this->externalApiClient->getPosts(50);
        $posts = $this->processService->addRatingToPosts($apiPosts);
        
        foreach ($posts as $post) {
            $this->postRepository->updateBodyOrInsertData($post);
        }

        try {
            $posts =$this->postRepository->getAllPosts();
        } 
        catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $apiUsers = $this->externalApiClient->getUsers();
        $userIds = $this->processService->getUsersIdWithPosts($apiPosts);

        foreach ($apiUsers as $apiUser) {
            
            if (in_array($apiUser['id'], $userIds)) {
                $user = [
                    'id' => $apiUser['id'],
                    'name' => $apiUser['name'],
                    'email' => $apiUser['email'],
                    'city' => $apiUser['address']['city'],
                ]; 

                dd($user);
                $userIds[] = $user['id'];
            }
        }
        return response()->json([
            'data' => $posts,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }
}