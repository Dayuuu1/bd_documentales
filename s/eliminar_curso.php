<?php
require 'conexion.php';
$db = Conexion::conectar();

$id = $_GET['id'] ?? null;
if ($id) {
    $db->cursos->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}
header("Location: listar_cursos.php");
exit;
