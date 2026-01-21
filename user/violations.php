<?php
require "../auth/middleware.php";
require "../config/database.php";
require "../models/Violation.php";
require "../models/Student.php";

// STUDENT OR PARENT ONLY
if (!in_array($currentUser['role'], ['siswa', 'orang_tua'])) {
    jsonResponse(["message" => "Access denied"], 403);
}

$db = (new Database())->connect();
$violation = new Violation($db);
$student = new Student($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== "GET") {
    jsonResponse(["message" => "Method not allowed"], 405);
}

// Get student_id based on role
if ($currentUser['role'] === 'siswa') {
    $studentData = $student->getByUserId($currentUser['id']);
    if (!$studentData) {
        jsonResponse(["message" => "Student data not found"], 404);
    }
    $student_id = $studentData['id'];
} elseif ($currentUser['role'] === 'orang_tua') {
    // Assuming parent has access to their child's violations
    // For simplicity, get all students' violations if parent, but ideally link to specific child
    jsonResponse(["message" => "Feature not implemented for parents"], 501);
}

$violations = $violation->getByStudentId($student_id);
jsonResponse($violations);