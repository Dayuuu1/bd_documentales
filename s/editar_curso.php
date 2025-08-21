<?php
require 'conexion.php';
use MongoDB\BSON\ObjectId;

$db = Conexion::conectar();

$id = $_GET['id'] ?? null;
if (!$id) die("❌ ID no válido");

// Buscar curso
$curso = $db->cursos->findOne(['_id' => new ObjectId($id)]);
$docentes = $db->docentes->find();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $update = [
        "nombre" => $_POST['nombre'],
        "descripcion" => $_POST['descripcion'],
        "fecha_inicio" => $_POST['fecha_inicio'], // se guarda como string YYYY-MM-DD
        "fecha_fin" => $_POST['fecha_fin'],
        "aula" => $_POST['aula']
    ];

    // Solo agregar docente_id si se seleccionó
    if (!empty($_POST['docente_id'])) {
        $update["docente_id"] = new ObjectId($_POST['docente_id']);
    } else {
        $update["docente_id"] = null; // se guarda null si no se elige
    }

    $db->cursos->updateOne(
        ['_id' => new ObjectId($id)],
        ['$set' => $update]
    );

    header("Location: listar_cursos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Curso</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Editar Curso</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre del curso</label>
                    <input type="text" class="form-control" name="nombre" value="<?= $curso['nombre'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="descripcion" required><?= $curso['descripcion'] ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" value="<?= $curso['fecha_inicio'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" class="form-control" name="fecha_fin" value="<?= $curso['fecha_fin'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Aula</label>
                    <input type="text" class="form-control" name="aula" value="<?= $curso['aula'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Docente</label>
                    <select class="form-select" name="docente_id">
                        <option value="">-- Sin asignar --</option>
                        <?php foreach ($docentes as $doc): ?>
                            <option value="<?= $doc->_id ?>" 
                                <?= (isset($curso['docente_id']) && $curso['docente_id'] == $doc->_id) ? 'selected' : '' ?>>
                                <?= $doc->nombre ?> (<?= $doc->especialidad ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="listar_cursos.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
