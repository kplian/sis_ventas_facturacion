CREATE OR REPLACE FUNCTION vef.ft_forma_pago_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
  /**************************************************************************
   SISTEMA:		Sistema de Ventas
   FUNCION: 		vef.ft_forma_pago_sel
   DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tforma_pago'
   AUTOR: 		 (jrivera)
   FECHA:	        08-10-2015 13:29:06
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
    v_join				varchar;
    v_select			varchar;

  BEGIN

    v_nombre_funcion = 'vef.ft_forma_pago_sel';
    v_parametros = pxp.f_get_record(p_tabla);

    /*********************************
     #TRANSACCION:  'VF_FORPA_SEL'
     #DESCRIPCION:	Consulta de datos
     #AUTOR:		jrivera
     #FECHA:		08-10-2015 13:29:06
    ***********************************/

    if(p_transaccion='VF_FORPA_SEL')then

      begin
        v_join = '';
        v_select = '0::numeric as valor,
            			''''::varchar as numero_tarjeta,
                        ''''::varchar as codigo_tarjeta,
                        ''''::varchar as tipo_tarjeta
                        ';
        if (pxp.f_existe_parametro(p_tabla,'id_venta')) then
          if (v_parametros.id_venta is not null) then

            v_join = 'left join vef.tventa_forma_pago ve on ve.id_forma_pago = forpa.id_forma_pago and
                                                                    ve.id_venta =  ' || v_parametros.id_venta;
            v_select = '(case when ve.id_forma_pago is not null then
                                    ve.monto::numeric 
                                else
                                    0::numeric
                                end) as valor,
                                (case when ve.id_forma_pago is not null then 
                                    ve.numero_tarjeta::varchar 
                                else
                                    ''''::varchar
                                end) as numero_tarjeta,
                                (case when ve.id_forma_pago is not null then 
                                    ve.codigo_tarjeta::varchar
                                else
                                    ''''::varchar
                                end) as codigo_tarjeta,
                                (case when ve.id_forma_pago is not null then 
                                    ve.tipo_tarjeta::varchar
                                else
                                    ''''::varchar
                                end) as tipo_tarjeta';
          end if;
        end if;
        --Sentencia de la consulta
        v_consulta:='select
						forpa.id_forma_pago,
						forpa.estado_reg,
						forpa.codigo,
						forpa.nombre,
						forpa.id_entidad,
						forpa.id_moneda,
						forpa.id_usuario_reg,
						forpa.fecha_reg,
						forpa.id_usuario_ai,
						forpa.usuario_ai,
						forpa.id_usuario_mod,
						forpa.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						mon.codigo_internacional,
						forpa.defecto, 
						forpa.registrar_tarjeta,
                        forpa.registrar_tipo_tarjeta,
						forpa.registrar_cc , ' || v_select || '
								
						from vef.tforma_pago forpa
						inner join segu.tusuario usu1 on usu1.id_usuario = forpa.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = forpa.id_usuario_mod
						inner join param.tmoneda mon on mon.id_moneda = forpa.id_moneda
						' || v_join || '
				        where  ';

        --Definicion de la respuesta
        v_consulta:=v_consulta||v_parametros.filtro;
        v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
        raise notice '%',v_consulta;
        --Devuelve la respuesta
        return v_consulta;

      end;

    /*********************************
     #TRANSACCION:  'VF_FORPA_CONT'
     #DESCRIPCION:	Conteo de registros
     #AUTOR:		jrivera
     #FECHA:		08-10-2015 13:29:06
    ***********************************/

    elsif(p_transaccion='VF_FORPA_CONT')then

      begin


        --Sentencia de la consulta de conteo de registros
        v_consulta:='select count(id_forma_pago)
					    from vef.tforma_pago forpa
					    inner join segu.tusuario usu1 on usu1.id_usuario = forpa.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = forpa.id_usuario_mod
						inner join param.tmoneda mon on mon.id_moneda = forpa.id_moneda
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