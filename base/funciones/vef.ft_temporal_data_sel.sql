CREATE OR REPLACE FUNCTION vef.ft_temporal_data_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_temporal_data_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.ttemporal_data'
 AUTOR: 		 (eddy.gutierrez)
 FECHA:	        06-11-2018 20:39:08
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0                06-11-2018 20:39:08                                Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.ttemporal_data'    
 #
 ***************************************************************************/

DECLARE

    v_consulta            varchar;
    v_parametros          record;
    v_nombre_funcion       text;
    v_resp                varchar;
                
BEGIN

    v_nombre_funcion = 'vef.ft_temporal_data_sel';
    v_parametros = pxp.f_get_record(p_tabla);

    /*********************************    
     #TRANSACCION:  'VF_dad_SEL'
     #DESCRIPCION:    Consulta de datos
     #AUTOR:        eddy.gutierrez    
     #FECHA:        06-11-2018 20:39:08
    ***********************************/

    if(p_transaccion='VF_dad_SEL')then
                     
        begin
            --Sentencia de la consulta
            v_consulta:='select
                        dad.id_temporal_data,
                        dad.razon_social,
                        dad.estado_reg,
                        dad.nro_factura,
                        dad.id_usuario_ai,
                        dad.id_usuario_reg,
                        dad.usuario_ai,
                        dad.fecha_reg,
                        dad.id_usuario_mod,
                        dad.fecha_mod,
                        usu1.cuenta as usr_reg,
                        usu2.cuenta as usr_mod    
                        from vef.ttemporal_data dad
                        inner join segu.tusuario usu1 on usu1.id_usuario = dad.id_usuario_reg
                        left join segu.tusuario usu2 on usu2.id_usuario = dad.id_usuario_mod
                        where  ';
            
            --Definicion de la respuesta
            v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            --Devuelve la respuesta
            return v_consulta;
                        
        end;

    /*********************************    
     #TRANSACCION:  'VF_dad_CONT'
     #DESCRIPCION:    Conteo de registros
     #AUTOR:        eddy.gutierrez    
     #FECHA:        06-11-2018 20:39:08
    ***********************************/

    elsif(p_transaccion='VF_dad_CONT')then

        begin
            --Sentencia de la consulta de conteo de registros
            v_consulta:='select count(id_temporal_data)
                        from vef.ttemporal_data dad
                        inner join segu.tusuario usu1 on usu1.id_usuario = dad.id_usuario_reg
                        left join segu.tusuario usu2 on usu2.id_usuario = dad.id_usuario_mod
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