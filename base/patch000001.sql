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
