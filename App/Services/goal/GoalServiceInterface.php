<?php

namespace App\Services\goal;

interface GoalServiceInterface
{
    public function create(array $userInputs, $user_id);
    public function updateTitle(array $userInputs, array $userInfo);
    public function updateDescription(array $userInputs, array $userInfo);
    public function updateCategory(array $userInputs, array $userInfo);
    public function updateDueDate(array $userInputs, array $userInfo);
    public function delete(array $userInputs, array $userInfo);
    public function getGoalById(int $user_id, int $goal_id);
    public function getGoalsByUserId(int $user_id);
}