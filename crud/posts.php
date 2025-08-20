<?php
$bd = require __DIR__ . '/../conexion/conexion.php';
use MongoDB\BSON\ObjectId;

function crearPost($titulo, $contenido, $id_usuario) {
    global $bd;
    $resultado = $bd->posts->insertOne([
        'titulo' => $titulo,
        'contenido' => $contenido,
        'id_usuario' => new ObjectId($id_usuario)
    ]);
    return $resultado->getInsertedId();
}

function listarPosts() {
    global $bd;
    return $bd->posts->find()->toArray();
}

function actualizarPost($id, $datos) {
    global $bd;
    return $bd->posts->updateOne(
        ['_id' => new ObjectId($id)],
        ['$set' => $datos]
    );
}

function eliminarPost($id) {
    global $bd;
    return $bd->posts->deleteOne(['_id' => new ObjectId($id)]);
}
