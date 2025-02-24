<?php
session_start();
require 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para verificar el usuario
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['estado'] === 'pendiente') {
            $_SESSION['error_message'] = 'Tu solicitud aún está pendiente. Por favor, espera la activación de tu cuenta.';
            header("Location: index.php");
            exit();
        } elseif ($user['estado'] === 'inactivo') {
            $_SESSION['error_message'] = 'Tu cuenta ha sido desactivada. Contacta al administrador.';
            header("Location: index.php");
            exit();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['rol'];
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = 'Credenciales incorrectas.';
        header("Location: index.php");
        exit();
    }
}
?>