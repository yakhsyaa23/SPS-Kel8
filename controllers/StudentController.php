<?php
require_once "../models/Student.php";
require_once "../helpers/response.php";

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
        if (!$data['user_id'] || !$data['name'] || !$data['class'] || !$data['nis']) {
            jsonResponse(["message" => "Invalid data"], 422);
        }

        $this->student->create(
            $data['user_id'],
            $data['name'],
            $data['class'],
            $data['nis']
        );

        jsonResponse(["message" => "Student created"]);
    }

    public function update($id, $data)
    {
        $this->student->update($id, $data['name'], $data['class'], $data['nis']);
        jsonResponse(["message" => "Student updated"]);
    }

    public function destroy($id)
    {
        $this->student->delete($id);
        jsonResponse(["message" => "Student deleted"]);
    }
}
