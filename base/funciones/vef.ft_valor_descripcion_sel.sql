--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.ft_valor_descripcion_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_valor_descripcion_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tvalor_descripcion'
 AUTOR: 		 (admin)
 FECHA:	        23-04-2016 14:24:45
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

	v_nombre_funcion = 'vef.ft_valor_descripcion_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_vald_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		23-04-2016 14:24:45
	***********************************/

	if(p_transaccion='VF_vald_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='  select
                              vald.id_valor_descripcion,
                              vald.estado_reg,
                              vald.valor,
                              vald.id_tipo_descripcion,
                              vald.obs,
                              vald.id_venta,
                              vald.id_usuario_reg,
                              vald.fecha_reg,
                              vald.usuario_ai,
                              vald.id_usuario_ai,
                              vald.id_usuario_mod,
                              vald.fecha_mod,
                              usu1.cuenta as usr_reg,
                              usu2.cuenta as usr_mod,
                              td.codigo,
                              td.nombre,
                              td.columna,
                              td.fila,
                              td.obs as obs_tipo,
                              vald.valor_label
                            from vef.tvalor_descripcion vald
                            inner join vef.ttipo_descripcion td on td.id_tipo_descripcion = vald.id_tipo_descripcion
                            inner join segu.tusuario usu1 on usu1.id_usuario = vald.id_usuario_reg
                            left join segu.tusuario usu2 on usu2.id_usuario = vald.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_vald_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		23-04-2016 14:24:45
	***********************************/

	elsif(p_transaccion='VF_vald_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(vald.id_valor_descripcion)
					    from vef.tvalor_descripcion vald
                            inner join vef.ttipo_descripcion td on td.id_tipo_descripcion = vald.id_tipo_descripcion
                            inner join segu.tusuario usu1 on usu1.id_usuario = vald.id_usuario_reg
                            left join segu.tusuario usu2 on usu2.id_usuario = vald.id_usuario_mod
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