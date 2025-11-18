<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="bi bi-pencil"></i> Editar Producto
                </h4>
            </div>
            <div class="card-body">
                <form action="index.php?module=productos&action=actualizar&id=<?= $producto['id'] ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="factura_id" class="form-label">Factura *</label>
                                <select class="form-select" id="factura_id" name="factura_id" required>
                                    <?php foreach ($facturas as $factura): ?>
                                        <option value="<?= $factura['id'] ?>" 
                                                <?= $producto['factura_id'] == $factura['id'] ? 'selected' : '' ?>>
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
                                <input type="text" class="form-control" id="codigo" name="codigo" 
                                       value="<?= htmlspecialchars($producto['codigo']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad *</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" 
                                       step="0.01" min="0" value="<?= $producto['cantidad'] ?>" 
                                       onchange="calcularImporte()" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="laboratorio" class="form-label">Laboratorio</label>
                                <input type="text" class="form-control" id="laboratorio" name="laboratorio" 
                                       value="<?= htmlspecialchars($producto['laboratorio']) ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="lote" class="form-label">Lote</label>
                                <input type="text" class="form-control" id="lote" name="lote" 
                                       value="<?= htmlspecialchars($producto['lote']) ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="fecha_vencimiento" class="form-label">F. Vencimiento</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" 
                                       value="<?= $producto['fecha_vencimiento'] ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="precio_unitario" class="form-label">Precio Unitario *</label>
                                <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" 
                                       step="0.01" min="0" value="<?= $producto['precio_unitario'] ?>" 
                                       onchange="calcularImporte()" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="importe" class="form-label">Importe Total</label>
                                <input type="number" class="form-control" id="importe" name="importe" 
                                       step="0.01" min="0" value="<?= $producto['importe'] ?>" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-end">
                        <a href="index.php?module=productos" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>