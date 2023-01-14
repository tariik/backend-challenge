<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Interfaces\UserRepositoryInterface;

class UsersApiController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function users()
    {
        $users = [];

        try {
            $users = $this->userRepository->getUsersOrderBYavgRating();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => 'Users not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $users,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }



}

