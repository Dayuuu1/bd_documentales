<?php
$bd = require __DIR__ . '/../conexion/conexion.php';
use MongoDB\BSON\ObjectId;

// Crear usuario con datos mínimos de la API
function crearUsuario($apiResponse, $correo = null, $password = null)
{
    global $bd;

    $usuario = [
        'dni' => $apiResponse['DNI'] ?? '',
        'ap_pat' => $apiResponse['AP_PAT'] ?? '',
        'ap_mat' => $apiResponse['AP_MAT'] ?? '',
        'nombres' => $apiResponse['NOMBRES'] ?? '',
        'fecha_nac' => $apiResponse['FECHA_NAC'] ?? '',
        'direccion' => $apiResponse['DIRECCION'] ?? '',
        'sexo' => $apiResponse['SEXO'] ?? '',
        'est_civil' => $apiResponse['EST_CIVIL'] ?? '',
        'correo' => $correo ?? '',
        'telefono' => $apiResponse['TELEFONO'] ?? '',
        'password' => $password ? password_hash($password, PASSWORD_DEFAULT) : ''
    ];

    $resultado = $bd->usuarios->insertOne($usuario);
    return $resultado->getInsertedId();
}

// Leer usuarios
function listarUsuarios() {
    global $bd;
    return $bd->usuarios->find()->toArray();
}

// Actualizar usuario
function actualizarUsuario($id, $datos) {
    global $bd;
    return $bd->usuarios->updateOne(
        ['_id' => new ObjectId($id)],
        ['$set' => $datos]
    );
}

// Eliminar usuario
function eliminarUsuario($id) {
    global $bd;
    return $bd->usuarios->deleteOne(['_id' => new ObjectId($id)]);
}
