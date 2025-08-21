<?php
require 'conexion.php';

$db = Conexion::conectar();

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $especialidad = $_POST['especialidad'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($nombre && $especialidad && $email) {
        $docente = [
            "nombre" => $nombre,
            "especialidad" => $especialidad,
            "email" => $email
        ];

        $db->docentes->insertOne($docente);
        $mensaje = "✅ Docente registrado correctamente";
    } else {
        $mensaje = "❌ Todos los campos son obligatorios";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Docente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registrar Docente</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-info"><?= $mensaje ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre del docente</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Especialidad</label>
                        <input type="text" class="form-control" name="especialidad" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="listar_docentes.php" class="btn btn-secondary">Ver Docentes</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
