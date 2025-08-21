<?php
if (isset($_GET['dni'])) {
    $dni = trim($_GET['dni']);
    $url = "https://apidatos.unamad.edu.pe/api/consulta/$dni";

    // Opcional: usar contexto HTTP para timeout
    $context = stream_context_create([
        'http' => [
            'timeout' => 5
        ]
    ]);

    $data = @file_get_contents($url, false, $context);

    if ($data === false) {
        // Si falla la API
        http_response_code(500);
        echo json_encode([
            "error" => true,
            "message" => "No se pudo obtener datos de la API."
        ]);
        exit;
    }

    // Devuelve la respuesta como JSON
    header('Content-Type: application/json; charset=utf-8');
    echo $data;
} else {
    http_response_code(400);
    echo json_encode([
        "error" => true,
        "message" => "Debe enviar un DNI en la URL (?dni=12345678)."
    ]);
}
