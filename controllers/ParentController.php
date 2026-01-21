<?php
require_once "../models/ParentModel.php";
require_once "../helpers/response.php";
require_once "../helpers/validation.php";

class ParentController
{
    private $parent;

    public function __construct($db)
    {
        $this->parent = new ParentModel($db);
    }

    public function index()
    {
        jsonResponse($this->parent->getAll());
    }

    public function show($id)
    {
        $data = $this->parent->getById($id);
        $data
            ? jsonResponse($data)
            : jsonResponse(["message" => "Parent not found"], 404);
    }

    public function store($data)
    {
        // Add User model for creating the user account
        require_once "../models/User.php";
        require_once "../models/Student.php";

        $errors = validateParentData($data);
        if (!empty($errors)) {
            jsonResponse(["message" => "Validation errors", "errors" => $errors], 422);
            return;
        }

        $db = $this->parent->getDbConnection(); // Assumes a method to get the DB connection
        $userModel = new User($db);
        $studentModel = new Student($db);

        // 1. Check if student_id exists
        if (!$studentModel->getById($data['student_id'])) {
            jsonResponse(["message" => "Student with ID {$data['student_id']} not found"], 404);
            return;
        }

        // 2. Check if username already exists
        if ($userModel->findByUsername($data['username'])) {
            jsonResponse(["message" => "Username '{$data['username']}' already exists"], 409);
            return;
        }

        // 3. Create the user account
        $newUserId = $userModel->create(
            $data['username'],
            $data['password'],
            'orang_tua' // Hardcode the role
        );

        if (!$newUserId) {
            jsonResponse(["message" => "Failed to create user account"], 500);
            return;
        }

        // 4. Create the parent profile and link it to the user account
        $parentCreated = $this->parent->create(
            $newUserId,
            htmlspecialchars($data['name']),
            htmlspecialchars($data['phone'] ?? ''),
            filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL),
            $data['student_id']
        );

        if ($parentCreated) {
            jsonResponse(["message" => "Parent created successfully"]);
        } else {
            // Optional: clean up created user if parent creation fails
            $userModel->delete($newUserId);
            jsonResponse(["message" => "Failed to create parent profile"], 500);
        }
    }

    public function update($id, $data)
    {
        $errors = validateParentData($data);
        if (!empty($errors)) {
            jsonResponse(["message" => "Validation errors", "errors" => $errors], 422);
        }

        $this->parent->update($id, htmlspecialchars($data['name']), htmlspecialchars($data['phone'] ?? ''), filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL));
        jsonResponse(["message" => "Parent updated"]);
    }

    public function destroy($id)
    {
        $this->parent->delete($id);
        jsonResponse(["message" => "Parent deleted"]);
    }
}
