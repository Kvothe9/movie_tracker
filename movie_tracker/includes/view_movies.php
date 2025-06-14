<?php

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cambiar estado
    if (isset($_POST['movie_id']) && isset($_POST['new_status'])) {
        $stmt = $pdo->prepare("UPDATE movies SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $_POST['new_status'],
            ':id' => $_POST['movie_id']
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Cambiar puntuación
    if (isset($_POST['movie_id']) && isset($_POST['new_rating'])) {
        $rating = (int)$_POST['new_rating'];
        if ($rating < 1 || $rating > 10) {
            $rating = null;
        }
        $stmt = $pdo->prepare("UPDATE movies SET rating = :rating WHERE id = :id");
        $stmt->execute([
            ':rating' => $rating,
            ':id' => $_POST['movie_id']
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Eliminar película
    if (isset($_POST['delete_id'])) {
        $stmt = $pdo->prepare("DELETE FROM movies WHERE id = :id");
        $stmt->execute([':id' => $_POST['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM movies ORDER BY release_year DESC");
?>

<div class="container">
    <h1><i class="fas fa-film"></i> Lista de Películas</h1>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #4c51bf; color: white;">
                <th>Portada</th>
                <th>Título</th>
                <th>Género</th>
                <th>Año</th>
                <th>Director</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Puntuación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $imageBase = 'https://image.tmdb.org/t/p/w200';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $posterUrl = $row['poster_path'] ? $imageBase . $row['poster_path'] : 'ruta/a/imagen_defecto.jpg';
            echo "<tr style='border-bottom: 1px solid #ddd;'>";
            echo "<td><img src='" . htmlspecialchars($posterUrl) . "' alt='Portada' style='width: 80px; border-radius: 6px;'></td>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['genre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['release_year']) . "</td>";
            echo "<td>" . htmlspecialchars($row['director']) . "</td>";
            echo "<td style='max-width: 250px; overflow-wrap: break-word;'>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status'] ?? 'no visto') . "</td>";
            echo "<td>" . ($row['rating'] !== null ? (int)$row['rating'] : '-') . "</td>";

            echo "<td>
                <form method='POST' style='display:inline-block; margin-bottom:3px;'>
                    <input type='hidden' name='movie_id' value='" . $row['id'] . "'>
                    <select name='new_status' onchange='this.form.submit()' style='padding: 2px; border-radius: 4px;'>
                        <option value='no visto'" . ($row['status'] === 'no visto' ? ' selected' : '') . ">No visto</option>
                        <option value='pendiente'" . ($row['status'] === 'pendiente' ? ' selected' : '') . ">Pendiente</option>
                        <option value='visto'" . ($row['status'] === 'visto' ? ' selected' : '') . ">Visto</option>
                    </select>
                </form>

                <form method='POST' style='display:inline-block; margin-left:5px; margin-bottom:3px;'>
                    <input type='hidden' name='movie_id' value='" . $row['id'] . "'>
                    <input type='number' name='new_rating' min='1' max='10' value='" . ($row['rating'] ?? '') . "' style='width:50px; padding: 2px; border-radius: 4px;' onchange='this.form.submit()' placeholder='Puntúa'>
                </form>

                <form method='POST' style='display:inline-block; margin-left:5px;' onsubmit='return confirm(\"¿Eliminar película?\");'>
                    <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                    <button type='submit' style='color:red; border:none; background:none; cursor:pointer;'>Eliminar</button>
                </form>
            </td>";

            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>



<style>
    .table-container {
        max-width: 90%;
        margin: 40px auto;
        padding: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
    }

    thead {
        background-color: #4f46e5; /* Indigo-600 */
        color: white;
    }

    th, td {
        padding: 14px 18px;
        text-align: left;
    }

    tr:nth-child(even) {
        background-color: #f9fafb; /* Light gray */
    }

    tr:hover {
        background-color: #eef2ff; /* Indigo-100 */
    }

    td {
        color: #374151; /* Gray-700 */
    }
</style>



<?php require_once '../includes/footer.php'; ?>
