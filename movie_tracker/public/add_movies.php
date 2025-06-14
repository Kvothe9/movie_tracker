<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/tmdb.php'; // Funciones TMDB

$mensaje = null;

// Procesar adición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tmdb_id'])) {
    $id = $_POST['tmdb_id'];
    $detalles = obtenerDetallesPelicula($id);

    if ($detalles) {
        $titulo = $detalles['title'] ?? 'Sin título';
        $descripcion = $detalles['overview'] ?? '';
        $anio = isset($detalles['release_date']) ? substr($detalles['release_date'], 0, 4) : null;
        $genero = $detalles['genres'][0]['name'] ?? 'Desconocido';

        // Buscar director
        $director = 'Desconocido';
        if (isset($detalles['credits']['crew'])) {
            foreach ($detalles['credits']['crew'] as $persona) {
                if ($persona['job'] === 'Director') {
                    $director = $persona['name'];
                    break;
                }
            }
        }

        $poster_path = $detalles['poster_path'] ?? null;

        // Evitar duplicados
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM movies WHERE title = :title");
        $stmt->execute([':title' => $titulo]);
        $existe = $stmt->fetchColumn();

        if ($existe == 0) {
            $stmt = $pdo->prepare("INSERT INTO movies (title, genre, release_year, director, description, poster_path) VALUES (:title, :genre, :year, :director, :description, :poster)");
            $stmt->execute([
                ':title' => $titulo,
                ':genre' => $genero,
                ':year' => $anio,
                ':director' => $director,
                ':description' => $descripcion,
                ':poster' => $poster_path
            ]);
            $mensaje = "Película <strong>$titulo</strong> añadida correctamente.";
        } else {
            $mensaje = "La película <strong>$titulo</strong> ya existe.";
        }
    } else {
        $mensaje = "Error al obtener detalles de la película.";
    }
}

// Procesar búsqueda
$peliculas = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo']) && empty($_POST['tmdb_id'])) {
    $query = trim($_POST['titulo']);
    if ($query !== '') {
        $peliculas = buscarPeliculasTMDB($query);
    }
}
?>

<div class="container">
    <h1><i class="fas fa-search"></i> Buscar película</h1>

    <form method="POST">
        <div class="search-box">
            <input type="text" name="titulo" class="search-input" placeholder="Busca tu película favorita..." required>
            <button type="submit" class="search-btn"><i class="fas fa-search"></i> Buscar</button>
        </div>
    </form>

    <?php if ($mensaje): ?>
        <p class="info-msg"><?= $mensaje ?></p>
    <?php endif; ?>

    <?php if (!empty($peliculas)): ?>
    <div class="movies-grid" style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
        <?php
        $imageBase = 'https://image.tmdb.org/t/p/w200';
        foreach ($peliculas as $movie):
            $poster = $movie['poster_path'] ? $imageBase . $movie['poster_path'] : 'ruta/a/imagen_defecto.jpg';
        ?>
            <div class="movie-card" style="width: 250px;">
                <img src="<?= $poster ?>" alt="Poster de <?= htmlspecialchars($movie['title']) ?>" class="movie-poster" style="width: 100%; border-radius: 12px;">
                <div class="movie-info" style="padding: 10px 0;">
                    <h3 class="movie-title" style="font-size: 16px; margin: 5px 0;"><?= htmlspecialchars($movie['title']) ?></h3>
                    <p class="movie-year" style="font-size: 14px; color: #888; margin: 0 0 8px;">
                        <?= isset($movie['release_date']) ? substr($movie['release_date'], 0, 4) : 'Desconocido' ?>
                    </p>
                    <div class="movie-actions">
                        <form method="POST">
                            <input type="hidden" name="tmdb_id" value="<?= $movie['id'] ?>">
                            <button type="submit" class="action-btn btn-watchlist" style="width: 100%;">Añadir</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>


</div>

<?php require_once '../includes/footer.php'; ?>
