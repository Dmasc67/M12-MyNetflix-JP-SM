<?php
include './db/conexion.php';
session_start();

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$filter = $_POST['filter'];
$searchTitle = $_POST['search_title'];
$searchDirector = $_POST['search_director'];
$searchCategory = $_POST['search_category'];
$searchDate = $_POST['search_date'];

$moviesQuery = "SELECT p.*, COUNT(l.usuario_id) AS likes, 
                (SELECT COUNT(*) FROM likes WHERE pelicula_id = p.id AND usuario_id = :user_id) AS user_like 
                FROM peliculas p 
                LEFT JOIN likes l ON p.id = l.pelicula_id 
                WHERE 1=1";

if ($filter === 'liked') {
    $moviesQuery .= " AND p.id IN (SELECT pelicula_id FROM likes WHERE usuario_id = :user_id)";
} elseif ($filter === 'unliked') {
    $moviesQuery .= " AND p.id NOT IN (SELECT pelicula_id FROM likes WHERE usuario_id = :user_id)";
}

if (!empty($searchTitle)) {
    $moviesQuery .= " AND p.titulo LIKE :search_title";
}

if (!empty($searchDirector)) {
    $moviesQuery .= " AND p.director LIKE :search_director";
}

if (!empty($searchCategory)) {
    $moviesQuery .= " AND p.categoria LIKE :search_category";
}

if (!empty($searchDate)) {
    $moviesQuery .= " AND p.fecha_estreno = :search_date";
}

$moviesQuery .= " GROUP BY p.id ORDER BY p.titulo";

$stmt = $pdo->prepare($moviesQuery);
$params = ['user_id' => $userId];

if (!empty($searchTitle)) {
    $params['search_title'] = '%' . $searchTitle . '%';
}

if (!empty($searchDirector)) {
    $params['search_director'] = '%' . $searchDirector . '%';
}

if (!empty($searchCategory)) {
    $params['search_category'] = '%' . $searchCategory . '%';
}

if (!empty($searchDate)) {
    $params['search_date'] = $searchDate;
}

$stmt->execute($params);
$moviesResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($moviesResult);
?>