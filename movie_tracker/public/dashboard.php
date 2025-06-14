<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require_once '../includes/header.php';
require_once '../includes/db.php';

echo "<h1>Panel de Control</h1>";
echo "<p>Bienvenido, " . htmlspecialchars($_SESSION['usuario']) . ".</p>";

echo '<a href="add_movies.php" style="display: inline-block; margin-bottom: 15px; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Añadir película</a>';

// Aquí se muestran las películas
require_once '../includes/view_movies.php';

require_once '../includes/footer.php';
?>
