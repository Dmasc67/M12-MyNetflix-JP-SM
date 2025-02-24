<?php
include './db/conexion.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['movie_id'];
    $userId = $_SESSION['user_id'];

    // Verificar si el usuario ya dio like a la película
    $checkLikeQuery = "SELECT * FROM likes WHERE usuario_id = :user_id AND pelicula_id = :movie_id";
    $stmt = $pdo->prepare($checkLikeQuery);
    $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);
    $likeExists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($likeExists) {
        // Si ya existe, quitar el like
        $deleteLikeQuery = "DELETE FROM likes WHERE usuario_id = :user_id AND pelicula_id = :movie_id";
        $stmt = $pdo->prepare($deleteLikeQuery);
        $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);
        $action = 'unliked';
    } else {
        // Si no existe, agregar el like
        $insertLikeQuery = "INSERT INTO likes (usuario_id, pelicula_id) VALUES (:user_id, :movie_id)";
        $stmt = $pdo->prepare($insertLikeQuery);
        $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);
        $action = 'liked';
    }

    // Obtener el nuevo número de likes
    $countLikesQuery = "SELECT COUNT(*) AS likes FROM likes WHERE pelicula_id = :movie_id";
    $stmt = $pdo->prepare($countLikesQuery);
    $stmt->execute(['movie_id' => $movieId]);
    $likesCount = $stmt->fetch(PDO::FETCH_ASSOC)['likes'];

    // Verificar si el usuario actual ha dado like a esta película
    $userLikeQuery = "SELECT COUNT(*) AS user_like FROM likes WHERE usuario_id = :user_id AND pelicula_id = :movie_id";
    $stmt = $pdo->prepare($userLikeQuery);
    $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);
    $userLike = $stmt->fetch(PDO::FETCH_ASSOC)['user_like'];

    echo json_encode([
        'status' => 'success',
        'action' => $action,
        'likes' => $likesCount,
        'user_like' => $userLike
    ]);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
exit();
?>