CREATE OR REPLACE FUNCTION vef.ft_tipo_venta_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_tipo_venta_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.ttipo_venta'
 AUTOR: 		 (jrivera)
 FECHA:	        22-03-2016 15:29:00
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

	v_nombre_funcion = 'vef.ft_tipo_venta_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_TIPVEN_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 15:29:00
	***********************************/

	if(p_transaccion='VF_TIPVEN_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						tipven.id_tipo_venta,
						tipven.codigo_relacion_contable,
						tipven.nombre,
						tipven.tipo_base,
						tipven.estado_reg,
						tipven.codigo,
						tipven.id_usuario_ai,
						tipven.id_usuario_reg,
						tipven.fecha_reg,
						tipven.usuario_ai,
						tipven.id_usuario_mod,
						tipven.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						tipven.id_plantilla,
						pla.desc_plantilla	
						from vef.ttipo_venta tipven
						inner join segu.tusuario usu1 on usu1.id_usuario = tipven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = tipven.id_usuario_mod
						left join param.tplantilla pla on pla.id_plantilla = tipven.id_plantilla
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--raise exception '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_TIPVEN_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 15:29:00
	***********************************/

	elsif(p_transaccion='VF_TIPVEN_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_tipo_venta)
					    from vef.ttipo_venta tipven
					    inner join segu.tusuario usu1 on usu1.id_usuario = tipven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = tipven.id_usuario_mod
						left join param.tplantilla pla on pla.id_plantilla = tipven.id_plantilla
					    
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