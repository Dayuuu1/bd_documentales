<?php
session_start();

// Cargar la conexión a MongoDB
$db = require __DIR__ . '/conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['usuario_id'])) {
    if (!empty($_POST['post_id']) && !empty($_POST['comentario'])) {
        $post_id = $_POST['post_id'];
        $comentario = trim($_POST['comentario']);

        try {
            $db->comentarios->insertOne([
                "post_id"    => new MongoDB\BSON\ObjectId($post_id),
                "usuario_id" => new MongoDB\BSON\ObjectId($_SESSION['usuario_id']),
                "usuario"    => $_SESSION['usuario_nombre'] ?? "Anónimo", // Guardamos nombre
                "comentario" => $comentario,
                "fecha"      => new MongoDB\BSON\UTCDateTime()
            ]);
        } catch (Exception $e) {
            die("Error al guardar comentario: " . $e->getMessage());
        }
    }
    header("Location: home.php");
    exit;
}
?>
