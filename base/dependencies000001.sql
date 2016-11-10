/************************************I-DEP-JRR-VEF-0-02/05/2015*************************************************/

ALTER TABLE ONLY vef.tformula
    ADD CONSTRAINT fk_tformula__id_medico
    FOREIGN KEY (id_medico) REFERENCES vef.tmedico(id_medico);
    

ALTER TABLE ONLY vef.tformula_detalle
    ADD CONSTRAINT fk_tformula_detalle__id_item
    FOREIGN KEY (id_item) REFERENCES alm.titem(id_item);
    
ALTER TABLE ONLY vef.tformula_detalle
    ADD CONSTRAINT fk_tformula_detalle__id_formula
    FOREIGN KEY (id_formula) REFERENCES vef.tformula(id_formula);
    

ALTER TABLE ONLY vef.tsucursal_usuario
    ADD CONSTRAINT fk_tsucursal_usuario__id_sucursal
    FOREIGN KEY (id_sucursal) REFERENCES vef.tsucursal(id_sucursal);    
    
ALTER TABLE ONLY vef.tsucursal_usuario
    ADD CONSTRAINT fk_tsucursal_usuario__id_usuario
    FOREIGN KEY (id_usuario) REFERENCES segu.tusuario(id_usuario);
    
ALTER TABLE ONLY vef.tsucursal_almacen
    ADD CONSTRAINT fk_tsucursal_almacen__id_sucursal
    FOREIGN KEY (id_sucursal) REFERENCES vef.tsucursal(id_sucursal);    
    
ALTER TABLE ONLY vef.tsucursal_almacen
    ADD CONSTRAINT fk_tsucursal_almacen__id_almacen
    FOREIGN KEY (id_almacen) REFERENCES alm.talmacen(id_almacen);
    
ALTER TABLE ONLY vef.tsucursal_producto
    ADD CONSTRAINT fk_tsucursal_producto__id_sucursal
    FOREIGN KEY (id_sucursal) REFERENCES vef.tsucursal(id_sucursal);    
    
ALTER TABLE ONLY vef.tsucursal_producto
    ADD CONSTRAINT fk_tsucursal_producto__id_item
    FOREIGN KEY (id_item) REFERENCES alm.titem(id_item); 
    
ALTER TABLE ONLY vef.tformula
    ADD CONSTRAINT fk_tformula__id_tipo_presentacion
    FOREIGN KEY (id_tipo_presentacion) REFERENCES vef.ttipo_presentacion(id_tipo_presentacion); 
    
ALTER TABLE ONLY vef.tformula
    ADD CONSTRAINT fk_tformula__id_unidad_medida
    FOREIGN KEY (id_unidad_medida) REFERENCES param.tunidad_medida(id_unidad_medida);   



ALTER TABLE ONLY vef.tventa
    ADD CONSTRAINT fk_tventa__id_cliente
    FOREIGN KEY (id_cliente) REFERENCES vef.tcliente(id_cliente);
    
ALTER TABLE ONLY vef.tventa
    ADD CONSTRAINT fk_tventa__id_sucursal
    FOREIGN KEY (id_sucursal) REFERENCES vef.tsucursal(id_sucursal); 

ALTER TABLE ONLY vef.tventa
    ADD CONSTRAINT fk_tventa__id_proceso_wf
    FOREIGN KEY (id_proceso_wf) REFERENCES wf.tproceso_wf(id_proceso_wf); 

ALTER TABLE ONLY vef.tventa
    ADD CONSTRAINT fk_tventa__id_estado_wf
    FOREIGN KEY (id_estado_wf) REFERENCES wf.testado_wf(id_estado_wf);  
    


ALTER TABLE ONLY vef.tventa_detalle
    ADD CONSTRAINT fk_tventa_detalle__id_venta
    FOREIGN KEY (id_venta) REFERENCES vef.tventa(id_venta);
    
ALTER TABLE ONLY vef.tventa_detalle
    ADD CONSTRAINT fk_tventa_detalle__id_item
    FOREIGN KEY (id_item) REFERENCES alm.titem(id_item); 
    
ALTER TABLE ONLY vef.tventa_detalle
    ADD CONSTRAINT fk_tventa_detalle__id_sucursal_producto
    FOREIGN KEY (id_sucursal_producto) REFERENCES vef.tsucursal_producto(id_sucursal_producto); 
    
ALTER TABLE ONLY vef.tventa_detalle
    ADD CONSTRAINT fk_tventa_detalle__id_formula
    FOREIGN KEY (id_formula) REFERENCES vef.tformula(id_formula);    



    

CREATE OR REPLACE VIEW vef.vmedico(
    id_usuario_reg,
    id_usuario_mod,
    fecha_reg,
    fecha_mod,
    estado_reg,
    id_usuario_ai,
    usuario_ai,
    id_medico,
    nombres,
    primer_apellido,
    segundo_apellido,
    telefono_celular,
    telefono_fijo,
    otros_telefonos,
    correo,
    otros_correos,
    porcentaje,
    nombre_completo)
AS
  SELECT m.id_usuario_reg,
         m.id_usuario_mod,
         m.fecha_reg,
         m.fecha_mod,
         m.estado_reg,
         m.id_usuario_ai,
         m.usuario_ai,
         m.id_medico,
         m.nombres,
         m.primer_apellido,
         m.segundo_apellido,
         m.telefono_celular,
         m.telefono_fijo,
         m.otros_telefonos,
         m.correo,
         m.otros_correos,
         m.porcentaje,
         (((m.nombres::text || ' ' ::text) || m.primer_apellido::text) || ' '
          ::text) || COALESCE(m.segundo_apellido, '' ::character varying) ::text
           AS nombre_completo
  FROM vef.tmedico m;
  
  CREATE OR REPLACE VIEW vef.vcliente(
    id_usuario_reg,
    id_usuario_mod,
    fecha_reg,
    fecha_mod,
    estado_reg,
    id_usuario_ai,
    usuario_ai,
    id_cliente,
    nombres,
    primer_apellido,
    segundo_apellido,
    telefono_celular,
    telefono_fijo,
    otros_telefonos,
    correo,
    otros_correos,
    nombre_factura,
    nit,
    nombre_completo)
AS
  SELECT c.id_usuario_reg,
         c.id_usuario_mod,
         c.fecha_reg,
         c.fecha_mod,
         c.estado_reg,
         c.id_usuario_ai,
         c.usuario_ai,
         c.id_cliente,
         c.nombres,
         c.primer_apellido,
         c.segundo_apellido,
         c.telefono_celular,
         c.telefono_fijo,
         c.otros_telefonos,
         c.correo,
         c.otros_correos,
         c.nombre_factura,
         c.nit,
         (((c.nombres::text || ' ' ::text) || c.primer_apellido::text) || ' '
          ::text) || COALESCE(c.segundo_apellido, '' ::character varying) ::text
           AS nombre_completo
  FROM vef.tcliente c;

/************************************F-DEP-JRR-VEF-0-02/05/2015*************************************************/

/************************************I-DEP-JRR-VEF-0-20/09/2015*************************************************/
ALTER TABLE ONLY vef.tsucursal
    ADD CONSTRAINT fk_tsucursal__id_entidad
    FOREIGN KEY (id_entidad) REFERENCES param.tentidad(id_entidad);  

ALTER TABLE ONLY vef.tsucursal_moneda
    ADD CONSTRAINT fk_tsucursal_moneda__id_moneda
    FOREIGN KEY (id_moneda) REFERENCES param.tmoneda(id_moneda);  
    
ALTER TABLE ONLY vef.tsucursal_moneda
    ADD CONSTRAINT fk_tsucursal_moneda__id_sucursal
    FOREIGN KEY (id_sucursal) REFERENCES vef.tsucursal(id_sucursal); 

ALTER TABLE ONLY vef.tsucursal_producto
    ADD CONSTRAINT fk_tsucursal_producto__id_concepto_ingas
    FOREIGN KEY (id_concepto_ingas) REFERENCES param.tconcepto_ingas(id_concepto_ingas);  
    
ALTER TABLE ONLY param.tconcepto_ingas
    ADD CONSTRAINT fk_tconcepto_ingas__id_actividad_economica
    FOREIGN KEY (id_actividad_economica) REFERENCES vef.tactividad_economica(id_actividad_economica); 

ALTER TABLE ONLY vef.tdosificacion
    ADD CONSTRAINT fk_tdosificacion__id_sucursal
    FOREIGN KEY (id_sucursal) REFERENCES vef.tsucursal(id_sucursal);  
    
ALTER TABLE ONLY vef.tsucursal_usuario
    ADD CONSTRAINT fk_tsucursal_usuario__id_punto_venta
    FOREIGN KEY (id_punto_venta) REFERENCES vef.tpunto_venta(id_punto_venta); 

ALTER TABLE ONLY vef.tforma_pago
    ADD CONSTRAINT fk_tforma_pago__id_entidad
    FOREIGN KEY (id_entidad) REFERENCES param.tentidad(id_entidad); 
    
ALTER TABLE ONLY vef.tforma_pago
    ADD CONSTRAINT fk_tforma_pago__id_moneda
    FOREIGN KEY (id_moneda) REFERENCES param.tmoneda(id_moneda);  
    
ALTER TABLE ONLY vef.tventa
    ADD CONSTRAINT fk_tventa__id_punto_venta
    FOREIGN KEY (id_punto_venta) REFERENCES vef.tpunto_venta(id_punto_venta); 

ALTER TABLE ONLY vef.tventa_forma_pago
    ADD CONSTRAINT fk_tventa_forma_pago__id_forma_pago
    FOREIGN KEY (id_forma_pago) REFERENCES vef.tforma_pago(id_forma_pago); 
    
ALTER TABLE ONLY vef.tventa_forma_pago
    ADD CONSTRAINT fk_tventa_forma_pago__id_venta
    FOREIGN KEY (id_venta) REFERENCES vef.tventa(id_venta); 

CREATE OR REPLACE VIEW vef.vcliente(
    id_usuario_reg,
    id_usuario_mod,
    fecha_reg,
    fecha_mod,
    estado_reg,
    id_usuario_ai,
    usuario_ai,
    id_cliente,
    nombres,
    primer_apellido,
    segundo_apellido,
    telefono_celular,
    telefono_fijo,
    otros_telefonos,
    correo,
    otros_correos,
    nombre_factura,
    nit,
    nombre_completo)
AS
  SELECT c.id_usuario_reg,
         c.id_usuario_mod,
         c.fecha_reg,
         c.fecha_mod,
         c.estado_reg,
         c.id_usuario_ai,
         c.usuario_ai,
         c.id_cliente,
         c.nombres,
         c.primer_apellido,
         c.segundo_apellido,
         c.telefono_celular,
         c.telefono_fijo,
         c.otros_telefonos,
         c.correo,
         c.otros_correos,
         c.nombre_factura,
         c.nit,
         (((c.nombres::text || ' ' ::text) || c.primer_apellido::text) || ' '
          ::text) || COALESCE(c.segundo_apellido, '' ::character varying) ::text
           AS nombre_completo
  FROM vef.tcliente c;
/************************************F-DEP-JRR-VEF-0-20/09/2015*************************************************/


/************************************I-DEP-JRR-VEF-0-08/11/2015*************************************************/

ALTER TABLE ONLY vef.tventa_detalle
    ADD CONSTRAINT fk_tventa_detalle__id_vendedor
    FOREIGN KEY (id_vendedor) REFERENCES segu.tusuario(id_usuario); 
    
ALTER TABLE ONLY vef.tventa_detalle
    ADD CONSTRAINT fk_tventa_detalle__id_medico
    FOREIGN KEY (id_medico) REFERENCES vef.tmedico;
    
ALTER TABLE ONLY vef.tformula_detalle
    ADD CONSTRAINT fk_tformula_detalle__id_concepto_ingas
    FOREIGN KEY (id_concepto_ingas) REFERENCES param.tconcepto_ingas;
    
 ALTER TABLE ONLY vef.tsucursal
    ADD CONSTRAINT fk_tsucursal__id_lugar
    FOREIGN KEY (id_lugar) REFERENCES param.tlugar;
  

/************************************F-DEP-JRR-VEF-0-08/11/2015*************************************************/

/************************************I-DEP-JRR-VEF-0-25/11/2015*************************************************/

ALTER TABLE ONLY vef.tboleto
    ADD CONSTRAINT fk_tboleto__id_punto_venta
    FOREIGN KEY (id_punto_venta) REFERENCES vef.tpunto_venta(id_punto_venta); 
    
ALTER TABLE ONLY vef.tboleto_fp
    ADD CONSTRAINT fk_tboleto_fp__id_boleto
    FOREIGN KEY (id_boleto) REFERENCES vef.tboleto(id_boleto);
    
ALTER TABLE ONLY vef.tboleto_fp
    ADD CONSTRAINT fk_tboleto_fp__id_forma_pago
    FOREIGN KEY (id_forma_pago) REFERENCES vef.tforma_pago(id_forma_pago);  

/************************************F-DEP-JRR-VEF-0-25/11/2015*************************************************/


/************************************I-DEP-JRR-VEF-0-19/02/2016*************************************************/

ALTER TABLE ONLY vef.tventa
    ADD CONSTRAINT fk_tventa__id_dosificacion
    FOREIGN KEY (id_dosificacion) REFERENCES vef.tdosificacion(id_dosificacion);
    
ALTER TABLE ONLY vef.tpunto_venta_producto
    ADD CONSTRAINT fk_tpunto_venta_producto__id_punto_venta
    FOREIGN KEY (id_punto_venta) REFERENCES vef.tpunto_venta(id_punto_venta);
    
ALTER TABLE ONLY vef.tpunto_venta_producto
    ADD CONSTRAINT fk_tpunto_venta_producto__id_sucursal_producto
    FOREIGN KEY (id_sucursal_producto) REFERENCES vef.tsucursal_producto(id_sucursal_producto);

/************************************F-DEP-JRR-VEF-0-19/02/2016*************************************************/


/************************************I-DEP-JRR-VEF-0-14/03/2016*************************************************/

ALTER TABLE ONLY vef.tsucursal_producto
    ADD CONSTRAINT fk_tsucursal_producto__id_moneda
    FOREIGN KEY (id_moneda) REFERENCES param.tmoneda(id_moneda);
  
/************************************F-DEP-JRR-VEF-0-14/03/2016*************************************************/


/************************************I-DEP-JRR-VEF-0-02/05/2016*************************************************/

ALTER TABLE ONLY vef.ttipo_venta
    ADD CONSTRAINT fk_ttipo_venta__id_plantilla
    FOREIGN KEY (id_plantilla) REFERENCES param.tplantilla(id_plantilla);

ALTER TABLE ONLY vef.tsucursal
    ADD CONSTRAINT fk_tsucursal__id_depto
    FOREIGN KEY (id_depto) REFERENCES param.tdepto(id_depto);
/************************************F-DEP-JRR-VEF-0-02/05/2016*************************************************/

/************************************I-DEP-JRR-VEF-0-08/05/2016*************************************************/

ALTER TABLE ONLY vef.tvalor_descripcion
    ADD CONSTRAINT fk_tvalor_descripcion__id_venta
    FOREIGN KEY (id_venta) REFERENCES vef.tventa(id_venta);

ALTER TABLE ONLY vef.tvalor_descripcion
    ADD CONSTRAINT fk_tvalor_descripcion__id_tipo_descripcion
    FOREIGN KEY (id_tipo_descripcion) REFERENCES vef.ttipo_descripcion(id_tipo_descripcion);
/************************************F-DEP-JRR-VEF-0-08/05/2016*************************************************/


/************************************I-DEP-JRR-VEF-0-07/07/2016*************************************************/


ALTER TABLE ONLY vef.tapertura_cierre_caja
    ADD CONSTRAINT fk_tapertura_cierre_caja__id_sucursal
    FOREIGN KEY (id_sucursal) REFERENCES vef.tsucursal(id_sucursal);
    
ALTER TABLE ONLY vef.tapertura_cierre_caja
    ADD CONSTRAINT fk_tapertura_cierre_caja__id_punto_venta
    FOREIGN KEY (id_punto_venta) REFERENCES vef.tpunto_venta(id_punto_venta);
    
ALTER TABLE ONLY vef.tapertura_cierre_caja
    ADD CONSTRAINT fk_tapertura_cierre_caja__id_usuario_cajero
    FOREIGN KEY (id_usuario_cajero) REFERENCES segu.tusuario(id_usuario);
    
ALTER TABLE ONLY vef.tapertura_cierre_caja
    ADD CONSTRAINT fk_tapertura_cierre_caja__id_moneda
    FOREIGN KEY (id_moneda) REFERENCES param.tmoneda(id_moneda);

ALTER TABLE ONLY vef.tventa
    ADD CONSTRAINT fk_tventa__id_usuario_cajero
    FOREIGN KEY (id_usuario_cajero) REFERENCES segu.tusuario(id_usuario);

/************************************F-DEP-JRR-VEF-0-07/07/2016*************************************************/

/************************************I-DEP-JRR-VEF-0-18/09/2016*************************************************/

CREATE TRIGGER trig_tdosificacion
  BEFORE INSERT OR UPDATE 
  ON vef.tdosificacion FOR EACH ROW 
  EXECUTE PROCEDURE f_trig_tdosificacion();

/************************************F-DEP-JRR-VEF-0-18/09/2016*************************************************/

/************************************I-DEP-JRR-VEF-0-19/09/2016*************************************************/
DROP TRIGGER trig_tdosificacion ON vef.tdosificacion;

CREATE TRIGGER trig_tdosificacion
  BEFORE INSERT OR UPDATE
  ON vef.tdosificacion FOR EACH ROW
  EXECUTE PROCEDURE vef.f_trig_tdosificacion();

/************************************F-DEP-JRR-VEF-0-19/09/2016*************************************************/

