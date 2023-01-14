<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Interfaces\PostRepositoryInterface;
use App\Services\ExternalApiClient;
use App\Services\ProcessService;
use App\Interfaces\UserRepositoryInterface;


class PostsApiController extends Controller
{

    private PostRepositoryInterface $postRepository;
    private ExternalApiClient $externalApiClient;
    private ProcessService $processService;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
        ExternalApiClient $externalApiClient,
        ProcessService $processService,
        UserRepositoryInterface $userRepository
    ) {
        $this->postRepository = $postRepository;
        $this->externalApiClient = $externalApiClient;
        $this->processService = $processService;
        $this->userRepository = $userRepository;
    }

    public function inserApiPosts()
    {
        $apiPosts = $this->externalApiClient->getPosts(50);
        $posts = $this->processService->addRatingToPosts($apiPosts);

        foreach ($posts as $post) {
            $this->postRepository->updateBodyOrInsertData($post);
        }

        try {
            $posts = $this->postRepository->getAllPosts();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
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

                $this->userRepository->insertIfNotExist($user);
            }
        }

        $users = $this->userRepository->getAllUsers();

        return response()->json([
            'data' => $users,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }

    public function show($id)
    {
        try {
            $post = $this->postRepository->getPostById($id);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => 'Post not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $postData = [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'username' => $post->user->name,
        ];

        return response()->json([
            'data' => $postData,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }

    public function top()
    {
        $postData = [];

        try {
            $posts = $this->postRepository->getTopPosts();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => 'Posts not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        foreach ($posts as $post) {

            $postData[] = [
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body,
                'rating' => $post->rating,
                'username' => $post->user->name,
            ];
        }

        return response()->json([
            'data' => $postData,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }
}
