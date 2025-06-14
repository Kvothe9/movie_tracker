<?php
function autenticarUsuario($usuario, $contrasena) {
    $pdo = require __DIR__ . '/db.php'; // 👈 así recibes el PDO

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $usuario);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['password'] === $contrasena) {
            return $user['username'];
        } else {
            return false;
        }

    } catch (PDOException $e) {
        echo "Error al autenticar: " . $e->getMessage();
        return false;
    }
}
