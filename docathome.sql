-- docathome.sql
-- DocAtHome database schema (LAMP college project)
-- Database: docathome

-- Minimal DocAtHome schema (only bookings)
DROP DATABASE IF EXISTS docathome;
CREATE DATABASE docathome CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE docathome;

CREATE TABLE bookings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  type ENUM('chat','video') NOT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Done
