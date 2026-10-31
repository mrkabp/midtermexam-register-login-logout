CREATE DATABASE IF NOT EXISTS aaa_game_db;

USE aaa_game_db;

CREATE TABLE studios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    studio_name VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL
);

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_name VARCHAR(100) NOT NULL,
    genre VARCHAR(50) NOT NULL,
    release_year INT NOT NULL,
    studio_id INT NOT NULL,
    FOREIGN KEY (studio_id)
        REFERENCES studios(id)
        ON DELETE CASCADE
);