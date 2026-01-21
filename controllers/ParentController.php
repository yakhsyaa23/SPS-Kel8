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
        $errors = validateParentData($data);
        if (!empty($errors)) {
            jsonResponse(["message" => "Validation errors", "errors" => $errors], 422);
        }

        $this->parent->create(
            $data['user_id'],
            htmlspecialchars($data['name']),
            htmlspecialchars($data['phone'] ?? ''),
            filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL),
            $data['student_id']
        );

        jsonResponse(["message" => "Parent created"]);
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
