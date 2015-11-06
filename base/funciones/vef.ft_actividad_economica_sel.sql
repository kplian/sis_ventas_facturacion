CREATE OR REPLACE FUNCTION "vef"."ft_actividad_economica_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_actividad_economica_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tactividad_economica'
 AUTOR: 		 (jrivera)
 FECHA:	        06-10-2015 21:23:23
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

	v_nombre_funcion = 'vef.ft_actividad_economica_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_ACTECO_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		jrivera	
 	#FECHA:		06-10-2015 21:23:23
	***********************************/

	if(p_transaccion='VF_ACTECO_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						acteco.id_actividad_economica,
						acteco.codigo,
						acteco.estado_reg,
						acteco.descripcion,
						acteco.nombre,
						acteco.fecha_reg,
						acteco.usuario_ai,
						acteco.id_usuario_reg,
						acteco.id_usuario_ai,
						acteco.fecha_mod,
						acteco.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from vef.tactividad_economica acteco
						inner join segu.tusuario usu1 on usu1.id_usuario = acteco.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = acteco.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_ACTECO_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		jrivera	
 	#FECHA:		06-10-2015 21:23:23
	***********************************/

	elsif(p_transaccion='VF_ACTECO_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_actividad_economica)
					    from vef.tactividad_economica acteco
					    inner join segu.tusuario usu1 on usu1.id_usuario = acteco.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = acteco.id_usuario_mod
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
ALTER FUNCTION "vef"."ft_actividad_economica_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
