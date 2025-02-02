<?php

namespace App;

class Task
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getAllTask()
    {
        $result = $this->conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
        if ($result->num_rows == 0) {
            http_response_code(404);
            return ["error" => "No Task found"];
        } else {
            $tasks = $result->fetch_all(MYSQLI_ASSOC);
            return $tasks;
        }
    }
    public function getSingleTask($id)
    {
        $sql = "SELECT * FROM tasks WHERE id = $id ORDER BY created_at DESC";
        $result =  $this->conn->query($sql);
        if ($result->num_rows == 0) {
            http_response_code(404);
            return ["error" => "Task not found"];
        }
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        return $tasks;
    }
    public function createTask($data)
    {

        $title = $data['title'];
        $description = $data['description'] ?? '';
        $priority = $data['priority'] ?? 'low';

        $sql = "INSERT INTO tasks(title , description , priority) VALUES ('$title','$description','$priority')";
        $insert =  $this->conn->query($sql);

        if (!$insert) {
            return ['error' => 'Failed to insert data'];
        }
        http_response_code(201);
        return ['message' => 'Task created successfully'];
    }
    public function updateTask($id, $data)
    {
        $sql = "SELECT * FROM tasks WHERE id = $id";
        $task  = $this->conn->query($sql);
        if ($task->num_rows === 0) {
            http_response_code(404);
            return ['error' => 'Task not found'];
        }

        $task = $task->fetch_assoc();

        $title = isset($data['title']) ? $data['title'] : $task['title'];
        $description = isset($data['description']) ? $data['description'] : $task['description'];
        $priority = isset($data['priority']) ? $data['priority'] : $task['priority'];
        $is_completed = isset($data['is_completed']) ? $data['is_completed'] : $task['is_completed'];
        $sql = "UPDATE tasks SET title = '$title', description = '$description',
        priority = '$priority', is_completed = '$is_completed' WHERE id= $id";

        $result = $this->conn->query($sql);

        if (!$result) {
            return ['error' => 'There is problem while updating task'];
        }
        return ['message' => 'Task is updated successfully'];
    }

    public function deleteTask($id)
    {
        $sql = "SELECT * FROM tasks WHERE id = $id";

        $result = $this->conn->query($sql);
        var_dump($this->conn);
        if ($result->num_rows !== 0) {
            $sql = "DELETE FROM tasks WHERE id = $id";
            $result = $this->conn->query($sql);

            if ($this->conn->affected_rows > 0) {
                return ['message' => 'Task deleted successfully'];
            }
            return ['error' => 'Task can not be deleted'];
        }
        http_response_code(404);
        return ['error' => 'Task not found'];
    }
}
