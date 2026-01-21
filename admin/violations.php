<?php
require "../auth/middleware.php";
require "../config/database.php";
require "../controllers/ViolationController.php";

// ADMIN OR GURU ONLY
if (!in_array($currentUser['role'], ['admin', 'guru'])) {
    jsonResponse(["message" => "Access denied"], 403);
}

$db = (new Database())->connect();
$controller = new ViolationController($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$data = json_decode(file_get_contents("php://input"), true);

if ($method === "POST" && empty($data['reported_by'])) {
    $data['reported_by'] = $currentUser['uid'];
}

switch ($method) {
    case "GET":
        $id ? $controller->show($id) : $controller->index();
        break;

    case "POST":
        $controller->store($data);
        break;

    case "PUT":
        $controller->update($id, $data);
        break;

    case "DELETE":
        $controller->destroy($id);
        break;

    default:
        jsonResponse(["message" => "Method not allowed"], 405);
}