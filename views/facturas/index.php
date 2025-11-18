<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .tabla-facturas {
        width: 100%;
        background: white;
        border-collapse: collapse;
    }
    .tabla-facturas thead {
        background: #2c3e50;
        color: white;
    }
    .tabla-facturas th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #34495e;
        text-transform: uppercase;
    }
    .tabla-facturas td {
        padding: 10px 12px;
        border: 1px solid #ddd;
        font-size: 14px;
    }
    .tabla-facturas tbody tr:hover {
        background: #f8f9fa;
    }
    .badge-estado {
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-pagado {
        background: #27ae60;
        color: white;
    }
    .badge-pendiente {
        background: #f39c12;
        color: white;
    }
    .badge-registrado {
        background: #3498db;
        color: white;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-0">
                    <i class="bi bi-file-earmark-text"></i> Listado de Facturas
                </h4>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalImportarExcel">
                        <i class="bi bi-cloud-upload"></i> Importar CSV
                    </button>
                    <a href="index.php?action=descargar_plantilla" class="btn btn-success btn-sm">
                        <i class="bi bi-download"></i> Descargar Plantilla Excel
                    </a>
                    <a href="index.php?module=productos&action=crear" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle"></i> Nuevo
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="tabla-facturas" id="tablaFacturas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>LABORATORIO</th>
                                <th>CLIENTE</th>
                                <th>Nº FACTURA</th>
                                <th>RUC</th>
                                <th>FECHA EMISIÓN</th>
                                <th>TIPO PAGO</th>
                                <th>MONEDA</th>
                                <th style="text-align: right;">MONTO TOTAL</th>
                                <th style="text-align: center;">ESTADO</th>
                                <th style="text-align: center;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facturas as $factura): ?>
                                <tr>
                                    <td><?= htmlspecialchars($factura['id']) ?></td>
                                    <td><?= htmlspecialchars($factura['laboratorio']) ?></td>
                                    <td><?= htmlspecialchars($factura['cliente']) ?></td>
                                    <td><strong><?= htmlspecialchars($factura['numero_factura']) ?></strong></td>
                                    <td><?= htmlspecialchars($factura['ruc']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($factura['fecha_emision'])) ?></td>
                                    <td><?= htmlspecialchars($factura['tipo_pago']) ?></td>
                                    <td><?= htmlspecialchars($factura['moneda']) ?></td>
                                    <td style="text-align: right;"><strong>S/ <?= number_format($factura['monto_total'], 2) ?></strong></td>
                                    <td style="text-align: center;">
                                        <?php
                                        $estado = strtoupper($factura['estado']);
                                        $badgeClass = 'badge-registrado';
                                        if ($estado == 'PAGADO') $badgeClass = 'badge-pagado';
                                        elseif ($estado == 'PENDIENTE') $badgeClass = 'badge-pendiente';
                                        ?>
                                        <span class="badge-estado <?= $badgeClass ?>"><?= $estado ?></span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="btn-group" role="group">
                                            <a href="index.php?action=editar&id=<?= $factura['id'] ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button onclick="confirmarEliminacion(<?= $factura['id'] ?>, 'factura')" 
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

<div class="modal fade" id="modalImportarExcel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-cloud-upload"></i> Importar Facturas desde CSV
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?action=procesar_excel" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Formato del archivo:</strong> El archivo debe ser CSV con las columnas en el orden correcto. 
                        Descargue la plantilla de ejemplo para ver el formato.
                    </div>
                    
                    <div class="mb-3">
                        <label for="archivo_excel" class="form-label">Seleccionar archivo CSV *</label>
                        <input type="file" 
                               class="form-control" 
                               id="archivo_excel" 
                               name="archivo_excel" 
                               accept=".csv,.txt" 
                               required>
                        <small class="text-muted">Solo archivos CSV o TXT</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Importar Facturas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>