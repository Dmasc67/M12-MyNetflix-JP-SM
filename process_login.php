<?php
session_start();
require 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validaciones del lado del servidor
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'El email no es válido.']);
        exit();
    }

    // Consulta para verificar el usuario
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'El correo ingresado no está registrado en nuestra web.']);
        exit();
    }

    if ($user && password_verify($password, $user['password'])) {
        if ($user['estado'] === 'pendiente') {
            echo json_encode(['status' => 'error', 'message' => 'Tu cuenta está pendiente de validación por un administrador.']);
            exit();
        } elseif ($user['estado'] === 'inactivo') {
            echo json_encode(['status' => 'error', 'message' => 'Tu cuenta ha sido desactivada. Contacta al administrador.']);
            exit();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['rol'];
        $_SESSION['username'] = $user['nombre'];

        echo json_encode(['status' => 'success', 'username' => $user['nombre']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Credenciales incorrectas.']);
    }
    exit();
}
?>