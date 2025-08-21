<?php
require 'conexion.php';

use MongoDB\BSON\ObjectId;

$db = Conexion::conectar();
$cursos = $db->cursos->find();

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dni = $_POST['dni'] ?? '';
    $nombres = $_POST['nombres'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $curso_id = $_POST['curso_id'] ?? '';

    if ($dni && $nombres && $apellido_paterno && $apellido_materno && $direccion && $curso_id) {
        $matricula = [
            "dni" => $dni,
            "nombres" => $nombres,
            "apellido_paterno" => $apellido_paterno,
            "apellido_materno" => $apellido_materno,
            "direccion" => $direccion,
            "curso_id" => new ObjectId($curso_id),
            "fecha_matricula" => date("Y-m-d H:i:s")
        ];

        $db->matriculas->insertOne($matricula);
        $mensaje = "✅ Matrícula registrada correctamente";
    } else {
        $mensaje = "❌ Todos los campos son obligatorios";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Matrícula</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script>
        function consultarDNI() {
            const dni = document.getElementById("dni").value;
            if (dni.length !== 8) {
                alert("El DNI debe tener 8 dígitos");
                return;
            }

            fetch("proxy_api.php?dni=" + dni)
                .then(response => response.json())
                .then(data => {
                    if (data && data.NOMBRES) {
                        document.getElementById("nombres").value = data.NOMBRES;
                        document.getElementById("apellido_paterno").value = data.AP_PAT;
                        document.getElementById("apellido_materno").value = data.AP_MAT;
                        document.getElementById("direccion").value = data.DIRECCION ?? "";
                    } else {
                        alert("No se encontró información para este DNI");
                    }
                })
                .catch(error => console.error("Error en la consulta:", error));
        }
    </script>

</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registrar Matrícula</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-info"><?= $mensaje ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">DNI</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="dni" id="dni" maxlength="8" required>
                            <button type="button" class="btn btn-info" onclick="consultarDNI()">Buscar</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombres</label>
                        <input type="text" class="form-control" name="nombres" id="nombres" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" name="apellido_materno" id="apellido_materno" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="direccion" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Curso</label>
                        <select class="form-select" name="curso_id" required>
                            <option value="">Seleccione un curso</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?= $curso->_id ?>">
                                    <?= $curso->nombre ?> (<?= $curso->aula ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="listar_matriculas.php" class="btn btn-secondary">Ver Matrículas</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>