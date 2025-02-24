<?php
include './db/conexion.php';
session_start();

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Obtener el número de likes y si el usuario actual ha dado like
$query = "SELECT p.id AS pelicula_id, 
                 COUNT(l.usuario_id) AS count, 
                 SUM(CASE WHEN l.usuario_id = :user_id THEN 1 ELSE 0 END) AS user_like 
          FROM peliculas p 
          LEFT JOIN likes l ON p.id = l.pelicula_id 
          GROUP BY p.id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $userId]);
$likes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'status' => 'success',
    'likes' => $likes
]);
exit();
?>