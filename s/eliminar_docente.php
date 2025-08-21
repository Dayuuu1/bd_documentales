<?php
require 'conexion.php';
$db = Conexion::conectar();

$id = $_GET['id'] ?? null;

if ($id) {
    $db->docentes->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}

header("Location: listar_docentes.php");
exit;
