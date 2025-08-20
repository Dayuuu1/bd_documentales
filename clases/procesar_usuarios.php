<?php
require __DIR__ . '/../crud/usuarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_usuario'])) {
        $apiResponse = [
            'DNI' => $_POST['DNI'] ?? '',
            'AP_PAT' => $_POST['AP_PAT'] ?? '',
            'AP_MAT' => $_POST['AP_MAT'] ?? '',
            'NOMBRES' => $_POST['NOMBRES'] ?? '',
            'FECHA_NAC' => $_POST['FECHA_NAC'] ?? '',
            'DIRECCION' => $_POST['DIRECCION'] ?? '',
            'SEXO' => $_POST['SEXO'] ?? '',
            'EST_CIVIL' => $_POST['EST_CIVIL'] ?? '',
            'TELEFONO' => $_POST['TELEFONO'] ?? ''
        ];
        $correo = $_POST['correo'] ?? '';
        $password = $_POST['password'] ?? '';

        $idUser = crearUsuario($apiResponse, $correo, $password);

        header("Location: ../index.php?mensaje=UsuarioCreado");
        exit;
    }

    if (isset($_POST['editar_usuario'])) {
        $id = $_POST['id_usuario'] ?? '';
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'correo' => $_POST['correo'] ?? '',
            'edad' => (int)($_POST['edad'] ?? 0)
        ];
        if ($id) {
            actualizarUsuario($id, $datos);
            header("Location: ../index.php?mensaje=UsuarioEditado");
            exit;
        }
    }
}

if (isset($_GET['eliminar'])) {
    $idEliminar = $_GET['eliminar'];
    if (!empty($idEliminar)) {
        eliminarUsuario($idEliminar);
        header("Location: ../index.php?mensaje=UsuarioEliminado");
        exit;
    }
}
