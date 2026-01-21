<?php
require_once "../models/Violation.php";
require_once "../models/Student.php";
require_once "../helpers/response.php";
require_once "../helpers/validation.php";

class ViolationController
{
    private $violation;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->violation = new Violation($db);
    }

    public function index()
    {
        jsonResponse($this->violation->getAll());
    }

    public function show($id)
    {
        $data = $this->violation->getById($id);
        $data
            ? jsonResponse($data)
            : jsonResponse(["message" => "Violation not found"], 404);
    }

    public function store($data)
    {
        $errors = validateViolationData($data);
        if (!empty($errors)) {
            jsonResponse(["message" => "Validation errors", "errors" => $errors], 422);
            return;
        }

        $student = new Student($this->db);
        if (!$student->getById($data['student_id'])) {
            jsonResponse(["message" => "Student not found"], 404);
            return;
        }

        $this->violation->create(
            $data['student_id'],
            $data['reported_by'],
            htmlspecialchars($data['description']),
            $data['type'] ?? 'ringan',
            $data['points'] ?? 0
        );

        jsonResponse(["message" => "Violation created"]);
    }

    public function update($id, $data)
    {
        $errors = validateViolationData($data);
        if (!empty($errors)) {
            jsonResponse(["message" => "Validation errors", "errors" => $errors], 422);
        }

        $this->violation->update(
            $id,
            htmlspecialchars($data['description']),
            $data['type'] ?? 'ringan',
            $data['points'] ?? 0,
            $data['status'] ?? 'pending'
        );
        jsonResponse(["message" => "Violation updated"]);
    }

    public function destroy($id)
    {
        $this->violation->delete($id);
        jsonResponse(["message" => "Violation deleted"]);
    }
}