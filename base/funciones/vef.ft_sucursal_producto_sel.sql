CREATE OR REPLACE FUNCTION vef.ft_sucursal_producto_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_producto_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tsucursal_producto'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 03:18:44
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
	v_sucursal			record;
	v_join				varchar;
	v_where				varchar;
	v_select			varchar;
			    
BEGIN

	v_nombre_funcion = 'vef.ft_sucursal_producto_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SPROD_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 03:18:44
	***********************************/

	if(p_transaccion='VF_SPROD_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						sprod.id_sucursal_producto,
						sprod.id_sucursal,
						sprod.id_item,						
						sprod.precio,						
						sprod.estado_reg,
						sprod.tipo_producto,
						sprod.fecha_reg,
						sprod.usuario_ai,
						sprod.id_usuario_reg,
						sprod.id_usuario_ai,
						sprod.fecha_mod,
						sprod.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        item.nombre,
                        cig.id_concepto_ingas,
                        cig.desc_ingas,
                        cig.descripcion_larga,
                        acteco.id_actividad_economica,
                        acteco.nombre as nombre_actividad	
						from vef.tsucursal_producto sprod
						inner join segu.tusuario usu1 on usu1.id_usuario = sprod.id_usuario_reg						
						left join segu.tusuario usu2 on usu2.id_usuario = sprod.id_usuario_mod
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
                        left join vef.tactividad_economica acteco on acteco.id_actividad_economica = cig.id_actividad_economica
                        left join alm.titem item on item.id_item = sprod.id_item
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SPROD_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 03:18:44
	***********************************/

	elsif(p_transaccion='VF_SPROD_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_sucursal_producto)
					    from vef.tsucursal_producto sprod
					    inner join segu.tusuario usu1 on usu1.id_usuario = sprod.id_usuario_reg					    
						left join segu.tusuario usu2 on usu2.id_usuario = sprod.id_usuario_mod
                        left join alm.titem item on item.id_item = sprod.id_item
					    left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
					    left join vef.tactividad_economica acteco on acteco.id_actividad_economica = cig.id_actividad_economica
                        where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
	/*********************************    
 	#TRANSACCION:  'VF_PRODITEFOR_SEL'
 	#DESCRIPCION:	Listado de productos, servicios, item o formulas
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 03:18:44
	***********************************/

	elsif(p_transaccion='VF_PRODITEFOR_SEL')then
     				
    	begin
    		
    		if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
    			select suc.* into v_sucursal
    			from vef.tpunto_venta pv
    			inner join vef.tsucursal suc on suc.id_sucursal = pv.id_sucursal
    			where pv.id_punto_venta = v_parametros.id_punto_venta;
    		else
    			select suc.* into v_sucursal
    			from vef.tsucursal suc
    			where suc.id_sucursal = v_parametros.id_sucursal;
    			
    		end if;
    		
    		--Items si se integra con almacenes
    		if (v_parametros.tipo = 'producto_terminado' and pxp.f_get_variable_global('vef_integracion_almacenes') = 'true') then
				if (v_sucursal.tiene_precios_x_sucursal = 'si') then
					v_consulta := 'with tabla_temporal as (
									select it.id_tem as id_producto, ''producto_terminado''::varchar as tipo,
											it.nombre, it.descripcion::text,sp.precio as precio,''''::varchar as medico
									from alm.titem it 
									inner join vef.tsucursal_producto sp on sp.id_item = it.id_item
									where sp.estado_reg = ''activo'' and it.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || '
									)';
				else
					v_consulta := 'with tabla_temporal as (
									select it.id_tem as id_producto, ''producto_terminado''::varchar as tipo,
											it.nombre, it.descripcion::text,it.precio_ref as precio,''''::varchar as medico
									from alm.titem it 									
									where it.estado_reg = ''activo'' and
									(select s.clasificaciones_para_venta 
                                                from vef.tsucursal s 
                                                where s.id_sucursal = ' || v_sucursal.id_sucursal ||  '  ) && string_to_array(alm.f_get_id_clasificaciones(it.id_clasificacion,''padres''), '','')::integer[]
									)';
				
				end if;
			
			
			--Cocneptos de gasto para productos(sin integracion con almacenes) o servicios
			elsif (v_parametros.tipo = 'producto_terminado' or v_parametros.tipo = 'servicio') then
				
                if (v_parametros.tipo = 'producto_terminado') then
					v_where = 'sp.tipo_producto = ''producto''';
					v_select = '''producto_terminado''::varchar as tipo';
				else
					v_where = 'sp.tipo_producto = ''servicio''';
					v_select = '''servicio''::varchar as tipo';
				end if;
                
				v_join = '';
				if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
					v_join = 'inner join vef.tpunto_venta_producto pvp on pvp.id_sucursal_producto = sp.id_sucursal_producto';
				end if;
				
				v_consulta := 'with tabla_temporal as (
									select sp.id_sucursal_producto as id_producto, ' || v_select || ' ,
											cig.desc_ingas as nombre, cig.descripcion_larga::text as descripcion,sp.precio as precio,''''::varchar as medico
									from vef.tsucursal_producto sp
									' || v_join || '
									inner join param.tconcepto_ingas cig on cig.id_concepto_ingas = sp.id_concepto_ingas
									where sp.estado_reg = ''activo'' and cig.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || ' and ' || v_where ||'
									)';
			
			else
			--Formulas o paquetes
			
				v_consulta := 'with tabla_temporal as (
								select for.id_formula as id_producto, ''formula''::varchar as tipo,
										for.nombre, for.descripcion::text,sum(fd.cantidad * i.precio_ref) as precio,med.nombre_completo::varchar as medico
								from vef.tformula for
								inner join vef.vmedico med on med.id_medico = for.id_medico
								left join vef.tformula_detalle fd on fd.id_formula = for.id_formula
                        		left join alm.titem i on i.id_item = fd.id_item
								where for.estado_reg = ''activo'' and fd.estado_reg = ''activo''
								group by for.id_formula,
								for.nombre, for.descripcion,
								med.nombre_completo
								)';
			
			end if;
			v_consulta = v_consulta ||	' 	select todo.id_producto,todo.tipo,todo.nombre,
													todo.descripcion,todo.precio,todo.medico
											from tabla_temporal todo
											where ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;
	
	/*********************************    
 	#TRANSACCION:  'VF_PRODITEFOR_CONT'
 	#DESCRIPCION:	Conteo de productos, servicios, item o formulas
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 03:18:44
	***********************************/

	elsif(p_transaccion='VF_PRODITEFOR_CONT')then
     				
    	begin
    		
    		if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
    			select suc.* into v_sucursal
    			from vef.tpunto_venta pv
    			inner join vef.tsucursal suc on suc.id_sucursal = pv.id_sucursal
    			where pv.id_punto_venta = v_parametros.id_punto_venta;
    		else
    			select suc.* into v_sucursal
    			from vef.tsucursal suc
    			where suc.id_sucursal = v_parametros.id_sucursal;
    			
    		end if;
    		
    		--Items si se integra con almacenes
    		if (v_parametros.tipo = 'producto_terminado' and pxp.f_get_variable_global('vef_integracion_almacenes') = 'true') then
				if (v_sucursal.tiene_precios_x_sucursal = 'si') then
					v_consulta := 'with tabla_temporal as (
									select it.id_tem as id_producto, ''producto_terminado''::varchar as tipo,
											it.nombre, it.descripcion::text,sp.precio as precio,''''::varchar as medico
									from alm.titem it 
									inner join vef.tsucursal_producto sp on sp.id_item = it.id_item
									where sp.estado_reg = ''activo'' and it.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || '
									)';
				else
					v_consulta := 'with tabla_temporal as (
									select it.id_tem as id_producto, ''producto_terminado''::varchar as tipo,
											it.nombre, it.descripcion::text,it.precio_ref as precio,''''::varchar as medico
									from alm.titem it 									
									where it.estado_reg = ''activo'' and
									(select s.clasificaciones_para_venta 
                                                from vef.tsucursal s 
                                                where s.id_sucursal = ' || v_sucursal.id_sucursal ||  '  ) && string_to_array(alm.f_get_id_clasificaciones(it.id_clasificacion,''padres''), '','')::integer[]
									)';
				
				end if;
			
			
			--Cocneptos de gasto para productos(sin integracion con almacenes) o servicios
			elsif (v_parametros.tipo = 'producto_terminado' or v_parametros.tipo = 'servicio') then
				
                if (v_parametros.tipo = 'producto_terminado') then
					v_where = 'sp.tipo_producto = ''producto''';
					v_select = '''producto_terminado''::varchar as tipo';
				else
					v_where = 'sp.tipo_producto = ''servicio''';
					v_select = '''servicio''::varchar as tipo';
				end if;
                
				v_join = '';
				if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
					v_join = 'inner join vef.tpunto_venta_producto pvp on pvp.id_sucursal_producto = sp.id_sucursal_producto';
				end if;
				
				v_consulta := 'with tabla_temporal as (
									select sp.id_sucursal_producto as id_producto, ' || v_select || ' ,
											cig.desc_ingas as nombre, cig.descripcion_larga::text as descripcion,sp.precio as precio,''''::varchar as medico
									from vef.tsucursal_producto sp
									' || v_join || '
									inner join param.tconcepto_ingas cig on cig.id_concepto_ingas = sp.id_concepto_ingas
									where sp.estado_reg = ''activo'' and cig.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || ' and ' || v_where ||'
									)';
			
			else
			--Formulas o paquetes
			
				v_consulta := 'with tabla_temporal as (
								select for.id_formula as id_producto, ''formula''::varchar as tipo,
										for.nombre, for.descripcion::text,sum(fd.cantidad * i.precio_ref) as precio,med.nombre_completo::varchar as medico
								from vef.tformula for
								inner join vef.vmedico med on med.id_medico = for.id_medico
								left join vef.tformula_detalle fd on fd.id_formula = for.id_formula
                        		left join alm.titem i on i.id_item = fd.id_item
								where for.estado_reg = ''activo'' and fd.estado_reg = ''activo''
								group by for.id_formula,
								for.nombre, for.descripcion,
								med.nombre_completo
								)';
			
			end if;
			v_consulta = v_consulta ||	' 	select count(*)
											from tabla_temporal todo
											where ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			
			--Devuelve la respuesta
			return v_consulta;
						
		end;
					
	else
					     
		raise exception 'Transaccion inexistente';
					         
	end if;
					
EXCEPTION
					
	WHEN OTHERS THEN
			v_resp='';
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
			v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
			v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
			raise exception '%',v_resp;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;