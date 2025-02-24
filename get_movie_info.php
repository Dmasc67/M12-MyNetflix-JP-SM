<?php
include './db/conexion.php';

if (isset($_GET['id'])) {
    $movieId = $_GET['id'];

    // Obtener información básica de la película y el número de likes
    $query = "SELECT p.*, COUNT(l.usuario_id) AS likes 
              FROM peliculas p 
              LEFT JOIN likes l ON p.id = l.pelicula_id 
              WHERE p.id = :id 
              GROUP BY p.id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $movieId]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($movie) {
        // Obtener directores de la película
        $queryDirectores = "SELECT d.nombre 
                            FROM directores d 
                            JOIN pelicula_director pd ON d.id = pd.director_id 
                            WHERE pd.pelicula_id = :id";
        $stmtDirectores = $pdo->prepare($queryDirectores);
        $stmtDirectores->execute(['id' => $movieId]);
        $directores = $stmtDirectores->fetchAll(PDO::FETCH_COLUMN);

        // Obtener actores de la película
        $queryActores = "SELECT a.nombre 
                         FROM actores a 
                         JOIN pelicula_actor pa ON a.id = pa.actor_id 
                         WHERE pa.pelicula_id = :id";
        $stmtActores = $pdo->prepare($queryActores);
        $stmtActores->execute(['id' => $movieId]);
        $actores = $stmtActores->fetchAll(PDO::FETCH_COLUMN);

        // Obtener categorías de la película
        $queryCategorias = "SELECT c.nombre 
                            FROM categorias c 
                            JOIN pelicula_categoria pc ON c.id = pc.categoria_id 
                            WHERE pc.pelicula_id = :id";
        $stmtCategorias = $pdo->prepare($queryCategorias);
        $stmtCategorias->execute(['id' => $movieId]);
        $categorias = $stmtCategorias->fetchAll(PDO::FETCH_COLUMN);

        // Añadir directores, actores y categorías al array de la película
        $movie['directores'] = $directores;
        $movie['actores'] = $actores;
        $movie['categorias'] = $categorias;

        echo json_encode($movie);
    } else {
        echo json_encode(['error' => 'Película no encontrada']);
    }
} else {
    echo json_encode(['error' => 'ID de película no proporcionado']);
}
?>