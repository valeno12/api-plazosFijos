-- Script de Base de Datos - API de Plazo Fijo

CREATE TABLE public.plazo_fijos (
    id              BIGSERIAL PRIMARY KEY,
    numero_cuenta   VARCHAR(255) NOT NULL,
    tipo_moneda     VARCHAR(3)   NOT NULL,
    monto           NUMERIC(15,2) NOT NULL,
    tasa_anual      NUMERIC(6,2)  NOT NULL,
    fecha_inicio    DATE NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    plazo_dias      INTEGER NOT NULL,
    interes_calculado NUMERIC(15,2) NOT NULL,
    monto_final     NUMERIC(15,2) NOT NULL,
    estado          VARCHAR(255) DEFAULT 'ACTIVO' NOT NULL,
    deleted_at      TIMESTAMP(0) WITHOUT TIME ZONE,
    created_at      TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at      TIMESTAMP(0) WITHOUT TIME ZONE,
    CONSTRAINT plazo_fijos_estado_check CHECK (
        estado IN ('ACTIVO', 'VENCIDO', 'CANCELADO', 'RENOVADO')
    )
);

-- NOTA: En producción, los campos interes_calculado y monto_final son calculados
-- automáticamente por el backend (Laravel) al crear o actualizar un registro.
-- Los valores de los INSERT a continuación están precalculados manualmente
-- con la fórmula: Interés = Monto × (TasaAnual / 100 / 365) × PlazoDías

INSERT INTO public.plazo_fijos (numero_cuenta, tipo_moneda, monto, tasa_anual, fecha_inicio, fecha_vencimiento, plazo_dias, interes_calculado, monto_final, estado, created_at, updated_at)
VALUES
    ('386-00123-45678', 'ARS', 100000.00, 85.50, '2024-01-15', '2024-04-15', 90,  21082.19,  121082.19,  'ACTIVO',    NOW(), NOW()),
    ('386-00456-78901', 'USD', 5000.00,   4.50,  '2024-02-01', '2024-08-01', 182, 112.19,    5112.19,    'ACTIVO',    NOW(), NOW()),
    ('386-00789-12345', 'ARS', 250000.00, 75.00, '2023-06-01', '2023-09-01', 92,  47260.27,  297260.27,  'VENCIDO',   NOW(), NOW()),
    ('386-00321-65432', 'ARS', 50000.00,  90.00, '2024-03-01', '2024-04-01', 31,  3821.92,   53821.92,   'CANCELADO', NOW(), NOW()),
    ('386-00654-98765', 'ARS', 500000.00, 80.00, '2023-10-01', '2024-01-01', 92,  100821.92, 600821.92,  'RENOVADO',  NOW(), NOW());
