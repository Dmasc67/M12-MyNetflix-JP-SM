<?php
session_start();
require '../db/conexion.php';

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    echo "ID o acción no proporcionada.";
    exit();
}

$id = $_GET['id'];
$action = $_GET['action'];

if ($action === 'activate') {
    $query = $pdo->prepare("UPDATE usuarios SET estado = 'activo' WHERE id = ?");
    $successMessage = "Usuario activado con éxito.";
} elseif ($action === 'deactivate') {
    $query = $pdo->prepare("UPDATE usuarios SET estado = 'inactivo' WHERE id = ?");
    $successMessage = "Usuario desactivado con éxito.";
} elseif ($action === 'delete') {
    $query = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $successMessage = "Usuario eliminado con éxito.";
} else {
    echo "Acción no válida.";
    exit();
}

if ($query->execute([$id])) {
    echo $successMessage;
} else {
    echo "Error al realizar la acción.";
}
?>
