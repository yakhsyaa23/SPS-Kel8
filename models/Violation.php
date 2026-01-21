<?php

class Violation
{
    private $conn;
    private $table = "violations";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create($student_id, $reported_by, $description, $type, $points)
    {
        $sql = "INSERT INTO {$this->table} (student_id, reported_by, description, type, points)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$student_id, $reported_by, $description, $type, $points]);
    }

    // READ ALL
    public function getAll()
    {
        $sql = "SELECT v.id, v.description, v.type, v.points, v.date_reported, v.status, v.created_at,
                       s.name as student_name, u.username as reported_by_name
                FROM {$this->table} v
                JOIN students s ON v.student_id = s.id
                JOIN users u ON v.reported_by = u.id";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ BY ID
    public function getById($id)
    {
        $sql = "SELECT v.id, v.description, v.type, v.points, v.date_reported, v.status, v.created_at,
                       s.name as student_name, u.username as reported_by_name
                FROM {$this->table} v
                JOIN students s ON v.student_id = s.id
                JOIN users u ON v.reported_by = u.id
                WHERE v.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($id, $description, $type, $points, $status)
    {
        $sql = "UPDATE {$this->table}
                SET description = ?, type = ?, points = ?, status = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$description, $type, $points, $status, $id]);
    }

    // DELETE
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // GET BY STUDENT ID
    public function getByStudentId($student_id)
    {
        $sql = "SELECT v.id, v.description, v.type, v.points, v.date_reported, v.status, v.created_at,
                       u.username as reported_by_name
                FROM {$this->table} v
                JOIN users u ON v.reported_by = u.id
                WHERE v.student_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}