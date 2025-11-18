<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .productos-tabla {
        width: 100%;
        background: white;
        border-collapse: collapse;
    }
    .productos-tabla thead {
        background: #2c3e50;
        color: white;
    }
    .productos-tabla th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        border: 1px solid #34495e;
    }
    .productos-tabla td {
        padding: 10px;
        border: 1px solid #ddd;
        font-size: 14px;
    }
    .productos-tabla tbody tr:hover {
        background: #f8f9fa;
    }
    .totales-card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 16px;
    }
    .total-row.grand {
        border-top: 2px solid #2c3e50;
        margin-top: 10px;
        padding-top: 15px;
        font-weight: bold;
        font-size: 20px;
    }
    .form-compra {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .btn-nuevo-proveedor {
        font-size: 0.8rem;
        padding: 0.1rem 0.5rem;
        margin-left: 10px;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header" style="background: #2c3e50; color: white;">
                <h4 class="mb-0">Nueva Compra</h4>
            </div>
            <div class="card-body">
                
                <div class="mb-3 text-end">
                     <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalImportarCompra">
                        <i class="bi bi-file-earmark-arrow-up"></i> Importar Compra (CSV/TXT)
                    </button>
                </div>

                <form action="index.php?module=productos&action=guardar_compra" method="POST" id="formNuevaCompra" class="form-compra">
                    
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Tipo comprobante</label>
                                <select class="form-select" name="tipo_comprobante" required>
                                    <option value="FACTURA ELECTRÓNICA">FACTURA ELECTRÓNICA</option>
                                    <option value="BOLETA">BOLETA</option>
                                    <option value="NOTA DE CRÉDITO">NOTA DE CRÉDITO</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Serie *</label>
                                <input type="text" class="form-control" name="serie" placeholder="F001" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Número *</label>
                                <input type="text" class="form-control" name="numero" placeholder="123456" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Fec Emisión</label>
                                <input type="date" class="form-control" name="fecha_emision" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Fec. Vencimiento</label>
                                <input type="date" class="form-control" name="fecha_vencimiento">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">Proveedor *</label>
                                    <button type="button" class="btn btn-primary btn-nuevo-proveedor" onclick="abrirModalProveedor()">
                                        <i class="bi bi-plus-lg"></i> Nuevo
                                    </button>
                                </div>
                                <select class="form-select" name="proveedor" id="selectProveedor" required>
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($empresas as $empresa): ?>
                                        <option value="<?= htmlspecialchars($empresa['nombre']) ?>">
                                            <?= htmlspecialchars($empresa['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Moneda</label>
                                <select class="form-select" name="moneda">
                                    <option value="Soles">Soles</option>
                                    <option value="Dólares">Dólares</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Cliente Comprador *</label>
                                <input type="text" class="form-control" name="cliente" placeholder="Nombre del cliente" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">RUC (Cliente)</label>
                                <input type="text" class="form-control" name="ruc_cliente" placeholder="RUC del cliente">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Tipo de cambio <i class="bi bi-info-circle" title="Tipo de cambio del día"></i></label>
                                <input type="number" class="form-control" id="tipo_cambio" name="tipo_cambio" step="0.001" value="" placeholder="Cargando...">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Orden de compra</label>
                                <input type="text" class="form-control" name="orden_compra" placeholder="Número de documento">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="2" placeholder="Observaciones"></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Productos de la Compra</h5>
                        <button type="button" class="btn btn-dark" onclick="agregarProducto()">
                            + Agregar Producto
                        </button>
                    </div>

                    <div id="listaProductos" style="min-height: 200px;">
                        <div class="text-center py-5" id="emptyState">
                            <i class="bi bi-box-seam" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-3">No hay productos agregados</p>
                            <button type="button" class="btn btn-dark mt-2" onclick="agregarProducto()">
                                + Agregar Primer Producto
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="totales-card">
                                <div class="total-row">
                                    <span>Subtotal:</span>
                                    <strong id="subtotalDisplay">S/ 0.00</strong>
                                </div>
                                <div class="total-row">
                                    <span>IGV (18%):</span>
                                    <strong id="igvDisplay">S/ 0.00</strong>
                                </div>
                                <div class="total-row grand">
                                    <span>TOTAL:</span>
                                    <strong style="color: #27ae60;" id="totalDisplay">S/ 0.00</strong>
                                </div>
                                <input type="hidden" name="subtotal" id="subtotal" value="0">
                                <input type="hidden" name="igv" id="igv" value="0">
                                <input type="hidden" name="monto_total" id="monto_total" value="0">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-end">
                        <a href="index.php?module=productos" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save"></i> Guardar Compra
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregarProducto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #2c3e50; color: white;">
                <h5 class="modal-title">Agregar Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigoProducto" placeholder="Código del producto">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Descripción *</label>
                        <input type="text" class="form-control" id="descripcionProducto" placeholder="Descripción del producto" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label class="form-label">Cantidad *</label>
                        <input type="number" class="form-control" id="cantidadProducto" value="1" min="0.01" step="0.01" onchange="calcularImporteModal()">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Laboratorio</label>
                        <input type="text" class="form-control" id="laboratorioProducto" placeholder="Laboratorio">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lote</label>
                        <input type="text" class="form-control" id="loteProducto" placeholder="Lote">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">F. Vencimiento</label>
                        <input type="date" class="form-control" id="fechaVencimiento">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Precio Unitario *</label>
                        <input type="number" class="form-control" id="precioUnitario" step="0.01" min="0" placeholder="0.00" onchange="calcularImporteModal()" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Importe</label>
                        <input type="text" class="form-control" id="importeModal" readonly style="background: #e9ecef; font-weight: bold;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="confirmarAgregarProducto()">
                    <i class="bi bi-check-circle"></i> Agregar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoProveedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nuevo Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevoProveedor">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tipo_documento" class="form-label">Tipo Doc. Identidad *</label>
                        <select id="tipo_documento" name="tipo_documento" class="form-select">
                            <option value="RUC">RUC</option>
                            <option value="DNI">DNI</option>
                            <option value="CE">CARNET EXT.</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="numero_proveedor" class="form-label">Número *</label>
                        <input type="text" class="form-control" id="numero_proveedor" name="numero_proveedor" maxlength="11" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_proveedor" class="form-label">Nombre / Razón Social *</label>
                        <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" required>
                    </div>
                    <div id="errorProveedor" class="text-danger" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarNuevoProveedor()">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImportarCompra" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-dark">
                <h5 class="modal-title">Importar Compra Completa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="archivo_compra" class="form-label">Seleccionar archivo .TXT o .CSV</label>
                    <input type="file" class="form-control" id="archivo_compra" name="archivo_compra" accept=".csv,.txt">
                </div>
                <div class="alert alert-warning">
                    El archivo debe tener el formato específico de factura con cabecera y lista de productos.
                </div>
                <div id="errorImportarCompra" class="text-danger" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" onclick="subirArchivoCompra()">
                    <i class="bi bi-upload"></i> Importar
                </button>
            </div>
        </div>
    </div>
</div>


<script>
let modalProveedor;
let modalImportarCompra;

document.addEventListener("DOMContentLoaded", function() {
    modalProveedor = new bootstrap.Modal(document.getElementById('modalNuevoProveedor'));
    modalImportarCompra = new bootstrap.Modal(document.getElementById('modalImportarCompra'));
    
    const inputCambio = document.getElementById('tipo_cambio');
    const fallbackRate = '3.370';

    fetch('ajax_tipo_cambio.php')
        .then(response => response.json())
        .then(data => {
            let rate = fallbackRate;
            if (data.status === 'success' && data.rate) {
                rate = parseFloat(data.rate).toFixed(3);
            }
            inputCambio.value = rate;
            inputCambio.placeholder = rate;
        })
        .catch(err => {
            inputCambio.value = fallbackRate;
            inputCambio.placeholder = fallbackRate;
        });
});

function abrirModalProveedor() {
    document.getElementById('formNuevoProveedor').reset();
    document.getElementById('errorProveedor').style.display = 'none';
    modalProveedor.show();
}

function guardarNuevoProveedor() {
    const form = document.getElementById('formNuevoProveedor');
    const errorDiv = document.getElementById('errorProveedor');
    const formData = new FormData(form);
    
    if (!formData.get('nombre_proveedor') || !formData.get('numero_proveedor')) {
        errorDiv.textContent = 'Todos los campos son obligatorios.';
        errorDiv.style.display = 'block';
        return;
    }
    
    errorDiv.style.display = 'none';

    fetch('ajax_guardar_empresa.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const selectProveedor = document.getElementById('selectProveedor');
            const nombreProveedor = data.nombre_proveedor;
            
            const newOption = new Option(nombreProveedor, nombreProveedor);
            selectProveedor.add(newOption);
            
            selectProveedor.value = nombreProveedor;
            
            modalProveedor.hide();
            
            Swal.fire({
                icon: 'success',
                title: '¡Guardado!',
                text: 'Nuevo proveedor agregado correctamente.'
            });
            
        } else {
            errorDiv.textContent = 'Error: ' + data.message;
            errorDiv.style.display = 'block';
        }
    })
    .catch(error => {
        errorDiv.textContent = 'Error de conexión: ' + error.message;
        errorDiv.style.display = 'block';
    });
}

function subirArchivoCompra() {
    const fileInput = document.getElementById('archivo_compra');
    const errorDiv = document.getElementById('errorImportarCompra');
    
    if (fileInput.files.length === 0) {
        errorDiv.textContent = 'Por favor, seleccione un archivo.';
        errorDiv.style.display = 'block';
        return;
    }
    
    const formData = new FormData();
    formData.append('archivo_compra', fileInput.files[0]);
    
    errorDiv.style.display = 'none';
    
    fetch('ajax_importar_compra.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            modalImportarCompra.hide();
            Swal.fire({
                icon: 'success',
                title: '¡Importación Exitosa!',
                text: data.message,
            }).then(() => {
                window.location.href = 'index.php';
            });
        } else {
            errorDiv.textContent = 'Error: ' + data.message;
            errorDiv.style.display = 'block';
        }
    })
    .catch(error => {
        errorDiv.textContent = 'Error de conexión: ' + error.message;
        errorDiv.style.display = 'block';
    });
}


let productosAgregados = [];
let productoCounter = 0;

function agregarProducto() {
    const modal = new bootstrap.Modal(document.getElementById('modalAgregarProducto'));
    modal.show();
}

function calcularImporteModal() {
    const cantidad = parseFloat(document.getElementById('cantidadProducto').value) || 0;
    const precio = parseFloat(document.getElementById('precioUnitario').value) || 0;
    const importe = cantidad * precio;
    document.getElementById('importeModal').value = importe.toFixed(2);
}

function confirmarAgregarProducto() {
    const descripcion = document.getElementById('descripcionProducto').value.trim();
    const precio = parseFloat(document.getElementById('precioUnitario').value);
    const cantidad = parseFloat(document.getElementById('cantidadProducto').value);

    if (!descripcion) {
        alert('Por favor ingrese la descripción del producto');
        return;
    }
    if (!precio || precio <= 0) {
        alert('Por favor ingrese un precio válido');
        return;
    }
    if (!cantidad || cantidad <= 0) {
        alert('Por favor ingrese una cantidad válida');
        return;
    }

    const producto = {
        id: ++productoCounter,
        codigo: document.getElementById('codigoProducto').value || 'SIN CÓDIGO',
        descripcion: descripcion,
        cantidad: cantidad,
        laboratorio: document.getElementById('laboratorioProducto').value || '-',
        lote: document.getElementById('loteProducto').value || '-',
        fecha_vencimiento: document.getElementById('fechaVencimiento').value || '',
        precio_unitario: precio,
        importe: cantidad * precio
    };

    productosAgregados.push(producto);
    renderizarProductos();
    calcularTotalesCompra();
    bootstrap.Modal.getInstance(document.getElementById('modalAgregarProducto')).hide();
    limpiarModalProducto();
}

function limpiarModalProducto() {
    document.getElementById('codigoProducto').value = '';
    document.getElementById('descripcionProducto').value = '';
    document.getElementById('cantidadProducto').value = '1';
    document.getElementById('precioUnitario').value = '';
    document.getElementById('laboratorioProducto').value = '';
    document.getElementById('loteProducto').value = '';
    document.getElementById('fechaVencimiento').value = '';
    document.getElementById('importeModal').value = '';
}

function renderizarProductos() {
    const container = document.getElementById('listaProductos');
    const emptyState = document.getElementById('emptyState');
    
    if (productosAgregados.length === 0) {
        emptyState.style.display = 'block';
        container.innerHTML = '';
        container.appendChild(emptyState);
        return;
    }
    
    let html = '<table class="productos-tabla"><thead><tr>';
    html += '<th>CÓDIGO</th>';
    html += '<th>DESCRIPCIÓN</th>';
    html += '<th>CANT.</th>';
    html += '<th>LAB.</th>';
    html += '<th>LOTE</th>';
    html += '<th>F.VENC</th>';
    html += '<th style="text-align: right;">P.UNIT</th>';
    html += '<th style="text-align: right;">IMPORTE</th>';
    html += '<th style="text-align: center;">ACCIÓN</th>';
    html += '</tr></thead><tbody>';
    
    productosAgregados.forEach((prod, index) => {
        const fechaFormateada = prod.fecha_vencimiento ? new Date(prod.fecha_vencimiento + 'T00:00:00').toLocaleDateString('es-PE') : '-';
        
        html += `<tr>
            <td>${prod.codigo}</td>
            <td>${prod.descripcion}
                <input type="hidden" name="productos[${index}][codigo]" value="${prod.codigo}">
                <input type="hidden" name="productos[${index}][descripcion]" value="${prod.descripcion}">
                <input type="hidden" name="productos[${index}][cantidad]" value="${prod.cantidad}">
                <input type="hidden" name="productos[${index}][laboratorio]" value="${prod.laboratorio}">
                <input type="hidden" name="productos[${index}][lote]" value="${prod.lote}">
                <input type="hidden" name="productos[${index}][fecha_vencimiento]" value="${prod.fecha_vencimiento}">
                <input type="hidden" name="productos[${index}][precio_unitario]" value="${prod.precio_unitario}">
                <input type="hidden" name="productos[${index}][importe]" value="${prod.importe}">
            </td>
            <td style="text-align: center;">${prod.cantidad}</td>
            <td>${prod.laboratorio}</td>
            <td>${prod.lote}</td>
            <td>${fechaFormateada}</td>
            <td style="text-align: right;">S/ ${prod.precio_unitario.toFixed(2)}</td>
            <td style="text-align: right;"><strong>S/ ${prod.importe.toFixed(2)}</strong></td>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(${index})" title="Eliminar">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
    });
    
    html += '</tbody></table>';
    container.innerHTML = html;
}

function eliminarProducto(index) {
    if (confirm('¿Eliminar este producto?')) {
        productosAgregados.splice(index, 1);
        renderizarProductos();
        calcularTotalesCompra();
    }
}

function calcularTotalesCompra() {
    let subtotal = 0;
    
    productosAgregados.forEach(prod => {
        subtotal += parseFloat(prod.importe);
    });
    
    const igv = subtotal * 0.18;
    const total = subtotal + igv;
    
    document.getElementById('subtotalDisplay').textContent = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('igvDisplay').textContent = 'S/ ' + igv.toFixed(2);
    document.getElementById('totalDisplay').textContent = 'S/ ' + total.toFixed(2);
    
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('igv').value = igv.toFixed(2);
    document.getElementById('monto_total').value = total.toFixed(2);
}

document.getElementById('formNuevaCompra').addEventListener('submit', function(e) {
    if (productosAgregados.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un producto a la compra');
        return false;
    }
});

calcularTotalesCompra();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>