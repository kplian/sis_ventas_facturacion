CREATE OR REPLACE FUNCTION vef.ft_medico_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_medico_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tmedico'
 AUTOR: 		 (admin)
 FECHA:	        20-04-2015 11:17:42
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

	v_nombre_funcion = 'vef.ft_medico_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_MED_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 11:17:42
	***********************************/

	if(p_transaccion='VF_MED_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						med.id_medico,
						med.correo,
						med.telefono_fijo,
						med.estado_reg,
						med.segundo_apellido,
						med.porcentaje,
						med.telefono_celular,
						med.primer_apellido,
						med.otros_correos,
						med.otros_telefonos,
						med.nombres,
						med.id_usuario_reg,
						med.fecha_reg,
						med.usuario_ai,
						med.id_usuario_ai,
						med.id_usuario_mod,
						med.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						med.fecha_nacimiento,
                        med.especialidad	
						from vef.tmedico med
						inner join segu.tusuario usu1 on usu1.id_usuario = med.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = med.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_MED_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 11:17:42
	***********************************/

	elsif(p_transaccion='VF_MED_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_medico)
					    from vef.tmedico med
					    inner join segu.tusuario usu1 on usu1.id_usuario = med.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = med.id_usuario_mod
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    
    /*********************************    
 	#TRANSACCION:  'VF_VENMED_SEL'
 	#DESCRIPCION:	Consulta de datos de vendedor medico
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 11:17:42
	***********************************/

	elsif(p_transaccion='VF_VENMED_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='with medico as
                              (select
                              (med.id_medico || ''_medico'')::varchar as id_vendedor_medico,
                              med.nombre_completo::varchar as nombre,
                              ''medico''::varchar as tipo                           
                              from vef.vmedico med
            				union all
                            	select
                              (usu.id_usuario || ''_usuario'')::varchar as id_vendedor_medico,
                              usu.desc_persona::varchar as nombre,
                              ''vendedor''::varchar  as tipo                           
                              from segu.vusuario usu)
            			select todo.id_vendedor_medico,todo.nombre,todo.tipo
                        from medico todo						
                        where    ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;
    /*********************************    
 	#TRANSACCION:  'VF_VENMED_CONT'
 	#DESCRIPCION:	Consulta de conteo de vendedor medico
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 11:17:42
	***********************************/

	elsif(p_transaccion='VF_VENMED_CONT')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='with medico as
                              (select
                              (med.id_medico || ''_medico'')::varchar as id_vendedor_medico,
                              med.nombre_completo::varchar as nombre,
                              ''medico''::varchar                            
                              from vef.vmedico med
            				union all
                            	select
                              (usu.id_usuario || ''_usuario'')::varchar as id_vendedor_medico,
                              usu.desc_persona::varchar as nombre,
                              ''vendedor''::varchar                            
                              from segu.vusuario usu)
            			select count(todo.id_vendedor_medico)
                        from medico todo						
                        where    ';
			
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