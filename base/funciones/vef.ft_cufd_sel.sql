CREATE OR REPLACE FUNCTION vef.ft_cufd_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_cufd_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tcufd'
 AUTOR: 		 (admin)
 FECHA:	        22-01-2019 02:23:54
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-01-2019 02:23:54								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tcufd'	
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_count				integer;
	v_aux				varchar;
BEGIN

	v_nombre_funcion = 'vef.ft_cufd_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_CUFD_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		22-01-2019 02:23:54
	***********************************/

	if(p_transaccion='VF_CUFD_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						cufd.id_cufd,
						cufd.codigo,
						cufd.fecha_inicio,
						cufd.fecha_fin,
						cufd.estado_reg,
						cufd.id_cuis,
						cufd.id_usuario_ai,
						cufd.id_usuario_reg,
						cufd.usuario_ai,
						cufd.fecha_reg,
						cufd.id_usuario_mod,
						cufd.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from vef.tcufd cufd
						inner join segu.tusuario usu1 on usu1.id_usuario = cufd.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cufd.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_CUFD_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		22-01-2019 02:23:54
	***********************************/

	elsif(p_transaccion='VF_CUFD_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_cufd)
					    from vef.tcufd cufd
					    inner join segu.tusuario usu1 on usu1.id_usuario = cufd.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cufd.id_usuario_mod
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
			
        
        /*********************************    
 	#TRANSACCION:  'VF_VERCUFD_CONT'
 	#DESCRIPCION:	Verificacion de fechas
 	#AUTOR:		jmita
 	#FECHA:		31-01-2019 02:23:54
	***********************************/

	elsif(p_transaccion='VF_VERCUFD_SEL')then

		begin
        	
            v_aux:='select count(*) from vef.tcufd cf where cf.estado_reg=''activo'' and '|| v_parametros.filtro;
            execute v_aux into v_count;
                      
        	IF( v_count>0 ) then 
        	begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select ((to_char( cf.fecha_fin, ''dd/mm/yyyy HH24:MI:SS''))::timestamp 
            					 < (to_char( now(), ''dd/mm/yyyy HH24:MI:SS''))::timestamp)::varchar as alerta,
                                 (to_char( cf.fecha_fin, ''dd/mm/yyyy HH24:MI:SS''))::varchar as fecha
                                  from vef.tcufd cf where cf.estado_reg=''activo'' and  ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;
            end;
			else
            	begin
            	v_consulta:='select ''true''::varchar as alerta , now()::varchar as fecha ';
            	return v_consulta;
                end;
            end if;
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