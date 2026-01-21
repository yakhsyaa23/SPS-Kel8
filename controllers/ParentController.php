<?php
require_once "../models/ParentModel.php";
require_once "../helpers/response.php";

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
        if (!$data['user_id'] || !$data['name'] || !$data['student_id']) {
            jsonResponse(["message" => "Invalid data"], 422);
        }

        $this->parent->create(
            $data['user_id'],
            $data['name'],
            $data['phone'] ?? '',
            $data['email'] ?? '',
            $data['student_id']
        );

        jsonResponse(["message" => "Parent created"]);
    }

    public function update($id, $data)
    {
        $this->parent->update($id, $data['name'], $data['phone'] ?? '', $data['email'] ?? '');
        jsonResponse(["message" => "Parent updated"]);
    }

    public function destroy($id)
    {
        $this->parent->delete($id);
        jsonResponse(["message" => "Parent deleted"]);
    }
}
