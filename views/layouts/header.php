<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Facturación</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="bi bi-receipt-cutoff"></i> Sistema de Facturación</h4>
            <button class="btn-close-sidebar" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="sidebar-menu">
            <!-- Facturas -->
            <a href="index.php" class="menu-item <?= (!isset($_GET['module']) || $_GET['module'] == 'facturas') && !isset($_GET['action']) ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Facturas</span>
            </a>
            
            <!-- Nueva Compra -->
            <a href="index.php?module=productos&action=crear" class="menu-item <?= (isset($_GET['module']) && $_GET['module'] == 'productos' && isset($_GET['action']) && $_GET['action'] == 'crear') ? 'active' : '' ?>">
                <i class="bi bi-plus-circle"></i>
                <span>Nueva Compra</span>
            </a>
            
            <!-- Productos -->
            <a href="index.php?module=productos" class="menu-item <?= (isset($_GET['module']) && $_GET['module'] == 'productos' && (!isset($_GET['action']) || $_GET['action'] == 'index')) ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i>
                <span>Productos</span>
            </a>
            
            <!-- Reportes -->
            <a href="index.php?module=reportes" class="menu-item <?= (isset($_GET['module']) && $_GET['module'] == 'reportes') ? 'active' : '' ?>">
                <i class="bi bi-graph-up"></i>
                <span>Reportes</span>
            </a>
            
            <div class="sidebar-divider"></div>
            
            <!-- Configuración -->
            <a href="#" class="menu-item">
                <i class="bi bi-gear"></i>
                <span>Configuración</span>
            </a>
        </div>
        
        <div class="sidebar-footer">
            <small class="text-muted">© <?= date('Y') ?> Sistema de Facturación</small>
        </div>
    </div>
    
    <!-- Overlay para cerrar sidebar en móvil -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <button class="btn-menu" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                
                <span class="navbar-brand mb-0 h1">
                    <?php
                    $module = $_GET['module'] ?? 'facturas';
                    $action = $_GET['action'] ?? 'index';
                    
                    $titles = [
                        'facturas' => 'Gestión de Facturas',
                        'productos' => $action == 'crear' ? 'Nueva Compra' : 'Gestión de Productos',
                        'reportes' => 'Reportes'
                    ];
                    echo $titles[$module] ?? 'Sistema de Facturación';
                    ?>
                </span>
                
                <div class="ms-auto">
                    <span class="badge bg-primary">Sistema Activo</span>
                </div>
            </div>
        </nav>
        
        <!-- Contenido -->
        <div class="content-wrapper">
            <!-- Mensajes de sesión -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['warning'])): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($_SESSION['warning']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['warning']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['info'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill"></i> <?= htmlspecialchars($_SESSION['info']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['info']); ?>
            <?php endif; ?>