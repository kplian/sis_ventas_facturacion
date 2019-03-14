CREATE OR REPLACE FUNCTION vef.ft_cuf_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_cuf_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tcuf'
 AUTOR: 		 (admin)
 FECHA:	        21-01-2019 15:18:42
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-01-2019 15:18:42								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tcuf'	
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'vef.ft_cuf_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_CUF_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		21-01-2019 15:18:42
	***********************************/

	if(p_transaccion='VF_CUF_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						cuf.id_cuf,
						cuf.nro_factura,
						cuf.codigo_documento_fiscal,
						cuf.nit,
						cuf.base11,
						cuf.sucursal,
						cuf.punto_venta,
						cuf.fecha_emision,
						cuf.modalidad,
						cuf.codigo_autoverificador,
						cuf.tipo_documento_sector,
						cuf.tipo_emision,
						cuf.base16,
						cuf.estado_reg,
						cuf.concatenacion,
						cuf.id_usuario_ai,
						cuf.id_usuario_reg,
						cuf.fecha_reg,
						cuf.usuario_ai,
						cuf.fecha_mod,
						cuf.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from vef.tcuf cuf
						inner join segu.tusuario usu1 on usu1.id_usuario = cuf.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cuf.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_CUF_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		21-01-2019 15:18:42
	***********************************/

	elsif(p_transaccion='VF_CUF_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_cuf)
					    from vef.tcuf cuf
					    inner join segu.tusuario usu1 on usu1.id_usuario = cuf.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cuf.id_usuario_mod
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