CREATE OR REPLACE FUNCTION vef.ft_cliente_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_cliente_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tcliente'
 AUTOR: 		 (admin)
 FECHA:	        20-04-2015 08:41:29
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

	v_nombre_funcion = 'vef.ft_cliente_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_CLI_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 08:41:29
	***********************************/

	if(p_transaccion='VF_CLI_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						cli.id_cliente,
						cli.correo,
						cli.telefono_fijo,
						cli.estado_reg,
						cli.segundo_apellido,
						cli.nombre_factura,
						cli.primer_apellido,
						cli.telefono_celular,
						cli.nit,
						cli.otros_correos,
						cli.otros_telefonos,
						cli.nombres,
						cli.id_usuario_reg,
						cli.fecha_reg,
						cli.usuario_ai,
						cli.id_usuario_ai,
						cli.id_usuario_mod,
						cli.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        cli.direccion,

                        cli.lugar,

                        cli.observaciones

						from vef.tcliente cli
						inner join segu.tusuario usu1 on usu1.id_usuario = cli.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cli.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_CLI_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 08:41:29
	***********************************/

	elsif(p_transaccion='VF_CLI_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_cliente)
					    from vef.tcliente cli
					    inner join segu.tusuario usu1 on usu1.id_usuario = cli.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cli.id_usuario_mod
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