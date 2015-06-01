CREATE OR REPLACE FUNCTION "vef"."ft_venta_detalle_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_venta_detalle_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tventa_detalle'
 AUTOR: 		 (admin)
 FECHA:	        01-06-2015 09:21:07
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

	v_nombre_funcion = 'vef.ft_venta_detalle_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_VEDET_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	if(p_transaccion='VF_VEDET_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						vedet.id_venta_detalle,
						vedet.id_venta,
						vedet.id_item,
						vedet.id_sucursal_producto,
						vedet.id_formula,
						vedet.tipo,
						vedet.estado_reg,
						vedet.cantidad,
						vedet.precio,
						vedet.sw_porcentaje_formula,
						vedet.id_usuario_ai,
						vedet.usuario_ai,
						vedet.fecha_reg,
						vedet.id_usuario_reg,
						vedet.id_usuario_mod,
						vedet.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						vedet.precio*vedet.cantidad,
						item.nombre as nombre_item,
						form.nombre as nombre_formula,
						sprod.nombre_producto	as nombre_producto
						from vef.tventa_detalle vedet
						inner join segu.tusuario usu1 on usu1.id_usuario = vedet.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = vedet.id_usuario_mod
						left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
						left join vef.tformula form on form.id_formula = vedet.id_formula
						left join alm.titem item on item.id_item = vedet.id_item
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VEDET_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	elsif(p_transaccion='VF_VEDET_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_venta_detalle)
					    from vef.tventa_detalle vedet
					    inner join segu.tusuario usu1 on usu1.id_usuario = vedet.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = vedet.id_usuario_mod
						left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
						left join vef.tformula form on form.id_formula = vedet.id_formula
						left join alm.titem item on item.id_item = vedet.id_item
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
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "vef"."ft_venta_detalle_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
