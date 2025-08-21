<?php
require 'conexion.php';
$db = Conexion::conectar();

$pipeline = [
    [
        '$lookup' => [
            'from' => 'docentes',
            'localField' => 'docente_id',
            'foreignField' => '_id',
            'as' => 'docente'
        ]
    ],
    [
        '$unwind' => [
            'path' => '$docente',
            'preserveNullAndEmptyArrays' => true
        ]
    ]
];
$cursos = $db->cursos->aggregate($pipeline);
?>

<?php include "header.php"; ?> <!-- Menú de navegación -->

<div class="card shadow-lg">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">📚 Cursos Registrados</h4>
    </div>
    <div class="card-body">
        <a href="crear_curso.php" class="btn btn-primary mb-3">➕ Nuevo Curso</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Docente</th>
                    <th>Fechas</th>
                    <th>Aula</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?= $curso['nombre'] ?></td>
                        <td><?= isset($curso['docente']['nombre']) ? $curso['docente']['nombre'] : 'No asignado' ?></td>
                        <td>
                            <?php
                            $fechaInicio = $curso['fecha_inicio'];
                            $fechaFin = $curso['fecha_fin'];

                            // Si es BSON\UTCDateTime lo convertimos
                            if ($fechaInicio instanceof MongoDB\BSON\UTCDateTime) {
                                $fechaInicio = $fechaInicio->toDateTime()->format('d/m/Y');
                            }

                            if ($fechaFin instanceof MongoDB\BSON\UTCDateTime) {
                                $fechaFin = $fechaFin->toDateTime()->format('d/m/Y');
                            }

                            echo $fechaInicio . " - " . $fechaFin;
                            ?>
                        </td>

                        <td><?= $curso['aula'] ?></td>
                        <td>
                            <a href="editar_curso.php?id=<?= $curso['_id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                            <a href="eliminar_curso.php?id=<?= $curso['_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este curso?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</div> <!-- container abierto en header.php -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>