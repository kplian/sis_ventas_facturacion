/***********************************I-DAT-JRR-VEF-0-02/05/2015*****************************************/

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'VEF', E'Sistema de Ventas', E'2015-04-20', E'VF', E'activo', E'ventas_facturacion', NULL);

select pxp.f_insert_tgui ('SISTEMA DE VENTAS', '', 'VEF', 'si', 1, '', 1, '', '', 'VEF');


INSERT INTO param.tdocumento ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai",  "id_subsistema", "codigo", "descripcion", "periodo_gestion", "tipo", "tipo_numeracion", "formato", "ruta_plantilla")
VALUES (1, NULL, E'2015-12-03 00:00:00', E'2015-12-03 09:38:53', E'activo', NULL, NULL,  (select id_subsistema from segu.tsubsistema s where s.codigo like 'VEF' and s.estado_reg = 'activo'), E'VEN', E'VEN', E'periodo', E'', E'tabla', E'codtabla-correlativo-periodo/gestion', NULL);


/***********************************F-DAT-JRR-VEF-0-02/05/2015*****************************************/

/***********************************I-DAT-JRR-VEF-0-05/07/2015*****************************************/
/*
select pxp.f_insert_tgui ('SISTEMA DE VENTAS', '', 'VEF', 'si', 1, '', 1, '', '', 'VEF');
select pxp.f_insert_tgui ('Sucursal', 'Sucursal', 'SUCUR', 'si', 1, 'sis_ventas_facturacion/vista/sucursal/Sucursal.php', 2, '', 'Sucursal', 'VEF');
select pxp.f_insert_tgui ('Ventas Farmacia', 'Ventas Farmacia', 'VFVENTA', 'si', 0, 'sis_ventas_facturacion/vista/venta_farmacia/VentaVendedorFarmacia.php', 2, '', 'VentaVendedorFarmacia', 'VEF');
select pxp.f_insert_tgui ('Revisión de Venta', 'Revisión de Venta', 'REVVEN', 'si', 0, 'sis_ventas_facturacion/vista/venta/VentaRevision.php', 2, '', 'VentaRevision', 'VEF');
select pxp.f_insert_tgui ('Elaboración de Formulas', 'Elaboración de Formulas', 'VENELABO', 'si', 0, 'sis_ventas_facturacion/vista/formula/Formula.php', 2, '', 'Formula', 'VEF');
select pxp.f_insert_tgui ('Actividad Economica', 'Actividad Economica', 'ACTECO', 'si', 1, 'sis_ventas_facturacion/vista/actividad_economica/ActividadEconomica.php', 2, '', 'ActividadEconomica', 'VEF');
select pxp.f_insert_tgui ('Entidad Forma de Pago', 'Entidad Forma de Pago', 'ENFORPA', 'si', 1, 'sis_ventas_facturacion/vista/forma_pago/EntidadFormaPago.php', 2, '', 'EntidadFormaPago', 'VEF');
select pxp.f_insert_tgui ('Cliente', 'Cliente', 'VEFCLI', 'si', 1, 'sis_ventas_facturacion/vista/cliente/Cliente.php', 2, '', 'Cliente', 'VEF');
select pxp.f_insert_tgui ('Ventas con Recibo', 'Ventas', 'VENFACVE', 'si', 0, 'sis_ventas_facturacion/vista/venta/VentaVendedor.php', 2, '', 'VentaVendedor', 'VEF');
select pxp.f_insert_tgui ('Registro de Boletos', 'Registro de Boletos', 'REGBOL', 'si', 7, 'sis_ventas_facturacion/vista/boleto/Boleto.php', 2, '', 'Boleto', 'VEF');
select pxp.f_insert_tgui ('Reportes', 'Reportes', 'VEFREP', 'si', 8, '', 2, '', '', 'VEF');
select pxp.f_insert_tgui ('Resumen de Ventas', 'Resumen de Ventas', 'VEFREM', 'si', 1, 'sis_ventas_facturacion/vista/reporte_resumen_ventas/ReporteResumenVentas.php', 3, '', 'ReporteResumenVentas', 'VEF');
select pxp.f_insert_tgui ('Formula', 'Formula', 'FORM', 'si', 6, 'sis_ventas_facturacion/vista/formula/Formula.php', 2, '', 'Formula', 'VEF');
select pxp.f_insert_tgui ('Ventas con Factura Manual', 'Factura Manual', 'FACMAN', 'si', 0, 'sis_ventas_facturacion/vista/venta/VentaVendedorManual.php', 2, '', 'VentaVendedorManual', 'VEF');
select pxp.f_insert_tgui ('Ventas con Factura Computarizada', 'Ventas con Factura Computarizada', 'VENFACOM', 'si', 0, 'sis_ventas_facturacion/vista/venta/VentaVendedorComputarizada.php', 2, '', 'VentaVendedorComputarizada', 'VEF');
select pxp.f_insert_tgui ('Tipo de Venta', 'Tipo de Venta', 'TIPVEN', 'si', 1, 'sis_ventas_facturacion/vista/tipo_venta/TipoVenta.php', 2, '', 'TipoVenta', 'VEF');
select pxp.f_insert_tgui ('Proceso de Contabilización', 'Proceso de Contabilización', 'CONVEF', 'si', 7, 'sis_ventas_facturacion/vista/proceso_venta/ProcesoVenta.php', 2, '', 'ProcesoVenta', 'VEF');
select pxp.f_insert_tgui ('Ventas', 'Ventas', 'VENCARP', 'si', 3, '', 2, '', '', 'VEF');


select pxp.f_insert_testructura_gui ('SUCUR', 'VEF');
select pxp.f_insert_testructura_gui ('ACTECO', 'VEF');
select pxp.f_insert_testructura_gui ('ENFORPA', 'VEF');
select pxp.f_insert_testructura_gui ('VEFCLI', 'VEF');
select pxp.f_insert_testructura_gui ('VEF', 'SISTEMA');
select pxp.f_insert_testructura_gui ('REGBOL', 'VEF');
select pxp.f_insert_testructura_gui ('VEFREP', 'VEF');
select pxp.f_insert_testructura_gui ('VEFREM', 'VEFREP');
select pxp.f_insert_testructura_gui ('FORM', 'VEF');
select pxp.f_insert_testructura_gui ('TIPVEN', 'VEF');
select pxp.f_insert_testructura_gui ('CONVEF', 'VEF');
select pxp.f_insert_testructura_gui ('VENCARP', 'VEF');
select pxp.f_insert_testructura_gui ('VENFACVE', 'VENCARP');
select pxp.f_insert_testructura_gui ('VENFACOM', 'VENCARP');
select pxp.f_insert_testructura_gui ('FACMAN', 'VENCARP');
select pxp.f_insert_testructura_gui ('REVVEN', 'VENCARP');
select pxp.f_insert_testructura_gui ('VENELABO', 'VENCARP');
select pxp.f_insert_testructura_gui ('VFVENTA', 'VENCARP');*/

/***********************************F-DAT-JRR-VEF-0-05/07/2015*****************************************/


/***********************************I-DAT-JRR-VEF-0-06/10/2015*****************************************/
INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES ( E'vef_estados_validar_fp', E'borrador', E'variable global para definir los estados en los q se valida la forma de pago');

INSERT INTO pxp.variable_global ( "variable", "valor", "descripcion")
VALUES ( E'vef_integracion_almacenes', E'false', E'variable global para definir si el sistema de ventas se integrara con el de almacenes para obtener listadod e items');

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES ( E'vef_tiene_punto_venta', E'true', E'variable global para definir si las ventas se manejaran a nivel sucursal o a nivel punto de venta');

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'vef_tipo_venta_habilitado', E'servicio', E'variable global para definir que tipos de venta estaran habilitados');

/***********************************F-DAT-JRR-VEF-0-06/10/2015*****************************************/


/***********************************I-DAT-JRR-VEF-0-28/03/2016*****************************************/

INSERT INTO vef.ttipo_venta ("id_usuario_reg",  "codigo", "nombre", "codigo_relacion_contable", "tipo_base")
VALUES (1,E'computarizada', E'Computarizada', E'VENTA', E'computarizada');

INSERT INTO vef.ttipo_venta ("id_usuario_reg", "codigo", "nombre", "codigo_relacion_contable", "tipo_base")
VALUES (1,E'manual', E'Manual', E'VENTA', E'manual');

INSERT INTO vef.ttipo_venta ("id_usuario_reg", "codigo", "nombre", "codigo_relacion_contable", "tipo_base")
VALUES (1, E'recibo', E'Recibo', E'VENTA', E'recibo');


/***********************************F-DAT-JRR-VEF-0-28/03/2016*****************************************/

/***********************************I-DAT-JRR-VEF-0-30/04/2016*****************************************/

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'vef_integracion_lcv', E'si', E'Si el sistema de ventas se integra con el libro de compras y ventas de contabilidad');


/***********************************F-DAT-JRR-VEF-0-30/04/2016*****************************************/


/***********************************I-DAT-RAC-VEF-0-05/05/2016*****************************************/


----------------------------------
--COPY LINES TO data.sql FILE  
---------------------------------

select pxp.f_insert_tgui ('Venta Computarizada Exportación', 'Factura de Exportación', 'VEFACEX', 'si', 5, 'sis_ventas_facturacion/vista/venta/VentaVendedorExportacion.php', 3, '', 'VentaVendedorExportacion', 'VEF');
select pxp.f_insert_tgui ('Exportación Minera', 'Factura de exportación para mineria', 'EXPOMIN', 'si', 7, 'sis_ventas_facturacion/vista/venta/VentaVendedorExportacionMin.php', 3, '', 'VentaVendedorExportacionMin', 'VEF');
select pxp.f_insert_tgui ('Computarizada minera', 'Computarizada minera', 'COMMIN', 'si', 8, 'sis_ventas_facturacion/vista/venta/VentaVendedorMin.php', 3, '', 'VentaVendedorMin', 'VEF');

 
/***********************************F-DAT-RAC-VEF-0-05/05/2016*****************************************/

/***********************************I-DAT-JRR-VEF-0-13/07/2016*****************************************/

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'vef_integracion_obingresos', E'false', E'Si el sistema de ventas se integra con el sistema de ingresos boa');

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'vef_tiene_apertura_cierre', E'no', E'Si el sistema de ventas controlara la apertura y cierre de cajas');

/***********************************F-DAT-JRR-VEF-0-13/07/2016*****************************************/

/***********************************I-DAT-JRR-VEF-0-27/10/2016*****************************************/


select wf.f_import_tproceso_macro ('insert','VEN', 'VEF', 'Sistema de Ventas','si');
select wf.f_import_tcategoria_documento ('insert','legales', 'Legales');
select wf.f_import_tcategoria_documento ('insert','proceso', 'Proceso');
select wf.f_import_ttipo_proceso ('insert','VEN',NULL,NULL,'VEN','Sistema de Ventas','','','si','','obligatorio','','VEN',NULL);
select wf.f_import_ttipo_estado ('insert','borrador','VEN','Borrador','si','no','no','anterior','','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','',NULL);
select wf.f_import_ttipo_estado ('insert','finalizado','VEN','finalizado','no','no','si','listado','','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','','borrador');
select wf.f_import_ttipo_estado ('insert','anulado','VEN','anulado','no','no','si','anterior','','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','',NULL);
select wf.f_import_ttipo_estado ('insert','caja','VEN','caja','no','no','no','funcion_listado','vef.f_lista_funcionario_cajero','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','','borrador');



/***********************************F-DAT-JRR-VEF-0-27/10/2016*****************************************/

/***********************************I-DAT-RAC-VEF-0-11/11/2016*****************************************/

----------------------------------
--COPY LINES TO SUBSYSTEM data.sql FILE  
---------------------------------

select param.f_import_tcatalogo_tipo ('insert','estado_detalle','VEF','tventa_detalle');
select param.f_import_tcatalogo ('insert','VEF','registrado','registrado','estado_detalle');
select param.f_import_tcatalogo ('insert','VEF','comprado','comprado','estado_detalle');
select param.f_import_tcatalogo ('insert','VEF','finalizado','finalizado','estado_detalle');


INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES 
  (E'vef_codigo_cliente', E'false', E'habilita la generacion de codigo automatico al insertar clientes');

/***********************************F-DAT-RAC-VEF-0-11/11/2016*****************************************/

/***********************************I-DAT-JRR-VEF-0-17/03/2017*****************************************/

select pxp.f_insert_tgui ('Ventas en Caja', 'Ventas en Caja', 'VEFCAJE', 'si', 8, 'sis_ventas_facturacion/vista/venta/VentaCaja.php', 3, '', 'VentaCaja', 'VEF');
select pxp.f_insert_tgui ('Apertura de Caja', 'Apertura de Caja', 'VEFAPCIECAJ', 'si', 1, 'sis_ventas_facturacion/vista/apertura_cierre_caja/AperturaCierreCaja.php', 3, '', 'AperturaCierreCaja', 'VEF');


/***********************************F-DAT-JRR-VEF-0-17/03/2017*****************************************/



/***********************************I-DAT-RAC-VEF-0-08/10/2018*****************************************/

----------------------------------
--COPY LINES TO SUBSYSTEM data.sql FILE  
---------------------------------

select param.f_import_tcatalogo_tipo ('insert','tipo_credito_sobre_venta','CONTA','tdoc_compra_venta');
select param.f_import_tcatalogo ('insert','CONTA','Devolución Peaje Empresas de Grupo','dev_peaje_grupo','tipo_credito_sobre_venta');
select param.f_import_tcatalogo ('insert','CONTA','Devolución Peaje  Otros','dev_peaje_otros','tipo_credito_sobre_venta');


/* Data for the 'pxp.variable_global' table  (Records 1 - 1) */

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES 
  (E'vef_porcentaje_permitodo_ncd', E'0.5', E'porcentaje permitido para  notas de credito por defecto solo el 50% de peude devolver');



/***********************************F-DAT-RAC-VEF-0-08/10/2018*****************************************/


/***********************************I-DAT-EGS-VEF-0-25/10/2018*****************************************/


select pxp.f_insert_tgui ('Notas de Crédito ETR', 'Nos ta Credito Sobre Ventas', 'NCETR', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaVendedorNCETR.php', 3, '', 'VentaVendedorNCETR', 'VEF');
select pxp.f_insert_tgui ('Ventas ETR', 'Ventas ETR', 'VENETR', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaVendedorETR.php', 3, '', 'VentaVendedorETR', 'VEF');
select pxp.f_insert_tgui ('Ventas Peaje ETR', 'Ventas Peaje ETR', 'VEPEETR', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaVendedorPeajeETR.php', 3, '', 'VentaVendedorPeajeETR', 'VEF');





/***********************************F-DAT-EGS-VEF-0-25/10/2018*****************************************/



/***********************************I-DAT-RAC-VEF-0-05/11/2018*****************************************/
select pxp.f_insert_tgui ('Pendiente Emisión ETR', 'Pendientes Emisión ETR', 'PENETR', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaEmisor.php', 3, '', 'VentaEmisor', 'VEF');
/***********************************F-DAT-RAC-VEF-0-05/11/2018*****************************************/

/***********************************I-DAT-EGS-VEF-1-21/11/2018*****************************************/

select param.f_import_tplantilla_archivo_excel ('insert','SUBFACTURA','SubirFactura','activo',NULL,'2',NULL,'','csv',',');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA1','SUBFACTURA','si','',NULL,'17','aplicacion','aplicacion','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA2','SUBFACTURA','si','',NULL,'6','codigo conIng','codigo','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA3','SUBFACTURA','si','',NULL,'4','cantidad','cantidad','entero','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA4','SUBFACTURA','si','',NULL,'5','unidad','unidad','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA5','SUBFACTURA','no','',NULL,'7','precio uni usd','precio_uni_usd','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA6','SUBFACTURA','no','',NULL,'9','precio total usd','precio_total_usd','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA7','SUBFACTURA','si','',NULL,'1','nro','nro','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA8','SUBFACTURA','si','',NULL,'13','observaciones','observaciones','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA9','SUBFACTURA','si','dd/mm/yyyy',NULL,'14','fecha','fecha','date','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA10','SUBFACTURA','si','',NULL,'15','numero contrato','nro_contrato','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA11','SUBFACTURA','si','',NULL,'16','forma de pago','forma_pago','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA12','SUBFACTURA','si','',NULL,'8','precio_uni_bs','precio_uni_bs','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA13','SUBFACTURA','si','',NULL,'2','razon social','razon_social','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA14','SUBFACTURA','si','',NULL,'3','nit','nit','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA15','SUBFACTURA','si','',NULL,'10','precio total bs','precio_total_bs','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA16','SUBFACTURA','si','',NULL,'11','centro de costo','centro_costo','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA17','SUBFACTURA','si','',NULL,'12','clase de costo','clase_costo','string','','activo');
/***********************************F-DAT-EGS-VEF-1-21/11/2018*****************************************/


/***********************************I-DAT-EGS-VEF-2-10/12/2018*****************************************/
select param.f_import_tplantilla_archivo_excel ('insert','SUBNOTA','SubirNota','activo',NULL,'2',NULL,'','csv',',');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA18','SUBNOTA','si','',NULL,'16','nro_factura','nro_factura','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA19','SUBNOTA','si','',NULL,'18','codigo_factura','codigo_factura','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA20','SUBNOTA','si','',NULL,'19','precio_uni_bs_fac','precio_uni_bs_fac','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA21','SUBNOTA','si','',NULL,'17','nro_autori_fac','nro_autori_fac','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA2','SUBNOTA','si','',NULL,'6','codigo conIng','codigo','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA3','SUBNOTA','si','',NULL,'4','cantidad','cantidad','entero','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA4','SUBNOTA','si','',NULL,'5','unidad','unidad','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA5','SUBNOTA','no','',NULL,'7','precio uni usd','precio_uni_usd','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA6','SUBNOTA','no','',NULL,'9','precio total usd','precio_total_usd','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA7','SUBNOTA','si','',NULL,'1','nro','nro','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA8','SUBNOTA','si','',NULL,'13','observaciones','observaciones','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA12','SUBNOTA','si','',NULL,'8','precio_uni_bs','precio_uni_bs','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA13','SUBNOTA','si','',NULL,'2','razon social','razon_social','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA14','SUBNOTA','si','',NULL,'3','nit','nit','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA15','SUBNOTA','si','',NULL,'10','precio total bs','precio_total_bs','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA16','SUBNOTA','si','',NULL,'11','centro de costo','centro_costo','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA17','SUBNOTA','si','',NULL,'12','clase de costo','clase_costo','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA11','SUBNOTA','si','',NULL,'14','forma de pago','forma_pago','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBNOTA1','SUBNOTA','si','',NULL,'15','aplicacion','aplicacion','string','','activo');
/***********************************F-DAT-EGS-VEF-2-10/12/2018*****************************************/

/***********************************I-DAT-EGS-VEF-3-13/12/2018*****************************************/

select pxp.f_insert_tgui ('<i class="fa fa-openid fa-2x"></i> SISTEMA DE  FACTURACIÓN', '', 'VEF', 'si', 1, '', 1, '', '', 'VEF');
select pxp.f_insert_tgui ('Modulo de ventas', 'Sucursal', 'SUCUR', 'si', 1, 'sis_ventas_facturacion/vista/sucursal/Sucursal.php', 2, '', 'Sucursal', 'VEF');
select pxp.f_insert_tgui ('Ventas Farmacia', 'Ventas Farmacia', 'VFVENTA', 'si', 0, 'sis_ventas_facturacion/vista/venta_farmacia/VentaVendedorFarmacia.php', 2, '', 'VentaVendedorFarmacia', 'VEF');
select pxp.f_insert_tgui ('Revisión de Venta', 'Revisión de Venta', 'REVVEN', 'si', 0, 'sis_ventas_facturacion/vista/venta/VentaRevision.php', 2, '', 'VentaRevision', 'VEF');
select pxp.f_insert_tgui ('Elaboración de Formulas', 'Elaboración de Formulas', 'VENELABO', 'si', 0, 'sis_ventas_facturacion/vista/formula/Formula.php', 2, '', 'Formula', 'VEF');
select pxp.f_insert_tgui ('Actividad Economica', 'Actividad Economica', 'ACTECO', 'si', 1, 'sis_ventas_facturacion/vista/actividad_economica/ActividadEconomica.php', 2, '', 'ActividadEconomica', 'VEF');
select pxp.f_insert_tgui ('Entidad Forma de Pago', 'Entidad Forma de Pago', 'ENFORPA', 'si', 1, 'sis_ventas_facturacion/vista/forma_pago/EntidadFormaPago.php', 2, '', 'EntidadFormaPago', 'VEF');
select pxp.f_insert_tgui ('Cliente', 'Cliente', 'VEFCLI', 'si', 1, 'sis_ventas_facturacion/vista/cliente/Cliente.php', 2, '', 'Cliente', 'VEF');
select pxp.f_insert_tgui ('Ventas con Recibo', 'Ventas', 'VENFACVE', 'si', 0, 'sis_ventas_facturacion/vista/venta/VentaVendedor.php', 2, '', 'VentaVendedor', 'VEF');
select pxp.f_insert_tgui ('Registro de Boletos', 'Registro de Boletos', 'REGBOL', 'si', 7, 'sis_ventas_facturacion/vista/boleto/Boleto.php', 2, '', 'Boleto', 'VEF');
select pxp.f_insert_tgui ('Reportes', 'Reportes', 'VEFREP', 'si', 8, '', 2, '', '', 'VEF');
select pxp.f_insert_tgui ('Resumen de Ventas', 'Resumen de Ventas', 'VEFREM', 'si', 1, 'sis_ventas_facturacion/vista/reporte_resumen_ventas/ReporteResumenVentas.php', 3, '', 'ReporteResumenVentas', 'VEF');
select pxp.f_insert_tgui ('Formula', 'Formula', 'FORM', 'si', 6, 'sis_ventas_facturacion/vista/formula/Formula.php', 2, '', 'Formula', 'VEF');
select pxp.f_insert_tgui ('Ventas con Factura Manual', 'Factura Manual', 'FACMAN', 'si', 0, 'sis_ventas_facturacion/vista/venta/VentaVendedorManual.php', 2, '', 'VentaVendedorManual', 'VEF');
select pxp.f_insert_tgui ('Ventas con Factura Computarizada', 'Ventas con Factura Computarizada', 'VENFACOM', 'si', 0, 'sis_ventas_facturacion/vista/venta/VentaVendedorComputarizada.php', 2, '', 'VentaVendedorComputarizada', 'VEF');
select pxp.f_insert_tgui ('Tipo de Venta', 'Tipo de Venta', 'TIPVEN', 'si', 1, 'sis_ventas_facturacion/vista/tipo_venta/TipoVenta.php', 2, '', 'TipoVenta', 'VEF');
select pxp.f_insert_tgui ('Proceso de Contabilización', 'Proceso de Contabilización', 'CONVEF', 'si', 7, 'sis_ventas_facturacion/vista/proceso_venta/ProcesoVenta.php', 2, '', 'ProcesoVenta', 'VEF');
select pxp.f_insert_tgui ('Ventas', 'Ventas', 'VENCARP', 'si', 3, '', 2, '', '', 'VEF');
select pxp.f_insert_tgui ('Venta Computarizada Exportación', 'Factura de Exportación', 'VEFACEX', 'si', 5, 'sis_ventas_facturacion/vista/venta/VentaVendedorExportacion.php', 3, '', 'VentaVendedorExportacion', 'VEF');
select pxp.f_insert_tgui ('Exportación Minera', 'Factura de exportación para mineria', 'EXPOMIN', 'si', 7, 'sis_ventas_facturacion/vista/venta/VentaVendedorExportacionMin.php', 3, '', 'VentaVendedorExportacionMin', 'VEF');
select pxp.f_insert_tgui ('Computarizada minera', 'Computarizada minera', 'COMMIN', 'si', 8, 'sis_ventas_facturacion/vista/venta/VentaVendedorMin.php', 3, '', 'VentaVendedorMin', 'VEF');
select pxp.f_insert_tgui ('Ventas en Caja', 'Ventas en Caja', 'VEFCAJE', 'si', 8, 'sis_ventas_facturacion/vista/venta/VentaCaja.php', 3, '', 'VentaCaja', 'VEF');
select pxp.f_insert_tgui ('Apertura de Caja', 'Apertura de Caja', 'VEFAPCIECAJ', 'si', 1, 'sis_ventas_facturacion/vista/apertura_cierre_caja/AperturaCierreCaja.php', 3, '', 'AperturaCierreCaja', 'VEF');
select pxp.f_insert_tgui ('Ventas ETR', 'Ventas ETR', 'VENETR', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaVendedorETR.php', 3, '', 'VentaVendedorETR', 'VEF');
select pxp.f_insert_tgui ('Notas de Crédito ETR', 'Nos ta Credito Sobre Ventas', 'NCETR', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaVendedorNCETR.php', 3, '', 'VentaVendedorNCETR', 'VEF');
select pxp.f_insert_tgui ('Reporte Ventas y Notas', 'Reportes de Ventasy Notas', 'RPV', 'si', 1, 'sis_ventas_facturacion/vista/reporteVenta/FormFiltro.php', 3, '', 'FormFiltro', 'VEF');
select pxp.f_insert_tgui ('Ventas Peaje ETR', 'Ventas Peaje ETR', 'VEPEETR', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaVendedorPeajeETR.php', 3, '', 'VentaVendedorPeajeETR', 'VEF');
select pxp.f_insert_tgui ('Pendiente Emisión ETR', 'Pendientes Emisión ETR', 'PENETR', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaEmisor.php', 3, '', 'VentaEmisor', 'VEF');
select pxp.f_insert_tgui ('Cuenta Bancaria Proveedor', 'Cuenta Bancaria Proveedor', 'CBP', 'si', 6, 'sis_ventas_facturacion/vista/proveedor_cuenta_banco_cobro/ProveedorCuentaBancoCobro.php', 2, '', 'ProveedorCuentaBancoCobro', 'VEF');

/***********************************F-DAT-EGS-VEF-3-13/12/2018*****************************************/
/***********************************I-DAT-EGS-VEF-4-11/01/2019*****************************************/
/*actualizacion proceso WF*/
select wf.f_import_tproceso_macro ('insert','VEN', 'VEF', 'Sistema de Ventas','si');
select wf.f_import_tcategoria_documento ('insert','legales', 'Legales');
select wf.f_import_tcategoria_documento ('insert','proceso', 'Proceso');
select wf.f_import_ttipo_proceso ('insert','VEN',NULL,NULL,'VEN','Sistema de Ventas','','','si','','obligatorio','','VEN',NULL);
select wf.f_import_ttipo_estado ('insert','borrador','VEN','Borrador','si','no','no','anterior','','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','',NULL);
select wf.f_import_ttipo_estado ('insert','finalizado','VEN','finalizado','no','no','si','anterior','','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','','borrador');
select wf.f_import_ttipo_estado ('insert','anulado','VEN','anulado','no','no','si','anterior','','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','',NULL);
select wf.f_import_ttipo_estado ('insert','caja','VEN','caja','no','no','no','funcion_listado','vef.f_lista_funcionario_cajero','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','','borrador');
select wf.f_import_ttipo_estado ('insert','emision','VEN','Emisión de Factura','no','no','no','funcion_listado','vef.f_lista_funcionario_emisor','ninguno','','','si','si',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','',NULL);
select wf.f_import_testructura_estado ('delete','borrador','finalizado','VEN',NULL,NULL);
select wf.f_import_testructura_estado ('insert','borrador','emision','VEN',1,'');
select wf.f_import_testructura_estado ('insert','emision','finalizado','VEN',1,'');
select wf.f_import_testructura_estado ('delete','borrador','caja','VEN',NULL,NULL);
select wf.f_import_testructura_estado ('insert','caja','finalizado','VEN',1,'');
/***********************************F-DAT-EGS-VEF-4-11/01/2019*****************************************/

/***********************************I-DAT-EGS-VEF-5-29/01/2019*****************************************/
select param.f_import_tcatalogo_tipo ('insert','tipo_punto_venta','VEF','tpunto_venta');
select param.f_import_tcatalogo ('insert','VEF','aeropuerto','ato','tipo_punto_venta');
/***********************************F-DAT-EGS-VEF-5-29/01/2019*****************************************/
/***********************************I-DAT-EGS-VEF-6-28/10/2019*****************************************/
select param.f_import_tplantilla_archivo_excel ('insert','SUBFACTURA','SubirFactura','activo',NULL,'2',NULL,'','csv',',');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA2','SUBFACTURA','si','',NULL,'6','codigo conIng','codigo','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA3','SUBFACTURA','si','',NULL,'4','cantidad','cantidad','entero','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA4','SUBFACTURA','si','',NULL,'5','unidad','unidad','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA5','SUBFACTURA','no','',NULL,'7','precio uni usd','precio_uni_usd','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA6','SUBFACTURA','no','',NULL,'9','precio total usd','precio_total_usd','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA7','SUBFACTURA','si','',NULL,'1','nro','nro','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA8','SUBFACTURA','si','',NULL,'13','observaciones','observaciones','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA12','SUBFACTURA','si','',NULL,'8','precio_uni_bs','precio_uni_bs','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA13','SUBFACTURA','si','',NULL,'2','razon social','razon_social','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA14','SUBFACTURA','si','',NULL,'3','nit','nit','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA15','SUBFACTURA','si','',NULL,'10','precio total bs','precio_total_bs','numeric','.','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA16','SUBFACTURA','si','',NULL,'11','centro de costo','centro_costo','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA17','SUBFACTURA','si','',NULL,'12','clase de costo','clase_costo','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA9','SUBFACTURA','si','dd/mm/yyyy',NULL,'15','fecha','fecha','date','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA10','SUBFACTURA','si','',NULL,'16','numero contrato','nro_contrato','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA11','SUBFACTURA','si','',NULL,'17','forma de pago','forma_pago','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA1','SUBFACTURA','si','',NULL,'18','aplicacion','aplicacion','string','','activo');
select param.f_import_tcolumna_plantilla_archivo_excel ('insert','SUBFACTURA18','SUBFACTURA','si','',NULL,'14','descripcion','descripcion','string','','activo');
/***********************************F-DAT-EGS-VEF-6-28/10/2019*****************************************/
