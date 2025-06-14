<?php
session_start();

// Incluir los archivos necesarios
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lógica para iniciar sesión
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $usuarioAutenticado = autenticarUsuario($usuario, $contrasena);

    if ($usuarioAutenticado) {
        $_SESSION['usuario'] = $usuarioAutenticado;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<main>
    <body>
        <h1>Bienvenido a Mi Biblioteca de Películas</h1>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="container">
            <div class="header">
                <div class="logo">
                    <i class="fas fa-film"></i>
                    <h1>MovieTracker</h1>
                </div>

            <div id="auth-section">
                <form id="login-form" method="POST">
                    <div class="form-group">
                        <input type="text" name="usuario" class="form-input" id="login-username" placeholder="Usuario" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="contrasena" class="form-input" id="login-password" placeholder="Contraseña" required>
                    </div>
                        <button type="submit" class="form-btn">Iniciar Sesión</button>
                            <i class="fas fa-sign-in-alt"></i>
                </form>
            </div>
        </div>
    </body>

</main>

<?php require_once '../includes/footer.php'; ?>

