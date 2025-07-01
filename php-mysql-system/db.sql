-- Database schema for replacement request system

CREATE DATABASE IF NOT EXISTS replacement_system;
USE replacement_system;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

-- Employees table
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rut VARCHAR(12) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    planta VARCHAR(100),
    turno VARCHAR(50),
    grado VARCHAR(50)
);

-- Replacement requests table
CREATE TABLE IF NOT EXISTS replacement_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    fecha_ausencia DATE NOT NULL,
    fecha_inicio_reemplazo DATE NOT NULL,
    fecha_termino_reemplazo DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);
