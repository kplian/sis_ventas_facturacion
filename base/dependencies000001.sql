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



    
select pxp.f_insert_testructura_gui ('VEF', 'SISTEMA');

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
