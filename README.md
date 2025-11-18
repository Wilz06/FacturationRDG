# Sistema de Facturación - Proyecto Académico

Sistema completo de gestión de facturas desarrollado en PHP con Bootstrap y MySQL.

## 📋 Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior / MariaDB 10.2 o superior
- Apache con mod_rewrite habilitado
- Composer (para instalar PhpSpreadsheet)

## 🚀 Instalación

### Paso 1: Configurar la Base de Datos

1. Abre MySQL Workbench o phpMyAdmin
2. Ejecuta el script SQL proporcionado para crear la base de datos:

```sql
CREATE DATABASE IF NOT EXISTS sistema_facturacion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_facturacion;

CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    laboratorio VARCHAR(255) NOT NULL,
    cliente VARCHAR(255) NOT NULL,
    numero_factura VARCHAR(100) NOT NULL UNIQUE,
    ruc VARCHAR(20),
    direccion TEXT,
    telefono VARCHAR(50),
    email VARCHAR(100),
    fecha_emision DATE NOT NULL,
    fecha_vencimiento DATE,
    tipo_pago VARCHAR(50),
    moneda VARCHAR(10) DEFAULT 'SOLES',
    subtotal DECIMAL(10, 2) DEFAULT 0.00,
    igv DECIMAL(10, 2) DEFAULT 0.00,
    monto_total DECIMAL(10, 2) NOT NULL,
    estado VARCHAR(50) DEFAULT 'PENDIENTE',
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_numero_factura ON facturas(numero_factura);
CREATE INDEX idx_cliente ON facturas(cliente);
CREATE INDEX idx_fecha_emision ON facturas(fecha_emision);
CREATE INDEX idx_laboratorio ON facturas(laboratorio);
```

### Paso 2: Configurar la Conexión

Edita el archivo `config/database.php` y ajusta los datos de conexión:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_facturacion');
define('DB_USER', 'root');
define('DB_PASS', ''); // Tu contraseña de MySQL
```

### Paso 3: Instalar Dependencias

Abre una terminal en la carpeta del proyecto y ejecuta:

```bash
composer install
```

Si no tienes Composer instalado, descárgalo desde: https://getcomposer.org/

### Paso 4: Configurar Permisos

Asegúrate de que la carpeta `uploads/` tenga permisos de escritura:

```bash
chmod 755 uploads/
```

### Paso 5: Acceder al Sistema

1. Coloca el proyecto en tu servidor web (htdocs, www, etc.)
2. Accede desde tu navegador: `http://localhost/facturacion/`

## 📁 Estructura del Proyecto

```
facturacion/
├── config/
│   └── database.php          # Configuración de base de datos
├── controllers/
│   └── FacturaController.php # Lógica de negocio
├── models/
│   └── Factura.php           # Modelo de datos
├── views/
│   ├── layouts/
│   │   ├── header.php        # Cabecera HTML
│   │   └── footer.php        # Pie de página
│   └── facturas/
│       ├── index.php         # Lista de facturas
│       ├── crear.php         # Formulario de creación
│       └── editar.php        # Formulario de edición
├── assets/
│   └── css/
│       └── style.css         # Estilos personalizados
├── uploads/                  # Carpeta para archivos Excel
├── vendor/                   # Librerías de Composer
├── index.php                 # Archivo principal
├── composer.json             # Dependencias de PHP
└── .htaccess                 # Configuración de Apache
```

## 📊 Formato del Archivo Excel

Para importar facturas desde Excel, el archivo debe tener las siguientes columnas (en orden):

| Columna | Campo | Descripción |
|---------|-------|-------------|
| A | Laboratorio | Nombre del laboratorio |
| B | Cliente | Nombre del cliente |
| C | Número Factura | Número único de factura |
| D | RUC | RUC del laboratorio |
| E | Dirección | Dirección del laboratorio |
| F | Teléfono | Teléfono de contacto |
| G | Email | Correo electrónico |
| H | Fecha Emisión | Formato: YYYY-MM-DD |
| I | Fecha Vencimiento | Formato: YYYY-MM-DD |
| J | Tipo Pago | CONTADO, CREDITO o TRANSFERENCIA |
| K | Moneda | SOLES o DOLARES |
| L | Subtotal | Monto sin IGV |
| M | IGV | Impuesto (18%) |
| N | Monto Total | Total con IGV |
| O | Estado | PENDIENTE, PAGADO, VENCIDO o ANULADO |
| P | Observaciones | Notas adicionales |

**Nota:** La primera fila del Excel debe contener los encabezados.

## 🔧 Funcionalidades

### CRUD Completo
- ✅ **Crear** nuevas facturas manualmente
- ✅ **Leer** y listar todas las facturas
- ✅ **Actualizar** facturas existentes
- ✅ **Eliminar** facturas

### Importación de Excel
- ✅ Subir archivo Excel (.xlsx o .xls)
- ✅ Lectura automática de datos
- ✅ Inserción masiva en base de datos
- ✅ Validación de datos duplicados
- ✅ Reporte de errores por fila

### Características Adicionales
- ✅ Búsqueda y filtrado con DataTables
- ✅ Cálculo automático de IGV (18%)
- ✅ Estados de factura (Pendiente, Pagado, Vencido, Anulado)
- ✅ Diseño responsive con Bootstrap 5
- ✅ Alertas y confirmaciones con SweetAlert2
- ✅ Validación de formularios

## 🎨 Tecnologías Utilizadas

- **Backend:** PHP 7.4+
- **Base de Datos:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5.3
- **Librería Excel:** PhpSpreadsheet
- **Iconos:** Bootstrap Icons
- **Tablas:** DataTables
- **Alertas:** SweetAlert2
- **Servidor Web:** Apache

## 🐛 Solución de Problemas

### Error de conexión a la base de datos
- Verifica las credenciales en `config/database.php`
- Asegúrate de que MySQL esté ejecutándose
- Confirma que la base de datos existe

### PhpSpreadsheet no encontrado
- Ejecuta `composer install` en la raíz del proyecto
- Verifica que la carpeta `vendor/` exista

### Error al subir archivos Excel
- Verifica permisos de la carpeta `uploads/`
- Aumenta `upload_max_filesize` en `php.ini`
- Revisa el formato del archivo Excel

### Las fechas no se importan correctamente
- Asegúrate de usar formato de fecha: YYYY-MM-DD
- Verifica que las celdas en Excel estén formateadas como fecha

## 📞 Soporte

Este es un proyecto académico. Para dudas o mejoras, revisa el código fuente y la documentación inline.

## 📄 Licencia

Proyecto académico de uso libre para fines educativos.

---

**Desarrollado como proyecto académico - Sistema de Facturación**

-----------------------------------------------------------------------------------------------------------------

# 📊 Guía de Importación de Facturas desde CSV

## 🎯 Pasos para Importar Facturas

### Paso 1: Descargar la Plantilla
1. En el sistema, haz clic en el botón verde **"Descargar Plantilla"**
2. Se descargará un archivo llamado `plantilla_facturas_YYYY-MM-DD.csv`
3. Este archivo contiene:
   - Los encabezados correctos en la primera fila
   - 3 ejemplos de facturas para que veas el formato

### Paso 2: Editar la Plantilla
1. **Abre el archivo** con Excel, LibreOffice Calc o Google Sheets
2. **Mantén la primera fila** (encabezados) sin cambios
3. **Edita o elimina** las filas de ejemplo
4. **Agrega tus facturas** siguiendo el mismo formato

### Paso 3: Formato de las Columnas

#### Columnas Obligatorias (con formato específico):

| # | Columna | Ejemplo | Notas |
|---|---------|---------|-------|
| 1 | Laboratorio | `CONSORCIO MEDICORP & SALUD S.A.C.` | Nombre completo del laboratorio |
| 2 | Cliente | `MEDINA CASTRO LUZ YUBANA` | Nombre del cliente |
| 3 | Numero Factura | `F009-00020214` | Debe ser único |
| 4 | RUC | `20449809295` | Solo números, 11 dígitos |
| 5 | Direccion | `AV. JAVIER PRADO ESTE 2520` | Dirección completa |
| 6 | Telefono | `988279527` | Números y símbolos permitidos |
| 7 | Email | `contacto@empresa.com` | Email válido |
| 8 | Fecha Emision | `2024-11-15` | **Formato: YYYY-MM-DD** |
| 9 | Fecha Vencimiento | `2024-12-15` | **Formato: YYYY-MM-DD** (puede estar vacío) |
| 10 | Tipo Pago | `CONTADO` | Solo: CONTADO, CREDITO o TRANSFERENCIA |
| 11 | Moneda | `SOLES` | Solo: SOLES o DOLARES |
| 12 | Subtotal | `671.53` | Número decimal (sin símbolos) |
| 13 | IGV | `120.88` | Número decimal (18% del subtotal) |
| 14 | Monto Total | `792.41` | Número decimal (Subtotal + IGV) |
| 15 | Estado | `PAGADO` | Solo: PENDIENTE, PAGADO, VENCIDO o ANULADO |
| 16 | Observaciones | `Medicamentos oncológicos` | Texto libre (opcional) |

#### ⚠️ Importante sobre las Fechas:
- **SIEMPRE** usa el formato `YYYY-MM-DD`
- Ejemplos correctos: `2024-11-15`, `2024-01-05`, `2024-12-31`
- Ejemplos incorrectos: `15/11/2024`, `11-15-2024`, `15-Nov-2024`

#### 💰 Cálculo de Montos:
- **Subtotal**: El monto sin impuestos
- **IGV**: El 18% del subtotal (Subtotal × 0.18)
- **Monto Total**: Subtotal + IGV

Ejemplo:
- Subtotal: 1000.00
- IGV: 180.00 (1000 × 0.18)
- Monto Total: 1180.00

### Paso 4: Guardar como CSV
1. En Excel: **Archivo** → **Guardar como**
2. En **"Tipo"** selecciona: **CSV (delimitado por comas) (*.csv)**
3. Guarda el archivo con un nombre descriptivo
4. **¡IMPORTANTE!** Si Excel pregunta si quieres mantener el formato CSV, haz clic en **Sí**

### Paso 5: Importar en el Sistema
1. En el sistema, haz clic en **"Importar CSV"**
2. En el modal que aparece, haz clic en **"Seleccionar archivo CSV"**
3. Selecciona tu archivo CSV
4. Haz clic en **"Importar CSV"**
5. El sistema procesará las facturas y te mostrará:
   - ✅ Cuántas facturas se importaron exitosamente
   - ⚠️ Cuántos errores se encontraron (si los hay)

## 🚫 Errores Comunes y Soluciones

### Error: "Número de factura ya existe"
**Causa**: Intentaste importar una factura con un número que ya está en el sistema
**Solución**: Cambia el número de factura o elimina esa fila del CSV

### Error: "Datos incompletos"
**Causa**: Faltan campos obligatorios (Laboratorio, Cliente, Número Factura o Monto Total)
**Solución**: Asegúrate de llenar todos los campos obligatorios

### Error: "Formato de fecha incorrecto"
**Causa**: Las fechas no están en formato YYYY-MM-DD
**Solución**: Cambia el formato de las fechas a YYYY-MM-DD

### Error: "Tipo de Pago inválido"
**Causa**: Usaste un valor diferente a CONTADO, CREDITO o TRANSFERENCIA
**Solución**: Usa solo uno de estos tres valores exactos

### Las fechas se ven raras en Excel
**Causa**: Excel cambia automáticamente el formato de las fechas
**Solución**: 
1. Selecciona la columna de fechas
2. Clic derecho → Formato de celdas
3. Selecciona "Texto" o "Personalizado"
4. Usa el formato: `yyyy-mm-dd`

## 💡 Consejos

1. **Usa la plantilla de ejemplo** para entender el formato correcto
2. **Revisa los datos** antes de importar
3. **Importa en lotes pequeños** primero para verificar que todo funcione
4. **Mantén una copia** del archivo CSV original por si necesitas corregir errores
5. **No modifiques los encabezados** de la primera fila

## 📝 Ejemplo Completo de una Fila

```csv
LABORATORIO ROCHE S.A.,CLINICA SAN PABLO,F001-00050123,20100123456,AV. JAVIER PRADO ESTE 2520,4416000,contacto@roche.com.pe,2024-11-10,2024-12-10,CREDITO,SOLES,15000.00,2700.00,17700.00,PENDIENTE,Medicamentos oncológicos
```

## ❓ ¿Necesitas Ayuda?

Si encuentras problemas durante la importación:
1. Verifica que el archivo sea realmente CSV (no Excel .xlsx)
2. Asegúrate de que la primera fila sean los encabezados
3. Revisa que las fechas estén en formato YYYY-MM-DD
4. Confirma que los valores de Tipo Pago, Moneda y Estado sean válidos
5. Verifica que los números de factura sean únicos

---

**¡Listo! Ahora puedes importar facturas masivamente a tu sistema.**