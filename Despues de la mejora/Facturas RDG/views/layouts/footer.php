</div> 
</div> 
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        $(document).ready(function() {
            if ($('#tablaFacturas').length) {
                $('#tablaFacturas').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                        emptyTable: '<div class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 48px;"></i><p class="mt-2">No hay facturas registradas</p></div>'
                    },
                    order: [[0, 'desc']],
                    pageLength: 25,
                    responsive: true
                });
            }
            
            if ($('#tablaProductos').length) {
                $('#tablaProductos').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                        emptyTable: '<div class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 48px;"></i><p class="mt-2">No hay productos registrados</p></div>'
                    },
                    order: [[0, 'desc']],
                    pageLength: 50,
                    responsive: true
                });
            }
        });
        
        function confirmarEliminacion(id, tipo = 'factura') {
            const textos = {
                factura: {
                    title: '¿Eliminar esta factura?',
                    text: 'Esta acción eliminará la factura y todos sus productos'
                },
                producto: {
                    title: '¿Eliminar este producto?',
                    text: 'Esta acción no se puede deshacer'
                }
            };
            
            Swal.fire({
                title: textos[tipo].title,
                text: textos[tipo].text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (tipo === 'factura') {
                        window.location.href = 'index.php?action=eliminar&id=' + id;
                    } else {
                        window.location.href = 'index.php?module=productos&action=eliminar&id=' + id;
                    }
                }
            });
        }
        
        function calcularTotales() {
            const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
            const igv = subtotal * 0.18;
            const total = subtotal + igv;
            
            document.getElementById('igv').value = igv.toFixed(2);
            document.getElementById('monto_total').value = total.toFixed(2);
        }
        
        function calcularImporte() {
            const cantidad = parseFloat(document.getElementById('cantidad')?.value) || 0;
            const precio = parseFloat(document.getElementById('precio_unitario')?.value) || 0;
            const importe = cantidad * precio;
            
            if (document.getElementById('importe')) {
                document.getElementById('importe').value = importe.toFixed(2);
            }
        }
        
        function validarArchivo(input, tipo = 'csv') {
            const file = input.files[0];
            if (file) {
                const extension = file.name.split('.').pop().toLowerCase();
                const extensionesValidas = tipo === 'csv' ? ['csv', 'txt'] : ['xlsx', 'xls', 'txt'];
                
                if (!extensionesValidas.includes(extension)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Formato de archivo no válido. Use: ' + extensionesValidas.join(', ')
                    });
                    input.value = '';
                    return false;
                }
            }
            return true;
        }
        
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        $('form').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true);
        });
    </script>
</body>
</html>