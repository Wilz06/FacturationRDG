<?php
require_once __DIR__ . '/../models/Factura.php';
require_once __DIR__ . '/../models/Empresa.php';
require_once __DIR__ . '/../models/Cliente.php';

class FacturaController {
    private $model;
    private $empresaModel;
    private $clienteModel;
    
    public function __construct() {
        $this->model = new Factura();
        $this->empresaModel = new Empresa();
        $this->clienteModel = new Cliente();
    }
    
    // Listar todas las facturas
    public function index() {
        $facturas = $this->model->getAll();
        $empresas = $this->empresaModel->getAll();
        require_once __DIR__ . '/../views/facturas/index.php';
    }
    
    // Mostrar formulario de creación
    public function crear() {
        $empresas = $this->empresaModel->getAll();
        $clientes = $this->clienteModel->getAll();
        require_once __DIR__ . '/../views/facturas/crear.php';
    }
    
    // Guardar nueva factura
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeData($_POST);
            
            if ($this->model->existeNumeroFactura($data['numero_factura'])) {
                $_SESSION['error'] = "El número de factura ya existe";
                header('Location: index.php?action=crear');
                exit;
            }
            
            if ($this->model->create($data)) {
                $_SESSION['success'] = "Factura creada exitosamente";
                header('Location: index.php');
            } else {
                $_SESSION['error'] = "Error al crear la factura";
                header('Location: index.php?action=crear');
            }
            exit;
        }
    }
    
    // Mostrar formulario de edición
    public function editar($id) {
        $factura = $this->model->getById($id);
        if (!$factura) {
            $_SESSION['error'] = "Factura no encontrada";
            header('Location: index.php');
            exit;
        }
        require_once __DIR__ . '/../views/facturas/editar.php';
    }
    
    // Actualizar factura
    public function actualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeData($_POST);
            
            if ($this->model->existeNumeroFactura($data['numero_factura'], $id)) {
                $_SESSION['error'] = "El número de factura ya existe";
                header('Location: index.php?action=editar&id=' . $id);
                exit;
            }
            
            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = "Factura actualizada exitosamente";
                header('Location: index.php');
            } else {
                $_SESSION['error'] = "Error al actualizar la factura";
                header('Location: index.php?action=editar&id=' . $id);
            }
            exit;
        }
    }
    
    // Eliminar factura
    public function eliminar($id) {
        if ($this->model->delete($id)) {
            $_SESSION['success'] = "Factura eliminada exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar la factura";
        }
        header('Location: index.php');
        exit;
    }
    
    // Descargar plantilla Excel de ejemplo
    public function descargarPlantilla() {
        $filename = "plantilla_facturas_" . date('Y-m-d') . ".xls";
        
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo "\xEF\xBB\xBF";
        
        $headers = [
            'Laboratorio', 'Cliente', 'Numero Factura', 'RUC', 'Direccion', 'Telefono', 'Email',
            'Fecha Emision', 'Fecha Vencimiento', 'Tipo Pago', 'Moneda', 'Subtotal', 'IGV',
            'Monto Total', 'Estado', 'Observaciones'
        ];
        
        $ejemplos = [
            [
                'CONSORCIO MEDICORP & SALUD S.A.C.', 'MEDINA CASTRO LUZ YUBANA', 'F009-00020214',
                '20449809295', 'JR LOS GONZALES MZ U LTE 44 INT 393', '988279527',
                'medicorp_1_salud@hotmail.com', '2024-11-15', '2024-12-15', 'CONTADO', 'SOLES',
                '671.53', '120.88', '792.41', 'PAGADO', 'Factura de productos farmaceuticos'
            ],
            [
                'LABORATORIO ROCHE S.A.', 'CLINICA SAN PABLO', 'F001-00050123', '20100123456',
                'AV. JAVIER PRADO ESTE 2520', '4416000', 'contacto@roche.com.pe', '2024-11-10',
                '2024-12-10', 'CREDITO', 'SOLES', '15000.00', '2700.00', '17700.00', 'PENDIENTE',
                'Medicamentos oncologicos'
            ]
        ];
        
        echo '<table border="1">';
        echo '<thead><tr style="background-color: #0d6efd; color: white; font-weight: bold;">';
        foreach ($headers as $header) {
            echo '<th>' . htmlspecialchars($header) . '</th>';
        }
        echo '</tr></thead>';
        
        echo '<tbody>';
        foreach ($ejemplos as $fila) {
            echo '<tr>';
            foreach ($fila as $celda) {
                echo '<td>' . htmlspecialchars($celda) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        
        exit;
    }
    
    // Procesar Excel con CSV
    public function procesarExcel() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel'])) {
            $archivo = $_FILES['archivo_excel'];
            
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), ['csv', 'txt'])) {
                $_SESSION['error'] = "Solo se permiten archivos CSV. Guarda tu Excel como CSV primero.";
                header('Location: index.php');
                exit;
            }
            
            try {
                $insertados = 0;
                $errores = 0;
                $mensajes_error = [];
                
                if (($handle = fopen($archivo['tmp_name'], "r")) !== FALSE) {
                    $headers = fgetcsv($handle, 1000, ",");
                    
                    $row_number = 1;
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $row_number++;
                        
                        if (empty(array_filter($row))) {
                            continue;
                        }
                        
                        $data = [
                            'laboratorio' => $row[0] ?? '',
                            'cliente' => $row[1] ?? '',
                            'numero_factura' => $row[2] ?? '',
                            'ruc' => $row[3] ?? '',
                            'direccion' => $row[4] ?? '',
                            'telefono' => $row[5] ?? '',
                            'email' => $row[6] ?? '',
                            'fecha_emision' => $this->formatearFecha($row[7] ?? ''),
                            'fecha_vencimiento' => $this->formatearFecha($row[8] ?? ''),
                            'tipo_pago' => $row[9] ?? 'CONTADO',
                            'moneda' => $row[10] ?? 'SOLES',
                            'subtotal' => floatval($row[11] ?? 0),
                            'igv' => floatval($row[12] ?? 0),
                            'monto_total' => floatval($row[13] ?? 0),
                            'estado' => $row[14] ?? 'PENDIENTE',
                            'observaciones' => $row[15] ?? ''
                        ];
                        
                        if (empty($data['numero_factura']) || empty($data['monto_total'])) {
                            $errores++;
                            $mensajes_error[] = "Fila $row_number: Datos incompletos";
                            continue;
                        }
                        
                        if ($this->model->existeNumeroFactura($data['numero_factura'])) {
                            $errores++;
                            $mensajes_error[] = "Fila $row_number: Factura ya existe";
                            continue;
                        }
                        
                        if ($this->model->create($data)) {
                            $insertados++;
                        } else {
                            $errores++;
                            $mensajes_error[] = "Fila $row_number: Error al insertar";
                        }
                    }
                    fclose($handle);
                }
                
                if ($insertados > 0) {
                    $_SESSION['success'] = "Se importaron $insertados facturas exitosamente";
                    if ($errores > 0) {
                        $_SESSION['warning'] = "Se encontraron $errores errores";
                    }
                } else {
                    $_SESSION['error'] = "No se pudo importar ninguna factura";
                }
                
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al procesar el archivo: " . $e->getMessage();
            }
            
            header('Location: index.php');
            exit;
        }
    }
    
    private function sanitizeData($data) {
        return [
            'laboratorio' => trim($data['laboratorio'] ?? ''),
            'empresa_id' => intval($data['empresa_id'] ?? 0),
            'cliente_id' => intval($data['cliente_id'] ?? 0),
            'cliente' => trim($data['cliente'] ?? ''), // Mantener para compatibilidad
            'numero_factura' => trim($data['numero_factura'] ?? ''),
            'ruc' => trim($data['ruc'] ?? ''),
            'direccion' => trim($data['direccion'] ?? ''),
            'telefono' => trim($data['telefono'] ?? ''),
            'email' => trim($data['email'] ?? ''),
            'fecha_emision' => $data['fecha_emision'] ?? date('Y-m-d'),
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
            'tipo_pago' => $data['tipo_pago'] ?? 'CONTADO',
            'moneda' => $data['moneda'] ?? 'SOLES',
            'subtotal' => floatval($data['subtotal'] ?? 0),
            'igv' => floatval($data['igv'] ?? 0),
            'monto_total' => floatval($data['monto_total'] ?? 0),
            'estado' => $data['estado'] ?? 'PENDIENTE',
            'observaciones' => trim($data['observaciones'] ?? '')
        ];
    }
    
    private function formatearFecha($fecha) {
        if (empty($fecha)) {
            return null;
        }
        
        try {
            $date = new DateTime($fecha);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }
}
?>
