<?php

namespace App\Services\goal;

use App\Models\goal\GoalDTO;
use App\Repositories\goal\GoalRepository;
use App\Repositories\goal\GoalRepositoryInterface;
use App\Services\validator\InputValidator;
use Exception;

class GoalService implements GoalServiceInterface
{
    private GoalRepositoryInterface $goalRepository;

    public function __construct()
    {
        $this->goalRepository = new GoalRepository();
    }

    /** ----------------CREATE-------------------- */
    public function create(array $userInputs, array $userInfo)
    {
        if (!isset($userInputs['title']) || !isset($userInputs['description'])
            || !isset($userInputs['category']) || !isset($userInputs['user_id'])
            || !isset($userInputs['due_date']) || $userInputs['user_id'] != $userInfo['id'])
        {
            http_response_code(403);
            echo json_encode([
                'message' => 'All fields are required!'
            ],JSON_PRETTY_PRINT);
        }

        $title = $userInputs['title'];
        $description = $userInputs['description'];
        $category = $userInputs['category'];
        $userId = $userInputs['user_id'];
        $dueDate = $userInputs['due_date'];

        $goal = null;
        try {
            $goal = GoalDTO::create($title, $description, $category, $userId, $dueDate);
        } catch (Exception $e) {
            echo json_encode($e->getMessage(), JSON_PRETTY_PRINT);
        }

        $result = $this->goalRepository->insert($goal);

        if (!$result) {
            http_response_code(403);
            echo json_encode([
                'message' => 'All fields are required!'
            ],JSON_PRETTY_PRINT);
            return;
        }

        http_response_code(200);
        echo json_encode([
            'message' => 'Successfully Created New Goal!'
        ],JSON_PRETTY_PRINT);

    }

    /** ----------------UPDATE-------------------- */
    public function updateTitle(array $userInputs,array $userInfo)
    {
        $goal = new GoalDTO();

        try {
            $goal->setTitle($userInputs['title']);
            $goal->setId($userInputs['goal_id']);
            $goal->setUserId($userInputs['user_id']);
        }catch (Exception $exception){
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! ' . $exception->getMessage()
            ],JSON_PRETTY_PRINT);
            return;
        }

        if ($userInfo['id'] !== $goal->getUserId()){
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ],JSON_PRETTY_PRINT);
            return;
        }

        $goalFromDb = $this->goalRepository->getGoalById($goal);
        if (null === $goalFromDb){
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Goal ID or User ID!'
            ],JSON_PRETTY_PRINT);
            return;
        }

        $result = $this->goalRepository->updateTitle($goal);

        if (!$result){
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ],JSON_PRETTY_PRINT);
            return;
        }
        http_response_code(200);
        echo json_encode([
            'message' => 'Successfully Changed Goal title!'
        ],JSON_PRETTY_PRINT);

    }

    public function updateDescription(array $userInputs,array $userInfo)
    {
        // TODO: Implement updateDescription() method.
    }

    public function updateDueDate(array $userInputs,array $userInfo)
    {
        // TODO: Implement updateDueDate() method.
    }

    public function updateCategory(array $userInputs, array $userInfo)
    {
        // TODO: Implement updateCategory() method.
    }


    /** ----------------DELETE-------------------- */
    public function delete(array $userInputs, array $userInfo)
    {
        // TODO: Implement delete() method.
    }

    /** ----------------GET-------------------- */
    public function getGoalById(array $userInputs, array $userInfo)
    {
        // TODO: Implement getGoalById() method.
    }

    public function getUserGoalsByUserId(array $userInputs, array $userInfo)
    {
        // TODO: Implement getUserGoalsByUserId() method.
    }
}