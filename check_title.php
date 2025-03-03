<?php
require 'db/conexion.php';

// Obtener los datos enviados en la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$titulo = $data['titulo'] ?? '';

$response = ['exists' => false];

if ($titulo) {
    // Preparar y ejecutar la consulta para verificar si el título ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM peliculas WHERE titulo = ?");
    $stmt->execute([$titulo]);
    $count = $stmt->fetchColumn();

    // Si el título ya existe, actualizar la respuesta
    if ($count > 0) {
        $response['exists'] = true;
    }
}

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>
