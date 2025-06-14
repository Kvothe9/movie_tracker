-- Crear la base de datos 'movie_tracker'
CREATE DATABASE IF NOT EXISTS movie_tracker;

-- Seleccionar la base de datos
USE movie_tracker;

-- Crear la tabla 'movies' para almacenar las películas
CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    release_year INT,
    director VARCHAR(255),
    description TEXT,
    poster_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear la tabla 'users' para almacenar la información de los usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE movies
ADD COLUMN status ENUM('pendiente', 'visto', 'no visto') DEFAULT 'no visto',
ADD COLUMN rating TINYINT UNSIGNED DEFAULT NULL;


-- Insertar datos de ejemplo en la tabla 'users'
INSERT INTO users (username, password, email) VALUES
('admin', 'adminpassword', 'admin@example.com'),
('user1', 'userpassword', 'user1@example.com');
