-- docathome_complete.sql
-- DocAtHome Complete Database Schema
-- Includes all tables and columns needed

DROP DATABASE IF EXISTS docathome;
CREATE DATABASE docathome CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE docathome;

-- Bookings table with status column
CREATE TABLE bookings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  type ENUM('chat','video') NOT NULL,
  notes TEXT,
  status ENUM('pending','completed') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messages table for chat functionality
CREATE TABLE messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  booking_id INT UNSIGNED NOT NULL,
  sender ENUM('doctor','patient') NOT NULL,
  message TEXT NOT NULL,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data (optional - for testing)
INSERT INTO bookings (name, email, phone, type, notes, status) VALUES
('Test Patient', 'patient@test.com', '9876543210', 'video', 'This is a test booking', 'pending');

-- Done
SELECT 'Database setup complete!' as Status;
