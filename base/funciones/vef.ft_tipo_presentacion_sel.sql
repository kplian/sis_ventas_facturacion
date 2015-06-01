CREATE OR REPLACE FUNCTION "vef"."ft_tipo_presentacion_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_tipo_presentacion_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.ttipo_presentacion'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 09:00:49
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

	v_nombre_funcion = 'vef.ft_tipo_presentacion_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_TIPRE_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:00:49
	***********************************/

	if(p_transaccion='VF_TIPRE_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						tipre.id_tipo_presentacion,
						tipre.estado_reg,
						tipre.nombre,
						tipre.id_usuario_ai,
						tipre.id_usuario_reg,
						tipre.fecha_reg,
						tipre.usuario_ai,
						tipre.id_usuario_mod,
						tipre.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from vef.ttipo_presentacion tipre
						inner join segu.tusuario usu1 on usu1.id_usuario = tipre.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = tipre.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_TIPRE_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:00:49
	***********************************/

	elsif(p_transaccion='VF_TIPRE_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_tipo_presentacion)
					    from vef.ttipo_presentacion tipre
					    inner join segu.tusuario usu1 on usu1.id_usuario = tipre.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = tipre.id_usuario_mod
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
ALTER FUNCTION "vef"."ft_tipo_presentacion_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
