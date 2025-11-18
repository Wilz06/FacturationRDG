<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Nuevo Producto
                </h4>
            </div>
            <div class="card-body">
                <form action="index.php?module=productos&action=guardar" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="factura_id" class="form-label">Factura *</label>
                                <select class="form-select" id="factura_id" name="factura_id" required>
                                    <option value="">-- Seleccione una factura --</option>
                                    <?php foreach ($facturas as $factura): ?>
                                        <option value="<?= $factura['id'] ?>">
                                            <?= htmlspecialchars($factura['numero_factura']) ?> - 
                                            <?= htmlspecialchars($factura['cliente']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" placeholder="0052991">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad *</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" 
                                       step="0.01" min="0" value="1" onchange="calcularImporte()" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="laboratorio" class="form-label">Laboratorio</label>
                                <input type="text" class="form-control" id="laboratorio" name="laboratorio" placeholder="FIN">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="lote" class="form-label">Lote</label>
                                <input type="text" class="form-control" id="lote" name="lote" placeholder="20742105">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="fecha_vencimiento" class="form-label">F. Vencimiento</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="precio_unitario" class="form-label">Precio Unitario *</label>
                                <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" 
                                       step="0.01" min="0" onchange="calcularImporte()" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="importe" class="form-label">Importe Total</label>
                                <input type="number" class="form-control" id="importe" name="importe" 
                                       step="0.01" min="0" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-end">
                        <a href="index.php?module=productos" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>