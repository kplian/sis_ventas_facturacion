/***********************************I-DAT-JRR-VEF-0-02/05/2015*****************************************/

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'VEF', E'Sistema de Ventas', E'2015-04-20', E'VF', E'activo', E'ventas_facturacion', NULL);

select pxp.f_insert_tgui ('SISTEMA DE VENTAS', '', 'VEF', 'si', 1, '', 1, '', '', 'VEF');

/***********************************F-DAT-JRR-VEF-0-02/05/2015*****************************************/

/***********************************I-DAT-JRR-VEF-0-05/07/2015*****************************************/

select pxp.f_insert_tgui ('Sucursal', 'Sucursal', 'SUCUR', 'si', 1, 'sis_ventas_facturacion/vista/sucursal/Sucursal.php', 2, '', 'Sucursal', 'VEF');
select pxp.f_insert_tgui ('Ventas', 'Ventas', 'VFVENTA', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaVendedor.php', 2, '', 'VentaVendedor', 'VEF');
select pxp.f_insert_tgui ('Revisión de Venta', 'Revisión de Venta', 'REVVEN', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaRevision.php', 2, '', 'VentaRevision', 'VEF');
select pxp.f_insert_tgui ('Elaboración de Formulas', 'Elaboración de Formulas', 'VENELABO', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaRevision.php', 2, '', 'VentaRevision', 'VEF');

select pxp.f_insert_testructura_gui ('VFVENTA', 'VEF');
select pxp.f_insert_testructura_gui ('REVVEN', 'VEF');
select pxp.f_insert_testructura_gui ('VENELABO', 'VEF');
select pxp.f_insert_testructura_gui ('SUCUR', 'VEF');
/***********************************F-DAT-JRR-VEF-0-05/07/2015*****************************************/


/***********************************I-DAT-JRR-VEF-0-06/10/2015*****************************************/
select pxp.f_insert_tgui ('Actividad Economica', 'Actividad Economica', 'ACTECO', 'si', 1, 'sis_ventas_facturacion/vista/actividad_economica/ActividadEconomica.php', 2, '', 'ActividadEconomica', 'VEF');
select pxp.f_insert_testructura_gui ('ACTECO', 'VEF');

select pxp.f_insert_tgui ('Entidad Forma de Pago', 'Entidad Forma de Pago', 'ENFORPA', 'si', 1, 'sis_ventas_facturacion/vista/forma_pago/EntidadFormaPago.php', 2, '', 'EntidadFormaPago', 'VEF');
select pxp.f_insert_testructura_gui ('ENFORPA', 'VEF');

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'vef_tiene_punto_venta', E'false', E'variable global para definir si las ventas se manejaran a nivel sucursal o a nivel punto de venta');

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'vef_tipo_venta_habilitado', E'producto_terminado,formula,servicio', E'variable global para definir que tipos de venta estaran habilitados');

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'vef_estados_validar_fp', E'pendiente_entrega,entregado', E'variable global para definir los estados en los q se valida la forma de pago');


INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'vef_integracion_almacenes', E'true', E'variable global para definir si el sistema de ventas se integrara con el de almacenes para obtener listadod e items');


select pxp.f_insert_tgui ('Cliente', 'Cliente', 'VEFCLI', 'si', 1, 'sis_ventas_facturacion/vista/cliente/Cliente.php', 2, '', 'Cliente', 'VEF');
select pxp.f_insert_testructura_gui ('VEFCLI', 'VEF');

/***********************************F-DAT-JRR-VEF-0-06/10/2015*****************************************/