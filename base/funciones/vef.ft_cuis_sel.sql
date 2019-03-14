CREATE OR REPLACE FUNCTION "vef"."ft_cuis_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_cuis_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tcuis'
 AUTOR: 		 (admin)
 FECHA:	        21-01-2019 15:18:39
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-01-2019 15:18:39								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tcuis'	
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'vef.ft_cuis_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_CUIS_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-01-2019 15:18:39
	***********************************/

	if(p_transaccion='VF_CUIS_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						cuis.id_cuis,
						cuis.codigo,
						cuis.fecha_fin,
						cuis.estado_reg,
						cuis.fecha_inicio,
						cuis.id_usuario_ai,
						cuis.id_usuario_reg,
						cuis.fecha_reg,
						cuis.usuario_ai,
						cuis.id_usuario_mod,
						cuis.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from vef.tcuis cuis
						inner join segu.tusuario usu1 on usu1.id_usuario = cuis.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cuis.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_CUIS_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-01-2019 15:18:39
	***********************************/

	elsif(p_transaccion='VF_CUIS_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_cuis)
					    from vef.tcuis cuis
					    inner join segu.tusuario usu1 on usu1.id_usuario = cuis.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cuis.id_usuario_mod
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
ALTER FUNCTION "vef"."ft_cuis_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
