-- ============================
-- DATABASE
-- ============================
Drop database IF Exists crypto_puzzle_db;

CREATE DATABASE IF NOT EXISTS crypto_puzzle_db;
USE crypto_puzzle_db;

-- ============================
-- USERS TABLE
-- ============================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(100) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,
    
    score INT DEFAULT 0,

    correct_answers INT DEFAULT 0,

    questions_answered INT DEFAULT 0,

    start_time DATETIME NULL,

    is_logged_in TINYINT(1) DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================
-- QUESTIONS TABLE
-- ============================

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,

    question TEXT NOT NULL,

    answer VARCHAR(255) NOT NULL,

    correct_answer VARCHAR(255) NOT NULL,

    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'easy',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================
-- SAMPLE USERS
-- ============================

INSERT INTO users (username, password, score, correct_answers, questions_answered, is_logged_in)
VALUES
('kanguva', '$2y$10$OX573J9irO57ERi//cFjbeiadAfkHcggiamwE57EwA/0jD371Qo1y', 0, 0, 0, 0);


-- ============================
-- SAMPLE QUESTIONS
-- ============================

INSERT INTO questions (question, answer, correct_answer, difficulty)
VALUES
(
 'What does AES stand for in cryptography?',
 'Advanced Encryption Standard',
 'Advanced Encryption Standard',
 'easy'
),
(
 'Which algorithm is commonly used for secure password hashing?',
 'bcrypt',
 'bcrypt',
 'easy'
),
(
 'What key size is considered secure for RSA today?',
 '2048 bits',
 '2048 bits',
 'medium'
),
(
 'What does SHA-256 belong to?',
 'Hash function',
 'Hash function',
 'medium'
),
(
 'Which cryptographic method uses two keys?',
 'Asymmetric encryption',
 'Asymmetric encryption',
 'hard'
);
