<?php
session_start();
require 'db/conexion.php';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $año = $_POST['año'];
    $duracion = $_POST['duracion'];

    // Manejo de la carátula
    if (isset($_FILES['caratula']) && $_FILES['caratula']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['caratula']['tmp_name'];
        $fileName = $_FILES['caratula']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Validar la extensión del archivo
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Directorio donde se guardará la carátula
            $uploadFileDir = './img/peliculas/';
            $dest_path = $uploadFileDir . $fileName;

            // Mover el archivo a la carpeta de destino
            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                // Insertar la nueva película en la base de datos
                $insertQuery = "INSERT INTO peliculas (titulo, descripcion, año, duracion, caratula) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($insertQuery);
                $stmt->execute([$titulo, $descripcion, $año, $duracion, $fileName]);
                echo "La película se ha añadido correctamente.";
            } else {
                echo "Error al mover el archivo.";
            }
        } else {
            echo "Tipo de archivo no permitido.";
        }
    } else {
        echo "Error en la carga del archivo.";
    }
}
?> 