<?php
require_once "../models/Student.php";
require_once "../helpers/response.php";
require_once "../helpers/validation.php";

class StudentController
{
    private $student;

    public function __construct($db)
    {
        $this->student = new Student($db);
    }

    public function index()
    {
        jsonResponse($this->student->getAll());
    }

    public function show($id)
    {
        $data = $this->student->getById($id);
        $data
            ? jsonResponse($data)
            : jsonResponse(["message" => "Student not found"], 404);
    }

    public function store($data)
    {
        $errors = validateStudentData($data);
        if (!empty($errors)) {
            jsonResponse(["message" => "Validation errors", "errors" => $errors], 422);
        }

        $this->student->create(
            $data['user_id'],
            htmlspecialchars($data['name']),
            htmlspecialchars($data['class']),
            htmlspecialchars($data['nis'])
        );

        jsonResponse(["message" => "Student created"]);
    }

    public function update($id, $data)
    {
        $errors = validateStudentData($data);
        if (!empty($errors)) {
            jsonResponse(["message" => "Validation errors", "errors" => $errors], 422);
        }

        $this->student->update($id, htmlspecialchars($data['name']), htmlspecialchars($data['class']), htmlspecialchars($data['nis']));
        jsonResponse(["message" => "Student updated"]);
    }

    public function destroy($id)
    {
        $this->student->delete($id);
        jsonResponse(["message" => "Student deleted"]);
    }
}
