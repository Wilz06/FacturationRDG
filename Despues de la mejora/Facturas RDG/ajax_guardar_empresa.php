<?php
session_start();
header('Content-Type: application/json');

function getAjaxPath($path) {
    return __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
}

require_once getAjaxPath('config/database.php');
require_once getAjaxPath('models/Empresa.php');

$response = ['status' => 'error', 'message' => 'Solicitud no válida'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = trim($_POST['nombre_proveedor'] ?? '');
        $documento = trim($_POST['numero_proveedor'] ?? '');
        $tipo_doc = trim($_POST['tipo_documento'] ?? 'RUC');

        if (empty($nombre)) {
            $response['message'] = 'El nombre es obligatorio';
            echo json_encode($response);
            exit;
        }
        if (empty($documento)) {
            $response['message'] = 'El número de documento es obligatorio';
            echo json_encode($response);
            exit;
        }

        $data = [
            'nombre' => $nombre,
            'documento' => $documento,
            'tipo_documento' => $tipo_doc,
        ];

        $model = new Empresa();
        
        if ($model->create($data)) {
            $nuevoId = $model->getUltimoId();
            
            $response = [
                'status' => 'success',
                'message' => 'Proveedor guardado exitosamente',
                'id_proveedor' => $nuevoId,
                'nombre_proveedor' => $nombre
            ];
        } else {
            $response['message'] = 'Error al guardar. El RUC/Documento ya podría existir.';
        }

    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }
}

echo json_encode($response);
exit;
?>