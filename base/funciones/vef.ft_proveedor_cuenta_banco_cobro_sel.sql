CREATE OR REPLACE FUNCTION vef.ft_proveedor_cuenta_banco_cobro_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_proveedor_cuenta_banco_cobro_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tproveedor_cuenta_banco_cobro'
 AUTOR: 		 (m.mamani)
 FECHA:	        22-11-2018 22:19:44
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-11-2018 22:19:44								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tproveedor_cuenta_banco_cobro'	
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'vef.ft_proveedor_cuenta_banco_cobro_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_PCC_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		m.mamani	
 	#FECHA:		22-11-2018 22:19:44
	***********************************/

	if(p_transaccion='VF_PCC_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select	  pcc.id_proveedor_cuenta_banco_cobro,
                                  pcc.id_proveedor,
                                  pcc.id_institucion,
                                  pcc.id_moneda,
                                  pr.desc_proveedor,
                                  pcc.tipo,
                                  ins.nombre as desc_nombre,
                                  mo.codigo_internacional as desc_moneda,
                                  pcc.fecha_alta,
                                  pcc.fecha_baja,
                                  pcc.nro_cuenta_bancario,
                                  pcc.estado_reg,
                                  pcc.fecha_reg,
                                  pcc.usuario_ai,
                                  pcc.id_usuario_reg,
                                  pcc.id_usuario_ai,
                                  pcc.fecha_mod,
                                  pcc.id_usuario_mod,
                                  usu1.cuenta as usr_reg,
                                  usu2.cuenta as usr_mod	
                                  from vef.tproveedor_cuenta_banco_cobro pcc
                                  inner join param.vproveedor pr on pr.id_proveedor = pcc.id_proveedor
                                  inner join segu.tusuario usu1 on usu1.id_usuario = pcc.id_usuario_reg
                                  inner join param.tinstitucion ins on ins.id_institucion = pcc.id_institucion
                                  inner join param.tmoneda mo on mo.id_moneda = pcc.id_moneda
                                  left join segu.tusuario usu2 on usu2.id_usuario = pcc.id_usuario_mod
				        		  where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_PCC_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		m.mamani	
 	#FECHA:		22-11-2018 22:19:44
	***********************************/

	elsif(p_transaccion='VF_PCC_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select  count(id_proveedor_cuenta_banco_cobro)
                                  from vef.tproveedor_cuenta_banco_cobro pcc
                                  inner join param.vproveedor pr on pr.id_proveedor = pcc.id_proveedor
                                  inner join segu.tusuario usu1 on usu1.id_usuario = pcc.id_usuario_reg
                                  inner join param.tinstitucion ins on ins.id_institucion = pcc.id_institucion
                                  inner join param.tmoneda mo on mo.id_moneda = pcc.id_moneda
                                  left join segu.tusuario usu2 on usu2.id_usuario = pcc.id_usuario_mod
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
