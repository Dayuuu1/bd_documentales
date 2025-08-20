<?php
if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];
    $url = "https://apidatos.unamad.edu.pe/api/consulta/$dni";

    // Llamada a la API remota
    $data = file_get_contents($url);

    // Devuelve la respuesta como JSON al navegador
    header('Content-Type: application/json');
    echo $data;
}
