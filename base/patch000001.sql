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
  id_activida_economica INTEGER[] NOT NULL,
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
) INHERITS (pxp.tbase);
  
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
) INHERITS (pxp.tbase);

CREATE TABLE vef.tproceso_venta (
  id_proceso_venta SERIAL,
  estado VARCHAR(20) NOT NULL,
  fecha_desde DATE NOT NULL,
  fecha_hasta DATE NOT NULL,
  id_int_comprobante INTEGER,
  tipos VARCHAR[],
  CONSTRAINT pk_tproceso_venta PRIMARY KEY(id_proceso_venta)
) INHERITS (pxp.tbase);
  
/************************************F-SCP-JRR-VEF-0-22/03/2016*************************************************/

/************************************I-SCP-JRR-VEF-0-29/03/2016*************************************************/

ALTER TABLE vef.tsucursal_producto
  ADD COLUMN contabilizable VARCHAR(2) DEFAULT 'no' NOT NULL;
  
ALTER TABLE vef.tsucursal_producto
  ADD COLUMN excento VARCHAR(2) DEFAULT 'no' NOT NULL;
  
CREATE TABLE vef.tventa_boleto (
  id_venta_boleto SERIAL,
  id_venta INTEGER NOT NULL,
  id_boleto INTEGER,
  nro_boleto VARCHAR(20) NOT NULL,
  monto_moneda_susursal NUMERIC(18,2),  
  CONSTRAINT pk_tventa_boleto PRIMARY KEY(id_venta_boleto)
) INHERITS (pxp.tbase);
  
/************************************F-SCP-JRR-VEF-0-29/03/2016*************************************************/


/************************************I-SCP-RAC-VEF-0-16/04/2016*************************************************/

--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN id_moneda INTEGER;

COMMENT ON COLUMN vef.tventa.id_moneda
IS 'moneda de la venta';


--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN total_venta_msuc NUMERIC(18,2);

COMMENT ON COLUMN vef.tventa.total_venta_msuc
IS 'total venta en la moneda de la sucursal';



--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN transporte_fob NUMERIC(18,2) DEFAULT 0 NOT NULL;

COMMENT ON COLUMN vef.tventa.transporte_fob
IS 'transporte fob para exportacion';


--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN seguros_fob NUMERIC(18,2) DEFAULT 0 NOT NULL;

COMMENT ON COLUMN vef.tventa.seguros_fob
IS 'seguros fob para exportacion';


--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN otros_fob NUMERIC(18,2) DEFAULT 0 NOT NULL;

COMMENT ON COLUMN vef.tventa.otros_fob
IS 'otros fob para exportacion';


--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN transporte_cif NUMERIC(18,2) DEFAULT 0 NOT NULL;

COMMENT ON COLUMN vef.tventa.transporte_cif
IS 'trasporte cif para exportacion';


--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN seguros_cif NUMERIC(18,2) DEFAULT 0 NOT NULL;

COMMENT ON COLUMN vef.tventa.seguros_cif
IS 'seguros cif para exportacion';



--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN otros_cif NUMERIC(18,2) DEFAULT 0 NOT NULL;


--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN cantidad TYPE NUMERIC;

--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN tipo_cambio_venta NUMERIC DEFAULT 1 NOT NULL;

COMMENT ON COLUMN vef.tventa.tipo_cambio_venta
IS 'solo si la trasaccion se define en una moneda diferetne de la base';


-------------- SQL ---------------

ALTER TABLE vef.tcliente
  ADD COLUMN direccion VARCHAR DEFAULT '' NOT NULL;

COMMENT ON COLUMN vef.tcliente.direccion
IS 'direccion del cliente';

/************************************F-SCP-RAC-VEF-0-16/04/2016*************************************************/


/************************************I-SCP-RAC-VEF-0-22/04/2016*************************************************/


--------------- SQL ---------------

CREATE TABLE vef.ttipo_descripcion (
  id_tipo_descripcion SERIAL NOT NULL,
  codigo VARCHAR(100),
  nombre VARCHAR(300),
  obs VARCHAR,
  PRIMARY KEY(id_tipo_descripcion)
) INHERITS (pxp.tbase)

WITH (oids = false);

--------------- SQL ---------------

ALTER TABLE vef.ttipo_descripcion
  ADD COLUMN columna NUMERIC(10,2) DEFAULT 1 NOT NULL;

COMMENT ON COLUMN vef.ttipo_descripcion.columna
IS 'posicion en reporte';


--------------- SQL ---------------

ALTER TABLE vef.ttipo_descripcion
  ADD COLUMN fila NUMERIC(10,2) DEFAULT 1 NOT NULL;

COMMENT ON COLUMN vef.ttipo_descripcion.fila
IS 'numeros de fila';


--------------- SQL ---------------

ALTER TABLE vef.ttipo_descripcion
  ADD COLUMN id_sucursal INTEGER;
  
  
 --------------- SQL ---------------

CREATE TABLE vef.tvalor_descripcion (
  id_valor_descripcion SERIAL NOT NULL,
  id_venta INTEGER NOT NULL,
  id_tipo_descripcion INTEGER NOT NULL,
  valor VARCHAR(300) DEFAULT '' NOT NULL,
  obs VARCHAR,
  PRIMARY KEY(id_valor_descripcion)
) INHERITS (pxp.tbase)

WITH (oids = false); 


--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ADD COLUMN bruto NUMERIC(18,2) DEFAULT 0 NOT NULL;

COMMENT ON COLUMN vef.tventa_detalle.bruto
IS 'esto es para facturas de mineria';


--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ADD COLUMN ley NUMERIC(18,4) DEFAULT 0 NOT NULL;

COMMENT ON COLUMN vef.tventa_detalle.ley
IS 'atributo para venta de mineria';


--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ADD COLUMN kg_fino NUMERIC(18,4) DEFAULT 0 NOT NULL;

COMMENT ON COLUMN vef.tventa_detalle.kg_fino
IS 'atributo para mineria';

/************************************F-SCP-RAC-VEF-0-22/04/2016*************************************************/


/************************************I-SCP-RAC-VEF-0-29/04/2016*************************************************/



--------------- SQL ---------------

ALTER TABLE vef.tsucursal
  ADD COLUMN tipo_interfaz VARCHAR(100)[];

COMMENT ON COLUMN vef.tsucursal.tipo_interfaz
IS 'interfaces con las que puede trabajr una sucursal,  son los nombre de clase';

--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN kg_fino DROP DEFAULT;

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN kg_fino TYPE VARCHAR;

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN kg_fino SET DEFAULT 0;
  
  --------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN ley DROP DEFAULT;

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN ley TYPE VARCHAR;

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN ley SET DEFAULT 0;
  
--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN bruto DROP DEFAULT;

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN bruto TYPE VARCHAR;

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN bruto SET DEFAULT 0;  
  
--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ADD COLUMN id_unidad_medida INTEGER;
  
ALTER TABLE vef.ttipo_venta
  ADD COLUMN id_plantilla INTEGER;
  
ALTER TABLE vef.tsucursal
  ADD COLUMN id_depto INTEGER;


/************************************F-SCP-RAC-VEF-0-29/04/2016*************************************************/




/************************************I-SCP-RAC-VEF-0-12/05/2016*************************************************/

--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN precio TYPE NUMERIC(18,6);

--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ALTER COLUMN precio_sin_descuento TYPE NUMERIC(18,6);  
  
ALTER TABLE vef.tsucursal
  ADD COLUMN nombre_comprobante VARCHAR ;
  
COMMENT ON COLUMN vef.tsucursal.nombre_comprobante
IS 'El nombre de la sucursal tal como se mostrara en el comprobante de venta. Debe incluir el nombre de la empresa';

/************************************F-SCP-JRR-VEF-0-12/05/2016*************************************************/


CREATE INDEX tdosificacion_idx ON vef.tdosificacion
  USING btree (nroaut)
  WHERE estado_reg = 'activo';



/************************************I-SCP-RAC-VEF-0-16/05/2016*************************************************/


ALTER TABLE vef.tventa
  ADD COLUMN valor_bruto NUMERIC(18,2) DEFAULT 0 NOT NULL;

COMMENT ON COLUMN vef.tventa.valor_bruto
IS 'valor de los materiales';

--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN descripcion_bulto VARCHAR(1000) DEFAULT '' NOT NULL;

COMMENT ON COLUMN vef.tventa.descripcion_bulto
IS 'descripon de bultos en exportacion';


/************************************F-SCP-RAC-VEF-0-16/05/2016*************************************************/


/************************************I-SCP-RAC-VEF-0-06/06/2016*************************************************/


--------------- SQL ---------------

ALTER TABLE vef.tvalor_descripcion
  ADD COLUMN valor_label VARCHAR(300);

/************************************F-SCP-RAC-VEF-0-06/06/2016*************************************************/

/************************************I-SCP-JRR-VEF-0-16/06/2016*************************************************/
ALTER TABLE vef.tforma_pago
  ADD COLUMN registrar_tipo_tarjeta VARCHAR(2);

ALTER TABLE vef.tforma_pago
  ALTER COLUMN registrar_tipo_tarjeta SET DEFAULT 'no';

/************************************F-SCP-JRR-VEF-0-16/06/2016*************************************************/


/************************************I-SCP-JRR-VEF-0-07/07/2016*************************************************/

CREATE TABLE vef.tapertura_cierre_caja (
  id_apertura_cierre_caja SERIAL NOT NULL,
  id_sucursal INTEGER,
  id_punto_venta INTEGER,
  id_usuario_cajero INTEGER NOT NULL,
  id_moneda INTEGER NOT NULL,
  monto_inicial INTEGER DEFAULT 0 NOT NULL,
  monto_inicial_moneda_extranjera INTEGER DEFAULT 0 NOT NULL,
  obs_cierre TEXT,
  obs_apertura TEXT,
  estado VARCHAR(50) NOT NULL,
  fecha_hora_cierre TIMESTAMP,
  fecha_apertura_cierre DATE NOT NULL,
  arqueo_moneda_local NUMERIC(18,2),
  arqueo_moneda_extranjera NUMERIC(18,2) DEFAULT 0,
  PRIMARY KEY(id_apertura_cierre_caja)
) INHERITS (pxp.tbase);


ALTER TABLE vef.tventa
  ADD COLUMN id_usuario_cajero INTEGER;

/************************************F-SCP-JRR-VEF-0-07/07/2016*************************************************/

/************************************I-SCP-JRR-VEF-0-30/09/2016*************************************************/
ALTER TABLE vef.tapertura_cierre_caja
  ALTER COLUMN monto_inicial DROP DEFAULT;

ALTER TABLE vef.tapertura_cierre_caja
  ALTER COLUMN monto_inicial TYPE NUMERIC(18,2);

ALTER TABLE vef.tapertura_cierre_caja
  ALTER COLUMN monto_inicial SET DEFAULT 0;

ALTER TABLE vef.tapertura_cierre_caja
  ALTER COLUMN monto_inicial_moneda_extranjera DROP DEFAULT;

ALTER TABLE vef.tapertura_cierre_caja
  ALTER COLUMN monto_inicial_moneda_extranjera TYPE NUMERIC(18,2);

ALTER TABLE vef.tapertura_cierre_caja
  ALTER COLUMN monto_inicial_moneda_extranjera SET DEFAULT 0;


/************************************F-SCP-JRR-VEF-0-30/09/2016*************************************************/

/************************************I-SCP-RAC-VEF-0-18/06/2016*************************************************/


ALTER TABLE vef.tcliente
  ADD COLUMN observaciones VARCHAR(255);

/************************************F-SCP-RAC-VEF-0-18/06/2016*************************************************/

/************************************I-SCP-JRR-VEF-0-16/07/2016*************************************************/

ALTER TABLE vef.tventa
  ADD COLUMN hora_estimada_entrega TIME(0) WITHOUT TIME ZONE;
  
ALTER TABLE vef.tformula_detalle
  ALTER COLUMN cantidad TYPE NUMERIC(18,6);

/************************************F-SCP-JRR-VEF-0-16/07/2016*************************************************/

/************************************I-SCP-JRR-VEF-0-14/08/2016*************************************************/
ALTER TABLE vef.tmedico
  ADD COLUMN especialidad VARCHAR(200);
  
ALTER TABLE vef.tventa
  ADD COLUMN forma_pedido VARCHAR(200);

/************************************F-SCP-JRR-VEF-0-14/08/2016*************************************************/


/************************************I-SCP-JRR-VEF-0-27/10/2016*************************************************/

ALTER TABLE vef.tsucursal_usuario
ALTER COLUMN id_sucursal DROP NOT NULL;

ALTER TABLE vef.tventa
ADD COLUMN contabilizable VARCHAR(2) DEFAULT 'si' NOT NULL;

/************************************F-SCP-JRR-VEF-0-27/10/2016*************************************************/



/************************************I-SCP-JRR-VEF-0-14/09/2016*************************************************/

ALTER TABLE vef.tventa
  ADD COLUMN nombre_factura VARCHAR(100);

ALTER TABLE vef.tventa
  ADD COLUMN nit VARCHAR(25);
/************************************F-SCP-JRR-VEF-0-14/09/2016*************************************************/

/************************************I-SCP-JRR-VEF-0-18/09/2016*************************************************/

CREATE INDEX tdosificacion_idx ON vef.tdosificacion
  USING btree (nroaut)
  WHERE estado_reg = 'activo';
  
/************************************F-SCP-JRR-VEF-0-18/09/2016*************************************************/




/************************************I-SCP-RAC-VEF-0-28/10/2016*************************************************/

--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN id_cliente_destino INTEGER;

COMMENT ON COLUMN vef.tventa.id_cliente_destino
IS 'identifica el cliente destino';


/************************************F-SCP-RAC-VEF-0-28/10/2016*************************************************/


/************************************I-SCP-RAC-VEF-0-31/10/2016*************************************************/

--------------- SQL ---------------

ALTER TABLE vef.tcliente
  ADD COLUMN lugar VARCHAR(500);

/************************************F-SCP-RAC-VEF-0-31/10/2016*************************************************/



/************************************I-SCP-RAC-VEF-0-11/11/2016*************************************************/



--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ADD COLUMN estado VARCHAR(100) DEFAULT 'registrado' NOT NULL;

COMMENT ON COLUMN vef.tventa_detalle.estado
IS 'registrado, validado';

--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ADD COLUMN obs VARCHAR;
  

--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ADD COLUMN serie VARCHAR(400) DEFAULT '' NOT NULL;
  
  --------------- SQL ---------------

ALTER TABLE vef.tcliente
  ADD COLUMN codigo VARCHAR(20);
  

/************************************F-SCP-RAC-VEF-0-11/11/2016*************************************************/





/************************************I-SCP-RAC-VEF-0-27/10/2018*************************************************/

--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN id_proveedor INTEGER;

COMMENT ON COLUMN vef.tventa.id_proveedor
IS 'para empresa que emiten facturas grandes lso mismos provewedor son clientes, por ejemplo por tema de venta de pliegos, pra no redunda usaremos opcionalmente en un nuevo tipo de factura  el id_proveedor';



--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN id_contrato INTEGER;

COMMENT ON COLUMN vef.tventa.id_contrato
IS 'hace ferencia al contrato para bancarizacion';


--------------- SQL ---------------

ALTER TABLE vef.tventa
  ALTER COLUMN id_cliente DROP NOT NULL;
  
 
--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN codigo_aplicacion VARCHAR(2000);

COMMENT ON COLUMN vef.tventa.codigo_aplicacion
IS 'aplicacion para generar comprobantes';

--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN id_centro_costo INTEGER;

COMMENT ON COLUMN vef.tventa.id_centro_costo
IS 'centro de costo para contabilizar el ingreso de la factura';


--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN nit_internacional VARCHAR(2) DEFAULT 'no' NOT NULL;

COMMENT ON COLUMN vef.tventa.nit_internacional
IS 'cuadno el nit es internacional el codigo de control segenera con ceroy tambien para libro de ventas';


ALTER TABLE vef.tventa
  ADD COLUMN id_doc_compra_venta INTEGER;

COMMENT ON COLUMN vef.tventa.id_doc_compra_venta
IS 'hace referencia a la factura en libro de venta que fue migrada'; 


--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN id_venta_fk INTEGER;

COMMENT ON COLUMN vef.tventa.id_venta_fk
IS 'se utiliza para nostra de credito sobre venta donde es necesario hacer referencia a la factura sobre la que se recuepra el credito';



--------------- SQL ---------------

ALTER TABLE vef.tventa
  ADD COLUMN ncd VARCHAR(2) DEFAULT 'no' NOT NULL;

COMMENT ON COLUMN vef.tventa.ncd
IS 'si o no, es nota de credito debito';

--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ADD COLUMN id_venta_detalle_fk INTEGER;

COMMENT ON COLUMN vef.tventa_detalle.id_venta_detalle_fk
IS 'para notas de credito so bre ventas, hace referencia al detalle de la factura que vamos devolver';

--------------- SQL ---------------

ALTER TABLE vef.tventa_detalle
  ADD COLUMN id_doc_concepto INTEGER;

COMMENT ON COLUMN vef.tventa_detalle.id_doc_concepto
IS 'referencia el concepto en libro de ventas';

/************************************F-SCP-RAC-VEF-0-27/10/2018*************************************************/

/************************************I-SCP-EGS-VEF-0-08/11/2018*************************************************/
--------------- SQL ---------------
CREATE TABLE vef.ttemp_factura_excel (
  razon_social VARCHAR,
  nit VARCHAR,
  precio_total_usd NUMERIC,
  precio_total_bs NUMERIC,
  centro_costo VARCHAR,
  nro_factura VARCHAR,
  observaciones VARCHAR,
  fecha DATE,
  id_punto_venta INTEGER,
  tipo_factura VARCHAR,
  nro_contrato VARCHAR,
  id_funcionario_usu INTEGER,
  clase_costo VARCHAR,
  id_sucursal INTEGER,
  id_proveedor INTEGER,
  id_centro_costo INTEGER,
  id_contrato INTEGER,
  id_factura_excel SERIAL,
  venta_generada BOOLEAN DEFAULT false NOT NULL,
  forma_pago VARCHAR,
  aplicacion VARCHAR,
  id_forma_pago INTEGER,
  codigo_aplicacion VARCHAR,
  nro VARCHAR,
  ncd BOOLEAN DEFAULT false,
  id_venta_fk INTEGER,
  CONSTRAINT ttemp_factura_excel_pkey PRIMARY KEY(id_factura_excel)
) INHERITS (pxp.tbase)
WITH (oids = false);

COMMENT ON COLUMN vef.ttemp_factura_excel.id_venta_fk
IS 'solo si es una nota';
--------------- SQL ---------------
CREATE TABLE vef.ttemp_factura_detalle_excel (
  id_producto INTEGER,
  tipo VARCHAR,
  cantidad_det INTEGER,
  precio_uni_bs NUMERIC,
  precio_uni_usd NUMERIC,
  tipo_factura VARCHAR,
  detalle VARCHAR,
  observaciones VARCHAR,
  nro_factura VARCHAR,
  unidad VARCHAR,
  fecha DATE,
  id_factura_excel_det SERIAL,
  id_factura_excel_fk INTEGER,
  nro VARCHAR,
  id_venta_detalle_fk INTEGER,
  id_venta_fk INTEGER,
  codigo_ingas VARCHAR,
  CONSTRAINT ttemp_factura_detalle_excel_pkey PRIMARY KEY(id_factura_excel_det)
) INHERITS (pxp.tbase)
WITH (oids = false);
--------------- SQL ---------------
CREATE TABLE vef.ttemporal_data (
  id_temporal_data SERIAL,
  nro_factura VARCHAR,
  razon_social VARCHAR,
  total_venta NUMERIC,
  total_detalle NUMERIC,
  nro VARCHAR,
  error VARCHAR,
  CONSTRAINT ttemporal_excel_pkey PRIMARY KEY(id_temporal_data)
) INHERITS (pxp.tbase)
WITH (oids = false);

/************************************F-SCP-EGS-VEF-0-08/11/2018*************************************************/
/************************************I-SCP-EGS-VEF-1-10/01/2019*************************************************/
CREATE TABLE vef.tproveedor_cuenta_banco_cobro (
  id_proveedor_cuenta_banco_cobro SERIAL,
  id_proveedor INTEGER,
  tipo VARCHAR(20) NOT NULL,
  nro_cuenta_bancario TEXT,
  id_institucion INTEGER,
  fecha_alta DATE,
  fecha_baja DATE,
  id_moneda INTEGER,
  CONSTRAINT tproveedor_cuenta_banco_cobro_pkey PRIMARY KEY(id_proveedor_cuenta_banco_cobro)
) INHERITS (pxp.tbase)
WITH (oids = false);

ALTER TABLE vef.tproveedor_cuenta_banco_cobro
  ALTER COLUMN id_proveedor SET STATISTICS 0;

ALTER TABLE vef.tproveedor_cuenta_banco_cobro
  ALTER COLUMN tipo SET STATISTICS 0;
  
CREATE TABLE vef.tconcepto_carta_plt (
  id_concepto_carta_plt SERIAL,
  codigo_concepto_ingas VARCHAR(100),
  npc VARCHAR(20),
  id_concepto_ingas INTEGER,
  tipo VARCHAR(50),
  id_carta_plantilla INTEGER,
  CONSTRAINT tconcepto_carta_plt_pkey PRIMARY KEY(id_concepto_carta_plt)
) INHERITS (pxp.tbase)
WITH (oids = false);  

/************************************F-SCP-EGS-VEF-1-10/01/2019*************************************************/

/************************************I-SCP-EGS-VEF-2-29/01/2019*************************************************/
ALTER TABLE vef.tventa
  ALTER COLUMN correlativo_venta TYPE VARCHAR(40);
/************************************F-SCP-EGS-VEF-2-29/01/2019*************************************************/
/************************************I-SCP-EGS-VEF-3-20/02/2019*************************************************/
ALTER TABLE vef.ttemporal_data
  ADD COLUMN id_punto_venta INTEGER;
/************************************F-SCP-EGS-VEF-3-20/02/2019*************************************************/
/************************************I-SCP-EGS-VEF-4-12/08/2019*************************************************/
ALTER TABLE vef.tsucursal
  ALTER COLUMN telefono TYPE VARCHAR(150);
/************************************F-SCP-EGS-VEF-4-12/08/2019*************************************************/
/************************************I-SCP-EGS-VEF-6-25/10/2019*************************************************/
ALTER TABLE vef.ttemp_factura_detalle_excel
  ADD COLUMN descripcion VARCHAR;
/************************************F-SCP-EGS-VEF-6-25/10/2019*************************************************/
