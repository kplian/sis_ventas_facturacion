/***********************************I-DAT-JRR-VEF-0-02/05/2015*****************************************/

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'VEF', E'Sistema de Ventas', E'2015-04-20', E'VF', E'activo', E'ventas_facturacion', NULL);

select pxp.f_insert_tgui ('SISTEMA DE VENTAS', '', 'VEF', 'si', 1, '', 1, '', '', 'VEF');

/***********************************F-DAT-JRR-VEF-0-02/05/2015*****************************************/

/***********************************I-DAT-JRR-VEF-0-05/07/2015*****************************************/

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
select pxp.f_insert_testructura_gui ('VFVENTA', 'VENCARP');

/***********************************F-DAT-JRR-VEF-0-05/07/2015*****************************************/


/***********************************I-DAT-JRR-VEF-0-06/10/2015*****************************************/
INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES ( E'vef_estados_validar_fp', E'borrador', E'variable global para definir los estados en los q se valida la forma de pago');

INSERT INTO pxp.variable_global ( "variable", "valor", "descripcion")
VALUES ( E'vef_integracion_almacenes', E'false', E'variable global para definir si el sistema de ventas se integrara con el de almacenes para obtener listadod e items');

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES ( E'vef_tiene_punto_venta', E'false', E'variable global para definir si las ventas se manejaran a nivel sucursal o a nivel punto de venta');

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
----------------------------------
--COPY LINES TO dependencies.sql FILE  
---------------------------------

select pxp.f_insert_testructura_gui ('VEFACEX', 'VENCARP');
select pxp.f_insert_testructura_gui ('EXPOMIN', 'VENCARP');
select pxp.f_insert_testructura_gui ('COMMIN', 'VENCARP');

/***********************************F-DAT-RAC-VEF-0-05/05/2016*****************************************/




