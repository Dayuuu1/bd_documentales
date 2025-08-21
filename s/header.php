
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Académica</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">📘 Ceinfo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" aria-controls="navbarNav" 
                    aria-expanded="false" aria-label="Menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- Cursos -->
                    <li class="nav-item">
                        <a class="nav-link" href="listar_cursos.php">Cursos</a>
                    </li>
                    <!-- Docentes -->
                    <li class="nav-item">
                        <a class="nav-link" href="listar_docentes.php">Docentes</a>
                    </li>
                    <!-- Matrículas -->
                    <li class="nav-item">
                        <a class="nav-link" href="listar_matriculas.php">Matrículas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
