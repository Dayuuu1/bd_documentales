<?php
require 'conexion.php';
$db = Conexion::conectar();

// Obtener todos los docentes
$docentes = $db->docentes->find();
?>

<?php include "header.php"; ?> <!-- Menú de navegación -->

<div class="card shadow-lg">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">👨‍🏫 Docentes Registrados</h4>
    </div>
    <div class="card-body">
        <a href="crear_docente.php" class="btn btn-primary mb-3">➕ Nuevo Docente</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docentes as $doc): ?>
                    <tr>
                        <td><?= $doc['nombre'] ?></td>
                        <td><?= $doc['especialidad'] ?? 'No registrada' ?></td>
                        <td><?= $doc['email'] ?? 'No registrado' ?></td>
                        <td>
                            <a href="editar_docente.php?id=<?= $doc['_id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                            <a href="eliminar_docente.php?id=<?= $doc['_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este docente?')">🗑️ Eliminar</a>
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
