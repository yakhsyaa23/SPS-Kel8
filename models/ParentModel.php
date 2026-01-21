<?php

class ParentModel
{
    private $conn;
    private $table = "parents";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create($user_id, $name, $phone, $email, $student_id)
    {
        $sql = "INSERT INTO {$this->table} (user_id, name, phone, email, student_id)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$user_id, $name, $phone, $email, $student_id]);
    }

    // READ ALL
    public function getAll()
    {
        $sql = "SELECT p.id, p.name, p.phone, p.email, p.created_at, s.name as student_name, u.username
                FROM {$this->table} p
                JOIN students s ON p.student_id = s.id
                JOIN users u ON p.user_id = u.id";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ BY ID
    public function getById($id)
    {
        $sql = "SELECT p.id, p.name, p.phone, p.email, p.created_at, s.name as student_name, u.username
                FROM {$this->table} p
                JOIN students s ON p.student_id = s.id
                JOIN users u ON p.user_id = u.id
                WHERE p.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($id, $name, $phone, $email)
    {
        $sql = "UPDATE {$this->table}
                SET name = ?, phone = ?, email = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $phone, $email, $id]);
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
        $sql = "SELECT * FROM {$this->table} WHERE student_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}