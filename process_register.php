<?php
session_start();
require 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validaciones del lado del servidor
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "El email no es válido.";
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
        $errors['password'] = "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número.";
    }

    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'El email ya está registrado.']);
        exit();
    }

    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Encriptar la contraseña

        // Insertar nuevo usuario con estado 'pendiente'
        $query = "INSERT INTO usuarios (nombre, email, password, rol, estado) VALUES (:nombre, :email, :password, 'cliente', 'pendiente')";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['nombre' => $nombre, 'email' => $email, 'password' => $passwordHash]);

        echo json_encode(['status' => 'success']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en los datos proporcionados.']);
        exit();
    }
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
    $stmt->execute(['email' => $email]);
    echo $stmt->fetchColumn() > 0 ? 'exists' : 'available';
}
?>