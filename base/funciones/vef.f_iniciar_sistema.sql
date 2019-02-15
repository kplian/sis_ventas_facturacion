--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.f_iniciar_sistema (
  p_id_usuario integer
)
RETURNS integer AS
$body$
/*
	Autor:JRR 
	Description: Funcion que permite iniciar lor parametros para el sistema de ventas y facturacion
*/
DECLARE

    v_registros			record;
    v_id_empresa		integer;
    v_cuenta_padre		varchar;
    v_id_cuenta_padre	integer;
    v_id_cuenta			integer;
	v_id_auxiliar		integer;
	v_id_partida		integer;
	v_id_gestion 		integer;
	v_id_entidad		integer;
	v_id_lugar			integer;
	v_id_depto			integer;
	v_lugares			integer[];
	v_id_sucursal		integer;
	v_id_actividad		integer;
	v_id_subsistema_param integer;
	v_id_subsistema integer;
	v_id_catalogo_tipo integer;
	v_id_catalogo integer;
	v_id_unidad_medida	integer;
	v_id_concepto_ingas	integer;
	v_id_funcionario	integer;
	v_id_tipo_proceso	integer;
	v_id_tipo_estado	integer;
	v_fecha_ini			date;
	v_fecha_fin			date;
	v_id_periodo		integer;
	v_cont				integer;
	v_rec				record;


BEGIN

	
	select id_subsistema into v_id_subsistema
	from segu.tsubsistema
	where codigo = 'VEF';
	
	select id_subsistema into v_id_subsistema_param
	from segu.tsubsistema
	where codigo = 'PARAM';
	
	if not exists (select 1 from param.tmoneda where id_moneda = 1) then
		INSERT INTO param.tmoneda
		(id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_usuario_ai, usuario_ai, id_moneda, moneda, codigo, tipo_moneda, prioridad, tipo_actualizacion, origen, contabilidad, triangulacion, codigo_internacional, show_combo, actualizacion)
		VALUES(1, NULL, '2016-01-13 02:08:11.893', NULL, 'activo', NULL, NULL, 1, 'Boliviano', 'BS', 'base', 1, '', 'nacional', 'si', 'no', 'BOB', 'si', 'no');
		
	end if;
	
	
	
	if not exists (select 1 from param.tmoneda where id_moneda = 2) then
		INSERT INTO param.tmoneda
		(id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_usuario_ai, usuario_ai, id_moneda, moneda, codigo, tipo_moneda, prioridad, tipo_actualizacion, origen, contabilidad, triangulacion, codigo_internacional, show_combo, actualizacion)
		VALUES(1, 1, '2016-01-13 02:08:49.634', '2017-10-22 00:52:17.134', 'activo', NULL, NULL, 2, 'Dolar', '$us', 'intercambio', 2, 'sin_actualizacion', 'internacional', 'si', 'si', 'USD', 'si', 'no');

	end if;
	
	if not exists (select 1 from param.tmoneda where id_moneda = 3) then
		INSERT INTO param.tmoneda
		(id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_usuario_ai, usuario_ai, id_moneda, moneda, codigo, tipo_moneda, prioridad, tipo_actualizacion, origen, contabilidad, triangulacion, codigo_internacional, show_combo, actualizacion)
		VALUES(1, 1, '2014-02-01 23:54:29.000', '2017-10-22 00:51:14.192', 'activo', NULL, NULL, 3, 'Unidad de Fomento a la Vivienda', 'UFV', 'ref', 3, 'por_transaccion', 'nacional', 'si', 'no', 'UFV', 'si', 'si');

	end if;
	select id_empresa into v_id_empresa
	from param.tempresa;
	
	if (v_id_empresa is null) then	
		INSERT INTO param.tempresa
		(id_usuario_reg,nombre, logo, nit, codigo)
		VALUES(1,'Empresa Electrica Corani', './../../sis_parametros/control/_archivo//docLog1.jpg', '111', 'CORANI') --cambiar nombre de empresa
		returning id_empresa into v_id_empresa;		
	end if;
	
	if not exists (select 1 from param.tgestion where gestion = 2019) then
		INSERT INTO param.tgestion
		(id_usuario_reg, gestion, estado, id_moneda_base, id_empresa, fecha_ini, fecha_fin, tipo)
		VALUES(1, 2019, 'activo', 1, v_id_empresa, NULL, NULL, 'MES') returning id_gestion into v_id_gestion;
		
		--(3) Generación de los Períodos y Períodos Subsistema
            v_cont =1;
            
			
                            
            while v_cont <= 12 loop
            
            	
                
	            --Obtención del primer día del mes correspondiente a la fecha_ini
	            v_fecha_ini= ('01-'||v_cont||'-2019')::date;
	            
	            --Obtención el último dia del mes correspondiente a la fecha_fin
	            v_fecha_fin=(date_trunc('MONTH', v_fecha_ini) + INTERVAL '1 MONTH - 1 day')::date;
	             
	            insert into param.tperiodo(
                  id_usuario_reg,
                  id_usuario_mod,
                  fecha_reg,
                  fecha_mod,
                  estado_reg,
                  periodo,
                  id_gestion,
                  fecha_ini,
                  fecha_fin
                ) VALUES (
                  1,
                  NULL,
                  now(),
                  NULL,
                  'activo',
                  v_cont,
                  v_id_gestion,
                  v_fecha_ini,
                  v_fecha_fin
                ) returning id_periodo into v_id_periodo;
                
                --Registro de los periodos de los subsistemas existentes
                for v_rec in (select id_subsistema
                			from segu.tsubsistema
                			where estado_reg = 'activo'
                			and codigo not in ('PXP','GEN','SEGU','WF','PARAM','ORGA','MIGRA')) loop
                	insert into param.tperiodo_subsistema(
                	id_periodo,
                	id_subsistema,
                	estado,
                	id_usuario_reg,
                	fecha_reg
                	) values(
                	v_id_periodo,
                	v_rec.id_subsistema,
                	'cerrado',
                	1,
                	now()
                	);
                	
                		
                end loop;     
            
               v_cont=v_cont+1;
            
            END LOOP;

	end if;
	
	select id_entidad into v_id_entidad
	from param.tentidad;
	
	
	if (v_id_entidad is null) then	
		INSERT INTO param.tentidad
		(id_usuario_reg, nombre, nit, tipo_venta_producto, estados_comprobante_venta, estados_anulacion_venta, pagina_entidad, direccion_matriz, identificador_min_trabajo, identificador_caja_salud)
		VALUES(1, 'Corani', '1111', '', 'finalizado', '', 'www.corani.bo', 'Edificio Torres Sofer piso 4', '', '')returning id_entidad into v_id_entidad;	
	end if;
	
	
	select id_lugar into v_id_lugar
	from param.tlugar
	where codigo = 'BO';
	
	
	
	if (v_id_lugar is null) then
		INSERT INTO param.tlugar
		(id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_usuario_ai, usuario_ai,  id_lugar_fk, codigo, nombre, tipo, sw_municipio, sw_impuesto, codigo_largo, es_regional)
		VALUES(1, NULL, '2019-01-23 18:00:07.180', NULL, 'activo', NULL, NULL, NULL, 'BO', 'Bolivia', 'pais', 'no', 'no', 'BO', 'si')returning id_lugar into v_id_lugar;
	end if;
	
	v_lugares[1] = v_id_lugar;
	select id_depto into v_id_depto
	from param.tdepto
	where id_subsistema = v_id_subsistema;
	
	if (v_id_depto is null) then
	
		INSERT INTO param.tdepto
		(id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_usuario_ai, usuario_ai,  id_subsistema, codigo, nombre, nombre_corto, id_lugares, prioridad, modulo, id_entidad)
		VALUES(1, NULL, '2019-01-31 00:00:00.000', '2019-01-31 16:11:32.344', 'activo', NULL, NULL,  v_id_subsistema, 'VEF', 'Ventas', 'Ventas', v_lugares, 1, '', v_id_entidad)returning id_depto into v_id_depto;
	end if;
	
	select id_sucursal into v_id_sucursal
	from vef.tsucursal
	where id_entidad = v_id_entidad;
	
	
	if (v_id_sucursal is null) then	
		INSERT INTO vef.tsucursal
		(id_usuario_reg, codigo, nombre, telefono, correo, tiene_precios_x_sucursal, clasificaciones_para_venta, clasificaciones_para_formula, direccion, id_entidad, plantilla_documento_factura, plantilla_documento_recibo, formato_comprobante, lugar, habilitar_comisiones, id_lugar, tipo_interfaz, id_depto, nombre_comprobante)
		VALUES(1, 'MAT', 'Casa Matriz', '4535543', '', 'no', '{}', '{}', '', v_id_entidad, '', '', '', '', 'no', v_id_lugar, '{computarizada}', v_id_depto, '')returning id_sucursal into v_id_sucursal;
	end if;
	
	
	select id_actividad_economica into v_id_actividad
	from vef.tactividad_economica;
	
	if (v_id_actividad is null) then	
		INSERT INTO vef.tactividad_economica
		(id_usuario_reg ,codigo, nombre, descripcion)
		VALUES(1,'ENE', 'Energia Electrica', '') returning id_actividad_economica into  v_id_actividad;
	end if;
	
	select id_catalogo_tipo into v_id_catalogo_tipo
	from param.tcatalogo_tipo
	where tabla = 'tunidad_medida';
	
	if (v_id_catalogo_tipo is null) then
		INSERT INTO param.tcatalogo_tipo
		(id_usuario_reg, id_subsistema, nombre, tabla, tabla_estado, columna_estado)
		VALUES(1, v_id_subsistema_param, 'tunidad_medida', 'tunidad_medida', NULL, NULL) returning id_catalogo_tipo into v_id_catalogo_tipo;
	end if;
	
	select id_catalogo into v_id_catalogo
	from param.tcatalogo
	where id_catalogo_tipo = v_id_catalogo_tipo;
	
	if (v_id_catalogo is null) then
		INSERT INTO param.tcatalogo
		(id_usuario_reg, codigo, descripcion, id_catalogo_tipo, orden, icono)
		VALUES(1, 'ENE', 'Energia Electrica', v_id_catalogo_tipo, NULL, NULL);
	end if;
	
	select id_unidad_medida into v_id_unidad_medida
	from param.tunidad_medida
	where codigo = 'KWH';
	
	if (v_id_unidad_medida is null) then
		INSERT INTO param.tunidad_medida
		(id_usuario_reg,codigo, descripcion, tipo)
		VALUES(1, 'KWH', 'Kilowatt hora', 'Energia Electrica')returning id_unidad_medida into v_id_unidad_medida;
	end if;
	
	
	select id_concepto_ingas into v_id_concepto_ingas
	from param.tconcepto_ingas
	where codigo = 'ENE';
	
	
	if (v_id_concepto_ingas is null) then
		INSERT INTO param.tconcepto_ingas
		(id_usuario_reg, tipo, desc_ingas, movimiento, sw_tes, id_oec, activo_fijo, almacenable, pago_automatico, sw_autorizacion, descripcion_larga, id_entidad, id_actividad_economica, codigo, id_grupo_ots, id_unidad_medida, nandina, ruta_foto, id_cat_concepto)
		VALUES(1, 'Servicio', 'Energia Electrica', 'recurso', NULL, NULL, 'no', 'no', 'no', NULL, '', 1, 1, 'ENE', NULL, 1, '', NULL, NULL) returning id_concepto_ingas into v_id_concepto_ingas;
	
		INSERT INTO vef.tsucursal_producto
		( id_usuario_reg,precio, id_sucursal, id_item, tipo_producto, id_concepto_ingas, requiere_descripcion, id_moneda, contabilizable, excento)
		VALUES(1, 20.00, 1, NULL, 'servicio', v_id_concepto_ingas, 'si', 1, 'no', 'no');
	end if;
	
	if (not exists(select 1 from vef.tsucursal_usuario where id_usuario = 1)) then
		INSERT INTO vef.tsucursal_usuario
		(id_usuario_reg, tipo_usuario, id_sucursal, id_usuario, id_punto_venta)
		VALUES(1, 'vendedor', v_id_sucursal, 1, NULL);
	end if;
	
	if (not exists(select 1 from vef.tforma_pago)) then
		INSERT INTO vef.tforma_pago
		(id_usuario_reg, codigo, nombre, id_entidad, id_moneda, defecto, registrar_tarjeta, registrar_cc, registrar_tipo_tarjeta)
		VALUES(1, 'EFE', 'Efectivo', 1, 1, 'si', 'no', 'no', 'no');

	end if;
	
	
	if (not exists(select 1 from vef.tsucursal_moneda where id_sucursal = v_id_sucursal )) then
		INSERT INTO vef.tsucursal_moneda
		(id_usuario_reg, tipo_moneda, id_sucursal, id_moneda)
		VALUES(1, 'moneda_base', v_id_sucursal, 1);

	end if;
	
	
	if (not exists(select 1 from orga.tfuncionario where id_persona = 1 )) then
		INSERT INTO orga.tfuncionario
		(id_usuario_reg, id_persona, codigo, email_empresa, interno, fecha_ingreso, telefono_ofi, antiguedad_anterior, id_biometrico, id_auxiliar)
		VALUES(1,1, '111', '', '', '2019-01-31', '', NULL, NULL, NULL) returning id_funcionario into v_id_funcionario;


	end if;
	
	if (not exists (select 1 from vef.tdosificacion)) then
		INSERT INTO vef.tdosificacion
		(id_usuario_reg,tipo, id_sucursal, nroaut, tipo_generacion, inicial, "final", llave, fecha_dosificacion, fecha_inicio_emi, fecha_limite, id_activida_economica, glosa_impuestos, glosa_empresa, nro_siguiente)
		VALUES(1, 'F', 1, '123456', 'computarizada', NULL, NULL, '542542354325', '2019-01-01', '2019-01-01', '2019-12-31', '{1}', 'test', 'test', 1);
		
	end if;
	
	select id_tipo_proceso into v_id_tipo_proceso
	from wf.ttipo_proceso
	where codigo = 'VEN';
	
	select id_tipo_estado into v_id_tipo_estado
	from wf.ttipo_estado
	where id_tipo_proceso =  v_id_tipo_proceso and codigo = 'finalizado';
	
	update wf.tfuncionario_tipo_estado
	set id_funcionario = v_id_funcionario
	where id_tipo_estado = v_id_tipo_estado;
	 
	
	
	
	
	
    return 1;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;