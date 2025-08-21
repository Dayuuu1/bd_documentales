<?php
require __DIR__ . '/../crud/usuarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- CREAR USUARIO ---
    if (isset($_POST['crear_usuario'])) {
        $apiResponse = [
            'DNI'       => $_POST['DNI'] ?? '',
            'AP_PAT'    => $_POST['AP_PAT'] ?? '',
            'AP_MAT'    => $_POST['AP_MAT'] ?? '',
            'NOMBRES'   => $_POST['NOMBRES'] ?? '',
            'FECHA_NAC' => $_POST['FECHA_NAC'] ?? '',
            'DIRECCION' => $_POST['DIRECCION'] ?? '',
            'SEXO'      => $_POST['SEXO'] ?? '',
            'EST_CIVIL' => $_POST['EST_CIVIL'] ?? '',
            'TELEFONO'  => $_POST['TELEFONO'] ?? ''
        ];
        $correo   = $_POST['correo'] ?? '';
        $password = $_POST['password'] ?? '';

        crearUsuario($apiResponse, $correo, $password);
        header("Location: ../index.php?mensaje=UsuarioCreado");
        exit;
    }

    // --- EDITAR USUARIO ---
    if (isset($_POST['editar_usuario'])) {
        $id = $_POST['id_usuario'] ?? '';

        $datos = [
            'dni'       => $_POST['dni'] ?? '',
            'ap_pat'    => $_POST['ap_pat'] ?? '',
            'ap_mat'    => $_POST['ap_mat'] ?? '',
            'nombres'   => $_POST['nombres'] ?? '',
            'fecha_nac' => $_POST['fecha_nac'] ?? '',
            'direccion' => $_POST['direccion'] ?? '',
            'sexo'      => $_POST['sexo'] ?? '',
            'est_civil' => $_POST['est_civil'] ?? '',
            'correo'    => $_POST['correo'] ?? '',
            'telefono'  => $_POST['telefono'] ?? ''
        ];

        if (!empty($id)) {
            actualizarUsuario($id, $datos);
            header("Location: ../index.php?mensaje=UsuarioEditado");
            exit;
        }
    }
}

// --- ELIMINAR USUARIO ---
if (isset($_GET['eliminar'])) {
    $idEliminar = $_GET['eliminar'];
    if (!empty($idEliminar)) {
        eliminarUsuario($idEliminar);
        header("Location: ../index.php?mensaje=UsuarioEliminado");
        exit;
    }
}
