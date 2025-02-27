<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

require '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['id'];

    try {
        // Iniciar una transacción para asegurar la atomicidad
        $pdo->beginTransaction();

        // 1. Obtener la ruta de la imagen antes de eliminar la película
        $query = "SELECT caratula FROM peliculas WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $movieId]);
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$movie) {
            echo json_encode(['status' => 'error', 'message' => 'Película no encontrada']);
            exit();
        }

        $caratulaPath = $movie['caratula'];

        // 2. Eliminar la imagen del servidor si existe
        if (file_exists($caratulaPath)) {
            if (!unlink($caratulaPath)) {
                throw new Exception("No se pudo eliminar la imagen del servidor.");
            }
        }

        // 3. Eliminar la película de la base de datos (las relaciones se eliminarán automáticamente)
        $query = "DELETE FROM peliculas WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $movieId]);

        // Confirmar la transacción
        $pdo->commit();

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la película: ' . $e->getMessage()]);
    }
    exit();
}

// Si llegamos aquí, significa que no se ha ejecutado el método POST
echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
exit();
?>