<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="bi bi-pencil"></i> Editar Factura #<?= htmlspecialchars($factura['numero_factura']) ?>
                </h4>
            </div>
            <div class="card-body">
                <form action="index.php?action=actualizar&id=<?= $factura['id'] ?>" method="POST">
                    <div class="row">
                        <!-- Información del Laboratorio -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-hospital"></i> Datos del Laboratorio
                            </h5>
                            
                            <div class="mb-3">
                                <label for="laboratorio" class="form-label">Laboratorio *</label>
                                <input type="text" class="form-control" id="laboratorio" name="laboratorio" 
                                       value="<?= htmlspecialchars($factura['laboratorio']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="ruc" class="form-label">RUC</label>
                                <input type="text" class="form-control" id="ruc" name="ruc" 
                                       value="<?= htmlspecialchars($factura['ruc']) ?>" maxlength="20">
                            </div>
                            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2"><?= htmlspecialchars($factura['direccion']) ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" 
                                       value="<?= htmlspecialchars($factura['telefono']) ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($factura['email']) ?>">
                            </div>
                        </div>
                        
                        <!-- Información del Cliente y Factura -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-person"></i> Datos del Cliente
                            </h5>
                            
                            <div class="mb-3">
                                <label for="cliente" class="form-label">Cliente *</label>
                                <input type="text" class="form-control" id="cliente" name="cliente" 
                                       value="<?= htmlspecialchars($factura['cliente']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="numero_factura" class="form-label">Número de Factura *</label>
                                <input type="text" class="form-control" id="numero_factura" name="numero_factura" 
                                       value="<?= htmlspecialchars($factura['numero_factura']) ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_emision" class="form-label">Fecha Emisión *</label>
                                        <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" 
                                               value="<?= $factura['fecha_emision'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento</label>
                                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" 
                                               value="<?= $factura['fecha_vencimiento'] ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                                        <select class="form-select" id="tipo_pago" name="tipo_pago">
                                            <option value="CONTADO" <?= $factura['tipo_pago'] == 'CONTADO' ? 'selected' : '' ?>>CONTADO</option>
                                            <option value="CREDITO" <?= $factura['tipo_pago'] == 'CREDITO' ? 'selected' : '' ?>>CRÉDITO</option>
                                            <option value="TRANSFERENCIA" <?= $factura['tipo_pago'] == 'TRANSFERENCIA' ? 'selected' : '' ?>>TRANSFERENCIA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="moneda" class="form-label">Moneda</label>
                                        <select class="form-select" id="moneda" name="moneda">
                                            <option value="SOLES" <?= $factura['moneda'] == 'SOLES' ? 'selected' : '' ?>>SOLES</option>
                                            <option value="DOLARES" <?= $factura['moneda'] == 'DOLARES' ? 'selected' : '' ?>>DÓLARES</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Montos -->
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
                                       step="0.01" min="0" value="<?= $factura['subtotal'] ?>" 
                                       onchange="calcularTotales()" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="igv" class="form-label">IGV (18%)</label>
                                <input type="number" class="form-control" id="igv" name="igv" 
                                       step="0.01" min="0" value="<?= $factura['igv'] ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="monto_total" class="form-label">Monto Total *</label>
                                <input type="number" class="form-control" id="monto_total" name="monto_total" 
                                       step="0.01" min="0" value="<?= $factura['monto_total'] ?>" readonly required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado y Observaciones -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="PENDIENTE" <?= $factura['estado'] == 'PENDIENTE' ? 'selected' : '' ?>>PENDIENTE</option>
                                    <option value="PAGADO" <?= $factura['estado'] == 'PAGADO' ? 'selected' : '' ?>>PAGADO</option>
                                    <option value="VENCIDO" <?= $factura['estado'] == 'VENCIDO' ? 'selected' : '' ?>>VENCIDO</option>
                                    <option value="ANULADO" <?= $factura['estado'] == 'ANULADO' ? 'selected' : '' ?>>ANULADO</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="2"><?= htmlspecialchars($factura['observaciones']) ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Factura
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>