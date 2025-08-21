<?php
require 'conexion.php';


use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

$db = Conexion::conectar();
$docentes = $db->docentes->find();

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $aula = $_POST['aula'] ?? '';
    $docente_id = $_POST['docente_id'] ?? '';

    if ($nombre && $descripcion && $fecha_inicio && $fecha_fin && $aula) {
        $curso = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            /** @var MongoDB\BSON\UTCDateTime $fecha_inicio */
            "fecha_inicio" => new UTCDateTime(strtotime($fecha_inicio) * 1000),
            /** @var MongoDB\BSON\UTCDateTime $fecha_fin */
            "fecha_fin" => new UTCDateTime(strtotime($fecha_fin) * 1000),
            "aula" => $aula
        ];

        if (!empty($docente_id)) {
            $curso["docente_id"] = new ObjectId($docente_id);
        }

        $db->cursos->insertOne($curso);
        $mensaje = "✅ Curso registrado correctamente";
    } else {
        $mensaje = "❌ Los campos (nombre, descripción, fechas y aula) son obligatorios";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Curso</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registrar Curso</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-info"><?= $mensaje ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre del curso</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" class="form-control" name="fecha_fin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Aula</label>
                        <input type="text" class="form-control" name="aula" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Docente</label>
                        <select class="form-select" name="docente_id">
                            <option value="">Seleccione un docente</option>
                            <?php foreach ($docentes as $doc): ?>
                                <option value="<?= $doc->_id ?>"><?= $doc->nombre ?> (<?= $doc->especialidad ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="listar_cursos.php" class="btn btn-secondary">Ver Cursos</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>