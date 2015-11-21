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
    v_select_precio_item		varchar;
    v_having			varchar;
			    
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
                        acteco.nombre as nombre_actividad,
                        sprod.requiere_descripcion	
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
									select it.id_item as id_producto, ''producto_terminado''::varchar as tipo,
											it.nombre, it.descripcion::text,sp.precio as precio,''''::varchar as medico,
                                            sp.requiere_descripcion
									from alm.titem it 
									inner join vef.tsucursal_producto sp on sp.id_item = it.id_item
									where sp.estado_reg = ''activo'' and it.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || '
									)';
				else
					v_consulta := 'with tabla_temporal as (
									select it.id_item as id_producto, ''producto_terminado''::varchar as tipo,
											it.nombre, it.descripcion::text,it.precio_ref as precio,''''::varchar as medico,
                                             ''''::varchar as requiere_descripcion
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
                	
					v_join = 'inner join vef.tpunto_venta_producto pvp on pvp.id_sucursal_producto = sp.id_sucursal_producto and
                    			pvp.estado_reg = ''activo'' and pvp.id_punto_venta = ' || v_parametros.id_punto_venta;
				end if;
				
				v_consulta := 'with tabla_temporal as (
									select sp.id_sucursal_producto as id_producto, ' || v_select || ' ,
											cig.desc_ingas as nombre, cig.descripcion_larga::text as descripcion,sp.precio as precio,''''::varchar as medico,
                                            sp.requiere_descripcion
									from vef.tsucursal_producto sp
									' || v_join || '
									inner join param.tconcepto_ingas cig on cig.id_concepto_ingas = sp.id_concepto_ingas
									where sp.estado_reg = ''activo'' and cig.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || ' and ' || v_where ||'
									)';
			
			else
			--Formulas o paquetes
            	v_having = '' ;            	
                v_having = 'having array_remove (array_agg(fd.id_concepto_ingas),NULL) <@ array_remove (array_agg(spc.id_concepto_ingas),NULL)';
				if (v_sucursal.tiene_precios_x_sucursal = 'si') then
                	v_select_precio_item = 'spi.precio';
                	v_join = ' 	left join alm.titem i on i.id_item = fd.id_item 
                    			left join vef.tsucursal_producto spi on spi.id_item = i.id_item 
                                	and spi.estado_reg = ''activo'' ';
                	v_having = v_having || ' and array_remove (array_agg(fd.id_item),NULL) <@ array_remove (array_agg(spi.id_item),NULL)';
                else
                	v_select_precio_item = 'i.precio_ref';
                	v_join = ' left join alm.titem i on i.id_item = fd.id_item ';
                end if;
                
				v_consulta := 'with tabla_temporal as (
								select form.id_formula as id_producto, ''formula''::varchar as tipo,
										form.nombre, form.descripcion::text,
                                        sum(fd.cantidad * (case when fd.id_item is not null then 
                                        						' || v_select_precio_item || '
                                        					else
                                                            	spc.precio
                                                            end))::numeric as precio,
                                        med.nombre_completo::varchar as medico,
                                        ''''::varchar as requiere_descripcion
								from vef.tformula form
								inner join vef.vmedico med on med.id_medico = form.id_medico
								inner join vef.tformula_detalle fd on fd.id_formula = form.id_formula
                                left join vef.tsucursal_producto spc on spc.id_concepto_ingas = fd.id_concepto_ingas 
                                	and spc.estado_reg = ''activo'' 
                        		' || v_join || '
								where form.estado_reg = ''activo'' and fd.estado_reg = ''activo''
								group by form.id_formula,
								form.nombre, form.descripcion,
								med.nombre_completo
                                ' || v_having || ')';
			
			end if;
			v_consulta = v_consulta ||	' 	select todo.id_producto,todo.tipo,todo.nombre,
													todo.descripcion,todo.precio,todo.medico,todo.requiere_descripcion
											from tabla_temporal todo
											where ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice '%',v_consulta;
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
									select it.id_item as id_producto, ''producto_terminado''::varchar as tipo,
											it.nombre, it.descripcion::text,sp.precio as precio,''''::varchar as medico,
                                            sp.requiere_descripcion
									from alm.titem it 
									inner join vef.tsucursal_producto sp on sp.id_item = it.id_item
									where sp.estado_reg = ''activo'' and it.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || '
									)';
				else
					v_consulta := 'with tabla_temporal as (
									select it.id_item as id_producto, ''producto_terminado''::varchar as tipo,
											it.nombre, it.descripcion::text,it.precio_ref as precio,''''::varchar as medico,
                                            ''''::varchar as requiere_descripcion
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
                	
					v_join = 'inner join vef.tpunto_venta_producto pvp on pvp.id_sucursal_producto = sp.id_sucursal_producto and
                    			pvp.estado_reg = ''activo'' and pvp.id_punto_venta = ' || v_parametros.id_punto_venta;
				end if;
				
				v_consulta := 'with tabla_temporal as (
									select sp.id_sucursal_producto as id_producto, ' || v_select || ' ,
											cig.desc_ingas as nombre, cig.descripcion_larga::text as descripcion,sp.precio as precio,''''::varchar as medico,
                                            sp.requiere_descripcion
									from vef.tsucursal_producto sp
									' || v_join || '
									inner join param.tconcepto_ingas cig on cig.id_concepto_ingas = sp.id_concepto_ingas
									where sp.estado_reg = ''activo'' and cig.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || ' and ' || v_where ||'
									)';
			
			else
			--Formulas o paquetes
            	v_having = '' ;            	
                v_having = 'having array_remove (array_agg(fd.id_concepto_ingas),NULL) <@ array_remove (array_agg(spc.id_concepto_ingas),NULL)';
				if (v_sucursal.tiene_precios_x_sucursal = 'si') then
                	v_select_precio_item = 'spi.precio';
                	v_join = ' 	left join alm.titem i on i.id_item = fd.id_item 
                    			left join vef.tsucursal_producto spi on spi.id_item = i.id_item 
                                	and spi.estado_reg = ''activo'' ';
                	v_having = v_having || ' and array_remove (array_agg(fd.id_item),NULL) <@ array_remove (array_agg(spi.id_item),NULL)';
                else
                	v_select_precio_item = 'i.precio_ref';
                	v_join = ' left join alm.titem i on i.id_item = fd.id_item ';
                end if;
                
				v_consulta := 'with tabla_temporal as (
								select form.id_formula as id_producto, ''formula''::varchar as tipo,
										form.nombre, form.descripcion::text,
                                        sum(fd.cantidad * (case when fd.id_item is not null then 
                                        						' || v_select_precio_item || '
                                        					else
                                                            	spc.precio
                                                            end))::numeric as precio,
                                        med.nombre_completo::varchar as medico,
                                        ''''::varchar as requiere_descripcion
								from vef.tformula form
								inner join vef.vmedico med on med.id_medico = form.id_medico
								inner join vef.tformula_detalle fd on fd.id_formula = form.id_formula
                                left join vef.tsucursal_producto spc on spc.id_concepto_ingas = fd.id_concepto_ingas 
                                	and spc.estado_reg = ''activo'' 
                        		' || v_join || '
								where form.estado_reg = ''activo'' and fd.estado_reg = ''activo''
								group by form.id_formula,
								form.nombre, form.descripcion,
								med.nombre_completo
                                ' || v_having || ')';
			
			end if;
			v_consulta = v_consulta ||	' 	select count(*)
											from tabla_temporal todo
											where ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			
			--Devuelve la respuesta
			return v_consulta;
						
		end;
    /*********************************    
 	#TRANSACCION:  'VF_PRODFORMU_SEL'
 	#DESCRIPCION:	Listado de productos, servicios, item para formulas
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 03:18:44
	***********************************/

	elsif(p_transaccion='VF_PRODFORMU_SEL')then
     				
    	begin
        
    		--Items si se integra con almacenes
    		if (v_parametros.tipo = 'item' and pxp.f_get_variable_global('vef_integracion_almacenes') = 'true') then
				
					v_consulta := 'with tabla_temporal as (
									select it.id_item as id_producto, ''item''::varchar as tipo,
											it.nombre, it.descripcion::text,um.descripcion as unidad_medida
									from alm.titem it 			
                                    inner join param.tunidad_medida um on (um.id_unidad_medida = it.id_unidad_medida)															
									where it.estado_reg = ''activo'')';
            elsif (v_parametros.tipo = 'item' and pxp.f_get_variable_global('vef_integracion_almacenes') != 'true') then
				
					raise exception 'No esta habilitada la integracion con items de almacenes';
           
				
			
			
			--Cocneptos de gasto para productos(sin integracion con almacenes) o servicios
			elsif (v_parametros.tipo = 'producto_servicio') then
								
				v_consulta := 'with tabla_temporal as (
									select cig.id_concepto_ingas as id_producto, ''producto_servicio''::varchar as tipo,
											cig.desc_ingas as nombre, cig.descripcion_larga::text as descripcion,
                                            ''''::varchar as unidad_medida
									from param.tconcepto_ingas cig 
									where cig.estado_reg = ''activo'' and cig.id_entidad is not null 
									)';		
                raise notice '%', v_consulta;	
			
			
			end if;
			v_consulta = v_consulta ||	' 	select todo.id_producto,todo.tipo,todo.nombre,
													todo.descripcion,todo.unidad_medida
											from tabla_temporal todo
											where ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;
	
	/*********************************    
 	#TRANSACCION:  'VF_PRODFORMU_CONT'
 	#DESCRIPCION:	Conteo de productos, servicios, item  para formulas
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 03:18:44
	***********************************/

	elsif(p_transaccion='VF_PRODFORMU_CONT')then
     				
    	begin    		
    		
    		
    		--Items si se integra con almacenes
    		if (v_parametros.tipo = 'item' and pxp.f_get_variable_global('vef_integracion_almacenes') = 'true') then
				
					v_consulta := 'with tabla_temporal as (
									select it.id_item as id_producto, ''item''::varchar as tipo,
											it.nombre, it.descripcion::text,um.descripcion as unidad_medida
									from alm.titem it 			
                                    inner join param.tunidad_medida um on (um.id_unidad_medida = it.id_unidad_medida)															
									where it.estado_reg = ''activo'')';
            elsif (v_parametros.tipo = 'item' and pxp.f_get_variable_global('vef_integracion_almacenes') != 'true') then
				
					raise exception 'No esta habilitada la integracion con items de almacenes';
           
				
			
			
			--Cocneptos de gasto para productos(sin integracion con almacenes) o servicios
			elsif (v_parametros.tipo = 'producto_servicio') then
								
				v_consulta := 'with tabla_temporal as (
									select cig.id_concepto_ingas as id_producto, ''producto_servicio''::varchar as tipo,
											cig.desc_ingas as nombre, cig.descripcion_larga::text as descripcion,
                                            '''' as unidad_medida
									from param.tconcepto_ingas cig 
									where cig.estado_reg = ''activo'' and cig.id_entidad is not null 
									)';		
                raise notice '%', v_consulta;	
			
			
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