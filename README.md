# API de Gestión de Plazos Fijos

API REST para la gestión integral de plazos fijos bancarios con cálculo automático de intereses.

## Stack Tecnológico

| Tecnología | Versión | Rol |
|-----------|---------|-----|
| PHP | 8.3 | Runtime |
| Laravel | 13 | Framework |
| PostgreSQL | 15 | Base de datos |
| Docker | - | Contenedorización |

## Modelo de Datos

```json
{
  "id": 1,
  "numeroCuenta": "386-00123-45678",
  "tipoMoneda": "ARS",
  "monto": 100000.00,
  "tasaAnual": 85.50,
  "fechaInicio": "2024-01-15",
  "fechaVencimiento": "2024-04-15",
  "plazoDias": 90,
  "interesCalculado": 21082.19,
  "montoFinal": 121082.19,
  "estado": "ACTIVO",
  "creadoEn": "2026-03-27T16:00:00+00:00",
  "actualizadoEn": "2026-03-27T16:00:00+00:00"
}
```

### Cálculo de Interés

El sistema calcula automáticamente el interés y monto final al crear o actualizar un plazo fijo:

```
Interés = Monto . (Tasa Anual / 100 / 365) . Plazo en Días
Monto Final = Monto + Interés
```

**Ejemplo:**
```
Monto:      $100.000
Tasa Anual: 85.50%
Días:       90

Interés     = 100.000 . (85.50 / 100 / 365) . 90 = $21.082,19
Monto Final = $100.000 + $21.082,19 = $121.082,19
```

### Estados

| Estado | Descripción |
|--------|-------------|
| `ACTIVO` | Vigente, aún no venció |
| `VENCIDO` | Ya pasó la fecha de vencimiento |
| `CANCELADO` | Cancelado anticipadamente por el cliente |
| `RENOVADO` | Renovado al vencimiento |

---

## Guía de Instalación

### Opción 1: Docker (recomendada)

> Un solo comando levanta la aplicación completa con base de datos incluida.

**Requisitos:** Docker y Docker Compose instalados.

```bash
# 1. Clonar el repositorio
git clone git@github.com:valeno12/api-plazosFijos.git
cd api-plazoFijo

# 2. Levantar los contenedores
docker compose up --build
```

Eso es todo. La aplicación:
- Instala las dependencias automáticamente
- Genera la `APP_KEY`
- Espera a que PostgreSQL esté disponible
- Ejecuta las migraciones y los seeders -> Genera tablas y datos de prueba
- Queda escuchando en **http://localhost:8000**

Para detener los contenedores:
```bash
docker compose down
```

Para detener y eliminar los volúmenes (resetear la base de datos):
```bash
docker compose down -v
```

### Opción 2: Instalación Manual

**Requisitos:** PHP 8.3+, Composer, PostgreSQL 15+.

```bash
# 1. Clonar el repositorio
git clone https://github.com/tu-usuario/api-plazoFijo.git
cd api-plazoFijo

# 2. Instalar dependencias
composer install

# 3. Configurar el entorno
cp .env.example .env
php artisan key:generate
```

Editar el archivo `.env` con las credenciales de tu base de datos PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=plazo_fijo_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

```bash
# 4. Crear la base de datos en PostgreSQL
createdb plazo_fijo_db

# 5. Ejecutar migraciones y datos de prueba
php artisan migrate --seed

# 6. Iniciar el servidor
php artisan serve
```

La API estará disponible en **http://localhost:8000**.

---

## Manual de Usuario

### Base URL

```
http://localhost:8000/api
```

Todos los requests deben incluir el header:
```
Accept: application/json
```

---

### 1. Crear un Plazo Fijo

```
POST /api/plazos-fijos
```

**Request Body:**
```json
{
  "numero_cuenta": "386-00123-45678",
  "tipo_moneda": "ARS",
  "monto": 100000,
  "tasa_anual": 85.50,
  "fecha_inicio": "2024-01-15",
  "fecha_vencimiento": "2024-04-15",
  "plazo_dias": 90
}
```

> El campo `estado` es opcional. Si no se envía, se asigna `ACTIVO` por defecto.
> Los campos `interesCalculado` y `montoFinal` se calculan automáticamente.

**Response `201 Created`:**
```json
{
  "data": {
    "id": 1,
    "numeroCuenta": "386-00123-45678",
    "tipoMoneda": "ARS",
    "monto": 100000.00,
    "tasaAnual": 85.50,
    "fechaInicio": "2024-01-15",
    "fechaVencimiento": "2024-04-15",
    "plazoDias": 90,
    "interesCalculado": 21082.19,
    "montoFinal": 121082.19,
    "estado": "ACTIVO",
    "creadoEn": "2026-03-27T16:00:00+00:00",
    "actualizadoEn": "2026-03-27T16:00:00+00:00"
  },
  "message": "Plazo fijo creado exitosamente."
}
```

---

### 2. Listar Todos los Plazos Fijos

```
GET /api/plazos-fijos
```

Devuelve una lista paginada (10 registros por página).

**Filtros disponibles (query params):**

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `page` | integer | Número de página |
| `estado` | string | Filtro exacto: `ACTIVO`, `VENCIDO`, `CANCELADO`, `RENOVADO` |
| `tipo_moneda` | string | Filtro exacto por moneda (ej: `ARS`, `USD`) |
| `numero_cuenta` | string | Búsqueda parcial por número de cuenta |

**Ejemplos:**
```
GET /api/plazos-fijos?estado=ACTIVO
GET /api/plazos-fijos?tipo_moneda=ARS&page=2
GET /api/plazos-fijos?numero_cuenta=00123
```

**Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "numeroCuenta": "386-00123-45678",
      "tipoMoneda": "ARS",
      "monto": 100000.00,
      "tasaAnual": 85.50,
      "fechaInicio": "2024-01-15",
      "fechaVencimiento": "2024-04-15",
      "plazoDias": 90,
      "interesCalculado": 21082.19,
      "montoFinal": 121082.19,
      "estado": "ACTIVO",
      "creadoEn": "2026-03-27T16:00:00+00:00",
      "actualizadoEn": "2026-03-27T16:00:00+00:00"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/plazos-fijos?page=1",
    "last": "http://localhost:8000/api/plazos-fijos?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 10,
    "to": 5,
    "total": 5
  }
}
```

---

### 3. Obtener un Plazo Fijo por ID

```
GET /api/plazos-fijos/{id}
```

**Response `200 OK`:**
```json
{
  "data": {
    "id": 1,
    "numeroCuenta": "386-00123-45678",
    "tipoMoneda": "ARS",
    "monto": 100000.00,
    "tasaAnual": 85.50,
    "fechaInicio": "2024-01-15",
    "fechaVencimiento": "2024-04-15",
    "plazoDias": 90,
    "interesCalculado": 21082.19,
    "montoFinal": 121082.19,
    "estado": "ACTIVO",
    "creadoEn": "2026-03-27T16:00:00+00:00",
    "actualizadoEn": "2026-03-27T16:00:00+00:00"
  }
}
```

---

### 4. Actualizar un Plazo Fijo

```
PUT /api/plazos-fijos/{id}
```

Requiere enviar todos los campos. El interés y monto final se recalculan automáticamente.

**Request Body:**
```json
{
  "numero_cuenta": "386-00123-45678",
  "tipo_moneda": "ARS",
  "monto": 200000,
  "tasa_anual": 90.00,
  "fecha_inicio": "2024-01-15",
  "fecha_vencimiento": "2024-07-15",
  "plazo_dias": 182,
  "estado": "RENOVADO"
}
```

**Response `200 OK`:**
```json
{
  "data": {
    "id": 1,
    "numeroCuenta": "386-00123-45678",
    "tipoMoneda": "ARS",
    "monto": 200000.00,
    "tasaAnual": 90.00,
    "fechaInicio": "2024-01-15",
    "fechaVencimiento": "2024-07-15",
    "plazoDias": 182,
    "interesCalculado": 89753.42,
    "montoFinal": 289753.42,
    "estado": "RENOVADO",
    "creadoEn": "2026-03-27T16:00:00+00:00",
    "actualizadoEn": "2026-03-27T16:30:00+00:00"
  }
}
```

---

### 5. Eliminar un Plazo Fijo

```
DELETE /api/plazos-fijos/{id}
```

Realiza un borrado lógico (Soft Delete) - el registro persiste en la base de datos con una marca de tiempo en `deleted_at`.

**Response `200 OK`:**
```json
{
  "message": "Plazo fijo eliminado exitosamente."
}
```

---

### Validaciones

| Campo | Regla |
|-------|-------|
| `numero_cuenta` | Obligatorio, string, máximo 50 caracteres |
| `tipo_moneda` | Obligatorio, string, exactamente 3 caracteres |
| `monto` | Obligatorio, numérico, mayor a 0 |
| `tasa_anual` | Obligatorio, numérico, entre 1 y 200 |
| `fecha_inicio` | Obligatorio, fecha válida |
| `fecha_vencimiento` | Obligatorio, fecha válida, posterior a `fecha_inicio` |
| `plazo_dias` | Obligatorio, entero, entre 1 y 365 |
| `estado` | Opcional, debe ser: `ACTIVO`, `VENCIDO`, `CANCELADO` o `RENOVADO` |

---

### Códigos de Error

**`404 Not Found` - Recurso no encontrado:**
```json
{
  "success": false,
  "message": "El recurso solicitado no fue encontrado."
}
```

**`404 Not Found` - Ruta inexistente:**
```json
{
  "success": false,
  "message": "La ruta solicitada no existe."
}
```

**`405 Method Not Allowed` - Método HTTP incorrecto:**
```json
{
  "success": false,
  "message": "El método HTTP utilizado no está permitido para esta ruta."
}
```

**`422 Unprocessable Entity` - Error de validación:**
```json
{
  "success": false,
  "message": "Los datos proporcionados no son válidos.",
  "errors": {
    "monto": ["El monto debe ser mayor a 0."],
    "tasa_anual": ["La tasa anual debe estar entre 1% y 200%."]
  }
}
```

**`503 Service Unavailable` - Error de base de datos:**
```json
{
  "success": false,
  "message": "No se pudo conectar con la base de datos. Verifique que el servidor esté activo."
}
```

**`500 Internal Server Error` - Error interno:**
```json
{
  "success": false,
  "message": "Ocurrió un error interno en el servidor."
}
```

---

## Documentación Interactiva

El archivo `swagger.yaml` en la raíz del proyecto contiene la especificación OpenAPI 3.0.3 completa. Podés visualizarla importándola en [Swagger Editor](https://editor.swagger.io/).

---

## Decisiones de Arquitectura

Para el diseño de la API REST se optó por respetar estrictamente los estándares definidos.

**Uso estricto del verbo PUT:**
El endpoint `PUT /api/plazos-fijos/{id}` exige enviar la entidad completa (todos los campos requeridos). Esto no es un error ni un caso no contemplado, sino una decisión intencional de diseño. Semánticamente, en REST, el verbo `PUT` implica el **reemplazo total** de un recurso. Para actualizaciones parciales (como por ejemplo enviar solamente el campo `estado`), la práctica estándar indica que se debería exponer o utilizar el verbo `PATCH`.

---

## Estructura del Proyecto

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── PlazoFijoController.php     # Controlador CRUD
│   │   ├── Requests/
│   │   │   └── PlazoFijoRequest.php        # Validaciones del request
│   │   └── Resources/
│   │       └── PlazoFijoResource.php       # Transformación JSON (camelCase)
│   └── Models/
│       └── PlazoFijo.php                   # Modelo con cálculo de interés
├── database/
│   ├── migrations/
│   │   └── ..._create_plazo_fijos_table.php
│   └── seeders/
│       └── PlazoFijoSeeder.php             # Datos de prueba
├── lang/es/
│   └── validation.php                      # Mensajes de validación en español
├── routes/
│   └── api.php                             # Definición de rutas API
├── database.sql                            # Script SQL (CREATE TABLE + INSERT)*
├── swagger.yaml                            # Documentación OpenAPI 3.0.3
├── Dockerfile
└── docker-compose.yml
```

> \* **Nota sobre `database.sql`:** Los campos `interes_calculado` y `monto_final` están precalculados en los INSERT porque este script es independiente de Laravel. En la aplicación, estos valores son calculados automáticamente por el modelo al crear o actualizar un registro a través de la API.
