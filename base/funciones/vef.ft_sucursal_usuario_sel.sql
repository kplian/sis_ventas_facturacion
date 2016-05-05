CREATE OR REPLACE FUNCTION "vef"."ft_sucursal_usuario_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_usuario_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tsucursal_usuario'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 07:33:37
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

	v_nombre_funcion = 'vef.ft_sucursal_usuario_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SUCUSU_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:37
	***********************************/

	if(p_transaccion='VF_SUCUSU_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						sucusu.id_sucursal_usuario,
						sucusu.id_sucursal,
						sucusu.id_punto_venta,
						sucusu.id_usuario,
						sucusu.estado_reg,
						sucusu.tipo_usuario,
						sucusu.id_usuario_ai,
						sucusu.id_usuario_reg,
						sucusu.fecha_reg,
						sucusu.usuario_ai,
						sucusu.id_usuario_mod,
						sucusu.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						usu.cuenta		
						from vef.tsucursal_usuario sucusu
						inner join segu.tusuario usu1 on usu1.id_usuario = sucusu.id_usuario_reg
						inner join segu.tusuario usu on usu.id_usuario = sucusu.id_usuario
						left join segu.tusuario usu2 on usu2.id_usuario = sucusu.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUCUSU_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:37
	***********************************/

	elsif(p_transaccion='VF_SUCUSU_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_sucursal_usuario)
					    from vef.tsucursal_usuario sucusu
					    inner join segu.tusuario usu1 on usu1.id_usuario = sucusu.id_usuario_reg
					    inner join segu.tusuario usu on usu.id_usuario = sucusu.id_usuario
						left join segu.tusuario usu2 on usu2.id_usuario = sucusu.id_usuario_mod
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
ALTER FUNCTION "vef"."ft_sucursal_usuario_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
