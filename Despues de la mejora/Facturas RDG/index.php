<?php
session_start();

// Función helper para rutas compatibles con cualquier sistema operativo
function getPath($path) {
    return __DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
}

// Obtener módulo y acción de la URL
$module = $_GET['module'] ?? 'facturas';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Enrutar según el módulo
switch ($module) {
    case 'facturas':
        require_once getPath('controllers/FacturaController.php');
        $controller = new FacturaController();
        
        switch ($action) {
            case 'index':
                $controller->index();
                break;
            case 'crear':
                $controller->crear();
                break;
            case 'guardar':
                $controller->guardar();
                break;
            case 'editar':
                if ($id) {
                    $controller->editar($id);
                } else {
                    $_SESSION['error'] = "ID no especificado";
                    header('Location: index.php');
                }
                break;
            case 'actualizar':
                if ($id) {
                    $controller->actualizar($id);
                } else {
                    $_SESSION['error'] = "ID no especificado";
                    header('Location: index.php');
                }
                break;
            case 'eliminar':
                if ($id) {
                    $controller->eliminar($id);
                } else {
                    $_SESSION['error'] = "ID no especificado";
                    header('Location: index.php');
                }
                break;
            case 'procesar_excel':
                $controller->procesarExcel();
                break;
            case 'descargar_plantilla':
                $controller->descargarPlantilla();
                break;
            default:
                $_SESSION['error'] = "Acción no válida";
                header('Location: index.php');
                break;
        }
        break;
        
    case 'productos':
        require_once getPath('controllers/ProductoController.php');
        $controller = new ProductoController();
        
        switch ($action) {
            case 'index':
                $controller->index();
                break;
            case 'crear':
                $controller->crear();
                break;
            case 'guardar':
                $controller->guardar();
                break;
            case 'guardar_compra':  // NUEVA ACCIÓN
                $controller->guardarCompra();
                break;
            case 'editar':
                if ($id) {
                    $controller->editar($id);
                } else {
                    $_SESSION['error'] = "ID no especificado";
                    header('Location: index.php?module=productos');
                }
                break;
            case 'actualizar':
                if ($id) {
                    $controller->actualizar($id);
                } else {
                    $_SESSION['error'] = "ID no especificado";
                    header('Location: index.php?module=productos');
                }
                break;
            case 'eliminar':
                if ($id) {
                    $controller->eliminar($id);
                } else {
                    $_SESSION['error'] = "ID no especificado";
                    header('Location: index.php?module=productos');
                }
                break;
            case 'procesar_bot':
                $controller->procesarArchivoBot();
                break;
            default:
                $_SESSION['error'] = "Acción no válida";
                header('Location: index.php?module=productos');
                break;
            }
        break;
        
    case 'reportes':
        require_once getPath('views/layouts/header.php');
        echo '<div class="alert alert-info"><i class="bi bi-info-circle"></i> Módulo de reportes en construcción</div>';
        require_once getPath('views/layouts/footer.php');
        break;
        
    default:
        $_SESSION['error'] = "Módulo no válido";
        header('Location: index.php');
        break;
}
?>