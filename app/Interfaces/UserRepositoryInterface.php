<?php

namespace App\Interfaces;

interface UserRepositoryInterface 
{
    public function getAllUsers();
    public function getUserById($userId);
    public function deleteUser($userId);
    public function createUser(array $userDetails);
    public function updateUser($userId, array $newDetails);
    public function exists($userId);
    public function insertIfNotExist(array $userDetails);
    public function getUsersOrderBYavgRating(); 
}