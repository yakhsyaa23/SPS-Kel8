<?php
require_once "../models/User.php";
require_once "../helpers/response.php";
require_once "../helpers/validation.php";

class UserController
{
    private $user;

    public function __construct($db)
    {
        $this->user = new User($db);
    }

    public function index()
    {
        jsonResponse($this->user->getAll());
    }

    public function show($id)
    {
        $data = $this->user->getById($id);
        $data
            ? jsonResponse($data)
            : jsonResponse(["message" => "User not found"], 404);
    }

    public function store($data)
    {
        $errors = validateUserData($data);
        if (!empty($errors)) {
            jsonResponse(["message" => "Validation errors", "errors" => $errors], 422);
        }

        $this->user->create(
            htmlspecialchars($data['username']),
            $data['password'],
            $data['role']
        );

        jsonResponse(["message" => "User created"]);
    }

    public function update($id, $data)
    {
        // Basic validation for update
        if (!isset($data['username']) || !isset($data['role'])) {
            jsonResponse(["message" => "Username and role required"], 422);
        }

        $this->user->update($id, htmlspecialchars($data['username']), $data['role']);
        jsonResponse(["message" => "User updated"]);
    }

    public function updatePassword($id, $data)
    {
        if (!isset($data['password']) || strlen($data['password']) < 6) {
            jsonResponse(["message" => "Password must be at least 6 characters"], 422);
        }

        $this->user->updatePassword($id, $data['password']);
        jsonResponse(["message" => "Password updated"]);
    }

    public function destroy($id)
    {
        $this->user->delete($id);
        jsonResponse(["message" => "User deleted"]);
    }
}
