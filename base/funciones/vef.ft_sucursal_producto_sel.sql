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
						sprod.descripcion_producto,
						sprod.precio,
						sprod.nombre_producto,
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
                        item.nombre	
						from vef.tsucursal_producto sprod
						inner join segu.tusuario usu1 on usu1.id_usuario = sprod.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = sprod.id_usuario_mod
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