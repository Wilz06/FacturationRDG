<?php
header('Content-Type: application/json');

$url = 'https://open.er-api.com/v6/latest/USD';
$json_data = @file_get_contents($url);

$fallback_rate = 3.37;
$response = ['status' => 'error', 'rate' => $fallback_rate];

if ($json_data === FALSE) {
    $response['message'] = 'No se pudo conectar a la API de tipo de cambio.';
    echo json_encode($response);
    exit;
}

$data = json_decode($json_data);

if (isset($data->rates->PEN)) {
    $response = [
        'status' => 'success',
        'rate' => $data->rates->PEN
    ];
} else {
    $response['message'] = 'Respuesta de API no válida.';
}

echo json_encode($response);
exit;
?>