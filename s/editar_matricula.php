<?php
require 'conexion.php';
use MongoDB\BSON\ObjectId;

$db = Conexion::conectar();

$id = $_GET['id'] ?? null;
if (!$id) die("❌ ID no válido");

// Buscar matrícula
$matricula = $db->matriculas->findOne(['_id' => new ObjectId($id)]);
if (!$matricula) die("❌ Matrícula no encontrada");

// Obtener cursos disponibles
$cursos = $db->cursos->find();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $update = [
        "dni" => $_POST['dni'],
        "nombres" => $_POST['nombres'],
        "apellido_paterno" => $_POST['apellido_paterno'],
        "apellido_materno" => $_POST['apellido_materno'],
        "direccion" => $_POST['direccion'],
    ];

    // Guardar curso_id si se selecciona
    if (!empty($_POST['curso_id'])) {
        $update["curso_id"] = new ObjectId($_POST['curso_id']);
    } else {
        $update["curso_id"] = null;
    }

    $db->matriculas->updateOne(
        ['_id' => new ObjectId($id)],
        ['$set' => $update]
    );

    header("Location: listar_matriculas.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Matrícula</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Editar Matrícula</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">DNI</label>
                    <input type="text" class="form-control" name="dni" value="<?= $matricula['dni'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombres</label>
                    <input type="text" class="form-control" name="nombres" value="<?= $matricula['nombres'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" name="apellido_paterno" value="<?= $matricula['apellido_paterno'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" name="apellido_materno" value="<?= $matricula['apellido_materno'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" name="direccion" value="<?= $matricula['direccion'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Curso</label>
                    <select class="form-select" name="curso_id">
                        <option value="">-- Sin asignar --</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso->_id ?>" 
                                <?= (isset($matricula['curso_id']) && $matricula['curso_id'] == $curso->_id) ? 'selected' : '' ?>>
                                <?= $curso->nombre ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="listar_matriculas.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
