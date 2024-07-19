CREATE DATABASE IF NOT EXISTS taskminder;
USE taskminder

CREATE TABLE user (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE category (
    categoryId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE task (
    taskId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    dueDate DATE,
    priority VARCHAR(50),
    status VARCHAR(50),
    categoryId INT,
    occurenceId INT NULL,
    notificationId INT NULL,
    FOREIGN KEY (userId) REFERENCES user(userId),
    FOREIGN KEY (categoryId) REFERENCES category(categoryId)
);

CREATE TABLE notification (
    notificationId INT AUTO_INCREMENT PRIMARY KEY,
    taskId INT,
    userId INT,
    reminderTime DATETIME,
    notificationSent BOOLEAN,
    FOREIGN KEY (taskId) REFERENCES task(taskId) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES user(userId)
);

CREATE TABLE occurence (
    occurenceId INT AUTO_INCREMENT PRIMARY KEY,
    taskId INT,
    pattern VARCHAR(255),
    occurenceInterval INT,
    FOREIGN KEY (taskId) REFERENCES task(taskId) ON DELETE CASCADE
);

ALTER TABLE task
ADD CONSTRAINT fk_occurenceId
FOREIGN KEY (occurenceId) REFERENCES occurence(occurenceId) ON DELETE CASCADE,
ADD CONSTRAINT fk_notificationId
FOREIGN KEY (notificationId) REFERENCES notification(notificationId) ON DELETE CASCADE;
