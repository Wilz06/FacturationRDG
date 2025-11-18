<?php
session_start();
header('Content-Type: application/json');

function getImportPath($path) {
    return __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
}

require_once getImportPath('config/database.php');
require_once getImportPath('models/Factura.php');
require_once getImportPath('models/Producto.php');

$response = ['status' => 'error', 'message' => 'Solicitud no válida.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_compra'])) {
    
    $file = $_FILES['archivo_compra'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'Error al subir el archivo.';
        echo json_encode($response);
        exit;
    }

    $tmp_name = $file['tmp_name'];
    
    try {
        $file_content = file_get_contents($tmp_name);
        $file_content = preg_replace("/\xef\xbb\xbf/", "", $file_content);
        $lines = preg_split('/\r\n|\r|\n/', $file_content);

        $clean_lines = [];
        foreach ($lines as $line) {
            $trimmed_line = trim($line);
            if (!empty($trimmed_line)) {
                $clean_lines[] = $trimmed_line;
            }
        }
        
        if (count($clean_lines) < 2) {
             $response['message'] = 'El archivo no tiene el formato esperado (mínimo 2 líneas).';
            echo json_encode($response);
            exit;
        }

        $all_data_parts = str_getcsv($clean_lines[1]);
        $facturaHeader = [];
        $itemsData = [];
        
        $codigo_index = -1;
        $product_header_keys = ['CODIGO', 'CANT', 'DESCRIPCION', 'LABORATORIO', 'LOTE', 'F. VENC', 'P. UNIT.', 'IMPORTE'];
        $product_col_count = 0;
        $header_map = [];

        foreach($all_data_parts as $index => $part) {
            $key = trim(strtoupper($part));
            if (in_array($key, $product_header_keys)) {
                 if ($codigo_index == -1 && $key == 'CODIGO') {
                    $codigo_index = $index;
                 }
                 $header_map[strtoupper($key)] = $product_col_count; 
                 $product_col_count++;
            }
        }

        if ($codigo_index === -1) {
            $response['message'] = 'No se pudo encontrar la cabecera "CODIGO" en la línea de datos.';
            echo json_encode($response);
            exit;
        }

        for ($i = 0; $i < $codigo_index; $i += 2) {
            $key = trim($all_data_parts[$i]);
            $value = trim($all_data_parts[$i+1] ?? '');
            if (!empty($key)) {
                $facturaHeader[$key] = $value;
            }
        }
        
        $product_data_start_index = $codigo_index + $product_col_count;
        
        if (isset($all_data_parts[$product_data_start_index])) {
            $temp_product_data = [];
            for ($k = $product_data_start_index; $k < count($all_data_parts); $k++) {
                $temp_product_data[] = $all_data_parts[$k];
            }
            
            for ($k = 0; $k < count($temp_product_data); $k += $product_col_count) {
                if (isset($temp_product_data[$k + $product_col_count - 1])) {
                    $itemsData[] = [
                        'codigo' => trim($temp_product_data[$k + 0]),
                        'cantidad' => floatval($temp_product_data[$k + 1]),
                        'descripcion' => trim($temp_product_data[$k + 2]),
                        'laboratorio' => trim($temp_product_data[$k + 3]),
                        'lote' => trim($temp_product_data[$k + 4]),
                        'fecha_vencimiento' => trim($temp_product_data[$k + 5]),
                        'precio_unitario' => floatval($temp_product_data[$k + 6]),
                        'importe' => floatval($temp_product_data[$k + 7])
                    ];
                }
            }
        }
        
        $facturaData = [
            'tipo_pago' => 'CONTADO',
            'estado' => 'REGISTRADO',
            'numero_factura' => $facturaHeader['Número de Serie'] ?? null,
            'laboratorio' => $facturaHeader['Emisor'] ?? null,
            'ruc' => $facturaHeader['RUC Emisor'] ?? null,
            'cliente' => $facturaHeader['Receptor'] ?? null,
            'subtotal' => floatval($facturaHeader['Subtotal'] ?? 0),
            'igv' => floatval($facturaHeader['IGV'] ?? 0),
            'monto_total' => floatval($facturaHeader['TOTAL'] ?? 0),
            'moneda' => $facturaHeader['Moneda'] ?? 'SOLES'
        ];
        
        $date = DateTime::createFromFormat('Y-m-d', $facturaHeader['Fecha de Emisión'] ?? '');
        if ($date === false) {
             $date = DateTime::createFromFormat('d/m/Y', $facturaHeader['Fecha de Emisión'] ?? '');
        }
        
        if ($date) {
            $facturaData['fecha_emision'] = $date->format('Y-m-d');
        }

        if (empty($facturaData['numero_factura']) || $facturaData['monto_total'] == 0) {
            $response['message'] = 'No se pudieron leer los datos principales de la factura desde el archivo. Verifique que las claves "Número de Serie" y "TOTAL" existan.';
            echo json_encode($response);
            exit;
        }
        
        if (empty($itemsData)) {
            $response['message'] = 'Se leyeron los datos de la factura, pero no se encontró ningún producto. Verifique el formato.';
            echo json_encode($response);
            exit;
        }
        
        $facturaModel = new Factura();
        
        if ($facturaModel->existeNumeroFactura($facturaData['numero_factura'])) {
            $response['message'] = 'Esta factura (' . $facturaData['numero_factura'] . ') ya ha sido importada.';
            echo json_encode($response);
            exit;
        }
        
        if ($facturaModel->create($facturaData)) {
            $facturaId = $facturaModel->getUltimoId();
            $productoModel = new Producto();
            $itemCount = 0;
            
            foreach ($itemsData as $item) {
                $item['factura_id'] = $facturaId;
                
                $fecha_venc_str = $item['fecha_vencimiento'];
                $date_venc = DateTime::createFromFormat('d/m/Y', $fecha_venc_str);
                if ($date_venc) {
                    $item['fecha_vencimiento'] = $date_venc->format('Y-m-d');
                } else {
                    $item['fecha_vencimiento'] = null;
                }
                
                if ($productoModel->create($item)) {
                    $itemCount++;
                }
            }
            
            $response = [
                'status' => 'success',
                'message' => 'Importación exitosa. Se creó la factura ' . $facturaData['numero_factura'] . ' con ' . $itemCount . ' productos.'
            ];
            
        } else {
            $response['message'] = 'Error: No se pudo crear la factura en la base de datos.';
        }
        
    } catch (Exception $e) {
        $response['message'] = 'Error al procesar el archivo: ' . $e->getMessage();
    }
    
    echo json_encode($response);
    exit;
}

echo json_encode($response);
exit;
?>