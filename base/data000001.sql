/***********************************I-DAT-JRR-VEF-0-02/05/2015*****************************************/

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'VEF', E'Sistema de Ventas', E'2015-04-20', E'VF', E'activo', E'ventas_facturacion', NULL);

select pxp.f_insert_tgui ('SISTEMA DE VENTAS', '', 'VEF', 'si', 1, '', 1, '', '', 'VEF');

/***********************************F-DAT-JRR-VEF-0-02/05/2015*****************************************/

/***********************************I-DAT-JRR-VEF-0-05/07/2015*****************************************/

select pxp.f_insert_tgui ('Ventas', 'Ventas', 'VFVENTA', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaVendedor.php', 2, '', 'VentaVendedor', 'VEF');
select pxp.f_insert_tgui ('Revisión de Venta', 'Revisión de Venta', 'REVVEN', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaRevision.php', 2, '', 'VentaRevision', 'VEF');
select pxp.f_insert_tgui ('Elaboración de Formulas', 'Elaboración de Formulas', 'VENELABO', 'si', 1, 'sis_ventas_facturacion/vista/venta/VentaRevision.php', 2, '', 'VentaRevision', 'VEF');

select pxp.f_insert_testructura_gui ('VFVENTA', 'VEF');
select pxp.f_insert_testructura_gui ('REVVEN', 'VEF');
select pxp.f_insert_testructura_gui ('VENELABO', 'VEF');
/***********************************F-DAT-JRR-VEF-0-05/07/2015*****************************************/