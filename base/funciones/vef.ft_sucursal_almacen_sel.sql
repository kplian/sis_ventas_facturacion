CREATE OR REPLACE FUNCTION "vef"."ft_sucursal_almacen_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_almacen_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tsucursal_almacen'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 07:33:41
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

	v_nombre_funcion = 'vef.ft_sucursal_almacen_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SUCALM_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:41
	***********************************/

	if(p_transaccion='VF_SUCALM_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						sucalm.id_sucursal_almacen,
						sucalm.id_sucursal,
						sucalm.id_almacen,
						sucalm.tipo_almacen,
						sucalm.estado_reg,
						sucalm.id_usuario_ai,
						sucalm.fecha_reg,
						sucalm.usuario_ai,
						sucalm.id_usuario_reg,
						sucalm.fecha_mod,
						sucalm.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						alm.nombre	
						from vef.tsucursal_almacen sucalm
						inner join segu.tusuario usu1 on usu1.id_usuario = sucalm.id_usuario_reg
						inner join alm.talmacen alm on alm.id_almacen = sucalm.id_almacen
						left join segu.tusuario usu2 on usu2.id_usuario = sucalm.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUCALM_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:41
	***********************************/

	elsif(p_transaccion='VF_SUCALM_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_sucursal_almacen)
					    from vef.tsucursal_almacen sucalm
					    inner join segu.tusuario usu1 on usu1.id_usuario = sucalm.id_usuario_reg
					    inner join alm.talmacen alm on alm.id_almacen = sucalm.id_almacen
						left join segu.tusuario usu2 on usu2.id_usuario = sucalm.id_usuario_mod
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
ALTER FUNCTION "vef"."ft_sucursal_almacen_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
