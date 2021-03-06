CREATE OR REPLACE FUNCTION vef.ft_sucursal_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tsucursal'
 AUTOR: 		 (admin)
 FECHA:	        20-04-2015 15:07:50
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
			    
BEGIN

	v_nombre_funcion = 'vef.ft_sucursal_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SUC_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 15:07:50
	***********************************/

	if(p_transaccion='VF_SUC_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						suc.id_sucursal,
						suc.id_entidad,
						suc.correo,
						suc.nombre,
						suc.telefono,
						suc.tiene_precios_x_sucursal,
						suc.estado_reg,
						array_to_string(suc.clasificaciones_para_formula,'''',''''),
						suc.codigo,
						array_to_string(suc.clasificaciones_para_venta,'''',''''),
						suc.id_usuario_ai,
						suc.id_usuario_reg,
						suc.usuario_ai,
						suc.fecha_reg,
						suc.id_usuario_mod,
						suc.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        (select pxp.list(c.nombre)
                        from alm.tclasificacion c
                        where id_clasificacion = ANY(suc.clasificaciones_para_formula))::varchar as desc_clasificaciones_para_formula,
                        (select pxp.list(c.nombre)
                        from alm.tclasificacion c
                        where id_clasificacion = ANY(suc.clasificaciones_para_venta))::varchar as desc_clasificaciones_para_venta 	
						,suc.plantilla_documento_factura
						,suc.plantilla_documento_recibo,
						suc.formato_comprobante,
						suc.direccion,
						suc.lugar,
                        suc.habilitar_comisiones,
                        suc.id_lugar,
                        lug.nombre as nombre_lugar,
                        array_to_string(suc.tipo_interfaz,'','') as tipo_interfaz,
                        dep.id_depto,
                        dep.nombre	as nombre_depto	,
                        suc.nombre_comprobante		
                        from vef.tsucursal suc
						inner join segu.tusuario usu1 on usu1.id_usuario = suc.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = suc.id_usuario_mod
                        left join param.tlugar lug on lug.id_lugar = suc.id_lugar
                        left join param.tdepto dep on dep.id_depto = suc.id_depto
				        where  ';
			
           
            
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUC_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 15:07:50
	***********************************/

	elsif(p_transaccion='VF_SUC_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_sucursal)
					    from vef.tsucursal suc
					    inner join segu.tusuario usu1 on usu1.id_usuario = suc.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = suc.id_usuario_mod
                        left join param.tlugar lug on lug.id_lugar = suc.id_lugar
                        left join param.tdepto dep on dep.id_depto = suc.id_depto
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