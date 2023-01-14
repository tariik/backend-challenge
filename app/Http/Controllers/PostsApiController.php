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

    /**
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/get-posts",
     *     operationId="inserApiPosts",
     *     tags={"Migrate posts"},
     *     summary="Insert the first 50 posts from the API into the database
     *     and calculate a rating for each post based on the number of words in 
     *    the title and body",
     * 
     *     @OA\Response(
     *         response=200,
     *         description="successful operation."
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found."
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="internal server error."
     *     )
     * ) 
     */
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

    /**
    * @param  int  $id
    * @return \Illuminate\Http\Response
    * @OA\Get(
    *     path="/api/posts/{id}",
    *     tags={"Get post"},
    *     summary="Get a specific post by ID",
    *     @OA\Parameter(
    *         in="path",
    *         name="id",
    *         required=true,
    *         @OA\Schema(type="string"),
    *         @OA\Examples(example="int", value="1", summary="post id.")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Get a specific post by ID."
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Post not found."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="internal server error."
    *     )
    * ) 
    */
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
     /**
     * @OA\Get(
     *     path="/api/posts/top",
     *     tags={"Top posts"},
     *     summary="Get the best post of each user",
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not Found"),
     * )
     */
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
