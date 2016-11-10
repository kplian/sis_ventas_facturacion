CREATE OR REPLACE FUNCTION "vef"."ft_proceso_venta_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_proceso_venta_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tproceso_venta'
 AUTOR: 		 (jrivera)
 FECHA:	        22-03-2016 21:50:14
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

	v_nombre_funcion = 'vef.ft_proceso_venta_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_PROCON_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 21:50:14
	***********************************/

	if(p_transaccion='VF_PROCON_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						procon.id_proceso_venta,
						procon.tipos,
						procon.estado_reg,
						procon.fecha_desde,
						procon.id_int_comprobante,
						procon.fecha_hasta,
						procon.estado,
						procon.usuario_ai,
						procon.fecha_reg,
						procon.id_usuario_reg,
						procon.id_usuario_ai,
						procon.id_usuario_mod,
						procon.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from vef.tproceso_venta procon
						inner join segu.tusuario usu1 on usu1.id_usuario = procon.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = procon.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_PROCON_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 21:50:14
	***********************************/

	elsif(p_transaccion='VF_PROCON_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_proceso_venta)
					    from vef.tproceso_venta procon
					    inner join segu.tusuario usu1 on usu1.id_usuario = procon.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = procon.id_usuario_mod
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
ALTER FUNCTION "vef"."ft_proceso_venta_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
