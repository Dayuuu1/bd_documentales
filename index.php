<?php
require 'crud/usuarios.php';
require 'crud/posts.php';
require 'crud/comentarios.php';

$mensaje = "";
$usuarios = listarUsuarios();

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    eliminarUsuario($id);
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Red Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
        }

        .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
        }

        .card {
            border-radius: 12px;
        }

        .modal-content {
            border-radius: 12px;
        }

        table th,
        table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">SKY</a>

            <div class="d-flex">
                <a href="login.php" class="btn btn-light btn-sm">
                    Iniciar sesión
                </a>
            </div>
        </div>
    </nav>


    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Gestión de Usuarios</h2>
            <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
             Crear Usuario
            </button>
        </div>

        <!-- Alert -->
        <?php if ($mensaje): ?>
            <div class="alert alert-info shadow-sm"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <!-- LISTA DE USUARIOS -->
        <div class="card shadow-sm">
            <div class="card-header ">
                <h5 class="mb-0">Lista de Usuarios</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>DNI</th>
                            <th>Nombre Completo</th>
                            <th>Correo</th>
                            <th>Fecha Nac.</th>
                            <th>Sexo</th>
                            <th>Estado Civil</th>
                            <th>Teléfono</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['dni'] ?? '') ?></td>
                                <td><?= htmlspecialchars(($u['ap_pat'] ?? '') . ' ' . ($u['ap_mat'] ?? '') . ' ' . ($u['nombres'] ?? '')) ?></td>
                                <td><?= htmlspecialchars($u['correo'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['fecha_nac'] ?? '') ?></td>
                                <td><?= ($u['sexo'] ?? '') == 1 ? 'Masculino' : 'Femenino' ?></td>
                                <td><?= htmlspecialchars($u['est_civil'] ?? '') ?></td>
                                <td><?= htmlspecialchars($u['telefono'] ?? '') ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editarUsuarioModal"
                                        data-id="<?= $u['_id'] ?>"
                                        data-dni="<?= htmlspecialchars($u['dni'] ?? '') ?>"
                                        data-ap_pat="<?= htmlspecialchars($u['ap_pat'] ?? '') ?>"
                                        data-ap_mat="<?= htmlspecialchars($u['ap_mat'] ?? '') ?>"
                                        data-nombres="<?= htmlspecialchars($u['nombres'] ?? '') ?>"
                                        data-fecha_nac="<?= htmlspecialchars($u['fecha_nac'] ?? '') ?>"
                                        data-direccion="<?= htmlspecialchars($u['direccion'] ?? '') ?>"
                                        data-sexo="<?= htmlspecialchars($u['sexo'] ?? '') ?>"
                                        data-est_civil="<?= htmlspecialchars($u['est_civil'] ?? '') ?>"
                                        data-correo="<?= htmlspecialchars($u['correo'] ?? '') ?>"
                                        data-telefono="<?= htmlspecialchars($u['telefono'] ?? '') ?>">
                                        Editar
                                    </button>
                                    <a href="index.php?eliminar=<?= $u['_id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar este usuario?')">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No hay usuarios registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL CREAR USUARIO -->
        <div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="clases/procesar_usuarios.php">
                        <input type="hidden" name="crear_usuario" value="1">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="crearUsuarioLabel">➕ Crear Nuevo Usuario</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">DNI:</label>
                                    <div class="input-group">
                                        <input type="text" id="dni" name="DNI" class="form-control" required>
                                        <button type="button" id="buscarDNI" class="btn btn-secondary">Buscar</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Apellido Paterno:</label>
                                    <input type="text" id="ap_pat" name="AP_PAT" class="form-control" required>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Apellido Materno:</label>
                                    <input type="text" id="ap_mat" name="AP_MAT" class="form-control" required>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Nombres:</label>
                                    <input type="text" id="nombres" name="NOMBRES" class="form-control" required>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Fecha de Nacimiento:</label>
                                    <input type="date" id="fecha_nac" name="FECHA_NAC" class="form-control" required>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Dirección:</label>
                                    <input type="text" id="direccion" name="DIRECCION" class="form-control" required>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Sexo:</label>
                                    <select id="sexo" name="SEXO" class="form-select" required>
                                        <option value="1">Masculino</option>
                                        <option value="2">Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Estado Civil:</label>
                                    <input type="text" id="est_civil" name="EST_CIVIL" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Correo:</label>
                                    <input type="email" name="correo" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Teléfono:</label>
                                    <input type="text" name="TELEFONO" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Contraseña:</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL EDITAR USUARIO -->
        <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="clases/procesar_usuarios.php">
                        <input type="hidden" name="editar_usuario" value="1">
                        <input type="hidden" name="id_usuario" id="modal_id">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title" id="editarUsuarioLabel">Editar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label">DNI:</label>
                                <input type="text" name="dni" id="modal_dni" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellido Paterno:</label>
                                <input type="text" name="ap_pat" id="modal_ap_pat" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellido Materno:</label>
                                <input type="text" name="ap_mat" id="modal_ap_mat" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombres:</label>
                                <input type="text" name="nombres" id="modal_nombres" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Nacimiento:</label>
                                <input type="date" name="fecha_nac" id="modal_fecha_nac" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dirección:</label>
                                <input type="text" name="direccion" id="modal_direccion" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sexo:</label>
                                <select name="sexo" id="modal_sexo" class="form-select">
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado Civil:</label>
                                <input type="text" name="est_civil" id="modal_est_civil" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono:</label>
                                <input type="text" name="telefono" id="modal_telefono" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correo:</label>
                                <input type="email" name="correo" id="modal_correo" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-info">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div> <!-- container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Pasar datos al modal de edición
        var editarModal = document.getElementById('editarUsuarioModal')
        editarModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget
            document.getElementById('modal_id').value = button.getAttribute('data-id')
            document.getElementById('modal_dni').value = button.getAttribute('data-dni')
            document.getElementById('modal_ap_pat').value = button.getAttribute('data-ap_pat')
            document.getElementById('modal_ap_mat').value = button.getAttribute('data-ap_mat')
            document.getElementById('modal_nombres').value = button.getAttribute('data-nombres')
            document.getElementById('modal_fecha_nac').value = button.getAttribute('data-fecha_nac')
            document.getElementById('modal_direccion').value = button.getAttribute('data-direccion')
            document.getElementById('modal_sexo').value = button.getAttribute('data-sexo')
            document.getElementById('modal_est_civil').value = button.getAttribute('data-est_civil')
            document.getElementById('modal_telefono').value = button.getAttribute('data-telefono')
            document.getElementById('modal_correo').value = button.getAttribute('data-correo')
        })

        // Buscar DNI con API
        document.getElementById('buscarDNI').addEventListener('click', function() {
            const dni = document.getElementById('dni').value.trim();
            if (!dni) return alert('Ingresa un DNI');
            fetch(`proxy_api.php?dni=${dni}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.DNI) {
                        document.getElementById('ap_pat').value = data.AP_PAT || '';
                        document.getElementById('ap_mat').value = data.AP_MAT || '';
                        document.getElementById('nombres').value = data.NOMBRES || '';
                        document.getElementById('fecha_nac').value = data.FECHA_NAC || '';
                        document.getElementById('direccion').value = data.DIRECCION || '';
                        document.getElementById('sexo').value = data.SEXO || '';
                        document.getElementById('est_civil').value = data.EST_CIVIL || '';
                    } else {
                        alert('No se encontró información para ese DNI');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error al consultar la API');
                });
        });
    </script>

</body>

</html>