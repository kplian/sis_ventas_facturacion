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

 ISSUE            FECHA:		      AUTOR               DESCRIPCION
 #0              21-04-2015        JRR                 Creacion 
 #123              08/10/2018        RAC                 Logica para listar conceptos de facturas relacionas para notas de credito sobre ventas	
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
    v_id_moneda_venta	integer;
    v_tipo_cambio_venta		numeric;
    v_tipo					varchar;
    v_ncd                   boolean;  --#123
    v_id_venta_fk         integer;  --#123
			    
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
                        sprod.requiere_descripcion,
                        sprod.id_moneda,
                        mon.codigo_internacional as desc_moneda,
                        sprod.contabilizable,
                        sprod.excento,
						um.id_unidad_medida,
                        um.codigo as desc_unidad_medida,
                        cig.nandina,
                        COALESCE(cig.ruta_foto,'''')::varchar as ruta_foto,
                        cig.codigo 
			
                        
						from vef.tsucursal_producto sprod
						inner join segu.tusuario usu1 on usu1.id_usuario = sprod.id_usuario_reg						
						left join segu.tusuario usu2 on usu2.id_usuario = sprod.id_usuario_mod
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
                        left join vef.tactividad_economica acteco on acteco.id_actividad_economica = cig.id_actividad_economica
                        left join alm.titem item on item.id_item = sprod.id_item
                        left join param.tmoneda mon on mon.id_moneda = sprod.id_moneda
                        left join param.tunidad_medida um on um.id_unidad_medida = cig.id_unidad_medida
				        where ';
			
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
					    left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
					    left join vef.tactividad_economica acteco on acteco.id_actividad_economica = cig.id_actividad_economica
                        left join alm.titem item on item.id_item = sprod.id_item
                        left join param.tmoneda mon on mon.id_moneda = sprod.id_moneda
                        left join param.tunidad_medida um on um.id_unidad_medida = cig.id_unidad_medida
				        where  ';
			
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
    		v_where = '';
            v_join = '';
            v_ncd = false; --#123
            
            --#123   adciona logica para cocnepto de gastos para notas de credito, el detaqlle viene de la factura previa seleccionada
            if (pxp.f_existe_parametro(p_tabla,'id_venta_fk')) then
               v_ncd = true;
               v_id_venta_fk = v_parametros.id_venta_fk;
            end if;
    		
    		if (pxp.f_existe_parametro(p_tabla,'id_punto_venta'))  then
    			select suc.*,sucmon.id_moneda into v_sucursal
    			from vef.tpunto_venta pv
    			inner join vef.tsucursal suc on suc.id_sucursal = pv.id_sucursal
    			inner join vef.tsucursal_moneda sucmon on sucmon.id_sucursal = suc.id_sucursal 
    										and sucmon.tipo_moneda = 'moneda_base'
    			where pv.id_punto_venta = v_parametros.id_punto_venta;
    			
    			--anadir join de punto de venta producto
                v_join = 'left join vef.tpunto_venta_producto pvp on pvp.id_sucursal_producto = sp.id_sucursal_producto and pvp.estado_reg = ''activo''  ';
                
                --anadir filtro de punto de venta
                v_where = ' and pvp.id_punto_venta = ' || v_parametros.id_punto_venta || '  ';
    		else
    			select suc.*,sucmon.id_moneda into v_sucursal
    			from vef.tsucursal suc
    			inner join vef.tsucursal_moneda sucmon on sucmon.id_sucursal = suc.id_sucursal 
    										and sucmon.tipo_moneda = 'moneda_base'
    			where suc.id_sucursal = v_parametros.id_sucursal;
    			
    		end if;
            
             if (pxp.f_existe_parametro(p_tabla,'id_moneda')) then
              v_id_moneda_venta =  v_parametros.id_moneda;
              v_tipo_cambio_venta =  v_parametros.tipo_cambio_venta;
              v_tipo = 'CUS';
            else
              v_id_moneda_venta = v_sucursal.id_moneda;
              v_tipo = 'O';
              v_tipo_cambio_venta = NULL;
            end if;
           
    		
    		--Items si se integra con almacenes
    		if (v_parametros.tipo = 'producto_terminado' and pxp.f_get_variable_global('vef_integracion_almacenes') = 'true') and  not v_ncd  then
				if (v_sucursal.tiene_precios_x_sucursal = 'si') then
					v_consulta := 'with tabla_temporal as (
									select it.id_item as id_producto, ''producto_terminado''::varchar as tipo,
											(it.codigo || '' - '' || it.nombre)::varchar as nombre, it.descripcion::text,
                                            param.f_convertir_moneda(sp.id_moneda,' ||v_id_moneda_venta  || ',sp.precio,now()::date,'''||v_tipo||''',2,'||COALESCE(v_tipo_cambio_venta::varchar,'NULL')||',''si'') as precio,
                                            ''''::varchar as medico,
                                            sp.requiere_descripcion,
                                            um.id_unidad_medida,
                                            um.codigo as codigo_unidad_medida,
                                           ''''::varchar as ruta_foto
									from alm.titem it 
									inner join vef.tsucursal_producto sp on sp.id_item = it.id_item
                                    left join param.tunidad_medida um on um.id_unidad_medida = it.id_unidad_medida
									where sp.estado_reg = ''activo'' and it.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || '
									)';
				else
					v_consulta := 'with tabla_temporal as (
									select it.id_item as id_producto, ''producto_terminado''::varchar as tipo,
											(it.codigo || '' - '' || it.nombre)::varchar as nombre, it.descripcion::text,it.precio_ref as precio,''''::varchar as medico,
                                             ''''::varchar as requiere_descripcion,
                                            um.id_unidad_medida,
                                            um.codigo as codigo_unidad_medida,
                                            ''''::varchar as ruta_foto
									from alm.titem it 
                                    left join param.tunidad_medida um on um.id_unidad_medida = it.id_unidad_medida									
									where it.estado_reg = ''activo'' and
									(select s.clasificaciones_para_venta 
                                                from vef.tsucursal s 
                                                where s.id_sucursal = ' || v_sucursal.id_sucursal ||  '  ) && string_to_array(alm.f_get_id_clasificaciones(it.id_clasificacion,''padres''), '','')::integer[]
									)';
				
				end if;
			
			
			--Cocneptos de gasto para productos(sin integracion con almacenes) o servicios
			elsif (v_parametros.tipo = 'producto_terminado' or v_parametros.tipo = 'servicio') and  not v_ncd  then
				
                if (v_parametros.tipo = 'producto_terminado') then
					v_where = v_where || ' and  sp.tipo_producto = ''producto''';
					v_select = '''producto_terminado''::varchar as tipo';
				else
					v_where = v_where || ' and sp.tipo_producto = ''servicio''';
					v_select = '''servicio''::varchar as tipo';
				end if;			
				
				v_consulta := 'with tabla_temporal as (
									select sp.id_sucursal_producto as id_producto, ' || v_select || ' ,
											cig.desc_ingas as nombre, cig.descripcion_larga::text as descripcion,
											round(param.f_convertir_moneda(sp.id_moneda,' || v_id_moneda_venta || ',sp.precio,now()::date,'''||v_tipo||''',2,'||COALESCE(v_tipo_cambio_venta::varchar,'NULL')||',''si''),2) as precio,
											''''::varchar as medico,
                                            sp.requiere_descripcion,
											sp.contabilizable,
											sp.excento,
											um.id_unidad_medida,
                                            um.codigo as codigo_unidad_medida,
                                            COALESCE(cig.ruta_foto,'''')::varchar as ruta_foto
																		from vef.tsucursal_producto sp
									' || v_join || '
									inner join param.tconcepto_ingas cig on cig.id_concepto_ingas = sp.id_concepto_ingas
                                     left join param.tunidad_medida um on um.id_unidad_medida = cig.id_unidad_medida	
									where sp.estado_reg = ''activo'' and cig.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || v_where ||'
									)';
			
			elseif (v_ncd  ) then   --#123 son nostas de credito debito
            
                v_consulta := 'with tabla_temporal as (
								
                                      
                                      select
                                         vd.id_venta_detalle as id_producto,
                                         ''servicio''::varchar as tipo ,
                                         cig.desc_ingas as nombre,
                                         vd.descripcion::text as descripcion,
                                         (vd.cantidad*vd.precio)*0.5 as precio,
                                         vd.cantidad as cantidad, 
                                         
                                         ''''::varchar as medico,
                                         spd.requiere_descripcion,
										 spd.contabilizable,
										 spd.excento,
                                         um.id_unidad_medida,
                                         um.codigo as codigo_unidad_medida,
                                         COALESCE(cig.ruta_foto,'''')::varchar as ruta_foto
                                         
                                         
                                      from  vef.tsucursal_producto  spd
                                      inner join  vef.tventa_detalle vd on vd.id_sucursal_producto = spd.id_sucursal_producto and vd.id_venta = '|| v_id_venta_fk ||'
                                      inner join param.tconcepto_ingas cig on cig.id_concepto_ingas = spd.id_concepto_ingas
                                      left join param.tunidad_medida um on um.id_unidad_medida = cig.id_unidad_medida
                                      
                                      
									) ';
            
               
            
            else
			--Formulas o paquetes
            	v_having = '' ;            	
                v_having = 'having array_remove (array_agg(fd.id_concepto_ingas),NULL) <@ array_remove (array_agg(sp.id_concepto_ingas),NULL)';
				if (v_sucursal.tiene_precios_x_sucursal = 'si') then
                	v_select_precio_item = 'spi.precio';
                	v_join = v_join || ' 	left join alm.titem i on i.id_item = fd.id_item 
                    			left join vef.tsucursal_producto spi on spi.id_item = i.id_item 
                                	and spi.estado_reg = ''activo'' ';
                	v_having = v_having || ' and array_remove (array_agg(fd.id_item),NULL) <@ array_remove (array_agg(spi.id_item),NULL)';
                else
                	v_select_precio_item = 'i.precio_ref';
                	v_join = v_join || ' left join alm.titem i on i.id_item = fd.id_item ';
                end if;
                
				v_consulta := 'with tabla_temporal as (
								select form.id_formula as id_producto, ''formula''::varchar as tipo,
										form.nombre, form.descripcion::text,
                                        sum(fd.cantidad * (case when fd.id_item is not null then 
                                        						' || v_select_precio_item || '
                                        					else
                                                            	sp.precio
                                                            end))::numeric as precio,
                                        med.nombre_completo::varchar as medico,
                                        ''''::varchar as requiere_descripcion,
										''''::varchar as contabilizable,
										''''::varchar as excento,
										um.id_unidad_medida,
                                        um.codigo as codigo_unidad_medida,
                                        ''''::varchar as ruta_foto
								from vef.tformula form
								left join vef.vmedico med on med.id_medico = form.id_medico
								inner join vef.tformula_detalle fd on fd.id_formula = form.id_formula
                                left join param.tunidad_medida um on um.id_unidad_medida = form.id_unidad_medida
                                left join vef.tsucursal_producto sp on sp.id_concepto_ingas = fd.id_concepto_ingas 
                                	and sp.estado_reg = ''activo'' 
                        		' || v_join || '
								where form.estado_reg = ''activo'' and fd.estado_reg = ''activo'' ' || v_where || '
								group by form.id_formula,
								form.nombre, form.descripcion,
								med.nombre_completo,
                                um.id_unidad_medida,
                                um.codigo
                                ' || v_having || ')';
			
			end if;
			v_consulta = v_consulta ||	' 	select todo.id_producto,todo.tipo,todo.nombre,
													todo.descripcion,
													todo.precio,
													todo.medico,
													todo.requiere_descripcion,
													todo.contabilizable,
													todo.excento,
													todo.id_unidad_medida,
                                                   todo.codigo_unidad_medida,
                                                   todo.ruta_foto
											from tabla_temporal todo
											where ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice  '%',v_consulta;
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
    		
    		v_where = '';
            v_join = '';
            v_ncd = false; --#123
                        
            --#123   adciona logica para cocnepto de gastos para notas de credito, el detaqlle viene de la factura previa seleccionada
            if (pxp.f_existe_parametro(p_tabla,'id_venta_fk')) then
               v_ncd = true;
               v_id_venta_fk = v_parametros.id_venta_fk;
            end if;
            
    		if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
    			select suc.*,sucmon.id_moneda into v_sucursal
    			from vef.tpunto_venta pv
    			inner join vef.tsucursal suc on suc.id_sucursal = pv.id_sucursal
    			inner join vef.tsucursal_moneda sucmon on sucmon.id_sucursal = suc.id_sucursal 
    										and sucmon.tipo_moneda = 'moneda_base'
    			where pv.id_punto_venta = v_parametros.id_punto_venta;
    			
    			--anadir join de punto de venta producto
                v_join = 'left join vef.tpunto_venta_producto pvp on pvp.id_sucursal_producto = sp.id_sucursal_producto and pvp.estado_reg = ''activo''  ';
                
                --anadir filtro de punto de venta
                v_where = ' and pvp.id_punto_venta = ' || v_parametros.id_punto_venta || '  ';
    		else
    			select suc.*,sucmon.id_moneda into v_sucursal
    			from vef.tsucursal suc
    			inner join vef.tsucursal_moneda sucmon on sucmon.id_sucursal = suc.id_sucursal 
    										and sucmon.tipo_moneda = 'moneda_base'
    			where suc.id_sucursal = v_parametros.id_sucursal;
    			
    		end if;
    		
    		--Items si se integra con almacenes
    		if (v_parametros.tipo = 'producto_terminado' and pxp.f_get_variable_global('vef_integracion_almacenes') = 'true') and not v_ncd then
				if (v_sucursal.tiene_precios_x_sucursal = 'si') then
					v_consulta := 'with tabla_temporal as (
									select it.id_item as id_producto, ''producto_terminado''::varchar as tipo,
											(it.codigo || '' - '' || it.nombre)::varchar as nombre, it.descripcion::text,
                                            1 as precio,
                                            ''''::varchar as medico,
                                            sp.requiere_descripcion
									from alm.titem it 
									inner join vef.tsucursal_producto sp on sp.id_item = it.id_item
									where sp.estado_reg = ''activo'' and it.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || '
									)';
				else
					v_consulta := 'with tabla_temporal as (
									select it.id_item as id_producto, ''producto_terminado''::varchar as tipo,
											(it.codigo || '' - '' || it.nombre)::varchar as nombre, it.descripcion::text,it.precio_ref as precio,''''::varchar as medico,
                                             ''''::varchar as requiere_descripcion
									from alm.titem it 									
									where it.estado_reg = ''activo'' and
									(select s.clasificaciones_para_venta 
                                                from vef.tsucursal s 
                                                where s.id_sucursal = ' || v_sucursal.id_sucursal ||  '  ) && string_to_array(alm.f_get_id_clasificaciones(it.id_clasificacion,''padres''), '','')::integer[]
									)';
				
				end if;
			
			
			--Cocneptos de gasto para productos(sin integracion con almacenes) o servicios
			elsif (v_parametros.tipo = 'producto_terminado' or v_parametros.tipo = 'servicio')  and not v_ncd  then
				
                if (v_parametros.tipo = 'producto_terminado') then
					v_where = v_where || ' and  sp.tipo_producto = ''producto''';
					v_select = '''producto_terminado''::varchar as tipo';
				else
					v_where = v_where || ' and sp.tipo_producto = ''servicio''';
					v_select = '''servicio''::varchar as tipo';
				end if;			
				
				v_consulta := 'with tabla_temporal as (
									select sp.id_sucursal_producto as id_producto, ' || v_select || ' ,
											cig.desc_ingas as nombre, cig.descripcion_larga::text as descripcion,
											1 as precio,
											''''::varchar as medico,
                                            sp.requiere_descripcion
									from vef.tsucursal_producto sp
									' || v_join || '
									inner join param.tconcepto_ingas cig on cig.id_concepto_ingas = sp.id_concepto_ingas
									where sp.estado_reg = ''activo'' and cig.estado_reg = ''activo'' and
									sp.id_sucursal = ' || v_sucursal.id_sucursal || v_where ||'
									)';
			
			elseif (v_ncd  ) then   --#123 son nostas de credito debito
            
                v_consulta := 'with tabla_temporal as (
								
                                      
                                      select
                                         vd.id_venta_detalle as id_producto,
                                         ''servicio''::varchar as tipo ,
                                         cig.desc_ingas as nombre,
                                         vd.descripcion::text as descripcion,
                                         (vd.cantidad*vd.precio)*0.5 as precio,
                                         vd.cantidad as cantidad, 
                                         
                                         ''''::varchar as medico,
                                         spd.requiere_descripcion,
										 spd.contabilizable,
										 spd.excento,
                                         um.id_unidad_medida,
                                         um.codigo as codigo_unidad_medida,
                                         COALESCE(cig.ruta_foto,'''')::varchar as ruta_foto
                                         
                                         
                                      from  vef.tsucursal_producto  spd
                                      inner join  vef.tventa_detalle vd on vd.id_sucursal_producto = spd.id_sucursal_producto and vd.id_venta = '|| v_id_venta_fk ||'
                                      inner join param.tconcepto_ingas cig on cig.id_concepto_ingas = spd.id_concepto_ingas
                                      left join param.tunidad_medida um on um.id_unidad_medida = cig.id_unidad_medida
                                      
                                      
									) ';
            
            else
			--Formulas o paquetes
            	v_having = '' ;            	
                v_having = 'having array_remove (array_agg(fd.id_concepto_ingas),NULL) <@ array_remove (array_agg(sp.id_concepto_ingas),NULL)';
				if (v_sucursal.tiene_precios_x_sucursal = 'si') then
                	v_select_precio_item = 'spi.precio';
                	v_join = v_join || ' 	left join alm.titem i on i.id_item = fd.id_item 
                    			left join vef.tsucursal_producto spi on spi.id_item = i.id_item 
                                	and spi.estado_reg = ''activo'' ';
                	v_having = v_having || ' and array_remove (array_agg(fd.id_item),NULL) <@ array_remove (array_agg(spi.id_item),NULL)';
                else
                	v_select_precio_item = 'i.precio_ref';
                	v_join = v_join || ' left join alm.titem i on i.id_item = fd.id_item ';
                end if;
                
				v_consulta := 'with tabla_temporal as (
								select form.id_formula as id_producto, ''formula''::varchar as tipo,
										form.nombre, form.descripcion::text,
                                        sum(fd.cantidad * (case when fd.id_item is not null then 
                                        						' || v_select_precio_item || '
                                        					else
                                                            	sp.precio
                                                            end))::numeric as precio,
                                        med.nombre_completo::varchar as medico,
                                        ''''::varchar as requiere_descripcion
								from vef.tformula form
								left join vef.vmedico med on med.id_medico = form.id_medico
								inner join vef.tformula_detalle fd on fd.id_formula = form.id_formula
                                left join vef.tsucursal_producto sp on sp.id_concepto_ingas = fd.id_concepto_ingas 
                                	and sp.estado_reg = ''activo'' 
                        		' || v_join || '
								where form.estado_reg = ''activo'' and fd.estado_reg = ''activo'' ' || v_where || '
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
                                            um.descripcion as unidad_medida
									from param.tconcepto_ingas cig 
									left join param.tunidad_medida um on (um.id_unidad_medida = cig.id_unidad_medida)
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
