<?php
require_once "../vendor/autoload.php";

use Respect\Validation\Validator as v;

function validateUserData($data)
{
    $errors = [];

    if (!v::stringType()->length(3, 50)->validate($data['username'] ?? '')) {
        $errors[] = "Username must be 3-50 characters";
    }

    if (!v::stringType()->length(6, 255)->validate($data['password'] ?? '')) {
        $errors[] = "Password must be at least 6 characters";
    }

    if (!v::in(['admin', 'guru', 'siswa', 'orang_tua'])->validate($data['role'] ?? '')) {
        $errors[] = "Invalid role";
    }

    return $errors;
}

function validateStudentData($data)
{
    $errors = [];

    if (!v::stringType()->length(1, 255)->validate($data['name'] ?? '')) {
        $errors[] = "Name is required";
    }

    if (!v::stringType()->length(1, 50)->validate($data['class'] ?? '')) {
        $errors[] = "Class is required";
    }

    if (!v::stringType()->length(1, 20)->validate($data['nis'] ?? '')) {
        $errors[] = "NIS is required";
    }

    return $errors;
}

function validateParentData($data)
{
    $errors = [];

    if (!v::stringType()->length(1, 255)->validate($data['name'] ?? '')) {
        $errors[] = "Name is required";
    }

    if (isset($data['email']) && !v::email()->validate($data['email'])) {
        $errors[] = "Invalid email";
    }

    if (!v::intVal()->positive()->validate($data['student_id'] ?? 0)) {
        $errors[] = "Valid student_id is required";
    }

    return $errors;
}

function validateViolationData($data)
{
    $errors = [];

    if (!v::intVal()->positive()->validate($data['student_id'] ?? 0)) {
        $errors[] = "Valid student_id is required";
    }

    if (!v::stringType()->length(1, 1000)->validate($data['description'] ?? '')) {
        $errors[] = "Description is required";
    }

    if (isset($data['type']) && !v::in(['ringan', 'sedang', 'berat'])->validate($data['type'])) {
        $errors[] = "Invalid type";
    }

    if (isset($data['points']) && !v::intVal()->min(0)->validate($data['points'])) {
        $errors[] = "Points must be non-negative";
    }

    return $errors;
}