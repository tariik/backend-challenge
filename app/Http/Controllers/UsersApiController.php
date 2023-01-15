<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Interfaces\UserRepositoryInterface;

class UsersApiController extends ApiController
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users from the database and order them by their average rating of their posts
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Get users with their posts ordered by average rating.",
     *     @OA\Response(
     *          response="200", 
     *          description="List of users"
     *      ),
     *     @OA\Response(
     *         response="404",
     *         description="Users not found."
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="internal server error."
     *     )
     * )
     */
    public function users()
    {
        $users = [];

        // Try to retrieve the users from the database by avg Rating
        try {
            $users = $this->userRepository->getUsersOrderBYavgRating();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => 'Users not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        // Return a JSON response with the retrieved user data and a success message
        return response()->json([
            'data' => $users,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }
}

