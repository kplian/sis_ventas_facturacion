CREATE OR REPLACE FUNCTION vef.ft_venta_detalle_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_venta_detalle_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tventa_detalle'
 AUTOR: 		 (admin)
 FECHA:	        01-06-2015 09:21:07
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

	v_nombre_funcion = 'vef.ft_venta_detalle_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_VEDET_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	if(p_transaccion='VF_VEDET_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						vedet.id_venta_detalle,
						vedet.id_venta,
						(case when vedet.id_item is not null then
							vedet.id_item
						when vedet.id_sucursal_producto is not null then
							vedet.id_sucursal_producto
						when vedet.id_formula is not null then
							vedet.id_formula
						end) as id_producto,
						vedet.tipo,
						vedet.estado_reg,
						vedet.cantidad,
						vedet.precio,						
						vedet.id_usuario_ai,
						vedet.usuario_ai,
						vedet.fecha_reg,
						vedet.id_usuario_reg,
						vedet.id_usuario_mod,
						vedet.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						vedet.precio*vedet.cantidad,						
						(case when vedet.id_item is not null then
							item.nombre
						when vedet.id_sucursal_producto is not null then
							cig.desc_ingas
						when vedet.id_formula is not null then
							form.nombre
						end) as nombre_producto
						from vef.tventa_detalle vedet
						inner join segu.tusuario usu1 on usu1.id_usuario = vedet.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = vedet.id_usuario_mod
						left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
						left join vef.tformula form on form.id_formula = vedet.id_formula
						left join alm.titem item on item.id_item = vedet.id_item
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VEDET_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	elsif(p_transaccion='VF_VEDET_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_venta_detalle)
					    from vef.tventa_detalle vedet
					    inner join segu.tusuario usu1 on usu1.id_usuario = vedet.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = vedet.id_usuario_mod
						left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
						left join vef.tformula form on form.id_formula = vedet.id_formula
						left join alm.titem item on item.id_item = vedet.id_item
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
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