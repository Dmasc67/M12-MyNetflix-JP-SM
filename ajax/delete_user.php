<?php
session_start();
require '../db/conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado']);
    exit();
}

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];

    // Eliminar el usuario
    $query = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $userId]);

    echo json_encode(['status' => 'success']);
    exit();
}

// Si no es una solicitud POST, devolver un error
echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
exit();
?>