<?php
session_start();
$db = require __DIR__ . '/conexion/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

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

$posts = $db->posts->find([], ['sort' => ['fecha' => -1]]);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>MiRed - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .post-card {
            border-radius: 12px;
        }
        .post-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        .comment-box {
            border-radius: 20px;
        }
        .create-post textarea {
            border-radius: 12px;
            resize: none;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">SKY</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 fw-semibold">
                    Hola, <?= htmlspecialchars($_SESSION['nombre']) ?>
                </span>
                <a href="logout.php" class="btn btn-light btn-sm rounded-pill">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container my-4" style="max-width: 700px;">

        <!-- Crear publicación -->
        <div class="card shadow-sm mb-4 create-post">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nombre']) ?>&background=random" class="post-avatar" alt="Perfil">
                    <h6 class="mb-0"><?= htmlspecialchars($_SESSION['nombre']) ?></h6>
                </div>
                <form method="POST">
                    <div class="mb-2">
                        <textarea class="form-control" name="contenido" rows="3" placeholder="¿Qué estás pensando?" required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-pill">Publicar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Feed de posts -->
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-4 shadow-sm post-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://ui-avatars.com/api/?name=User&background=0D8ABC&color=fff" class="post-avatar" alt="Usuario">
                            <div>
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($_SESSION['nombre']) ?></h6>
                                <small class="text-muted">
                                    <?php
                                    if (isset($post['fecha']) && $post['fecha'] instanceof MongoDB\BSON\UTCDateTime) {
                                        echo $post['fecha']->toDateTime()->format('d M Y H:i');
                                    }
                                    ?>
                                </small>
                            </div>
                        </div>
                        <p class="mt-2"><?= nl2br(htmlspecialchars($post['contenido'] ?? '')) ?></p>
                    </div>

                    <!-- Comentarios -->
                    <div class="card-body border-top">
                        <?php
                        $comentarios = $db->comentarios->find(
                            ["post_id" => $post['_id']],
                            ["sort" => ["fecha" => -1]]
                        );
                        foreach ($comentarios as $comentario):
                            $nombre = (
                                isset($_SESSION['usuario_id']) &&
                                (string) $comentario['usuario_id'] === (string) $_SESSION['usuario_id']
                            ) ? "Yo" : ($comentario['usuario'] ?? "Anónimo");
                        ?>
                            <div class="mb-2">
                                <strong><?= htmlspecialchars($nombre) ?></strong>:
                                <?= htmlspecialchars($comentario['comentario'] ?? '') ?><br>
                                <small class="text-muted">
                                    <?php
                                    if (isset($comentario['fecha']) && $comentario['fecha'] instanceof MongoDB\BSON\UTCDateTime) {
                                        echo $comentario['fecha']->toDateTime()->format('d M Y H:i');
                                    }
                                    ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Comentar -->
                    <div class="card-footer bg-light">
                        <form action="comentar.php" method="POST" class="d-flex">
                            <input type="hidden" name="post_id" value="<?= htmlspecialchars((string) $post['_id']) ?>">
                            <input type="text" name="comentario" class="form-control me-2 comment-box" placeholder="Escribe un comentario..." required>
                            <button type="submit" class="btn btn-sm btn-primary rounded-pill">Comentar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center">No hay publicaciones todavía.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
