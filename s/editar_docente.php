<?php
require 'conexion.php';
use MongoDB\BSON\ObjectId;

$db = Conexion::conectar();

$id = $_GET['id'] ?? null;
if (!$id) die("❌ ID no válido");

// Buscar docente
$docente = $db->docentes->findOne(['_id' => new ObjectId($id)]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $update = [
        "nombre" => $_POST['nombre'],
        "especialidad" => $_POST['especialidad'],
        "email" => $_POST['email']
    ];

    $db->docentes->updateOne(
        ['_id' => new ObjectId($id)],
        ['$set' => $update]
    );

    header("Location: listar_docentes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Docente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Editar Docente</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre del docente</label>
                    <input type="text" class="form-control" name="nombre" value="<?= $docente['nombre'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Especialidad</label>
                    <input type="text" class="form-control" name="especialidad" value="<?= $docente['especialidad'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" value="<?= $docente['email'] ?>" required>
                </div>
                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="listar_docentes.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
