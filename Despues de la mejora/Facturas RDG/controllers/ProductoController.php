<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Factura.php';
require_once __DIR__ . '/../models/Empresa.php';

class ProductoController {
    private $model;
    private $facturaModel;
    private $empresaModel;
    
    public function __construct() {
        $this->model = new Producto();
        $this->facturaModel = new Factura();
        $this->empresaModel = new Empresa();
    }
    
    public function index() {
        $productos = $this->model->getAll();
        require_once __DIR__ . '/../views/productos/index.php';
    }
    
    public function crear() {
        $empresas = $this->empresaModel->getAll(); 
        require_once __DIR__ . '/../views/productos/nueva_compra.php';
    }
    
    public function guardarCompra() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $numeroFactura = trim($_POST['serie']) . '-' . trim($_POST['numero']);
                
                if ($this->facturaModel->existeNumeroFactura($numeroFactura)) {
                    $_SESSION['error'] = "El número de factura ya existe";
                    header('Location: index.php?module=productos&action=crear');
                    exit;
                }
                
                $dataFactura = [
                    'laboratorio' => trim($_POST['proveedor'] ?? ''),
                    'cliente' => trim($_POST['cliente'] ?? ''),
                    'numero_factura' => $numeroFactura,
                    'ruc' => trim($_POST['ruc_cliente'] ?? ''),
                    'direccion' => '',
                    'telefono' => '',
                    'email' => '',
                    'fecha_emision' => $_POST['fecha_emision'] ?? date('Y-m-d'),
                    'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? null,
                    'tipo_pago' => 'CONTADO',
                    'moneda' => $_POST['moneda'] ?? 'SOLES',
                    'subtotal' => floatval($_POST['subtotal'] ?? 0),
                    'igv' => floatval($_POST['igv'] ?? 0),
                    'monto_total' => floatval($_POST['monto_total'] ?? 0),
                    'estado' => 'REGISTRADO',
                    'observaciones' => trim($_POST['observaciones'] ?? ''),
                    'empresa_id' => null,
                    'cliente_id' => null
                ];
                
                if ($this->facturaModel->create($dataFactura)) {
                    $facturaId = $this->facturaModel->getUltimoId();
                    
                    if (isset($_POST['productos']) && is_array($_POST['productos'])) {
                        $productosGuardados = 0;
                        
                        foreach ($_POST['productos'] as $productoData) {
                            $dataProducto = [
                                'factura_id' => $facturaId,
                                'codigo' => trim($productoData['codigo'] ?? ''),
                                'cantidad' => floatval($productoData['cantidad'] ?? 0),
                                'descripcion' => trim($productoData['descripcion'] ?? ''),
                                'laboratorio' => trim($productoData['laboratorio'] ?? ''),
                                'lote' => trim($productoData['lote'] ?? ''),
                                'fecha_vencimiento' => $productoData['fecha_vencimiento'] ?? null,
                                'precio_unitario' => floatval($productoData['precio_unitario'] ?? 0),
                                'importe' => floatval($productoData['importe'] ?? 0)
                            ];
                            
                            if ($this->model->create($dataProducto)) {
                                $productosGuardados++;
                            }
                        }
                        
                        $_SESSION['success'] = "Compra registrada exitosamente con $productosGuardados productos";
                        header('Location: index.php');
                    } else {
                        $_SESSION['error'] = "No se agregaron productos a la compra";
                        header('Location: index.php?module=productos&action=crear');
                    }
                } else {
                    $_SESSION['error'] = "Error al crear la factura";
                    header('Location: index.php?module=productos&action=crear');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                header('Location: index.php?module=productos&action=crear');
            }
            exit;
        }
    }
    
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeData($_POST);
            
            $factura = $this->facturaModel->getById($data['factura_id']);
            if (!$factura) {
                $_SESSION['error'] = "La factura seleccionada no existe";
                header('Location: index.php?module=productos&action=crear');
                exit;
            }
            
            if ($this->model->create($data)) {
                $_SESSION['success'] = "Producto agregado exitosamente";
                header('Location: index.php?module=productos');
            } else {
                $_SESSION['error'] = "Error al crear el producto";
                header('Location: index.php?module=productos&action=crear');
            }
            exit;
        }
    }
    
    public function editar($id) {
        $producto = $this->model->getById($id);
        if (!$producto) {
            $_SESSION['error'] = "Producto no encontrado";
            header('Location: index.php?module=productos');
            exit;
        }
        
        $facturas = $this->facturaModel->getAll();
        require_once __DIR__ . '/../views/productos/editar.php';
    }
    
    public function actualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeData($_POST);
            
            $factura = $this->facturaModel->getById($data['factura_id']);
            if (!$factura) {
                $_SESSION['error'] = "La factura seleccionada no existe";
                header('Location: index.php?module=productos&action=editar&id=' . $id);
                exit;
            }
            
            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = "Producto actualizado exitosamente";
                header('Location: index.php?module=productos');
            } else {
                $_SESSION['error'] = "Error al actualizar el producto";
                header('Location: index.php?module=productos&action=editar&id=' . $id);
            }
            exit;
        }
    }
    
    public function eliminar($id) {
        if ($this->model->delete($id)) {
            $_SESSION['success'] = "Producto eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el producto";
        }
        header('Location: index.php?module=productos');
        exit;
    }
    
    public function procesarArchivoBot() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_bot'])) {
            $_SESSION['info'] = "Funcionalidad de BOT en desarrollo";
            header('Location: index.php?module=productos');
            exit;
        }
    }
    
    private function sanitizeData($data) {
        $cantidad = floatval($data['cantidad'] ?? 0);
        $precio_unitario = floatval($data['precio_unitario'] ?? 0);
        $importe = $cantidad * $precio_unitario;
        
        return [
            'factura_id' => intval($data['factura_id'] ?? 0),
            'codigo' => trim($data['codigo'] ?? ''),
            'cantidad' => $cantidad,
            'descripcion' => trim($data['descripcion'] ?? ''),
            'laboratorio' => trim($data['laboratorio'] ?? ''),
            'lote' => trim($data['lote'] ?? ''),
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
            'precio_unitario' => $precio_unitario,
            'importe' => $importe
        ];
    }
}
?>