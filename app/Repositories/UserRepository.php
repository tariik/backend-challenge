<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{

    /**
     * Get all users from the database
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * Delete user by its id
     *
     * @param int $userId
     * @return int
     */
    public function getUserById($userId)
    {
        return User::findOrFail($userId);
    }

    
    /**
     * Delete user by its id
     *
     * @param int $userId
     * @return int
     */
    public function deleteUser($userId)
    {
        User::destroy($userId);
    }

    /**
     * Create a new user
     *
     * @param array $userDetails
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function createUser(array $userDetails)
    {
        return User::create($userDetails);
    }

   /**
     * Update user by its id
     *
     * @param int $userId
     * @param array $newDetails
     * @return int
     */
    public function updateUser($userId, array $newDetails)
    {
        return User::whereId($userId)->update($newDetails);
    }

    /**
     * Check if user exists
     *
     * @param int $userId
     * @return bool
     */
    public function exists($userId)
    {
        return  User::where('id', $userId)->exists();
    }

    /**
     * Create a new user if not exist
     *
     * @param array $userDetails
     * @return void
     */
    public function insertIfNotExist(array $userDetails)
    {
        if (!$this->exists($userDetails['id'])) {
            User::create($userDetails);
        }
    }

    /**
     * Retrive all users with their average rating of all posts
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUsersOrderBYavgRating() 
    {
        $users = User::with(
            [
               'posts' => function($query) {
                    $query->select('id', 'user_id', 'body', 'title', 'rating');
                }
            ]
        )
        ->select('id', 'name', 'email', 'city')
        ->withCount(
            [
                'posts' => function($query) {
                    $query->select(DB::raw('AVG(rating)'));
                }
            ]
        )
        ->orderBy('posts_count', 'desc')
        ->get();
    
        return $users;
    }
}
