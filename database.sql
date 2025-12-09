-- database.sql
-- Create database
CREATE DATABASE IF NOT EXISTS time_tracker;
USE time_tracker;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Time entries table
CREATE TABLE time_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    clock_in DATETIME NOT NULL,
    clock_out DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, clock_in)
);

-- Insert demo user
-- Password: demo123
INSERT INTO users (email, password, name) VALUES 
('demo@example.com', '$2y$12$609p/U79re4m2gbOmol9EeLUQrDYT7pZcq.jXmb8fEI0qddECL3Aq', 'Demo User');

-- Insert sample time entries for demol; optional - for testing
INSERT INTO time_entries (user_id, clock_in, clock_out) VALUES
(1, '2024-12-09 09:00:00', '2024-12-09 17:30:00'),
(1, '2024-12-10 08:45:00', '2024-12-10 17:15:00'),
(1, '2024-12-11 09:15:00', '2024-12-11 17:00:00');