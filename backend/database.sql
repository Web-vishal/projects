-- Create database
CREATE DATABASE IF NOT EXISTS software;
USE software;

-- Users table
-- Users table
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mobile_no VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    type VARCHAR(20) DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, type) 
VALUES ('admin', 'admin@example.com', 'admin123', 'Administrator', 'admin');

-- Insert some sample users (password: password123)
INSERT INTO users (username, email, password, full_name, type) 
VALUES 
('john_doe', 'john@example.com', 'password123', 'John Doe', 'user'),
('jane_smith', 'jane@example.com', 'password123', 'Jane Smith', 'user'),
('bob_wilson', 'bob@example.com', 'password123', 'Bob Wilson', 'user');
