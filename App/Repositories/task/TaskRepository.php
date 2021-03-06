<?php

namespace App\Repositories\task;

use App\Models\task\TaskDTO;
use Database\DBConnector;
use Database\PDODatabase;
use Generator;
use PDOException;

class TaskRepository implements TaskRepositoryInterface
{
    private PDODatabase $db;

    public function __construct()
    {
        $this->db = DBConnector::create();
    }

    /** -----------------CREATE-------------------- */

    public function insert(TaskDTO $taskDTO): bool|TaskDTO
    {
        $description = $taskDTO->getDescription() ?: 'Task Description...';
        $dueDate = $taskDTO->getDueDate() ?: '0000-00-00';
        $status = $taskDTO->getStatus() ?: 3;
        $priority = $taskDTO->getPriority() ?: 5;

        try {
            $this->db->query("
            INSERT INTO tasks 
            (task_title, task_description, due_date, status,priority, goal_id, user_id)
            VALUES (:title,:description,:due_date,:status,:priority,:goal_id, :user_id);
        ")->execute(array(
                ':title' => $taskDTO->getTitle(),
                ':description' => $description,
                ':due_date' => $dueDate,
                ':status' => $status,
                ':priority' => $priority,
                ':goal_id' => $taskDTO->getGoalId(),
                ':user_id' => $taskDTO->getUserId(),
            ));
            $result = $this->db->query("
                SELECT task_id as id,
                       task_title AS title,
                   task_description AS description,
                   priority,
                   progress,
                   status, 
                   due_date AS dueDate, 
                   created_on AS createdOn,
                   goal_id AS goalId, 
                   user_id AS userId
                FROM tasks
                WHERE task_id = LAST_INSERT_ID() AND user_id = :user_id;
            ")->execute(array(":user_id" => $taskDTO->getUserId()))
                ->fetch(TaskDTO::class)->current();

        } catch (PDOException $PDOException) {
            $pdoError = $PDOException->getMessage();
            //TODO: Log the errors
            $result = false;
        }
        if ($result instanceof TaskDTO){
            return $result;
        }
        throw new \Exception($result);
    }


    /** -----------------UPDATE-------------------- */

    /** UPDATE Task Title */
    public function updateTitle(TaskDTO $taskDTO): bool
    {
        try {
            $this->db->query("
            UPDATE tasks
            SET task_title = :title
            WHERE task_id = :task_id AND user_id = :user_id
        ")->execute(array(
                ':title' => $taskDTO->getTitle(),
                ':task_id' => $taskDTO->getId(),
                ':user_id' => $taskDTO->getUserId()
            ));
            return true;
        } catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log errors
            return false;
        }
    }

    public function updateDescription(TaskDTO $taskDTO): bool
    {
        try {
            $this->db->query("
            UPDATE tasks
            SET task_description = :description
            WHERE task_id = :task_id AND user_id = :user_id
        ")->execute(array(
                ':description' => $taskDTO->getDescription(),
                ':task_id' => $taskDTO->getId(),
                ':user_id' => $taskDTO->getUserId()
            ));
            return true;
        } catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log errors
            return false;
        }
    }

    public function updateStatus(TaskDTO $taskDTO): bool
    {
        try {
            $this->db->query("
            UPDATE tasks
            SET status = :status
            WHERE task_id = :task_id AND user_id = :user_id
        ")->execute(array(
                ':status' => $taskDTO->getStatus(),
                ':task_id' => $taskDTO->getId(),
                ':user_id' => $taskDTO->getUserId()
            ));
            return true;
        } catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log errors
            return false;
        }
    }

    public function updateProgress(TaskDTO $taskDTO): bool
    {
        try {
            $this->db->query("
            UPDATE tasks
            SET progress = :progress
            WHERE task_id = :task_id AND user_id = :user_id
        ")->execute(array(
                ':progress' => $taskDTO->getProgress(),
                ':task_id' => $taskDTO->getId(),
                ':user_id' => $taskDTO->getUserId()
            ));
            return true;
        } catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log errors
            return false;
        }
    }

    public function updatePriority(TaskDTO $taskDTO): bool
    {
        try {
            $this->db->query("
            UPDATE tasks
            SET priority = :priority
            WHERE task_id = :task_id AND user_id = :user_id
        ")->execute(array(
                ':priority' => $taskDTO->getPriority(),
                ':task_id' => $taskDTO->getId(),
                ':user_id' => $taskDTO->getUserId()
            ));
            return true;
        } catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log errors
            return false;
        }
    }

    public function updateDueDate(TaskDTO $taskDTO): bool
    {
        try {
            $this->db->query("
            UPDATE tasks
            SET due_date = :due_date
            WHERE task_id = :task_id AND user_id = :user_id
        ")->execute(array(
                ':due_date' => $taskDTO->getDueDate(),
                ':task_id' => $taskDTO->getId(),
                ':user_id' => $taskDTO->getUserId()
            ));
            return true;
        } catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log errors
            return false;
        }
    }

    public function updateGoalId(int $newGoalId, TaskDTO $taskDTO): bool
    {
        try {
            $this->db->query("
            UPDATE tasks
            SET goal_id = :newGoalId
            WHERE task_id = :task_id AND user_id = :user_id
        ")->execute(array(
                ':newGoalId' => $newGoalId,
                ':task_id' => $taskDTO->getId(),
                ':user_id' => $taskDTO->getUserId()
            ));
            return true;
        } catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log errors
            return false;
        }
    }


    /** -----------------DELETE-------------------- */

    public function delete(TaskDTO $taskDTO): bool
    {
        try {
            $this->db->query("
                DELETE
                FROM tasks
                WHERE task_id = :id AND user_id = :user_id
            ")->execute(array(
                ':id' => $taskDTO->getId(),
                ':user_id' => $taskDTO->getUserId(),
            ));
            return true;
        } catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO LOG the errors
            return false;
        }
    }


    /** -----------------GET-------------------- */

    public function getTaskById(TaskDTO $taskDTO): ?TaskDTO
    {
        $task = null;
        try {
            $task = $this->db->query("
            SELECT task_id AS id,
                   task_title AS title,
                   task_description AS description,
                   priority,
                   progress,
                   status, 
                   due_date AS dueDate, 
                   created_on AS createdOn,
                   goal_id AS goalId, 
                   user_id AS userId
            FROM tasks
            WHERE task_id = :task_id AND user_id = :user_id
        ")->execute(array(
                ':task_id' => $taskDTO->getId(),
                ':user_id' => $taskDTO->getUserId()
            ))->fetch(TaskDTO::class)
                ->current();

        } catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log errors
        }
        return $task;
    }

    public function getTasksByGoalId(int $user_id, int $goal_id): ?Generator
    {
        $tasksGenerator = null;
        try {
            $tasksGenerator = $this->db->query("
                SELECT task_id AS id,
                   task_title AS title,
                   task_description AS description,
                   priority,
                   progress,
                   status, 
                   due_date AS dueDate, 
                   created_on AS createdOn,
                   goal_id AS goalId, 
                   user_id AS userId
                   FROM tasks
                WHERE goal_id = :goal_id AND user_id = :user_id 
            ")->execute(array(
                'goal_id' => $goal_id,
                'user_id' => $user_id
            ))->fetch(TaskDTO::class);
        }catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log the errors
        }
        return $tasksGenerator;
    }

    public function getTasksByUserId($user_id): ?Generator
    {

        $tasksGenerator = null;
        try {
            $tasksGenerator = $this->db->query("
                SELECT task_id AS id,
                   task_title AS title,
                   task_description AS description,
                   priority,
                   progress,
                   status, 
                   due_date AS dueDate, 
                   created_on AS createdOn,
                   goal_id AS goalId, 
                   user_id AS userId
                   FROM tasks
                WHERE user_id = :user_id 
            ")->execute(array(
                'user_id' => $user_id
            ))->fetch(TaskDTO::class);
        }catch (PDOException $PDOException) {
            $err = $PDOException->getMessage();
            //TODO log the errors
        }
        return $tasksGenerator;

    }
}