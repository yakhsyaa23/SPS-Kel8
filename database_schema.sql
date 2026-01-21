-- Skema Basis Data untuk Sistem Pelanggaran Siswa (SPS)

-- Tabel users sudah ada (id, username, password, role, created_at)

-- Tabel students
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    class VARCHAR(50),
    nis VARCHAR(20) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel parents
CREATE TABLE parents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255),
    student_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Tabel violations
CREATE TABLE violations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    reported_by INT NOT NULL, -- user_id dari admin/guru
    description TEXT NOT NULL,
    type ENUM('ringan', 'sedang', 'berat') DEFAULT 'ringan',
    points INT DEFAULT 0,
    date_reported DATE DEFAULT CURRENT_DATE,
    status ENUM('pending', 'approved', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel notifications (opsional untuk notifikasi)
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    violation_id INT NOT NULL,
    recipient_type ENUM('student', 'parent') NOT NULL,
    recipient_id INT NOT NULL, -- student_id atau parent_id
    message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (violation_id) REFERENCES violations(id) ON DELETE CASCADE
);