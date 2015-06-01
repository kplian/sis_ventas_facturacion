CREATE OR REPLACE FUNCTION vef.ft_formula_detalle_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_formula_detalle_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tformula_detalle'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 13:16:56
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

	v_nombre_funcion = 'vef.ft_formula_detalle_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_FORDET_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 13:16:56
	***********************************/

	if(p_transaccion='VF_FORDET_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						fordet.id_formula_detalle,
						fordet.id_item,
						fordet.id_formula,
						fordet.cantidad,
						fordet.estado_reg,
						fordet.fecha_reg,
						fordet.usuario_ai,
						fordet.id_usuario_reg,
						fordet.id_usuario_ai,
						fordet.fecha_mod,
						fordet.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						item.nombre,
                        item.precio_ref,
                        item.precio_ref * fordet.cantidad	
						from vef.tformula_detalle fordet
						inner join segu.tusuario usu1 on usu1.id_usuario = fordet.id_usuario_reg
						inner join alm.titem item on item.id_item = fordet.id_item
						left join segu.tusuario usu2 on usu2.id_usuario = fordet.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_FORDET_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 13:16:56
	***********************************/

	elsif(p_transaccion='VF_FORDET_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_formula_detalle)
					    from vef.tformula_detalle fordet
					    inner join segu.tusuario usu1 on usu1.id_usuario = fordet.id_usuario_reg
					    inner join alm.titem item on item.id_item = fordet.id_item
						left join segu.tusuario usu2 on usu2.id_usuario = fordet.id_usuario_mod
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