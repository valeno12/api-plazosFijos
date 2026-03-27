CREATE TABLE public.plazo_fijos (
    id bigint NOT NULL,
    numero_cuenta character varying(255) NOT NULL,
    tipo_moneda character varying(3) NOT NULL,
    monto numeric(15,2) NOT NULL,
    tasa_anual numeric(6,2) NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_vencimiento date NOT NULL,
    plazo_dias integer NOT NULL,
    interes_calculado numeric(15,2) NOT NULL,
    monto_final numeric(15,2) NOT NULL,
    estado character varying(255) DEFAULT 'ACTIVO'::character varying NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT plazo_fijos_estado_check CHECK (((estado)::text = ANY ((ARRAY['ACTIVO'::character varying, 'VENCIDO'::character varying, 'CANCELADO'::character varying, 'RENOVADO'::character varying])::text[])))
);
