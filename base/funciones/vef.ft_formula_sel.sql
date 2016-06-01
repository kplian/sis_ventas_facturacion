CREATE OR REPLACE FUNCTION vef.ft_formula_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_formula_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tformula'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 09:14:49
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

	v_nombre_funcion = 'vef.ft_formula_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_FORM_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:14:49
	***********************************/

	if(p_transaccion='VF_FORM_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						form.id_formula,
						form.id_tipo_presentacion,
						form.id_unidad_medida,
						form.id_medico,
						form.nombre,
						form.cantidad,
						form.estado_reg,
						form.descripcion,
						form.usuario_ai,
						form.fecha_reg,
						form.id_usuario_reg,
						form.id_usuario_ai,
						form.fecha_mod,
						form.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						ume.descripcion::text,
						med.nombre_completo,
                        sum(fd.cantidad * i.precio_ref)
						from vef.tformula form
						inner join segu.tusuario usu1 on usu1.id_usuario = form.id_usuario_reg
						left join vef.vmedico med on med.id_medico = form.id_medico
						left join param.tunidad_medida ume on ume.id_unidad_medida = form.id_unidad_medida						
						left join segu.tusuario usu2 on usu2.id_usuario = form.id_usuario_mod
				        inner join vef.tformula_detalle fd on fd.id_formula = form.id_formula
                        left join alm.titem i on i.id_item = fd.id_item
                        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta|| ' group by form.id_formula,
						form.id_tipo_presentacion,
						form.id_unidad_medida,
						form.id_medico,
						form.nombre,
						form.cantidad,
						form.estado_reg,
						form.descripcion,
						form.usuario_ai,
						form.fecha_reg,
						form.id_usuario_reg,
						form.id_usuario_ai,
						form.fecha_mod,
						form.id_usuario_mod,
						usu1.cuenta,
						usu2.cuenta,
						ume.descripcion,
						med.nombre_completo';
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_FORM_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:14:49
	***********************************/

	elsif(p_transaccion='VF_FORM_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_formula)
					    from vef.tformula form
					    inner join segu.tusuario usu1 on usu1.id_usuario = form.id_usuario_reg
					    left join vef.vmedico med on med.id_medico = form.id_medico
						left join param.tunidad_medida ume on ume.id_unidad_medida = form.id_unidad_medida
						left join segu.tusuario usu2 on usu2.id_usuario = form.id_usuario_mod
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