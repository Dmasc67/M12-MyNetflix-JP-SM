<?php
require '../db/conexion.php';

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    exit("ID o acción no proporcionada.");
}

$id = $_GET['id'];
$action = $_GET['action'];

if ($action === 'activate') {
    $query = $pdo->prepare("UPDATE usuarios SET estado = 'activo' WHERE id = ?");
    $successMessage = "Usuario activado con éxito.";
} elseif ($action === 'delete') {
    $query = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $successMessage = "Usuario eliminado con éxito.";
} else {
    exit("Acción no válida.");
}

if ($query->execute([$id])) {
    echo $successMessage;
} else {
    echo "Error al realizar la acción.";
}
?>

