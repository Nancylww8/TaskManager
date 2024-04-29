CREATE DATABASE IF NOT EXISTS task_management_db;
GRANT USAGE ON *.* TO 'task_management_db'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON task_management_db.* TO 'task_management_db'@'localhost';
FLUSH PRIVILEGES;


USE task_management_db;

CREATE TABLE IF NOT EXISTS users(
id Int PRIMARY KEY AUTO_INCREMENT,
username VARCHAR(50) UNIQUE NOT NULL,
email VARCHAR(50) UNIQUE NOT NULL,
password VARCHAR(50) NOT NULL);

INSERT INTO users (username, email, password) VALUES
('john_doe', 'john.doe@example.com', 'password123'),
('jane_smith', 'jane.smith@example.com', 'securepassword456');

CREATE TABLE task(
id Int PRIMARY KEY AUTO_INCREMENT,
title VARCHAR(200) NOT NULL,
description TEXT,
priority ENUM('LOW','MEDIUM','HIGH') DEFAULT 'MEDIUM',
status ENUM('TODO','IN_PROGRESS','DONE') DEFAULT 'TODO',
due_date DATE,
user_id INT,
FOREIGN KEY(user_id) REFERENCES users(id));

INSERT INTO task (title, description, priority, status, due_date, user_id) VALUES
('Complete Project Proposal', 'Write a detailed project proposal including scope, timeline, and budget.', 'HIGH', 'TODO', '2024-04-15', 1),
('Review Code Changes', 'Review and test the latest code changes in the development branch.', 'MEDIUM', 'IN_PROGRESS', '2024-04-05', 2);
