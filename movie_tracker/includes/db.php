<?php
$host = 'localhost';
$db   = 'movie_tracker';
$user = 'root';
$pass = ''; // o tu contraseña si tienes una

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo; // 👈 esto es clave
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
