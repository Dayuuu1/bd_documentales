<?php
$bd = require __DIR__ . '/../conexion/conexion.php';
use MongoDB\BSON\ObjectId;

function crearComentario($contenido, $id_post, $id_usuario) {
    global $bd;
    $resultado = $bd->comentarios->insertOne([
        'contenido' => $contenido,
        'id_post' => new ObjectId($id_post),
        'id_usuario' => new ObjectId($id_usuario)
    ]);
    return $resultado->getInsertedId();
}

function listarComentarios() {
    global $bd;
    return $bd->comentarios->find()->toArray();
}

function actualizarComentario($id, $datos) {
    global $bd;
    return $bd->comentarios->updateOne(
        ['_id' => new ObjectId($id)],
        ['$set' => $datos]
    );
}

function eliminarComentario($id) {
    global $bd;
    return $bd->comentarios->deleteOne(['_id' => new ObjectId($id)]);
}
