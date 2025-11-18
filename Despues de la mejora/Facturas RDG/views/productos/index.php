<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .tabla-productos {
        width: 100%;
        background: white;
        border-collapse: collapse;
    }
    .tabla-productos thead {
        background: #2c3e50;
        color: white;
    }
    .tabla-productos th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #34495e;
        text-transform: uppercase;
    }
    .tabla-productos td {
        padding: 10px 12px;
        border: 1px solid #ddd;
        font-size: 14px;
    }
    .tabla-productos tbody tr:hover {
        background: #f8f9fa;
    }
    .badge-codigo {
        background: #3498db;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
    }
    .badge-factura {
        background: #9b59b6;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-box-seam"></i> Listado de Productos
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="tabla-productos" id="tablaProductos">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CÓDIGO</th>
                                <th>DESCRIPCIÓN</th>
                                <th style="text-align: center;">CANT.</th>
                                <th>LAB.</th>
                                <th>LOTE</th>
                                <th>F. VENCIMIENTO</th>
                                <th style="text-align: right;">P. UNIT.</th>
                                <th style="text-align: right;">IMPORTE</th>
                                <th>Nº FACTURA</th>
                                <th>CLIENTE</th>
                                <th style="text-align: center;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= htmlspecialchars($producto['id']) ?></td>
                                    <td>
                                        <span class="badge-codigo"><?= htmlspecialchars($producto['codigo']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                                    <td style="text-align: center;"><?= htmlspecialchars($producto['cantidad']) ?></td>
                                    <td><?= htmlspecialchars($producto['laboratorio']) ?></td>
                                    <td><?= htmlspecialchars($producto['lote']) ?></td>
                                    <td><?= $producto['fecha_vencimiento'] ? date('d/m/Y', strtotime($producto['fecha_vencimiento'])) : '-' ?></td>
                                    <td style="text-align: right;">S/ <?= number_format($producto['precio_unitario'], 2) ?></td>
                                    <td style="text-align: right;"><strong>S/ <?= number_format($producto['importe'], 2) ?></strong></td>
                                    <td>
                                        <span class="badge-factura"><?= htmlspecialchars($producto['numero_factura']) ?></span>
                                    </td>
                                    <td><small><?= htmlspecialchars($producto['cliente']) ?></small></td>
                                    <td style="text-align: center;">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-warning" 
                                                    onclick="editarProducto(<?= $producto['id'] ?>)" 
                                                    title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button onclick="confirmarEliminacion(<?= $producto['id'] ?>, 'producto')" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editarProducto(id) {
    window.location.href = 'index.php?module=productos&action=editar&id=' + id;
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>