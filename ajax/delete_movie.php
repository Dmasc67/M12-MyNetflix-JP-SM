<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

require '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['id'];

    // Eliminar película
    $query = "DELETE FROM peliculas WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $movieId]);

    echo json_encode(['status' => 'success']);
    exit();
}

// Si llegamos aquí, significa que no se ha ejecutado el método POST
echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
exit();
?>