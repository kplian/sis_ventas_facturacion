CREATE OR REPLACE FUNCTION vef.ft_venta_detalle_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
  RETURNS varchar AS
  $body$
  /**************************************************************************
   SISTEMA:		Sistema de Ventas
   FUNCION: 		vef.ft_venta_detalle_ime
   DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tventa_detalle'
   AUTOR: 		(admin)
   FECHA:	        01-06-2015 09:21:07
   COMENTARIOS:
  ***************************************************************************
   HISTORIAL DE MODIFICACIONES:

   DESCRIPCION:
   AUTOR:
   FECHA:
  ***************************************************************************/

  DECLARE

    v_nro_requerimiento    		integer;
    v_parametros           		record;
    v_tmp						record;
    v_id_requerimiento     		integer;
    v_resp		            	varchar;
    v_nombre_funcion        	text;
    v_mensaje_error         	text;
    v_id_venta_detalle			integer;
    v_precio					numeric;
    v_sucursal_define_precio	varchar;
    v_id_item_sucursal			integer;
    v_id_venta					integer;
    v_tiene_formula				varchar;
    v_id_formula				integer;
    v_id_item					integer;
    v_id_sucursal_producto		integer;

    v_porcentaje_descuento		integer;
    v_id_vendedor				integer;
    v_id_medico					integer;
    v_registros					record;
    v_total						numeric;

    v_bruto						varchar;
    v_ley						varchar;
    v_kg_fino					varchar;
    v_descripcion				varchar;
    v_id_unidad_medida			integer;
    v_id_boleto					integer;



  BEGIN

    v_nombre_funcion = 'vef.ft_venta_detalle_ime';
    v_parametros = pxp.f_get_record(p_tabla);

    /*********************************
     #TRANSACCION:  'VF_VEDET_INS'
     #DESCRIPCION:	Insercion de registros
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

    if(p_transaccion='VF_VEDET_INS')then

      begin

        if (v_parametros.tipo = 'formula') then
          v_id_formula = v_parametros.id_producto;
        elsif (v_parametros.tipo = 'servicio' or
               (v_parametros.tipo = 'producto_terminado' and pxp.f_get_variable_global('vef_integracion_almacenes') = 'false'))then
          v_id_sucursal_producto = v_parametros.id_producto;
        else
          v_id_item =  v_parametros.id_producto;
        end if;

        v_porcentaje_descuento = 0;

        --verificar si existe porcentaje de descuento
        if (pxp.f_existe_parametro(p_tabla,'porcentaje_descuento')) then
          v_porcentaje_descuento = v_parametros.porcentaje_descuento;
        end if;

        --verificar si existe vendedor o medico
        v_id_vendedor = NULL;
        v_id_medico = NULL;

        if (pxp.f_existe_parametro(p_tabla,'id_vendedor_medico')) then
          if (split_part(v_parametros.id_vendedor_medico,'_',2) = 'usuario') then
            v_id_vendedor =  split_part(v_parametros.id_vendedor_medico::text,'_'::text,1)::integer;
          else
            v_id_medico =  split_part(v_parametros.id_vendedor_medico::text,'_'::text,1)::integer;
          end if;
        end if;


        if (pxp.f_existe_parametro(p_tabla,'descripcion')) then
          v_descripcion =  v_parametros.descripcion;
        else
          v_descripcion = '';
        end if;

        v_bruto = 0;
        v_ley = 0;
        v_kg_fino = 0;
        if (pxp.f_existe_parametro(p_tabla,'bruto')) then
          v_bruto = v_parametros.bruto;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'ley')) then
          v_ley = v_parametros.ley;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'kg_fino')) then
          v_kg_fino = v_parametros.kg_fino;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'id_unidad_medida')) then
          v_id_unidad_medida = v_parametros.id_unidad_medida;
        end if;

        --Sentencia de la insercion
        insert into vef.tventa_detalle(
          id_venta,
          id_item,
          id_sucursal_producto,
          id_formula,
          tipo,
          estado_reg,
          cantidad,
          precio,
          fecha_reg,
          id_usuario_reg,
          id_usuario_mod,
          fecha_mod,
          precio_sin_descuento,
          porcentaje_descuento,
          id_vendedor,
          id_medico,
          descripcion,
          bruto,
          ley,
          kg_fino,
          id_unidad_medida
        ) values(
          v_parametros.id_venta,
          v_id_item,
          v_id_sucursal_producto,
          v_id_formula,
          v_parametros.tipo,
          'activo',
          v_parametros.cantidad_det,
          round(v_parametros.precio - (v_parametros.precio * v_porcentaje_descuento / 100),6),
          now(),
          p_id_usuario,
          null,
          null,
          v_parametros.precio,
          v_porcentaje_descuento,
          v_id_vendedor,
          v_id_medico,
          v_descripcion,
          v_bruto,
          v_ley,
          v_kg_fino,
          v_id_unidad_medida
        )RETURNING id_venta_detalle into v_id_venta_detalle;


        --recupera datos de la venta

        select
          *
        into
          v_registros
        from vef.tventa v
        where v.id_venta = v_parametros.id_venta;


        select precio, cantidad into  v_tmp
        from vef.tventa_detalle
        where id_venta = v_parametros.id_venta;

        IF v_parametros.tipo_factura != 'computarizadaexpo' THEN
          v_total = COALESCE(v_registros.transporte_fob ,0)  + COALESCE(v_registros.seguros_fob ,0)+ COALESCE(v_registros.otros_fob ,0) + COALESCE(v_registros.transporte_cif ,0) +  COALESCE(v_registros.seguros_cif ,0) + COALESCE(v_registros.otros_cif ,0);
        ELSE
          v_total = 0; --en la factura comun de exportacion el detalle ya incluye los precios fob y cif
        END IF;

        update vef.tventa
        set total_venta = round((select sum(precio * cantidad) from vef.tventa_detalle where id_venta = v_parametros.id_venta) + v_total,2)
        where id_venta = v_parametros.id_venta;

        --verificar si existe el sistema obingresos, si existe actualizar el ib_boleto
        if ( (v_descripcion != '' and v_descripcion is not null) and
             exists (
                 select 1
                 from segu.tsubsistema s
                 where s.codigo like 'OBINGRESOS')) then

          if (exists (select 1
                      from vef.tventa_detalle
                      where id_venta_detalle != v_parametros.id_venta_detalle and
                            descripcion = v_descripcion))then
            raise exception 'El boleto %, ya fue relacionado con otra venta',v_parametros.descripcion;
          end if;

          select b.id_boleto into v_id_boleto
          from obingresos.tboleto b
          where b.nro_boleto = v_descripcion;

          if (v_id_boleto is not null) then
            update vef.tventa_detalle
            set id_boleto = v_id_boleto
            where id_venta_detalle = v_id_venta_detalle;
          end if;
        end if;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle de Venta almacenado(a) con exito (id_venta_detalle'||v_id_venta_detalle||')');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta_detalle',v_id_venta_detalle::varchar);

        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_VEDET_MOD'
     #DESCRIPCION:	Modificacion de registros
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

    elsif(p_transaccion='VF_VEDET_MOD')then

      begin
        update vef.tventa_detalle
        set descripcion = v_parametros.descripcion
        where id_venta_detalle = v_parametros.id_venta_detalle;



        --verificar si existe el sistema obingresos, si existe actualizar el ib_boleto
        if ((v_parametros.descripcion != '' and v_parametros.descripcion is not null) and
            exists (
                select 1
                from segu.tsubsistema s
                where s.codigo like 'OBINGRESOS')) then

          if (exists (select 1
                      from vef.tventa_detalle
                      where id_venta_detalle != v_parametros.id_venta_detalle and
                            descripcion = v_parametros.descripcion))then
            raise exception 'El boleto %, ya fue relacionado con otra venta',v_parametros.descripcion;
          end if;

          select b.id_boleto into v_id_boleto
          from obingresos.tboleto b
          where b.nro_boleto = v_parametros.descripcion;

          if (v_id_boleto is not null) then
            update vef.tventa_detalle
            set id_boleto = v_id_boleto
            where id_venta_detalle = v_parametros.id_venta_detalle;
          end if;
        end if;
        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle de Venta modificado(a)');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta_detalle',v_parametros.id_venta_detalle::varchar);

        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_VEDET_ELI'
     #DESCRIPCION:	Eliminacion de registros
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

    elsif(p_transaccion='VF_VEDET_ELI')then

      begin
        select id_venta into v_id_venta
        from vef.tventa_detalle
        where id_venta_detalle = v_parametros.id_venta_detalle;

        --Sentencia de la eliminacion
        delete from vef.tventa_detalle
        where id_venta_detalle=v_parametros.id_venta_detalle;
        /*Verificar si todavia existe una formula*/
        v_tiene_formula = 'no';
        if (exists (select 1 from vef.tventa_detalle where id_venta = v_id_venta
                                                           and tipo = 'formula')) then
          v_tiene_formula = 'si';
        end if;


        --recupera datos de la venta

        select
          *
        into
          v_registros
        from vef.tventa v
        where v.id_venta = v_parametros.id_venta;


        v_total = COALESCE(v_registros.transporte_fob ,0)  + COALESCE(v_registros.seguros_fob ,0)+ COALESCE(v_registros.otros_fob ,0) + COALESCE(v_registros.transporte_cif ,0) +  COALESCE(v_registros.seguros_cif ,0) + COALESCE(v_registros.otros_cif ,0);

        update vef.tventa
        set total_venta = coalesce((select sum(precio * cantidad) from vef.tventa_detalle where id_venta = v_id_venta),0) + v_total,
          tiene_formula = v_tiene_formula
        where id_venta = v_id_venta;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle de Venta eliminado(a)');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta_detalle',v_parametros.id_venta_detalle::varchar);

        --Devuelve la respuesta
        return v_resp;

      end;

    else

      raise exception 'Transaccion inexistente: %',p_transaccion;

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