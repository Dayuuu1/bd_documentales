<?php
require 'conexion.php';
$db = Conexion::conectar();

$pipeline = [
    [
        '$lookup' => [
            'from' => 'cursos',
            'localField' => 'curso_id',
            'foreignField' => '_id',
            'as' => 'curso'
        ]
    ],
    [
        '$unwind' => [
            'path' => '$curso',
            'preserveNullAndEmptyArrays' => true
        ]
    ]
];
$matriculas = $db->matriculas->aggregate($pipeline);
?>

<?php include "header.php"; ?> <!-- Menú de navegación -->

<div class="card shadow-lg">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">📋 Matrículas Registradas</h4>
    </div>
    <div class="card-body">
        <a href="crear_matricula.php" class="btn btn-primary mb-3">➕ Nueva Matrícula</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Alumno</th>
                    <th>Dirección</th>
                    <th>Curso</th>
                    <th>Fecha Matrícula</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matriculas as $mat): ?>
                    <tr>
                        <td><?= $mat['dni'] ?></td>
                        <td><?= $mat['nombres'] . ' ' . $mat['apellido_paterno'] . ' ' . $mat['apellido_materno'] ?></td>
                        <td><?= $mat['direccion'] ?></td>
                        <td><?= isset($mat['curso']['nombre']) ? $mat['curso']['nombre'] : 'No asignado' ?></td>
                        <td>
                            <?php
                            $fecha = $mat['fecha_matricula'] ?? null;
                            echo $fecha ? date("d/m/Y H:i", strtotime($fecha)) : '-';
                            ?>
                        </td>
                        <td>
                            <a href="editar_matricula.php?id=<?= $mat['_id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                            <a href="eliminar_matricula.php?id=<?= $mat['_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta matrícula?')">🗑️ Eliminar</a>
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
