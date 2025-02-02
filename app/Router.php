<?php

namespace App;

use APP\Task;

class Router
{
    private $task;
    public function __construct($task)
    {
        $this->task = $task;
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        switch ($method) {
            case 'GET':
                $this->handleGetRequest($id);
                break;
            case 'POST':
                $this->handlePostRequest();
                break;
            case 'PUT':
                $this->handlePutRequest($id);
                break;
            case 'DELETE':
                $this->handleDeleteRequest($id);
                break;
            default:
                http_response_code(405);
                return json_encode(['error' => 'Method not Allowed']);
        }
    }
    public function handleGetRequest($id)
    {
        if ($id) {
            $result =  $this->task->getSingleTask($id);
            echo json_encode($result);
        } else {
            $result = $this->task->getAllTask();
            echo json_encode($result);
        }
    }
    public function handlePostRequest()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['title']) || trim($data['title']) === "") {
            http_response_code(400);
            echo  json_encode(['error' => "Title is required"]);
            return;
        }
        $priority = ['low', 'medium', 'high'];
        if (isset($data['priority']) && !in_array($data['priority'], $priority)) {
            http_response_code(400);
            echo  json_encode(['error' => $data['priority'] . ' is not valid priority']);
            return;
        }
        $result = $this->task->createTask($data);
        echo json_encode($result);
    }
    public function handlePutRequest($id)
    {
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->task->updateTask($id, $data);
        echo json_encode($result);
    }
    public function handleDeleteRequest($id)
    {
        $result = $this->task->deleteTask($id);
        echo json_encode($result);
    }
}
