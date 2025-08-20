<?php
session_start();
$db = require __DIR__ . '/conexion/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Guardar post
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['contenido'])) {
    $contenido = trim($_POST['contenido']);
    $db->posts->insertOne([
        "usuario_id" => new MongoDB\BSON\ObjectId($_SESSION['usuario_id']),
        "contenido" => $contenido,
        "fecha" => new MongoDB\BSON\UTCDateTime()
    ]);
    header("Location: home.php");
    exit;
}

// Mostrar posts
$posts = $db->posts->find([], ['sort' => ['fecha' => -1]]);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Red Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">MiRed</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Hola, <?= htmlspecialchars($_SESSION['nombre']) ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Publicar nuevo post -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Crear una publicación</h5>
                <form method="POST">
                    <div class="mb-3">
                        <textarea class="form-control" name="contenido" rows="3" placeholder="¿Qué estás pensando?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </form>
            </div>
        </div>

        <!-- Mostrar posts -->
        <h4 class="mb-3">Posts recientes</h4>

        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <p class="card-text">
                            <?= htmlspecialchars($post['contenido'] ?? '') ?>
                        </p>
                        <small class="text-muted">
                            <?php
                            if (isset($post['fecha']) && $post['fecha'] instanceof MongoDB\BSON\UTCDateTime) {
                                echo $post['fecha']->toDateTime()->format('d-m-Y H:i');
                            }
                            ?>
                        </small>
                    </div>

                    <!-- Mostrar comentarios -->
                    <div class="card-body border-top">
                        <h6 class="text-muted">Comentarios:</h6>
                        <?php
                        $comentarios = $db->comentarios->find(
                            ["post_id" => $post['_id']],
                            ["sort" => ["fecha" => -1]]
                        );

                        foreach ($comentarios as $comentario):
                            // Mostrar "Yo" si el comentario es del usuario logueado
                            if (
                                isset($_SESSION['usuario_id']) &&
                                (string) $comentario['usuario_id'] === (string) $_SESSION['usuario_id']
                            ) {
                                $nombre = "Yo";
                            } else {
                                $nombre = $comentario['usuario'] ?? "Anónimo";
                            }
                        ?>
                            <div class="mb-2">
                                <strong><?= htmlspecialchars($nombre) ?></strong>:
                                <?= htmlspecialchars($comentario['comentario'] ?? '') ?><br>
                                <small class="text-muted">
                                    <?php
                                    if (isset($comentario['fecha']) && $comentario['fecha'] instanceof MongoDB\BSON\UTCDateTime) {
                                        echo $comentario['fecha']->toDateTime()->format('d-m-Y H:i');
                                    }
                                    ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Formulario para comentar -->
                    <div class="card-footer">
                        <form action="comentar.php" method="POST" class="d-flex">
                            <input type="hidden" name="post_id" value="<?= htmlspecialchars((string) $post['_id']) ?>">
                            <input type="text" name="comentario" class="form-control me-2" placeholder="Escribe un comentario" required>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Comentar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No hay posts disponibles.</p>
        <?php endif; ?>



    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>