CREATE OR REPLACE FUNCTION vef.ft_formula_detalle_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_formula_detalle_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tformula_detalle'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 13:16:56
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_consulta    				varchar;
	v_parametros  				record;
	v_nombre_funcion   			text;
	v_resp						varchar;
    v_porcentaje_descuento		integer;
    v_id_vendedor_medico		varchar;
    v_nombre_vendedor_medico	varchar;
    v_sucursal					record;
    v_select_precio_item		varchar;
    v_join						varchar;
    v_id_vendedor				integer;
    v_id_medico					integer;
    v_filtro					varchar;
    v_id_sucursal				integer;
			    
BEGIN

	v_nombre_funcion = 'vef.ft_formula_detalle_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_FORDET_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 13:16:56
	***********************************/

	if(p_transaccion='VF_FORDET_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						fordet.id_formula_detalle,
						(case  when fordet.id_item is not null then
                        	fordet.id_item
                        else
                        	fordet.id_concepto_ingas
                        end) as id_producto,                        
						fordet.id_formula,
						fordet.cantidad,
						fordet.estado_reg,
						fordet.fecha_reg,
						fordet.usuario_ai,
						fordet.id_usuario_reg,
						fordet.id_usuario_ai,
						fordet.fecha_mod,
						fordet.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						(case  when fordet.id_item is not null then
                        	item.codigo  || '' - '' ||  item.nombre
                        else
                        	cig.desc_ingas
                        end)::varchar as nombre_producto,
                        (case  when fordet.id_item is not null then
                        	''item''::varchar
                        else
                        	''producto_servicio''::varchar
                        end) as tipo,
            (case when item.id_item is not null then
              umit.descripcion
            else
              umcig.descripcion
            end) as unidad_medida
						from vef.tformula_detalle fordet
						inner join segu.tusuario usu1 on usu1.id_usuario = fordet.id_usuario_reg
						left join alm.titem item on item.id_item = fordet.id_item
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = fordet.id_concepto_ingas
            left join param.tunidad_medida umcig on umcig.id_unidad_medida = cig.id_unidad_medida
            left join param.tunidad_medida umit on umit.id_unidad_medida = item.id_unidad_medida
						left join segu.tusuario usu2 on usu2.id_usuario = fordet.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_FORDET_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 13:16:56
	***********************************/

	elsif(p_transaccion='VF_FORDET_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_formula_detalle)
					    from vef.tformula_detalle fordet
					    inner join segu.tusuario usu1 on usu1.id_usuario = fordet.id_usuario_reg
					    left join alm.titem item on item.id_item = fordet.id_item
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = fordet.id_concepto_ingas
						left join segu.tusuario usu2 on usu2.id_usuario = fordet.id_usuario_mod
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    	/*********************************    
        #TRANSACCION:  'VF_FORDETINS_SEL'
        #DESCRIPCION:	Seleccion de detalle de formula para insercion a detalle de formulario de venta
        #AUTOR:		admin	
        #FECHA:		21-04-2015 13:16:56
        ***********************************/

	elsif(p_transaccion='VF_FORDETINS_SEL')then
    	begin
        	v_filtro = '';
            v_join = '';
        	if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
    			select suc.*,sucmon.id_moneda into v_sucursal
    			from vef.tpunto_venta pv
    			inner join vef.tsucursal suc on suc.id_sucursal = pv.id_sucursal
    			inner join vef.tsucursal_moneda sucmon on sucmon.id_sucursal = suc.id_sucursal 
    										and sucmon.tipo_moneda = 'moneda_base'
    			where pv.id_punto_venta = v_parametros.id_punto_venta;
                
                --anadir join de punto de venta producto
                v_join = 'inner join vef.tpunto_venta_producto pvp on pvp.id_sucursal_producto = spc.id_sucursal_producto ';
                
                --anadir filtro de punto de venta
                v_filtro = ' pvp.id_punto_venta = ' || v_parametros.id_punto_venta || ' and ';
    		else
    			select suc.*,sucmon.id_moneda into v_sucursal
    			from vef.tsucursal suc
    			inner join vef.tsucursal_moneda sucmon on sucmon.id_sucursal = suc.id_sucursal 
    										and sucmon.tipo_moneda = 'moneda_base'
    			where suc.id_sucursal = v_parametros.id_sucursal;
    			v_filtro = ' spc.id_sucursal = ' || v_parametros.id_sucursal || ' and ';
    		end if;
            
            --verificar si existe vendedor o medico
            v_id_vendedor = NULL;
            v_id_medico = NULL;
            v_id_vendedor_medico = '';
            v_nombre_vendedor_medico = '';
            v_porcentaje_descuento = 0;
            if (pxp.f_existe_parametro(p_tabla,'porcentaje_descuento')) then
            	v_porcentaje_descuento = v_parametros.porcentaje_descuento;
            end if;
            
            if (pxp.f_existe_parametro(p_tabla,'id_vendedor_medico')) then
            	v_id_vendedor_medico = v_parametros.id_vendedor_medico;
        		if (split_part(v_parametros.id_vendedor_medico,'_',2) = 'usuario') then
                	v_id_vendedor =  split_part(v_parametros.id_vendedor_medico::text,'_'::text,1)::integer;
                else
                	v_id_medico =  split_part(v_parametros.id_vendedor_medico::text,'_'::text,1)::integer;
                end if;
        	end if;
            
            if (v_id_vendedor is not null) then
            
            	select u.desc_persona into v_nombre_vendedor_medico
                from segu.vusuario u
                where id_usuario = v_id_vendedor;
            end if;
            
            if (v_id_medico is not null) then
            	select m.nombre_completo into v_nombre_vendedor_medico
                from vef.vmedico m
                where id_medico = v_id_medico;
            end if;
            
    		if (v_sucursal.tiene_precios_x_sucursal = 'si') then
                v_select_precio_item = 'spi.precio';
                v_join = v_join || ' 	left join alm.titem i on i.id_item = fd.id_item 
                            left join vef.tsucursal_producto spi on spi.id_item = i.id_item 
                                and spi.estado_reg = ''activo'' ';
                
            else
                v_select_precio_item = 'coalesce (i.precio_ref,0)';
                v_join = v_join || ' left join alm.titem i on i.id_item = fd.id_item ';
            end if;
            
            --raise exception 'llega%,%',v_filtro,v_join;
                
				v_consulta := '
								select (case when fd.id_item is not null then
                                      fd.id_item
                                  when spc.id_sucursal_producto is not null then
                                      spc.id_sucursal_producto						
                                  end) as id_producto,
								(case when fd.id_item is not null then
                                      ''producto_terminado''
                                  when spc.id_sucursal_producto is not null and spc.tipo_producto = ''servicio'' then
                                      ''servicio''	
                                  when spc.id_sucursal_producto is not null and spc.tipo_producto = ''producto'' then
                                      ''producto_terminado''							
                                  end)::varchar as tipo, 
                                  
                                (case when fd.id_item is not null then
                                      i.codigo  || '' - '' ||  i.nombre
                                  when spc.id_sucursal_producto is not null then
                                      cig.desc_ingas						
                                  end)::varchar as nombre_producto, 
                                  (case when fd.id_item is not null then
                                      i.descripcion
                                  when spc.id_sucursal_producto is not null then
                                      cig.descripcion_larga						
                                  end)::text as descripcion,
                                  fd.cantidad, 
                                  (case when fd.id_item is not null then 
                                      ' || v_select_precio_item || '
                                  else
                                      param.f_convertir_moneda(spc.id_moneda,' || v_sucursal.id_moneda || ',spc.precio,now()::date,''O'',2,NULL,''si'')
                                  end)::numeric as precio_unitario,
                                  (fd.cantidad * (case when fd.id_item is not null then 
                                      ' || v_select_precio_item || '
                                  else
                                      param.f_convertir_moneda(spc.id_moneda,' || v_sucursal.id_moneda || ',spc.precio,now()::date,''O'',2,NULL,''si'')
                                  end))::numeric as precio_total_sin_descuento,
                                  ' || v_porcentaje_descuento || '::integer as porcentaje_descuento,
                                  (fd.cantidad * (case when fd.id_item is not null then 
                                      ' || v_select_precio_item || '
                                  else
                                      param.f_convertir_moneda(spc.id_moneda,' || v_sucursal.id_moneda || ',spc.precio,now()::date,''O'',2,NULL,''si'')
                                  end) * (100 - ' || v_porcentaje_descuento || ')/100)::numeric as precio_total,
                                  ''' || v_id_vendedor_medico || '''::varchar as id_vendedor_medico,
                                  ''' || v_nombre_vendedor_medico || '''::varchar as nombre_vendedor_medico,
                                  spc.contabilizable, spc.excento
								from vef.tformula form
								left join vef.vmedico med on med.id_medico = form.id_medico
								inner join vef.tformula_detalle fd on fd.id_formula = form.id_formula
                                left join param.tconcepto_ingas cig on cig.id_concepto_ingas = fd.id_concepto_ingas 
                                left join vef.tsucursal_producto spc on spc.id_concepto_ingas = fd.id_concepto_ingas 
                                	and spc.estado_reg = ''activo'' 
                        		' || v_join || '
								where form.estado_reg = ''activo'' and fd.estado_reg = ''activo'' and ' || v_filtro ;
                
                --Devuelve la respuesta
                
                
                v_consulta:=v_consulta||v_parametros.filtro;
                
                raise notice '%',v_consulta;
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