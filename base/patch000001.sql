/************************************I-SCP-JRR-VEF-0-02/05/2015*************************************************/
CREATE TABLE vef.tmedico (
    id_medico serial NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    primer_apellido VARCHAR(100) NOT NULL,
    segundo_apellido VARCHAR(100),
    telefono_celular VARCHAR(20) NOT NULL,
    telefono_fijo VARCHAR(20),
    otros_telefonos VARCHAR(100),
    correo VARCHAR(150) NOT NULL,
    otros_correos VARCHAR(255),
    porcentaje INTEGER NOT NULL,
    CONSTRAINT pk_tmedico__id_medico
    PRIMARY KEY (id_medico))
INHERITS (pxp.tbase) WITHOUT OIDS;


CREATE TABLE vef.tcliente (
    id_cliente serial NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    primer_apellido VARCHAR(100) NOT NULL,
    segundo_apellido VARCHAR(100),
    telefono_celular VARCHAR(20),
    telefono_fijo VARCHAR(20),
    otros_telefonos VARCHAR(100),
    correo VARCHAR(150),
    otros_correos VARCHAR(255),
    nombre_factura VARCHAR(100),
    nit VARCHAR(25),
    CONSTRAINT pk_tcliente__id_cliente
    PRIMARY KEY (id_cliente))
INHERITS (pxp.tbase) WITHOUT OIDS;

CREATE TABLE vef.tsucursal (
    id_sucursal serial NOT NULL,
    codigo VARCHAR(20),
    nombre VARCHAR(200),
    telefono VARCHAR(50),
    correo VARCHAR(200),
    tiene_precios_x_sucursal VARCHAR(2),
    clasificaciones_para_venta INTEGER[],
    clasificaciones_para_formula INTEGER[],
    CONSTRAINT pk_tsucursal__id_sucursal
    PRIMARY KEY (id_sucursal))
INHERITS (pxp.tbase) WITHOUT OIDS;


CREATE TABLE vef.tsucursal_usuario (
    id_sucursal_usuario serial NOT NULL,
    tipo_usuario VARCHAR(20) NOT NULL,
    id_sucursal INTEGER NOT NULL,  
    id_usuario INTEGER NOT NULL,    
    CONSTRAINT pk_tsucursal_usuario__id_sucursal_usuario
    PRIMARY KEY (id_sucursal_usuario))
INHERITS (pxp.tbase) WITHOUT OIDS;

CREATE TABLE vef.tsucursal_almacen (
    id_sucursal_almacen serial NOT NULL,
    tipo_almacen VARCHAR(20) NOT NULL,
    id_sucursal INTEGER NOT NULL, 
    id_almacen INTEGER NOT NULL,   
    CONSTRAINT pk_tsucursal_almacen__id_sucursal_almacen
    PRIMARY KEY (id_sucursal_almacen))
INHERITS (pxp.tbase) WITHOUT OIDS;

CREATE TABLE vef.tsucursal_producto (
    id_sucursal_producto serial NOT NULL,
    precio NUMERIC(18,2) NOT NULL,
    id_sucursal INTEGER NOT NULL, 
    id_item INTEGER,
    tipo_producto VARCHAR(30) NOT NULL,
    nombre_producto VARCHAR(150) NOT NULL, 
    descripcion_producto TEXT NOT NULL,   
    CONSTRAINT pk_tsucursal_item__id_sucursal_producto
    PRIMARY KEY (id_sucursal_producto))
INHERITS (pxp.tbase) WITHOUT OIDS;


CREATE TABLE vef.tformula (
    id_formula serial NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    id_medico INTEGER NOT NULL,
    id_tipo_presentacion INTEGER,
    id_unidad_medida INTEGER NOT NULL,
    cantidad INTEGER NOT NULL,
    CONSTRAINT pk_tformula__id_formula
    PRIMARY KEY (id_formula))
INHERITS (pxp.tbase) WITHOUT OIDS;


CREATE TABLE vef.tformula_detalle (
    id_formula_detalle serial NOT NULL,
    cantidad NUMERIC(18,2) NOT NULL,
    id_item INTEGER NOT NULL,
    id_formula INTEGER NOT NULL,
    CONSTRAINT pk_tformula_detalle__id_formula_detalle
    PRIMARY KEY (id_formula_detalle))
INHERITS (pxp.tbase) WITHOUT OIDS;


CREATE TABLE vef.ttipo_presentacion (
    id_tipo_presentacion serial NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    CONSTRAINT pk_ttipo_presentacion__id_tipo_presentacion
    PRIMARY KEY (id_tipo_presentacion))
INHERITS (pxp.tbase) WITHOUT OIDS;


CREATE TABLE vef.tventa (
    id_venta serial NOT NULL,
    id_cliente INTEGER NOT NULL,
    id_sucursal INTEGER NOT NULL,
    id_proceso_wf INTEGER NOT NULL,
    id_estado_wf INTEGER NOT NULL,
    nro_tramite VARCHAR NOT NULL,
    total_venta NUMERIC(18,2) NOT NULL DEFAULT 0,
    a_cuenta NUMERIC(18,2) NOT NULL,
    fecha_estimada_entrega DATE NOT NULL,
    estado VARCHAR(100) NOT NULL,
    CONSTRAINT pk_tventa__id_venta
    PRIMARY KEY (id_venta))
INHERITS (pxp.tbase) WITHOUT OIDS;

CREATE TABLE vef.tventa_detalle (
    id_venta_detalle serial NOT NULL,
    id_venta INTEGER NOT NULL,
    id_item INTEGER,
    id_sucursal_producto INTEGER,
    id_formula INTEGER,
    tipo VARCHAR NOT NULL,
    precio NUMERIC(18,2) NOT NULL,
    cantidad INTEGER NOT NULL,
    sw_porcentaje_formula VARCHAR(2) NOT NULL,
    CONSTRAINT pk_tventa_detalle__id_venta_detalle
    PRIMARY KEY (id_venta_detalle))
INHERITS (pxp.tbase) WITHOUT OIDS;


/************************************F-SCP-JRR-VEF-0-02/05/2015*************************************************/

/************************************I-SCP-JRR-VEF-0-17/06/2015*************************************************/

ALTER TABLE vef.tventa
  ADD COLUMN tiene_formula VARCHAR(2) DEFAULT 'no' NOT NULL;
  
/************************************F-SCP-JRR-VEF-0-17/06/2015*************************************************/

/************************************I-SCP-JRR-VEF-0-05/07/2015*************************************************/
ALTER TABLE vef.tsucursal
  ADD COLUMN direccion VARCHAR(255);
  
ALTER TABLE vef.tventa
  ADD COLUMN id_movimiento INTEGER;

/************************************F-SCP-JRR-VEF-0-05/07/2015*************************************************/

/************************************I-SCP-JRR-VEF-0-20/09/2015*************************************************/
ALTER TABLE vef.tsucursal
  ADD COLUMN id_entidad INTEGER NOT NULL;
  
ALTER TABLE vef.tsucursal
  ADD COLUMN plantilla_documento_factura VARCHAR (50);
  
ALTER TABLE vef.tsucursal
  ADD COLUMN plantilla_documento_recibo VARCHAR (50);
  
ALTER TABLE vef.tsucursal
  ADD COLUMN formato_comprobante VARCHAR (50);
  
CREATE TABLE vef.tsucursal_moneda (
    id_sucursal_moneda serial NOT NULL,
    tipo_moneda VARCHAR(20) NOT NULL,
    id_sucursal INTEGER NOT NULL,  
    id_moneda INTEGER NOT NULL,    
    CONSTRAINT pk_tsucursal_moneda__id_sucursal_moneda
    PRIMARY KEY (id_sucursal_moneda))
INHERITS (pxp.tbase) WITHOUT OIDS;

ALTER TABLE vef.tsucursal
  ADD COLUMN lugar VARCHAR (150);
  
ALTER TABLE vef.tsucursal_producto
  ADD COLUMN id_concepto_ingas INTEGER;
  
 
ALTER TABLE vef.tsucursal_producto
  DROP COLUMN nombre_producto;
  
ALTER TABLE vef.tsucursal_producto
  DROP COLUMN descripcion_producto;
  
CREATE TABLE vef.tactividad_economica (
    id_actividad_economica serial NOT NULL,
    codigo VARCHAR(50) NOT NULL,
    nombre VARCHAR(200) NOT NULL, 
    descripcion TEXT,       
    CONSTRAINT pk_tactividad_economica__id_actividad_economica
    PRIMARY KEY (id_actividad_economica))
INHERITS (pxp.tbase) WITHOUT OIDS;

CREATE TABLE vef.tdosificacion (
  id_dosificacion SERIAL,  
  tipo VARCHAR(50) NOT NULL,
  id_sucursal INTEGER NOT NULL,  
  nroaut VARCHAR(150) NOT NULL,
  tipo_generacion VARCHAR(50) NOT NULL,
  inicial INTEGER,
  final INTEGER,
  llave VARCHAR(150),
  fecha_dosificacion DATE NOT NULL,  
  fecha_inicio_emi DATE,
  fecha_limite DATE,  
  id_actividad_economica INTEGER[] NOT NULL,
  glosa_impuestos VARCHAR(150),  
  glosa_empresa VARCHAR(150),  
  nro_siguiente INTEGER,
  CONSTRAINT pk_tdosificacion__id_dosificacion PRIMARY KEY(id_dosificacion)
) INHERITS (pxp.tbase);

COMMENT ON COLUMN vef.tdosificacion.tipo
IS 'F Factura, Notas de Credito y Debito todavia no se tiene ';

COMMENT ON COLUMN vef.tdosificacion.tipo_generacion
IS 'manual|computarizada';

CREATE TABLE vef.tpunto_venta (
  id_punto_venta SERIAL,  
  id_sucursal INTEGER NOT NULL, 
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,  
  CONSTRAINT pk_tpunto_venta__id_punto_venta PRIMARY KEY(id_punto_venta)
) INHERITS (pxp.tbase);

ALTER TABLE vef.tsucursal_usuario
  ADD COLUMN id_punto_venta INTEGER;
  
ALTER TABLE vef.tmedico
  ADD COLUMN fecha_nacimiento date;
  
  
CREATE TABLE vef.tforma_pago (
  id_forma_pago SERIAL,  
  codigo VARCHAR NOT NULL, 
  nombre VARCHAR(200) NOT NULL,
  id_entidad INTEGER NOT NULL,  
  id_moneda INTEGER NOT NULL,   
  CONSTRAINT pk_tforma_pago__id_forma_pago PRIMARY KEY(id_forma_pago)
) INHERITS (pxp.tbase);

DROP VIEW IF EXISTS vef.vcliente;

ALTER TABLE vef.tcliente
  ALTER COLUMN nombres DROP NOT NULL;
  
ALTER TABLE vef.tcliente
  ALTER COLUMN primer_apellido DROP NOT NULL;
  
ALTER TABLE vef.tcliente
  ALTER COLUMN nombre_factura TYPE VARCHAR(200) COLLATE pg_catalog."default";

ALTER TABLE vef.tcliente
  ALTER COLUMN nombre_factura SET NOT NULL;
  
ALTER TABLE vef.tventa
  ADD COLUMN id_punto_venta INTEGER;
  
 
ALTER TABLE vef.tventa
  ADD COLUMN correlativo_venta VARCHAR(20)  DEFAULT '' NOT NULL;
  
  
CREATE TABLE vef.tventa_forma_pago (
  id_venta_forma_pago SERIAL,  
  id_forma_pago INTEGER NOT NULL, 
  id_venta INTEGER NOT NULL,
  monto NUMERIC(18,2) NOT NULL,    
  CONSTRAINT pk_tventa_forma_pago__id_venta_forma_pago PRIMARY KEY(id_venta_forma_pago)
) INHERITS (pxp.tbase);

ALTER TABLE vef.tforma_pago
  ADD COLUMN defecto VARCHAR(2);
  
ALTER TABLE vef.tventa_forma_pago
  ADD COLUMN monto_transaccion NUMERIC(18,2) NOT NULL;
  
ALTER TABLE vef.tventa_forma_pago
  ADD COLUMN cambio NUMERIC(18,2) NOT NULL;

ALTER TABLE vef.tventa_forma_pago
  ADD COLUMN monto_mb_efectivo NUMERIC(18,2) NOT NULL;

ALTER TABLE vef.tventa_detalle
  DROP COLUMN sw_porcentaje_formula;

ALTER TABLE vef.tforma_pago
  ADD COLUMN registrar_tarjeta VARCHAR(2);

ALTER TABLE vef.tforma_pago
  ADD COLUMN registrar_cc VARCHAR(2);

ALTER TABLE vef.tventa_forma_pago
  ADD COLUMN numero_tarjeta VARCHAR(25);
  
ALTER TABLE vef.tventa_forma_pago
  ADD COLUMN codigo_tarjeta VARCHAR(25);
  
ALTER TABLE vef.tventa_forma_pago
  ADD COLUMN tipo_tarjeta VARCHAR(10);
  
/************************************F-SCP-JRR-VEF-0-20/09/2015*************************************************/

/************************************I-SCP-JRR-VEF-0-08/11/2015*************************************************/

ALTER TABLE vef.tventa_detalle
  ADD COLUMN precio_sin_descuento NUMERIC(18,2);
  
ALTER TABLE vef.tventa_detalle
  ADD COLUMN porcentaje_descuento NUMERIC(5);
  
ALTER TABLE vef.tventa_detalle
  ADD COLUMN id_vendedor INTEGER;
  
ALTER TABLE vef.tventa_detalle
  ADD COLUMN id_medico INTEGER;
  
ALTER TABLE vef.tventa
  ADD COLUMN porcentaje_descuento NUMERIC(5);
  
ALTER TABLE vef.tventa
  ADD COLUMN id_vendedor_medico VARCHAR(30);

ALTER TABLE vef.tsucursal_producto
  ADD COLUMN requiere_descripcion VARCHAR(2);

ALTER TABLE vef.tsucursal
  ADD COLUMN habilitar_comisiones VARCHAR(2);
  
ALTER TABLE vef.tpunto_venta
  ADD COLUMN habilitar_comisiones VARCHAR(2);
  
ALTER TABLE vef.tpunto_venta
  ADD COLUMN codigo VARCHAR(20);
  
ALTER TABLE vef.tventa
  ADD COLUMN comision NUMERIC(18,2);

ALTER TABLE vef.tventa_detalle
  ADD COLUMN descripcion TEXT;

ALTER TABLE vef.tformula_detalle
  ADD COLUMN id_concepto_ingas INTEGER;

ALTER TABLE vef.tformula_detalle
  ALTER COLUMN id_item DROP NOT NULL;
  
ALTER TABLE vef.tsucursal
  ADD COLUMN id_lugar INTEGER;

/************************************F-SCP-JRR-VEF-0-08/11/2015*************************************************/

/************************************I-SCP-JRR-VEF-0-19/11/2015*************************************************/

ALTER TABLE vef.tformula
  ALTER COLUMN cantidad DROP NOT NULL;

ALTER TABLE vef.tformula
  ALTER COLUMN id_unidad_medida DROP NOT NULL;

ALTER TABLE vef.tformula
  ALTER COLUMN id_medico DROP NOT NULL;
  
ALTER TABLE vef.tventa
  ADD COLUMN observaciones TEXT;
/************************************F-SCP-JRR-VEF-0-19/11/2015*************************************************/

/************************************I-SCP-JRR-VEF-0-25/11/2015*************************************************/

CREATE TABLE vef.tboleto (
  id_boleto SERIAL,  
  fecha DATE NOT NULL, 
  id_punto_venta INTEGER NOT NULL,
  numero VARCHAR (30) NOT NULL,
  ruta VARCHAR (50) NOT NULL,     
  CONSTRAINT pk_tboleto__id_boleto PRIMARY KEY(id_boleto)
) INHERITS (pxp.tbase);

CREATE TABLE vef.tboleto_fp (
  id_boleto_fp SERIAL,  
  id_forma_pago INTEGER NOT NULL ,
  id_boleto INTEGER NOT NULL,
  monto NUMERIC(18,2) NOT NULL,    
  CONSTRAINT pk_tboleto_fp__id_boleto_fp PRIMARY KEY(id_boleto_fp)
) INHERITS (pxp.tbase);

/************************************F-SCP-JRR-VEF-0-25/11/2015*************************************************/

/************************************I-SCP-JRR-VEF-0-19/02/2016*************************************************/

ALTER TABLE vef.tventa
  ADD COLUMN id_dosificacion INTEGER;
  
ALTER TABLE vef.tventa
  ADD COLUMN nro_factura INTEGER;
  
ALTER TABLE vef.tventa
  ADD COLUMN fecha DATE NOT NULL;
  
ALTER TABLE vef.tventa
  ADD COLUMN excento NUMERIC(18,2) DEFAULT 0 NOT NULL;
  
ALTER TABLE vef.tventa
  ADD COLUMN tipo_factura VARCHAR(20) DEFAULT 'recibo' NOT NULL;
  
ALTER TABLE vef.tventa
  ADD COLUMN cod_control VARCHAR(15);
  
CREATE TABLE vef.tpunto_venta_producto (
  id_punto_venta_producto SERIAL,
  id_punto_venta INTEGER NOT NULL,
  id_sucursal_producto INTEGER NOT NULL,
  CONSTRAINT pk_tpunto_venta_producto PRIMARY KEY(id_punto_venta_producto)
) INHERITS (pxp.tbase)
  
 /************************************F-SCP-JRR-VEF-0-19/02/2016*************************************************/


/************************************I-SCP-JRR-VEF-0-11/03/2016*************************************************/

ALTER TABLE vef.tpunto_venta
  ADD COLUMN tipo VARCHAR ;
  
ALTER TABLE vef.tsucursal_producto
  ADD COLUMN id_moneda INTEGER ;
  
/************************************F-SCP-JRR-VEF-0-11/03/2016*************************************************/


/************************************I-SCP-JRR-VEF-0-22/03/2016*************************************************/

CREATE TABLE vef.ttipo_venta (
  id_tipo_venta SERIAL,
  codigo VARCHAR(80) NOT NULL,
  nombre VARCHAR(150) NOT NULL,
  codigo_relacion_contable VARCHAR(100),
  tipo_base VARCHAR(40),
  CONSTRAINT pk_ttipo_venta PRIMARY KEY(id_tipo_venta)
) INHERITS (pxp.tbase)

CREATE TABLE vef.tproceso_venta (
  id_proceso_venta SERIAL,
  estado VARCHAR(20) NOT NULL,
  fecha_desde DATE NOT NULL,
  fecha_hasta DATE NOT NULL,
  id_int_comprobante INTEGER,
  tipos VARCHAR[],
  CONSTRAINT pk_tproceso_venta PRIMARY KEY(id_proceso_venta)
) INHERITS (pxp.tbase)
  
/************************************F-SCP-JRR-VEF-0-22/03/2016*************************************************/