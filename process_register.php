<?php
session_start();
require 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña

    // Insertar nuevo usuario con estado 'pendiente'
    $query = "INSERT INTO usuarios (nombre, email, password, rol, estado) VALUES (:nombre, :email, :password, 'cliente', 'pendiente')";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['nombre' => $nombre, 'email' => $email, 'password' => $password]);

    // Redirigir o mostrar mensaje
    header("Location: index.php");
}
?>