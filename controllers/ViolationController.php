<?php
require_once "../models/Violation.php";
require_once "../helpers/response.php";

class ViolationController
{
    private $violation;

    public function __construct($db)
    {
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
        if (!$data['student_id'] || !$data['reported_by'] || !$data['description']) {
            jsonResponse(["message" => "Invalid data"], 422);
        }

        $this->violation->create(
            $data['student_id'],
            $data['reported_by'],
            $data['description'],
            $data['type'] ?? 'ringan',
            $data['points'] ?? 0
        );

        jsonResponse(["message" => "Violation created"]);
    }

    public function update($id, $data)
    {
        $this->violation->update(
            $id,
            $data['description'],
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