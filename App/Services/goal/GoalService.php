<?php

namespace App\Services\goal;

use App\Models\goal\GoalDTO;
use App\Repositories\goal\GoalRepository;
use App\Repositories\goal\GoalRepositoryInterface;
use Exception;

class GoalService implements GoalServiceInterface
{
    private GoalRepositoryInterface $goalRepository;

    public function __construct()
    {
        $this->goalRepository = new GoalRepository();
    }

    /** ----------------CREATE-------------------- */
    public function create(array $userInputs, $user_id)
    {
        if (!isset($userInputs['title']) || !isset($userInputs['description'])
            || !isset($userInputs['category']) || !isset($userInputs['due_date'])) {
            http_response_code(403);
            echo json_encode([
                'message' => 'All fields are required!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        $title = $userInputs['title'];
        $description = $userInputs['description'];
        $category = $userInputs['category'];
        $dueDate = $userInputs['due_date'];

        if (!is_string($title)){
            http_response_code(403);
            echo json_encode([
                'message' => 'Invalid Title Input!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        if (!is_string($description)){
            http_response_code(403);
            echo json_encode([
                'message' => 'Invalid Description Input!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        if (!is_numeric($category)) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Invalid Category Input!'
            ], JSON_PRETTY_PRINT);
            return;
        }
        //TODO: validate dueDate input

        try {
            $goal = GoalDTO::create($title, $description, $category, $user_id, $dueDate);
        } catch (Exception $e) {
            echo json_encode($e->getMessage(), JSON_PRETTY_PRINT);
            return;
        }

        $result = $this->goalRepository->insert($goal);

        if (!$result) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error, can not add the goal, please try again or contact our support!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        http_response_code(200);
        echo json_encode([
            'message' => 'Successfully Created New Goal!'
        ], JSON_PRETTY_PRINT);

    }

    /** ----------------UPDATE-------------------- */
    /** UPDATE Title */
    public function updateTitle(array $userInputs, array $userInfo)
    {
        if (!isset($userInputs['goal_id'])){
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Empty Goal ID!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        $goal = new GoalDTO();

        try {
            $goal->setTitle($userInputs['title']);
            $goal->setId($userInputs['goal_id']);
            $goal->setUserId($userInfo['id']);
        } catch (Exception $exception) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! ' . $exception->getMessage()
            ], JSON_PRETTY_PRINT);
            return;
        }

        if (!$this->goalExist($userInfo['id'],$goal)) {
            return;
        }

        $result = $this->goalRepository->updateTitle($goal);

        if (!$result) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return;
        }
        http_response_code(200);
        echo json_encode([
            'message' => 'Successfully Changed Goal title!'
        ], JSON_PRETTY_PRINT);

    }

    /** UPDATE Description */
    public function updateDescription(array $userInputs, array $userInfo)
    {
        if (!isset($userInputs['goal_id'])){
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        $goal = new GoalDTO();

        try {
            $goal->setDescription($userInputs['description']);
            $goal->setId($userInputs['goal_id']);
            $goal->setUserId($userInfo['id']);
        } catch (Exception $exception) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! ' . $exception->getMessage()
            ], JSON_PRETTY_PRINT);
            return;
        }

        if (!$this->goalExist($userInfo['id'],$goal)) {
            return;
        }

        $result = $this->goalRepository->updateDescription($goal);

        if (!$result) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return;
        }
        http_response_code(200);
        echo json_encode([
            'message' => 'Successfully Changed Goal description!'
        ], JSON_PRETTY_PRINT);
    }

    /** UPDATE Due Date */
    public function updateDueDate(array $userInputs, array $userInfo)
    {
        if (!isset($userInputs['goal_id'])){
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        $goal = new GoalDTO();

        try {
            $goal->setDueDate($userInputs['due_date']);
            $goal->setId($userInputs['goal_id']);
            $goal->setUserId($userInfo['id']);
        } catch (Exception $exception) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! ' . $exception->getMessage()
            ], JSON_PRETTY_PRINT);
            return;
        }

        if (!$this->goalExist($userInfo['id'],$goal)) {
            return;
        }

        $result = $this->goalRepository->updateDueDate($goal);

        if (!$result) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return;
        }
        http_response_code(200);
        echo json_encode([
            'message' => 'Successfully Changed Goal Due Date!'
        ], JSON_PRETTY_PRINT);
    }

    /** UPDATE Category */
    public function updateCategory(array $userInputs, array $userInfo)
    {
        if (!isset($userInputs['goal_id'])){
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        $category = $userInputs['category'];
        if (!is_numeric($category)){
            http_response_code(400);
            echo json_encode([
                'message' => 'Error! Invalid Category ID!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        $goal = new GoalDTO();

        try {
            $goal->setCategory($userInputs['category']);
            $goal->setId($userInputs['goal_id']);
            $goal->setUserId($userInfo['id']);
        } catch (Exception $exception) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! ' . $exception->getMessage()
            ], JSON_PRETTY_PRINT);
            return;
        }

        if (!$this->goalExist($userInfo['id'],$goal)) {
            return;
        }

        $result = $this->goalRepository->updateCategory($goal);

        if (!$result) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return;
        }
        http_response_code(200);
        echo json_encode([
            'message' => 'Successfully Changed Goal Category!'
        ], JSON_PRETTY_PRINT);
    }


    /** ---------------------DELETE-------------------- */
    /** DELETE Goal */
    public function delete(array $userInputs, array $userInfo)
    {
        if (!isset($userInputs['goal_id'])){
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        $goal = new GoalDTO();

        try {
            $goal->setId($userInputs['goal_id']);
            $goal->setUserId($userInfo['id']);
        } catch (Exception $exception) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! ' . $exception->getMessage()
            ], JSON_PRETTY_PRINT);
            return;
        }

        if (!$this->goalExist($userInfo['id'],$goal)) {
            return;
        }

        $result = $this->goalRepository->delete($goal);

        if (!$result) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Can not DELETE the goal!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        http_response_code(200);
        echo json_encode([
            'message' => 'Successfully DELETED Goal!'
        ], JSON_PRETTY_PRINT);
    }


    /** ---------------------GET-------------------- */

    /** GET Single Goal By goal ID
     * @returns array with single goal or message
     */
    public function getGoalById(int $user_id, int $goal_id)
    {
        $goal = new GoalDTO();
        try {
            $goal->setId($goal_id);
            $goal->setUserId($user_id);
        } catch (Exception $exception) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! ' . $exception->getMessage()
            ], JSON_PRETTY_PRINT);
            return;
        }
        $goalFromDb = $this->goalRepository->getGoalById($goal);

        if (null === $goalFromDb) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return;
        }

        http_response_code(200);
        echo json_encode([
            'id' => $goalFromDb->getId(),
            'title' => $goalFromDb->getTitle(),
            'description' => $goalFromDb->getDescription(),
            'category' => $goalFromDb->getCategory(),
            'userId' => $goalFromDb->getUserId(),
            'createdOn' => $goalFromDb->getCreatedOn(),
            'dueDate' => $goalFromDb->getDueDate(),
        ], JSON_PRETTY_PRINT);
    }

    /** GET ALL Goals By User ID
     * @returns array with goals or message
     */
    public function getGoalsByUserId(int $user_id)
    {
        $goalsGenerator = $this->goalRepository->getGoalsByUserId($user_id);

        if (null === $goalsGenerator)
        {
            http_response_code(403);
            echo json_encode(['message' => 'No Goals Added Yet!'],JSON_PRETTY_PRINT);
            return;
        }

        $goals = $this->generateGoalList($goalsGenerator);

        if (count($goals) === 0)
        {
            http_response_code(403);
            echo json_encode(['message' => 'No Goals Added Yet!'],JSON_PRETTY_PRINT);
            return;
        }

        http_response_code(200);
        echo json_encode(['goals' => $goals],JSON_PRETTY_PRINT);
    }


    /** --------------CREATORS AND GENERATORS-------------- */

    /** CREATE Goal List
     * @returns array with goals
     */
    public function generateGoalList($goalsGenerator): array
    {
        $goals = [];
        foreach ($goalsGenerator as $goal){
            array_push($goals, [
                'id' => $goal->getId(),
                'title' => $goal->getTitle(),
                'description' => $goal->getDescription(),
                'category' => $goal->getCategory(),
                'userId' => $goal->getUserId(),
                'createdOn' => $goal->getCreatedOn(),
                'dueDate' => $goal->getDueDate(),
            ]);
        }
        return $goals;
    }


    /** -----------------VALIDATIONS----------------- */

    private function goalExist($user_id, GoalDTO $goalDTO): bool
    {
        $goalFromDb = $this->goalRepository->getGoalById($goalDTO);

        if (null === $goalFromDb) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Goal ID or User ID!'
            ], JSON_PRETTY_PRINT);
            return false;
        }
        if ($user_id !== $goalFromDb->getUserId()) {
            http_response_code(403);
            echo json_encode([
                'message' => 'Error! Invalid Request!'
            ], JSON_PRETTY_PRINT);
            return false;
        }
        return true;
    }
}