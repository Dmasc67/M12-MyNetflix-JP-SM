<?php
include '../db/conexion.php';
session_start();

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Verificar si los índices de $_POST existen antes de usarlos
$filter = isset($_POST['filter']) ? $_POST['filter'] : 'all';
$searchTitle = isset($_POST['search_title']) ? $_POST['search_title'] : '';
$searchDirector = isset($_POST['search_director']) ? $_POST['search_director'] : '';
$searchCategory = isset($_POST['search_category']) ? $_POST['search_category'] : '';
$searchYear = isset($_POST['search_year']) ? $_POST['search_year'] : ''; // Nuevo campo para el año

// Depuración: Verificar los valores recibidos
error_log("Valores recibidos:");
error_log("filter: " . $filter);
error_log("search_title: " . $searchTitle);
error_log("search_director: " . $searchDirector);
error_log("search_category: " . $searchCategory);
error_log("search_year: " . $searchYear);

try {
    // Consulta base
    $moviesQuery = "SELECT p.*, COUNT(l.usuario_id) AS likes, 
                    (SELECT COUNT(*) FROM likes WHERE pelicula_id = p.id AND usuario_id = :user_id) AS user_like 
                    FROM peliculas p 
                    LEFT JOIN likes l ON p.id = l.pelicula_id 
                    LEFT JOIN pelicula_director pd ON p.id = pd.pelicula_id 
                    LEFT JOIN directores d ON pd.director_id = d.id 
                    LEFT JOIN pelicula_categoria pc ON p.id = pc.pelicula_id 
                    LEFT JOIN categorias c ON pc.categoria_id = c.id 
                    WHERE 1=1";

    // Filtro por like / no like
    if ($filter === 'liked') {
        $moviesQuery .= " AND p.id IN (SELECT pelicula_id FROM likes WHERE usuario_id = :user_id)";
    } elseif ($filter === 'unliked') {
        $moviesQuery .= " AND p.id NOT IN (SELECT pelicula_id FROM likes WHERE usuario_id = :user_id)";
    }

    // Filtro por título
    if (!empty($searchTitle)) {
        $moviesQuery .= " AND p.titulo LIKE :search_title";
    }

    // Filtro por director
    if (!empty($searchDirector)) {
        $moviesQuery .= " AND d.nombre LIKE :search_director";
    }

    // Filtro por categoría
    if (!empty($searchCategory)) {
        $moviesQuery .= " AND c.nombre LIKE :search_category";
    }

    // Filtro por año
    if (!empty($searchYear)) {
        $moviesQuery .= " AND p.año = :search_year"; // Filtrar por la columna "año"
    }

    $moviesQuery .= " GROUP BY p.id ORDER BY p.titulo";

    // Depuración: Verificar la consulta SQL generada
    error_log("Consulta SQL: " . $moviesQuery);

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

    if (!empty($searchYear)) {
        $params['search_year'] = $searchYear; // Añadir el año a los parámetros
    }

    // Depuración: Verificar los parámetros
    error_log("Parámetros: " . print_r($params, true));

    $stmt->execute($params);
    $moviesResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Depuración: Verificar los resultados
    error_log("Resultados: " . print_r($moviesResult, true));

    echo json_encode($moviesResult);
} catch (PDOException $e) {
    // Depuración: Capturar errores de la base de datos
    error_log("Error en la consulta SQL: " . $e->getMessage());
    echo json_encode(["error" => "Error en la consulta SQL"]);
}
?>