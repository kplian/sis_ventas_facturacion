/***********************************I-DAT-JRR-VEF-0-02/05/2015*****************************************/

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'VEF', E'Sistema de Ventas', E'2015-04-20', E'VF', E'activo', E'ventas_farmacia', NULL);

select pxp.f_insert_tgui ('SISTEMA DE VENTAS', '', 'VEF', 'si', 1, '', 1, '', '', 'VEF');

/***********************************F-DAT-JRR-VEF-0-02/05/2015*****************************************/