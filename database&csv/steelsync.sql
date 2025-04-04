
-- Create the database
CREATE DATABASE IF NOT EXISTS steelsync;
USE steelsync;

-- Create users table with common fields
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    photo VARCHAR(255) DEFAULT 'default.jpg',
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    role ENUM('super_admin', 'hr_admin', 'support_admin', 'employee') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create table for super admin specific details
CREATE TABLE IF NOT EXISTS super_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    access_level VARCHAR(50) DEFAULT 'full',
    last_login TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create table for HR admin specific details
CREATE TABLE IF NOT EXISTS hr_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    department VARCHAR(100) DEFAULT 'Human Resources',
    position VARCHAR(100) DEFAULT 'HR Administrator',
    last_login TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create table for support admin specific details
CREATE TABLE IF NOT EXISTS support_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    support_area VARCHAR(100) DEFAULT 'General Support',
    position VARCHAR(100) DEFAULT 'Support Administrator',
    last_login TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create table for employee specific details
CREATE TABLE IF NOT EXISTS employee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    department VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    hire_date DATE NOT NULL,
    manager_id INT NULL,
    employment_status ENUM('Active', 'On Leave', 'Terminated') DEFAULT 'Active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (manager_id) REFERENCES employee(id) ON DELETE SET NULL
);

-- Insert default users for testing
-- Passwords are md5 hashed (not secure for production)
INSERT INTO users (username, password, firstname, lastname, email, phone, gender, role) VALUES 
('superadmin', MD5('superadmin123'), 'Super', 'Admin', 'superadmin@steelsync.com', '1234567890', 'Male', 'super_admin'),
('hradmin', MD5('hradmin123'), 'HR', 'Admin', 'hradmin@steelsync.com', '1234567891', 'Female', 'hr_admin'),
('supportadmin', MD5('supportadmin123'), 'Support', 'Admin', 'supportadmin@steelsync.com', '1234567892', 'Male', 'support_admin'),
('employee1', MD5('employee123'), 'John', 'Doe', 'john.doe@steelsync.com', '1234567893', 'Male', 'employee');

-- Insert role-specific details
INSERT INTO super_admin (user_id) SELECT id FROM users WHERE role = 'super_admin';
INSERT INTO hr_admin (user_id, department, position) SELECT id, 'Human Resources', 'HR Manager' FROM users WHERE role = 'hr_admin';
INSERT INTO support_admin (user_id, support_area, position) SELECT id, 'Technical Support', 'Support Manager' FROM users WHERE role = 'support_admin';
INSERT INTO employee (user_id, department, position, hire_date) SELECT id, 'Production', 'Steel Worker', CURDATE() FROM users WHERE role = 'employee';