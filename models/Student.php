<?php

class Student
{
    private $conn;
    private $table = "students";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create($user_id, $name, $class, $nis)
    {
        $sql = "INSERT INTO {$this->table} (user_id, name, class, nis)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$user_id, $name, $class, $nis]);
    }

    // READ ALL
    public function getAll()
    {
        $sql = "SELECT s.id, s.name, s.class, s.nis, s.created_at, u.username
                FROM {$this->table} s
                JOIN users u ON s.user_id = u.id";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ BY ID
    public function getById($id)
    {
        $sql = "SELECT s.id, s.name, s.class, s.nis, s.created_at, u.username
                FROM {$this->table} s
                JOIN users u ON s.user_id = u.id
                WHERE s.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($id, $name, $class, $nis)
    {
        $sql = "UPDATE {$this->table}
                SET name = ?, class = ?, nis = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $class, $nis, $id]);
    }

    // DELETE
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // GET BY USER ID
    public function getByUserId($user_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}