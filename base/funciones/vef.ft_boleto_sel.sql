CREATE OR REPLACE FUNCTION vef.ft_boleto_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_boleto_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tboleto'
 AUTOR: 		 (jrivera)
 FECHA:	        26-11-2015 22:03:32
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

	v_nombre_funcion = 'vef.ft_boleto_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_BOL_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		jrivera	
 	#FECHA:		26-11-2015 22:03:32
	***********************************/

	if(p_transaccion='VF_BOL_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						bol.id_boleto,
						bol.id_punto_venta,
						bol.numero,
						bol.ruta,
						bol.estado_reg,						
						bol.fecha,
						bol.id_usuario_ai,
						bol.id_usuario_reg,
						bol.fecha_reg,
						bol.usuario_ai,
						bol.id_usuario_mod,
						bol.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        (case when count(id_boleto_fp) > 1	then
                        	NULL
                        else
                        	pxp.list(bolfp.id_forma_pago::text)
                        end):: integer as id_forma_pago,
                        (case when count(id_boleto_fp) > 1	then
                        	''DIVIDIDO''
                        else
                        	pxp.list(fp.nombre)
                        end):: varchar as forma_pago,
                        sum(bolfp.monto) as monto
                        
						from vef.tboleto bol
						inner join segu.tusuario usu1 on usu1.id_usuario = bol.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = bol.id_usuario_mod
				        inner join vef.tboleto_fp bolfp on bolfp.id_boleto = bol.id_boleto
                        inner join vef.tforma_pago fp on fp.id_forma_pago = bolfp.id_forma_pago 
                        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta|| ' group by 
            			bol.id_boleto,
						bol.id_punto_venta,
						bol.numero,
						bol.ruta,
						bol.estado_reg,						
						bol.fecha,
						bol.id_usuario_ai,
						bol.id_usuario_reg,
						bol.fecha_reg,
						bol.usuario_ai,
						bol.id_usuario_mod,
						bol.fecha_mod,
						usu1.cuenta,
						usu2.cuenta';
                        
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_BOL_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		jrivera	
 	#FECHA:		26-11-2015 22:03:32
	***********************************/

	elsif(p_transaccion='VF_BOL_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_boleto)
					    from vef.tboleto bol
					    inner join segu.tusuario usu1 on usu1.id_usuario = bol.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = bol.id_usuario_mod
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