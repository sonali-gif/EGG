-- Online Voting System Database Schema
-- MySQL Database Setup

CREATE DATABASE IF NOT EXISTS voting_system;
USE voting_system;

-- Voters Table
CREATE TABLE IF NOT EXISTS voters (
    id INT PRIMARY KEY AUTO_INCREMENT,
    voter_id VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Elections Table
CREATE TABLE IF NOT EXISTS elections (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    status ENUM('active', 'closed', 'pending') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Candidates Table
CREATE TABLE IF NOT EXISTS candidates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    election_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    party VARCHAR(100),
    symbol VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    INDEX (election_id)
);

-- Votes Table
CREATE TABLE IF NOT EXISTS votes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    voter_id INT NOT NULL,
    candidate_id INT NOT NULL,
    election_id INT NOT NULL,
    vote_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voter_id) REFERENCES voters(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vote (voter_id, election_id),
    INDEX (candidate_id),
    INDEX (election_id)
);

-- Create Indexes for Performance
CREATE INDEX idx_voter_id ON voters(voter_id);
CREATE INDEX idx_election_status ON elections(status);

-- Insert Test Data

-- Test Admin Account (password: admin123)
INSERT INTO voters (voter_id, full_name, email, password, is_admin) VALUES
('ADMIN001', 'Admin User', 'admin@voting.com', '$2y$10$abcdefghijklmnopqrstuvwxyz.HashedPassword', 1);

-- Test Voter Accounts
INSERT INTO voters (voter_id, full_name, email, password, is_admin) VALUES
('VOT001', 'John Doe', 'john@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz.HashedPassword', 0),
('VOT002', 'Jane Smith', 'jane@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz.HashedPassword', 0),
('VOT003', 'Bob Johnson', 'bob@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz.HashedPassword', 0);

-- Test Election
INSERT INTO elections (title, description, start_date, end_date, status) VALUES
('General Elections 2026', 'Vote for your preferred candidate', '2026-02-01', '2026-02-20', 'active');

-- Test Candidates
INSERT INTO candidates (election_id, name, party, symbol, description) VALUES
(1, 'Alice Johnson', 'Progressive Party', '🔴', 'Focused on education and healthcare reform'),
(1, 'Bob Williams', 'Democratic Alliance', '🔵', 'Advocate for infrastructure development'),
(1, 'Carol Brown', 'Green Initiative', '🟢', 'Environmental protection and sustainability'),
(1, 'David Lee', 'Unity Front', '🟡', 'National unity and economic growth');

-- Note: Passwords above are placeholders. To create real accounts:
-- Use: password_hash('password', PASSWORD_BCRYPT) in PHP
-- Example: password_hash('admin123', PASSWORD_BCRYPT) generates a proper hash
