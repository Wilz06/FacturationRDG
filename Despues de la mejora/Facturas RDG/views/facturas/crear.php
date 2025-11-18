<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Nueva Factura
                </h4>
            </div>
            <div class="card-body">
                <form action="index.php?action=guardar" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-hospital"></i> Datos del Laboratorio
                            </h5>
                            
                            <div class="mb-3">
                                <label for="empresa_id" class="form-label">Laboratorio (Empresa) *</label>
                                <select class="form-select" id="empresa_id" name="empresa_id" required>
                                    <option value="">-- Seleccione un laboratorio --</option>
                                    <?php foreach ($empresas as $empresa): ?>
                                        <option value="<?= $empresa['id'] ?>">
                                            <?= htmlspecialchars($empresa['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="laboratorio" class="form-label">Nombre (si no existe)</label>
                                <input type="text" class="form-control" id="laboratorio" name="laboratorio" placeholder="Escribir solo si no está en la lista...">
                            </div>
                            <div class="mb-3">
                                <label for="ruc" class="form-label">RUC</label>
                                <input type="text" class="form-control" id="ruc" name="ruc" maxlength="20">
                            </div>
                            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono">
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-person"></i> Datos del Cliente
                            </h5>
                            
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <option value="">-- Seleccione un cliente --</option>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?= $cliente['id'] ?>">
                                            <?= htmlspecialchars($cliente['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="cliente" class="form-label">Nombre Cliente (si no existe)</label>
                                <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Escribir solo si no está en la lista...">
                            </div>
                            <div class="mb-3">
                                <label for="numero_factura" class="form-label">Número de Factura *</label>
                                <input type="text" class="form-control" id="numero_factura" name="numero_factura" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_emision" class="form-label">Fecha Emisión *</label>
                                        <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" 
                                               value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento</label>
                                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                                        <select class="form-select" id="tipo_pago" name="tipo_pago">
                                            <option value="CONTADO">CONTADO</option>
                                            <option value="CREDITO">CRÉDITO</option>
                                            <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="moneda" class="form-label">Moneda</label>
                                        <select class="form-select" id="moneda" name="moneda">
                                            <option value="SOLES">SOLES</option>
                                            <option value="DOLARES">DÓLARES</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-cash-stack"></i> Montos
                            </h5>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="subtotal" class="form-label">Subtotal *</label>
                                <input type="number" class="form-control" id="subtotal" name="subtotal" 
                                       step="0.01" min="0" onchange="calcularTotales()" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="igv" class="form-label">IGV (18%)</label>
                                <input type="number" class="form-control" id="igv" name="igv" 
                                       step="0.01" min="0" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="monto_total" class="form-label">Monto Total *</label>
                                <input type="number" class="form-control" id="monto_total" name="monto_total" 
                                       step="0.01" min="0" readonly required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="PENDIENTE">PENDIENTE</option>
                                    <option value="PAGADO">PAGADO</option>
                                    <option value="VENCIDO">VENCIDO</option>
                                    <option value="ANULADO">ANULADO</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar Factura
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>