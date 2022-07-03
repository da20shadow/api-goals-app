<?php

namespace App\Services\user;

interface UserServiceInterface
{
    public function register($userInputs);
    public function login($userInputs);
    public function update($userInputs);
    public function delete($userInputs);
    public function getUserById($user_id);
    public function getUserByUsername($username);
    public function getUserByEmail($email);
    public function createUserDTO($userInputs);
}