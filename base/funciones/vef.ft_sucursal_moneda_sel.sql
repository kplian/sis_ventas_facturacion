CREATE OR REPLACE FUNCTION "vef"."ft_sucursal_moneda_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_moneda_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tsucursal_moneda'
 AUTOR: 		 (admin)
 FECHA:	        22-09-2015 06:11:27
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

	v_nombre_funcion = 'vef.ft_sucursal_moneda_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SUCMON_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		22-09-2015 06:11:27
	***********************************/

	if(p_transaccion='VF_SUCMON_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						sucmon.id_sucursal_moneda,
						sucmon.id_moneda,
						sucmon.id_sucursal,
						sucmon.estado_reg,
						sucmon.tipo_moneda,
						sucmon.id_usuario_ai,
						sucmon.id_usuario_reg,
						sucmon.fecha_reg,
						sucmon.usuario_ai,
						sucmon.id_usuario_mod,
						sucmon.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						mon.codigo as desc_moneda	
						from vef.tsucursal_moneda sucmon
						inner join segu.tusuario usu1 on usu1.id_usuario = sucmon.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = sucmon.id_usuario_mod
						inner join param.tmoneda mon on mon.id_moneda = sucmon.id_moneda
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUCMON_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		22-09-2015 06:11:27
	***********************************/

	elsif(p_transaccion='VF_SUCMON_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_sucursal_moneda)
					    from vef.tsucursal_moneda sucmon
					    inner join segu.tusuario usu1 on usu1.id_usuario = sucmon.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = sucmon.id_usuario_mod
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
ALTER FUNCTION "vef"."ft_sucursal_moneda_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
