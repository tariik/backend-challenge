<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{

    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($userId)
    {
        return User::findOrFail($userId);
    }

    public function deleteUser($userId)
    {
        User::destroy($userId);
    }

    public function createUser(array $userDetails)
    {
        return User::create($userDetails);
    }

    public function updateUser($userId, array $newDetails)
    {
        return User::whereId($userId)->update($newDetails);
    }

    public function exists($userId)
    {
        return  User::where('id', $userId)->exists();
    }

    public function insertIfNotExist(array $userDetails)
    {
        if (!$this->exists($userDetails['id'])) {
            User::create($userDetails);
        }
    }

    public function getUsersOrderBYavgRating() {
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
