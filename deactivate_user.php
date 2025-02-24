<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

require 'db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];

    // Desactivar usuario
    $query = "UPDATE usuarios SET estado = 'inactivo' WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $userId]);

    echo json_encode(['status' => 'success']);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
exit();
?>