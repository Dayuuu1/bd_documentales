<?php
$db = require __DIR__ . "/conexion/conexion.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $usuario = $db->usuarios->findOne(['correo' => $email]);

    if ($usuario && password_verify($password, $usuario['password'])) {
        session_start();
        $_SESSION['usuario_id'] = (string)$usuario['_id'];
        $_SESSION['nombre'] = $usuario['nombres'] . ' ' . $usuario['ap_pat'];

        header("Location: home.php");
        exit;
    } else {
        $mensaje = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Red Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4"> Iniciar Sesión</h3>

                        <?php if (!empty($mensaje)): ?>
                            <div class="alert alert-danger text-center"><?= $mensaje ?></div>
                        <?php endif; ?>


                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="ejemplo@email.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                        </form>

                        <p class="text-center mt-3">
                            ¿No tienes cuenta? <a href="index.php">Regístrate</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>