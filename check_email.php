<?php
require 'db/conexion.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
    $stmt->execute(['email' => $email]);
    echo $stmt->fetchColumn() > 0 ? 'exists' : 'available';
}
?>
