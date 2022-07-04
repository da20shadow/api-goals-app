<?php

namespace App\Repositories\goal;

use App\Models\goal\GoalDTO;
use Database\DBConnector;
use Database\PDODatabase;
use Generator;
use PDOException;

class GoalRepository implements GoalRepositoryInterface
{
    private PDODatabase $db;

    public function __construct()
    {
        $this->db = DBConnector::create();
    }

    public function insert(GoalDTO $goalDTO): bool
    {
        try {
            $this->db->query("
                INSERT INTO goals
                (goal_title,goal_description,due_date,user_id,goal_category)
                VALUES (:title,:description,:due_date,:user_id,:category)
            ")->execute(array(
                'title' => $goalDTO->getTitle(),
                'description' => $goalDTO->getDescription(),
                'due_date' => $goalDTO->getDueDate(),
                'user_id' => $goalDTO->getUserId(),
                ':category' => $goalDTO->getCategory()
            ));
            return true;
        } catch (PDOException $PDOException) {
            $pdoError = $PDOException->getMessage();
            //TODO log the error
            return false;
        }
    }

    public function updateTitle(GoalDTO $goalDTO): bool
    {
        // TODO: Implement updateTitle() method.
    }

    public function updateDescription(GoalDTO $goalDTO): bool
    {
        // TODO: Implement updateDescription() method.
    }

    public function updateDueDate(GoalDTO $goalDTO): bool
    {
        // TODO: Implement updateDueDate() method.
    }

    public function delete(GoalDTO $goalDTO): bool
    {
        // TODO: Implement delete() method.
    }

    public function getGoalById(int $goal_id): GoalDTO
    {
        // TODO: Implement getGoalById() method.
    }

    public function getGoalsByUserId(int $user_id): Generator
    {
        // TODO: Implement getGoalsByUserId() method.
    }
}