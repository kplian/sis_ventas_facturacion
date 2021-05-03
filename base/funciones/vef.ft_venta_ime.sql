--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.ft_venta_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
  /**************************************************************************
   SISTEMA:		Sistema de Ventas
   FUNCION: 		vef.ft_venta_ime
   DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tventa'
   AUTOR:          (admin)
   FECHA:            01-06-2015 05:58:00
   COMENTARIOS:
  ***************************************************************************

    HISTORIAL DE MODIFICACIONES:

 ISSUE            FECHA:              AUTOR               DESCRIPCION
 #0              01/06/2016        JRR                 Creacion
 #123            25/09/2018        RAC                 Se adicionan datos de proveedor al guardar la factura
 #7              31/10/2019         EGS                 Se crean las transacciones VEF_VALIMUL_IME y VEF_SIGESTMUL_IMEss para pasar varios registros de estado borrador al siguiente estado

  ***************************************************************************/

  DECLARE

    v_nro_requerimiento        integer;
    v_res                    varchar;
    v_parametros               record;
    v_reg_tipo_desc            record;
    v_id_requerimiento         integer;
    v_resp                    varchar;
    v_nombre_funcion        text;
    v_mensaje_error         text;
    v_id_venta                integer;
    v_num_tramite            varchar;
    v_id_proceso_wf            integer;
    v_id_estado_wf            integer;
    v_codigo_estado            varchar;
    v_id_gestion            integer;
    v_codigo_proceso        varchar;
    v_id_tipo_estado        integer;
    v_id_funcionario        integer;
    v_id_usuario_reg        integer;
    v_id_depto                integer;

    v_id_estado_wf_ant        integer;
    v_acceso_directo        varchar;
    v_clase                    varchar;
    v_parametros_ad            varchar;
    v_tipo_noti                varchar;
    v_titulo                varchar;
    v_id_estado_actual        integer;
    v_codigo_estado_siguiente varchar;
    v_obs                    text;
    v_id_cliente            integer;
    v_venta                    record;
    v_suma_fp                numeric;
    v_suma_det                numeric;
    v_registros                record;
    v_id_sucursal            integer;
    v_cantidad_fp            integer;
    v_acumulado_fp            numeric;
    v_monto_fp                numeric;
    v_a_cuenta                numeric;
    v_fecha_estimada_entrega date;
    vef_estados_validar_fp    varchar;
    v_id_punto_venta            integer;
    v_porcentaje_descuento    integer;
    v_id_vendedor_medico    varchar;
    v_comision                numeric;
    v_id_funcionario_inicio    integer;
    v_codigo_tabla            varchar;
    v_num_ven                varchar;
    v_id_periodo            integer;
    v_tipo_factura            varchar;
    v_fecha                    date;
    v_excento                numeric;
    v_id_dosificacion        integer;
    v_nro_factura            integer;
    v_id_actividad_economica    integer[];
    v_dosificacion            record;
    v_tipo_base                varchar;
    v_cantidad                integer;
    v_tipo_usuario            varchar;
    v_id_moneda_venta        integer;
    v_id_moneda_suc            integer;
    v_total_venta_ms        numeric;
    v_fecha_venta             date;
    v_nombre_ae                varchar;
    v_id_activida_economica        integer;
    v_transporte_fob        numeric;
    v_seguros_fob            numeric;
    v_otros_fob                numeric;
    v_transporte_cif        numeric;
    v_seguros_cif            numeric;
    v_otros_cif                numeric;
    v_tipo_cambio_venta        numeric;
    v_es_fin                varchar;
    v_valor_bruto            numeric;
    v_descripcion_bulto        varchar;
    v_nombre_factura        varchar;
    v_id_cliente_destino    integer;

    v_tabla                    varchar;
    v_ventas                varchar;


    v_hora_estimada_entrega    time;
    v_tiene_formula            varchar;
    v_forma_pedido            varchar;
    v_id_proveedor          integer;
    v_id_contrato           integer;
    v_id_centro_costo       integer;
    v_codigo_aplicacion     varchar;
    v_nit_internacional     varchar;
    v_sw_ncd                boolean; --#123
    v_ncd                   varchar; --#123
    v_id_venta_fk           integer; --#123
    v_vef_por_per_ncd       varchar; --#123
    v_total_venta           numeric; --#123
    v_total_venta_ncd       numeric; --#123
    v_importe_codigo_control    numeric; --#123
    v_tipo_dosificacion         varchar; --#123

    j_data_json             json;
    v_estado                varchar;
    v_record_venta          record;

    va_id_tipo_estado 		integer[];
    va_codigo_estado 		varchar[];
    va_disparador    		varchar[];
    va_regla         		varchar[];
    va_prioridad     		integer[];
    v_id_funcionario_wf     integer;
    p_id_usuario_ai         integer;
    p_usuario_ai            varchar;


  BEGIN

    v_nombre_funcion = 'vef.ft_venta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

    /*********************************
     #TRANSACCION:  'VF_VEN_INS'
     #DESCRIPCION:    Insercion de registros
     #AUTOR:        admin
     #FECHA:        01-06-2015 05:58:00
    ***********************************/

    if(p_transaccion='VF_VEN_INS')then

      begin
        v_tiene_formula = 'no';
        v_nit_internacional = 'no';
        --obtener correlativo

        --#123    validar si viene  de una nota de credito
        v_sw_ncd = false;
        v_ncd = 'no';
        if (pxp.f_existe_parametro(p_tabla,'id_venta_fk')) then
           v_sw_ncd = true;
           v_ncd = 'si';
           v_id_venta_fk = v_parametros.id_venta_fk;
        end if;

        select id_periodo into v_id_periodo from
          param.tperiodo per
        where per.fecha_ini <= now()::date
              and per.fecha_fin >=  now()::date
        limit 1 offset 0;

        if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
          select pv.codigo into v_codigo_tabla
          from vef.tpunto_venta pv
          where id_punto_venta = v_parametros.id_punto_venta;
        else
          select pv.codigo into v_codigo_tabla
          from vef.tsucursal pv
          where id_sucursal = v_parametros.id_sucursal;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'id_moneda')) then
          v_id_moneda_venta = v_parametros.id_moneda;
        else
          if (v_parametros.id_sucursal is not null ) then
            select sm.id_moneda into v_id_moneda_venta
            from vef.tsucursal_moneda sm
            where sm.id_sucursal = v_parametros.id_sucursal
                  and sm.estado_reg = 'activo' and sm.tipo_moneda = 'moneda_base';
          else
            select sm.id_moneda into v_id_moneda_venta
            from vef.tsucursal_moneda sm
              inner join vef.tpunto_venta pv on pv.id_sucursal = sm.id_sucursal
            where pv.id_punto_venta = v_parametros.id_punto_venta
                  and sm.estado_reg = 'activo' and sm.tipo_moneda = 'moneda_base';
          end if;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'tipo_factura')) then
          v_tipo_factura = v_parametros.tipo_factura;
        else
          v_tipo_factura = 'recibo';
        end if;

        SELECT tv.tipo_base into v_tipo_base
        from vef.ttipo_venta tv
        where tv.codigo = v_tipo_factura and tv.estado_reg = 'activo';

        if (v_tipo_base is null) then
          raise exception 'No existe un tipo de venta con el codigo: % consulte con el administrador del sistema',v_tipo_factura;
        end if;

        v_excento = 0;
        if (v_tipo_base = 'recibo') THEN
          v_fecha = now()::date;
        ELSIF(v_tipo_base = 'manual') then
          v_fecha = v_parametros.fecha;
          v_nro_factura = v_parametros.nro_factura;
          IF  pxp.f_existe_parametro(p_tabla, 'excento') THEN --#1234 validacion del excento
               v_excento = v_parametros.excento;
          ELSE
               v_excento = 0;
          END IF;

          v_id_dosificacion = v_parametros.id_dosificacion;

          --validaciones de factura manual
          --validar que no exista el mismo nro para la dosificacion
          if (exists(    select 1
                       from vef.tventa ven
                       where ven.nro_factura = v_parametros.nro_factura::integer and ven.id_dosificacion = v_parametros.id_dosificacion)) then
            raise exception 'Ya existe el mismo numero de factura en otra venta y con la misma dosificacion. Por favor revise los datos';
          end if;

          --validar que el nro de factura no supere el maximo nro de factura de la dosificaiocn
          if (exists(    select 1
                       from vef.tdosificacion dos
                       where v_parametros.nro_factura::integer > dos.final and dos.id_dosificacion = v_parametros.id_dosificacion)) then
            raise exception 'El numero de factura supera el maximo permitido para esta dosificacion';
          end if;

          --validar que la fecha de factura no sea superior a la fecha limite de emision
          if (exists(    select 1
                       from vef.tdosificacion dos
                       where dos.fecha_limite < v_parametros.fecha and dos.id_dosificacion = v_parametros.id_dosificacion)) then
            raise exception 'La fecha de la factura supera la fecha limite de emision de la dosificacion';
          end if;

        ELSE

          IF   v_tipo_factura in ('computarizadaexpo','computarizadaexpomin','computarizadamin','computarizadareg')  THEN --#123  agrega computarizadareg
            -- la fecha es abierta
            v_fecha = v_parametros.fecha;

          ELSE
            v_fecha = now()::date;
            IF  pxp.f_existe_parametro(p_tabla, 'excento') THEN
               v_excento = v_parametros.excento;
            ELSE
               v_excento = 0;
            END IF;

          END IF;

        end if;
        if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
          v_id_punto_venta = v_parametros.id_punto_venta;
        else
          v_id_punto_venta = NULL;
        end if;

        -- obtener correlativo
        v_num_ven =   param.f_obtener_correlativo(
            'VEN',
            v_id_periodo,-- par_id,
            NULL, --id_uo
            NULL,    -- id_depto
            p_id_usuario,
            'VEF',
            NULL,
            0,
            0,
            (case when v_id_punto_venta is not null then
              'vef.tpunto_venta'
             else
               'vef.tsucursal'
             end),
            (case when v_id_punto_venta is not null then
              v_id_punto_venta
             else
               v_parametros.id_sucursal
             end),
            v_codigo_tabla
        );

        --fin obtener correlativo
        IF (v_num_ven is NULL or v_num_ven ='') THEN
          raise exception 'No se pudo obtener un numero correlativo para la venta consulte con el administrador';
        END IF;

        v_porcentaje_descuento = 0;

        --  verificar si existe porcentaje de descuento
        if (pxp.f_existe_parametro(p_tabla,'porcentaje_descuento')) then
          v_porcentaje_descuento = v_parametros.porcentaje_descuento;
        end if;

        v_id_vendedor_medico = NULL;
        if (pxp.f_existe_parametro(p_tabla,'id_vendedor_medico')) then
          v_id_vendedor_medico = v_parametros.id_vendedor_medico;
        end if;

        if (v_id_punto_venta is not null) then
          select id_sucursal into v_id_sucursal
          from vef.tpunto_venta
          where id_punto_venta = v_id_punto_venta;
        else
          v_id_sucursal = v_parametros.id_sucursal;

        end if;




        if (pxp.f_existe_parametro(p_tabla,'a_cuenta')) then
          v_a_cuenta = v_parametros.a_cuenta;
        else
          v_a_cuenta = 0;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'comision')) then
          v_comision = v_parametros.comision;
        else
          v_comision = 0;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'fecha_estimada_entrega')) then
          v_fecha_estimada_entrega = v_parametros.fecha_estimada_entrega;
          if (v_fecha_estimada_entrega is not null) then
            v_tiene_formula = 'si';
          else
            v_fecha_estimada_entrega = now();
          end if;
        else
          v_fecha_estimada_entrega = now();
        end if;

        if (pxp.f_existe_parametro(p_tabla,'hora_estimada_entrega')) then

          if (v_parametros.hora_estimada_entrega is not null and v_parametros.hora_estimada_entrega != '') then

            v_hora_estimada_entrega = (v_parametros.hora_estimada_entrega || ':00')::time;
          else
            v_hora_estimada_entrega = NULL;
          end if;
        else
          v_hora_estimada_entrega = now()::time;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'forma_pedido')) then
          v_forma_pedido = v_parametros.forma_pedido;
        else
          v_forma_pedido =NULL;
        end if;

        --#123 verifica si no existe el id_cliente entonces es id_proveedor
        IF  pxp.f_existe_parametro(p_tabla,'id_cliente')  THEN

              if (pxp.f_is_positive_integer(v_parametros.id_cliente)) THEN
                v_id_cliente = v_parametros.id_cliente::integer;

                update vef.tcliente
                set nit = v_parametros.nit
                where id_cliente = v_id_cliente;

                select c.nombre_factura into v_nombre_factura
                from vef.tcliente c
                where c.id_cliente = v_id_cliente;
              else
                INSERT INTO
                  vef.tcliente
                  (
                    id_usuario_reg,
                    fecha_reg,
                    estado_reg,
                    nombre_factura,
                    nit
                  )
                VALUES (
                  p_id_usuario,
                  now(),
                  'activo',
                  v_parametros.id_cliente,
                  v_parametros.nit
                ) returning id_cliente into v_id_cliente;

                v_nombre_factura = v_parametros.id_cliente;

              end if;
        ELSE

                v_id_proveedor = v_parametros.id_proveedor;


                select
                     p.desc_proveedor2,
                     tp.internacional
                into
                v_nombre_factura,
                v_nit_internacional
                from param.vproveedor p
                inner join param.tproveedor tp on tp.id_proveedor = p.id_proveedor
                where p.id_proveedor =  v_parametros.id_proveedor::integer;



        END IF;



        v_id_cliente_destino = null;
        --si tenemos cliente destino
        if v_tipo_factura = 'pedido' then
                 if (pxp.f_is_positive_integer(v_parametros.id_cliente_destino)) THEN
                    v_id_cliente_destino = v_parametros.id_cliente_destino::integer;
                  else

                    INSERT INTO
                      vef.tcliente
                    (
                      id_usuario_reg,
                      fecha_reg,
                      estado_reg,
                      nombre_factura
                    )
                    VALUES (
                      p_id_usuario,
                      now(),
                      'activo',
                      v_parametros.id_cliente
                    ) returning id_cliente into v_id_cliente_destino;


                end if;
        end if;





        --obtener gestion a partir de la fecha actual
        select id_gestion into v_id_gestion
        from param.tgestion
        where gestion = extract(year from now())::integer;

        select nextval('vef.tventa_id_venta_seq') into v_id_venta;

        v_codigo_proceso = 'VEN-' || v_id_venta;
        -- inciiar el tramite en el sistema de WF

        select f.id_funcionario into  v_id_funcionario_inicio
        from segu.tusuario u
          inner join orga.tfuncionario f on f.id_persona = u.id_persona
        where u.id_usuario = p_id_usuario;

        SELECT
          ps_num_tramite ,
          ps_id_proceso_wf ,
          ps_id_estado_wf ,
          ps_codigo_estado
        into
          v_num_tramite,
          v_id_proceso_wf,
          v_id_estado_wf,
          v_codigo_estado

        FROM wf.f_inicia_tramite(
            p_id_usuario,
            v_parametros._id_usuario_ai,
            v_parametros._nombre_usuario_ai,
            v_id_gestion,
            'VEN',
            v_id_funcionario_inicio,
            NULL,
            NULL,
            v_codigo_proceso);


        if (pxp.f_existe_parametro(p_tabla,'transporte_fob')) then
          v_transporte_fob = v_parametros.transporte_fob;
          v_seguros_fob = v_parametros.seguros_fob;
          v_otros_fob = v_parametros.otros_fob;
          v_transporte_cif = v_parametros.transporte_cif;
          v_seguros_cif = v_parametros.seguros_cif;
          v_otros_cif = v_parametros.otros_cif;
          v_valor_bruto = v_parametros.valor_bruto;
          v_descripcion_bulto = v_parametros.descripcion_bulto;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'tipo_cambio_venta')) then
          v_tipo_cambio_venta = v_parametros.tipo_cambio_venta;
        end if;


        --#123  verifica si tiene contrato
        if (pxp.f_existe_parametro(p_tabla,'id_contrato')) then
          v_id_contrato = v_parametros.id_contrato;
        end if;

        --#123 verifica si tiene centro de costo
        if (pxp.f_existe_parametro(p_tabla,'id_centro_costo')) then
          v_id_centro_costo = v_parametros.id_centro_costo;
        end if;

        --#123 verifica si tiene aplicacion
        if (pxp.f_existe_parametro(p_tabla,'codigo_aplicacion')) then
          v_codigo_aplicacion = v_parametros.codigo_aplicacion;
        end if;


        --Sentencia de la insercion
        insert into vef.tventa(
          id_venta,
          id_cliente,
          id_sucursal,
          id_proceso_wf,
          id_estado_wf,
          estado_reg,
          nro_tramite,
          a_cuenta,
          fecha_estimada_entrega,
          usuario_ai,
          fecha_reg,
          id_usuario_reg,
          id_usuario_ai,
          id_usuario_mod,
          fecha_mod,
          estado,
          id_punto_venta,
          id_vendedor_medico,
          porcentaje_descuento,
          comision,
          observaciones,
          correlativo_venta,
          tipo_factura,
          fecha,
          nro_factura,
          id_dosificacion,
          excento,

          id_moneda,
          transporte_fob,
          seguros_fob,
          otros_fob,
          transporte_cif,
          seguros_cif,
          otros_cif,
          tipo_cambio_venta,
          valor_bruto,
          descripcion_bulto,
          nit,
          nombre_factura,
          id_cliente_destino,
          hora_estimada_entrega,
          tiene_formula,
          forma_pedido,
          id_proveedor,--#123
          id_contrato,--#123
          id_centro_costo,--#123
          codigo_aplicacion, --#123
          nit_internacional, --#123
          ncd,
          id_venta_fk
        ) values(
          v_id_venta,
          v_id_cliente,
          v_id_sucursal,
          v_id_proceso_wf,
          v_id_estado_wf,
          'activo',
          v_num_tramite,
          v_a_cuenta,
          v_fecha_estimada_entrega,
          v_parametros._nombre_usuario_ai,
          now(),
          p_id_usuario,
          v_parametros._id_usuario_ai,
          null,
          null,
          v_codigo_estado,
          v_id_punto_venta,
          v_id_vendedor_medico,
          v_porcentaje_descuento,
          v_comision,
          v_parametros.observaciones,
          v_num_ven,
          v_tipo_factura,
          v_fecha,
          v_nro_factura,
          v_id_dosificacion,
          v_excento,


          v_id_moneda_venta,
          COALESCE(v_transporte_fob,0),
          COALESCE(v_seguros_fob,0),
          COALESCE(v_otros_fob,0),
          COALESCE(v_transporte_cif,0),
          COALESCE(v_seguros_cif,0),
          COALESCE(v_otros_cif,0),
          COALESCE(v_tipo_cambio_venta,0),
          COALESCE(v_valor_bruto,0),
          COALESCE(v_descripcion_bulto,''),
          v_parametros.nit,
          v_nombre_factura,
          v_id_cliente_destino,
          v_hora_estimada_entrega,
          v_tiene_formula,
          v_forma_pedido,
          v_id_proveedor,--#123
          v_id_contrato,--#123
          v_id_centro_costo,--#123
          v_codigo_aplicacion, --#123
          v_nit_internacional, --#123
          v_ncd, --#123
          v_id_venta_fk   -- #123


        ) returning id_venta into v_id_venta;




        if (v_parametros.id_forma_pago != 0 ) then

          insert into vef.tventa_forma_pago(
            usuario_ai,
            fecha_reg,
            id_usuario_reg,
            id_usuario_ai,
            estado_reg,
            id_forma_pago,
            id_venta,
            monto_transaccion,
            monto,
            cambio,
            monto_mb_efectivo,
            numero_tarjeta,
            codigo_tarjeta,
            tipo_tarjeta
          )
          values(
            v_parametros._nombre_usuario_ai,
            now(),
            p_id_usuario,
            v_parametros._id_usuario_ai,
            'activo',
            v_parametros.id_forma_pago,
            v_id_venta,
            v_parametros.monto_forma_pago,
            0,
            0,
            0,
            v_parametros.numero_tarjeta,
            v_parametros.codigo_tarjeta,
            v_parametros.tipo_tarjeta
          );
        end if;


        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas almacenado(a) con exito (id_venta'||v_id_venta||')');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_id_venta::varchar);

        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_VEN_MOD'
     #DESCRIPCION:    Modificacion de registros
     #AUTOR:        admin
     #FECHA:        01-06-2015 05:58:00
    ***********************************/

    elsif(p_transaccion='VF_VEN_MOD')then

      begin

        select
          v.*
        into
          v_registros
        from vef.tventa v
        where v.id_venta = v_parametros.id_venta;

        --  #123    validar si viene de una nota de credito
        v_sw_ncd = false;
        v_ncd = 'no';
        if (pxp.f_existe_parametro(p_tabla,'id_venta_fk')) then
           v_sw_ncd = true;
           v_ncd = 'si';
           v_id_venta_fk = v_parametros.id_venta_fk;
        end if;


        v_nit_internacional = 'no'; --#123   para identificar proveedor con nit internacional, por defecto valor no

        v_tiene_formula = 'no';
        if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
          v_id_punto_venta = v_parametros.id_punto_venta;
        else
          v_id_punto_venta = NULL;
        end if;

        SELECT * into v_venta FROM vef.tventa v where v.id_venta = v_parametros.id_venta;

        if (v_id_punto_venta is not null) then
          select id_sucursal into v_id_sucursal
          from vef.tpunto_venta
          where id_punto_venta = v_id_punto_venta;
        else
          v_id_sucursal = v_parametros.id_sucursal;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'tipo_factura')) then
          v_tipo_factura = v_parametros.tipo_factura;
        else
          v_tipo_factura = 'recibo';
        end if;

        SELECT tv.tipo_base into v_tipo_base
        from vef.ttipo_venta tv
        where tv.codigo = v_tipo_factura and tv.estado_reg = 'activo';

        if (v_tipo_base is null) then
          raise exception 'No existe un tipo de venta con el codigo: % consulte con el administrador del sistema',v_tipo_factura;
        end if;

        v_excento = 0;

        if (pxp.f_existe_parametro(p_tabla,'id_moneda')) then
          v_id_moneda_venta = v_parametros.id_moneda;
        else
          if (v_parametros.id_sucursal is not null ) then
            select sm.id_moneda into v_id_moneda_venta
            from vef.tsucursal_moneda sm
            where sm.id_sucursal = v_parametros.id_sucursal
                  and sm.estado_reg = 'activo' and sm.tipo_moneda = 'moneda_base';
          else
            select sm.id_moneda into v_id_moneda_venta
            from vef.tsucursal_moneda sm
              inner join vef.tpunto_venta pv on pv.id_sucursal = sm.id_sucursal
            where pv.id_punto_venta = v_parametros.id_punto_venta
                  and sm.estado_reg = 'activo' and sm.tipo_moneda = 'moneda_base';
          end if;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'transporte_fob')) then
          v_transporte_fob = v_parametros.transporte_fob;
          v_seguros_fob = v_parametros.seguros_fob;
          v_otros_fob = v_parametros.otros_fob;
          v_transporte_cif = v_parametros.transporte_cif;
          v_seguros_cif = v_parametros.seguros_cif;
          v_otros_cif = v_parametros.otros_cif;
          v_descripcion_bulto = v_parametros.descripcion_bulto;
          v_valor_bruto = v_parametros.valor_bruto;

        end if;


        if (pxp.f_existe_parametro(p_tabla,'tipo_cambio_venta')) then
          v_tipo_cambio_venta = v_parametros.tipo_cambio_venta;
        end if;


        IF(v_tipo_base = 'manual') then
          v_fecha = v_parametros.fecha;
          v_nro_factura = v_parametros.nro_factura;

          IF  pxp.f_existe_parametro(p_tabla, 'excento') THEN  --#123 validacion del excento
               v_excento = v_parametros.excento;
          ELSE
               v_excento = 0;
          END IF;

          v_id_dosificacion = v_parametros.id_dosificacion;
        elsif (v_tipo_base = 'computarizada')  then

          IF   v_tipo_factura in ('computarizadaexpo','computarizadaexpomin','computarizadamin','computarizadareg')  THEN
            v_fecha = v_parametros.fecha;
            v_nro_factura = v_venta.nro_factura;
            v_id_dosificacion = v_venta.id_dosificacion;

          ELSE
            IF  pxp.f_existe_parametro(p_tabla, 'excento') THEN  --#123 validacion del excento
               v_excento = v_parametros.excento;
            ELSE
               v_excento = 0;
            END IF;
          END IF;

        end if;

        /* Lanzar exception al tratar de modificar la fecha de una venta computarizada*/
        if (v_fecha is not null and v_fecha != v_registros.fecha and v_tipo_base = 'computarizada') then
          raise exception 'No es posible modificar la fecha de una venta computarizada';
        end if;

        if (pxp.f_existe_parametro(p_tabla,'a_cuenta')) then
          v_a_cuenta = v_parametros.a_cuenta;
        else
          v_a_cuenta = 0;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'comision')) then
          v_comision = v_parametros.comision;
        else
          v_comision = 0;
        end if;

        v_porcentaje_descuento = 0;
        --verificar si existe porcentaje de descuento
        if (pxp.f_existe_parametro(p_tabla,'porcentaje_descuento')) then
          v_porcentaje_descuento = v_parametros.porcentaje_descuento;
        end if;

        v_id_vendedor_medico = NULL;
        if (pxp.f_existe_parametro(p_tabla,'id_vendedor_medico')) then
          v_id_vendedor_medico = v_parametros.id_vendedor_medico;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'fecha_estimada_entrega')) then
          v_fecha_estimada_entrega = v_parametros.fecha_estimada_entrega;
          if (v_fecha_estimada_entrega is not null) then
            v_tiene_formula = 'si';
          else
            v_fecha_estimada_entrega = now();
          end if;
        else
          v_fecha_estimada_entrega = now();
        end if;

        if (pxp.f_existe_parametro(p_tabla,'hora_estimada_entrega')) then

          if (v_parametros.hora_estimada_entrega is not null and v_parametros.hora_estimada_entrega != '') then

            v_hora_estimada_entrega = (v_parametros.hora_estimada_entrega || ':00')::time;
          else
            v_hora_estimada_entrega = NULL;
          end if;
        else
          v_hora_estimada_entrega = now()::time;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'forma_pedido')) then
          v_forma_pedido = v_parametros.forma_pedido;
        else
          v_forma_pedido =NULL;
        end if;

         --#123 verifica si no existe el id_cliente entonces es id_proveedor
        IF  pxp.f_existe_parametro(p_tabla,'id_cliente')  THEN

              if (pxp.f_is_positive_integer(v_parametros.id_cliente)) THEN
                v_id_cliente = v_parametros.id_cliente::integer;

                update vef.tcliente
                set nit = v_parametros.nit
                where id_cliente = v_id_cliente;

                select c.nombre_factura into v_nombre_factura
                from vef.tcliente c
                where c.id_cliente = v_id_cliente;
              else
                INSERT INTO
                  vef.tcliente
                  (
                    id_usuario_reg,
                    fecha_reg,
                    estado_reg,
                    nombre_factura,
                    nit
                  )
                VALUES (
                  p_id_usuario,
                  now(),
                  'activo',
                  v_parametros.id_cliente,
                  v_parametros.nit
                ) returning id_cliente into v_id_cliente;

                v_nombre_factura = v_parametros.id_cliente;
              end if;
         ELSE

              v_id_proveedor = v_parametros.id_proveedor;


                select
                     p.desc_proveedor2,
                     tp.internacional
                into
                v_nombre_factura,
                v_nit_internacional
                from param.vproveedor p
                inner join param.tproveedor tp on tp.id_proveedor = p.id_proveedor
                where p.id_proveedor =  v_parametros.id_proveedor::integer;



         END IF; --#123

             v_id_cliente_destino = null;

            --si tenemos cliente destino
           if v_tipo_factura = 'pedido' then
                 if (pxp.f_is_positive_integer(v_parametros.id_cliente_destino)) THEN
                    v_id_cliente_destino = v_parametros.id_cliente_destino::integer;
                  else

                    INSERT INTO
                      vef.tcliente
                    (
                      id_usuario_reg,
                      fecha_reg,
                      estado_reg,
                      nombre_factura
                    )
                    VALUES (
                      p_id_usuario,
                      now(),
                      'activo',
                      v_parametros.id_cliente
                    ) returning id_cliente into v_id_cliente_destino;


                end if;
           end if;

         --#123  verifica si tiene contrato
        if (pxp.f_existe_parametro(p_tabla,'id_contrato')) then
          v_id_contrato = v_parametros.id_contrato;
        end if;

        --#123 verifica si tiene centro de costo
        if (pxp.f_existe_parametro(p_tabla,'id_centro_costo')) then
          v_id_centro_costo = v_parametros.id_centro_costo;
        end if;

        --#123 verifica si tiene aplicacion
        if (pxp.f_existe_parametro(p_tabla,'codigo_aplicacion')) then
          v_codigo_aplicacion = v_parametros.codigo_aplicacion;
        end if;



        --Sentencia de la modificacion
        update vef.tventa set
          id_cliente = v_id_cliente,
          id_sucursal = v_id_sucursal,
          a_cuenta = v_a_cuenta,
          fecha_estimada_entrega = v_fecha_estimada_entrega,
          hora_estimada_entrega = v_hora_estimada_entrega,
          id_usuario_mod = p_id_usuario,
          fecha_mod = now(),
          id_usuario_ai = v_parametros._id_usuario_ai,
          usuario_ai = v_parametros._nombre_usuario_ai,
          id_punto_venta = v_id_punto_venta,
          id_vendedor_medico = v_id_vendedor_medico,
          porcentaje_descuento = v_porcentaje_descuento,
          comision = v_comision,
          tiene_formula = v_tiene_formula,
          observaciones = v_parametros.observaciones,
          forma_pedido = v_forma_pedido,
          fecha = (case when v_fecha is null then
            fecha
                   else
                     v_fecha
                   end),
          nro_factura = v_nro_factura,
          id_dosificacion = v_id_dosificacion,
          excento = v_excento,

          id_moneda = v_id_moneda_venta,
          transporte_fob = COALESCE(v_transporte_fob,0),
          seguros_fob = COALESCE(v_seguros_fob,0),
          otros_fob = COALESCE(v_otros_fob,0),
          transporte_cif = COALESCE(v_transporte_cif,0),
          seguros_cif = COALESCE(v_seguros_cif,0),
          otros_cif = COALESCE(v_otros_cif,0),
          tipo_cambio_venta = COALESCE(v_tipo_cambio_venta,1),
          valor_bruto = COALESCE(v_valor_bruto,0),
          descripcion_bulto = COALESCE(v_descripcion_bulto,''),
          nit = v_parametros.nit,
          nombre_factura = v_nombre_factura ,
          id_cliente_destino = v_id_cliente_destino,
          id_proveedor = v_id_proveedor ,           -- #123 add id_proveedor
          nit_internacional = v_nit_internacional,  -- #123
          id_venta_fk = v_id_venta_fk,              -- #123
          id_centro_costo = v_id_centro_costo,      -- #123
          codigo_aplicacion = v_codigo_aplicacion,  -- #123
          id_contrato = v_id_contrato               -- #123
        where id_venta=v_parametros.id_venta;






        if (v_parametros.id_forma_pago != 0 ) then

          delete from vef.tventa_forma_pago
          where id_venta = v_parametros.id_venta;

          insert into vef.tventa_forma_pago(
            usuario_ai,
            fecha_reg,
            id_usuario_reg,
            id_usuario_ai,
            estado_reg,
            id_forma_pago,
            id_venta,
            monto_transaccion,
            monto,
            cambio,
            monto_mb_efectivo,
            numero_tarjeta,
            codigo_tarjeta,
            tipo_tarjeta
          )
          values(
            v_parametros._nombre_usuario_ai,
            now(),
            p_id_usuario,
            v_parametros._id_usuario_ai,
            'activo',
            v_parametros.id_forma_pago,
            v_parametros.id_venta,
            v_parametros.monto_forma_pago,
            0,
            0,
            0,
            v_parametros.numero_tarjeta,
            v_parametros.codigo_tarjeta,
            v_parametros.tipo_tarjeta
          );
        end if;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas modificado(a)');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);

        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_VEN_ELI'
     #DESCRIPCION:    Eliminacion de registros
     #AUTOR:        admin
     #FECHA:        01-06-2015 05:58:00
    ***********************************/

    elsif(p_transaccion='VF_VEN_ELI')then

      begin


        select
          v.*
        into
          v_registros  from vef.tventa v
        where v.id_venta = v_parametros.id_venta;



        IF  v_registros.tipo_factura not in  ('computarizadaexpo','computarizadaexpomin','computarizadamin','computarizadareg') THEN



          --Sentencia de la eliminacion
          delete from vef.tventa_forma_pago
          where id_venta=v_parametros.id_venta;

          delete from vef.tventa_detalle
          where id_venta=v_parametros.id_venta;

          update vef.tventa
          set estado_reg = 'inactivo'
          where id_venta=v_parametros.id_venta;
        ELSE
          v_res = vef.f_anula_venta(p_administrador,p_id_usuario,p_tabla, v_registros.id_proceso_wf,v_registros.id_estado_wf, v_parametros.id_venta);

        END IF;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas eliminado(a)');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);

        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_VEALLFORPA_ELI'
     #DESCRIPCION:    Eliminacion de formas de pago relacionadas a una venta
     #AUTOR:        admin
     #FECHA:        01-06-2015 05:58:00
    ***********************************/

    elsif(p_transaccion='VF_VEALLFORPA_ELI')then

      begin
        --Sentencia de la eliminacion
        delete from vef.tventa_forma_pago
        where id_venta=v_parametros.id_venta;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas forma de pago eliminado(a)');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);

        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_VEALLDET_ELI'
     #DESCRIPCION:    Eliminacion de los detalles relacionados a una venta
     #AUTOR:        admin
     #FECHA:        01-06-2015 05:58:00
    ***********************************/

    elsif(p_transaccion='VF_VEALLDET_ELI')then

      begin
        --Sentencia de la eliminacion
        delete from vef.tventa_detalle
        where id_venta=v_parametros.id_venta;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas detalle eliminado(a)');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);

        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_VENVALI_MOD'
     #DESCRIPCION:    Validacion de montos en una venta
     #AUTOR:        admin
     #FECHA:        01-06-2015 05:58:00
    ***********************************/

    elsif(p_transaccion='VF_VENVALI_MOD')then

      begin
         vef_estados_validar_fp = pxp.f_get_variable_global('vef_estados_validar_fp');
        --obtener datos de la venta y la moneda base

        --#123    validar si biene de para el registros de uan nota de credito
        v_sw_ncd = false;
        if (pxp.f_existe_parametro(p_tabla,'id_venta_fk')) then
           v_sw_ncd = true;
           v_id_venta_fk = v_parametros.id_venta_fk;
        end if;

        select
          v.* ,
          sm.id_moneda as id_moneda_base,
          m.codigo  as moneda ,
          v.id_dosificacion as id_dosificacion_venta
        into
          v_venta
        from vef.tventa v
          inner join vef.tsucursal suc on suc.id_sucursal = v.id_sucursal
          inner join vef.tsucursal_moneda sm on suc.id_sucursal = sm.id_sucursal and sm.tipo_moneda = 'moneda_base'
          inner join param.tmoneda m on m.id_moneda = sm.id_moneda
        where id_venta = v_parametros.id_venta;

        --si es venta de exportacion operamos con la moneda especificada por el usuario
        IF  v_venta.tipo_factura in ('computarizadaexpo','computarizadaexpomin') THEN
          v_id_moneda_venta = v_venta.id_moneda;
        ELSE
          v_id_moneda_venta = v_venta.id_moneda_base;
        END IF;

        v_id_moneda_suc = v_venta.id_moneda_base;

        --Validar que solo haya conceptos contabilizables o no contabilizables
        select count(distinct sp.contabilizable) into v_cantidad
        from vef.tventa_detalle vd
          left join vef.tsucursal_producto sp on sp.id_sucursal_producto = vd.id_sucursal_producto
        where vd.id_venta = v_parametros.id_venta;



       IF not v_sw_ncd THEN   --#123 validar solo si no es una nota de credito
          if (v_cantidad > 1) then
            raise exception 'No puede utilizar conceptos contabilizables y no contabilizables en la misma venta';
          else
            update vef.tventa set contabilizable = (
              select distinct sp.contabilizable
              from vef.tventa_detalle vd
                left join vef.tsucursal_producto sp on sp.id_sucursal_producto = vd.id_sucursal_producto
              where vd.id_venta = v_parametros.id_venta)
            where id_venta = v_parametros.id_venta;
          end if;


        ELSE
            --#123 si es una nota de credito sobre venta tenemos que validar , que la factura no haya sido devuelta es mas de un 50%, (peude ser acumulado)
           --obteermos la variable global de configuracion de porcentaje permitido
           v_vef_por_per_ncd =  pxp.f_get_variable_global('vef_porcentaje_permitodo_ncd');

           --calcula el porcetaje de la factura vinculada
           select
              vef.total_venta into v_total_venta
           from  vef.tventa vef
           where vef.estado_reg = 'activo' and vef.id_venta = v_id_venta_fk;

           --calcularmos todas las notas de credito
           select
              sum(vef.total_venta) into v_total_venta_ncd
           from vef.tventa vef
           where  vef.estado_reg = 'activo' and vef.id_venta_fk = v_id_venta_fk;
           --si el total de la notas de credito sobre pasa el porcetaje permitido, mostramos un error
           IF  v_total_venta_ncd > (v_total_venta * (v_vef_por_per_ncd::numeric))  THEN
              raise exception 'El total de NCD no puede superar el % %' , (v_vef_por_per_ncd::numeric)*100,'%';
           END IF;

        END IF;


        select count(*) into v_cantidad
        from vef.tventa_detalle vd
          left join vef.tsucursal_producto sp on sp.id_sucursal_producto = vd.id_sucursal_producto
        where vd.id_venta = v_parametros.id_venta and sp.excento= 'si';



        --Validar que si hay un concepto con excento el importe excento no sea 0
        if (v_cantidad > 0 and v_venta.excento = 0) then
          raise exception 'Tiene un concepto que requiere un importe excento y el importe excento para esta venta es 0';
        end if;

        --Validar que si el excento no es 0 que haya un concepto que tenga excento
        if (v_cantidad = 0 and v_venta.tipo_factura not in ('computarizadaexpo','computarizadaexpomin') and v_venta.excento > 0) then
          raise exception 'No tiene ningun concepto que requiera excento. El excento no puede ser mayor a 0 para esta venta';
        end if;

        --Validar que el excento no es mayor que el valor total de la venta

        if (v_venta.excento > v_venta.total_venta_msuc) then
          raise exception 'El importe excento no puede ser mayor al total de la venta%,%',v_venta.excento,v_venta.total_venta;
        end if;

        --si es un estado para validar la forma de pago
        if (v_venta.estado =ANY(string_to_array(vef_estados_validar_fp,',')))then

          select count(*) into v_cantidad_fp
          from vef.tventa_forma_pago
          where id_venta =   v_parametros.id_venta;

          --lo que ya se pago es igual a lo que se tenia a cuenta, suponiendo q esta en la moneda base
          v_acumulado_fp = v_venta.a_cuenta;

          for v_registros in (select vfp.id_venta_forma_pago, fp.id_moneda,vfp.monto_transaccion
                              from vef.tventa_forma_pago vfp
                                inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
                              where vfp.id_venta = v_parametros.id_venta)loop
            --si la moneda de la forma de pago es distinta a al moneda base de la sucursal convertimos a moneda base

            if (v_registros.id_moneda != v_id_moneda_venta) then

              IF  v_venta.tipo_cambio_venta is not null and v_venta.tipo_cambio_venta != 0 THEN
                v_monto_fp = param.f_convertir_moneda(v_registros.id_moneda,v_id_moneda_venta,v_registros.monto_transaccion,v_venta.fecha::date,'CUS',2, v_venta.tipo_cambio_venta,'si');
              ELSE
                v_monto_fp = param.f_convertir_moneda(v_registros.id_moneda,v_id_moneda_venta,v_registros.monto_transaccion,v_venta.fecha::date,'O',2,NULL,'si');
              END IF;
            else
              v_monto_fp = v_registros.monto_transaccion;
            end if;

            --si el monto de una d elas formas de pago es mayor q el total de la venta y la cantidad de formas de pago es mayor a 1 lanzo excepcion
            if (v_monto_fp >= v_venta.total_venta and v_cantidad_fp > 1) then
              raise exception 'Se ha definido mas de una forma de pago, pero existe una que supera el valor de la venta(solo se requiere una forma de pago)';
            end if;

            update vef.tventa_forma_pago set
              monto = v_monto_fp,
              cambio = (case when (v_monto_fp + v_acumulado_fp - v_venta.total_venta) > 0 then
                (v_monto_fp + v_acumulado_fp - v_venta.total_venta)
                        else
                          0
                        end),
              monto_mb_efectivo = (case when (v_monto_fp + v_acumulado_fp - v_venta.total_venta) > 0 then
                v_monto_fp - (v_monto_fp + v_acumulado_fp - v_venta.total_venta)
                                   else
                                     v_monto_fp
                                   end)
            where id_venta_forma_pago = v_registros.id_venta_forma_pago;
            v_acumulado_fp = v_acumulado_fp + v_monto_fp;
          end loop;

          select sum(round(monto_mb_efectivo,2)) into v_suma_fp
          from vef.tventa_forma_pago
          where id_venta =   v_parametros.id_venta;

          --raise exception '%',v_suma_fp;

          select sum(round(cantidad*precio,2)) into v_suma_det
          from vef.tventa_detalle
          where id_venta =   v_parametros.id_venta;



          IF v_parametros.tipo_factura != 'computarizadaexpo' THEN
            v_suma_det = COALESCE(v_suma_det,0) + COALESCE(v_venta.transporte_fob ,0)  + COALESCE(v_venta.seguros_fob ,0)+ COALESCE(v_venta.otros_fob ,0) + COALESCE(v_venta.transporte_cif ,0) +  COALESCE(v_venta.seguros_cif ,0) + COALESCE(v_venta.otros_cif ,0);
          END IF;


          if (v_suma_fp < v_venta.total_venta) then
            raise exception 'El importe recibido es menor al valor de la venta, falta %', v_venta.total_venta - v_suma_fp;
          end if;

          if (v_suma_fp > v_venta.total_venta) then
            raise exception 'El total de la venta no coincide con la división por forma de pago%',v_suma_fp;
          end if;

          if (v_suma_det != v_venta.total_venta) then
            raise exception 'El total de la venta no coincide con la suma de los detalles (% = %) en id: %',v_suma_det ,v_venta.total_venta, v_parametros.id_venta;
          end if;
        end if;

        select sum(cambio) into v_suma_fp
        from vef.tventa_forma_pago
        where id_venta =   v_parametros.id_venta;

          --#123  se bloquea que el monto percibido pueda ser mayor que el pagado
          if (v_suma_fp >0) then
            raise exception 'El importe recibido es mayor al valor de la venta, sobra %',  v_suma_fp;
          end if;



        --calcula el total de la venta en moenda de la sucursal

        IF  v_venta.tipo_cambio_venta is not null THEN
          v_total_venta_ms = param.f_convertir_moneda(v_id_moneda_venta,v_id_moneda_suc,v_venta.total_venta,v_venta.fecha,'CUS',2, v_venta.tipo_cambio_venta,'si');
        ELSE
          v_total_venta_ms = param.f_convertir_moneda(v_id_moneda_venta,v_id_moneda_suc,v_registros.monto_transaccion,v_venta.fecha::date,'O',2,NULL,'si');
        END IF;

        update vef.tventa v set
          total_venta_msuc = v_total_venta_ms
        where v.id_venta = v_parametros.id_venta;

        --si es factura comercial de exportacion generamos el numero de factura y validamos la fecha
        IF  v_venta.tipo_factura in ('computarizadaexpo','computarizadaexpomin','computarizadamin','computarizadareg') THEN
          IF  v_venta.tipo_factura in ('computarizadaexpo','computarizadaexpomin') THEN
            update vef.tventa v set
              excento = total_venta_msuc
            where v.id_venta = v_parametros.id_venta;
          END IF;
          -- si es eidicion ya tendremos un numeor de factura que no debemos cambiar
          IF  v_venta.nro_factura is null THEN

                    SELECT
                        MAX(v.fecha)
                    INTO
                    v_fecha
                    FROM vef.tventa v
                    WHERE v.tipo_factura = v_venta.tipo_factura
                    and v.estado != 'anulado'
                    and v.id_sucursal = v_venta.id_sucursal
                    and v.estado_reg = 'activo'
                    and v.id_venta <> v_parametros.id_venta;

            if (EXISTS(select 1
                       from vef.tventa v
                       where v.fecha > v_venta.fecha and v.tipo_factura = v_venta.tipo_factura
                             and v.estado != 'anulado'
                             and v.id_sucursal = v_venta.id_sucursal
                             and v.estado_reg = 'activo'))THEN
              raise exception 'Existen facturas emitidas con fechas posterior a la registrada (%). Por favor revise la fecha y hora del sistema (%..%)',v_fecha, v_venta.fecha, v_venta.tipo_factura;
            end if;


            select array_agg(distinct cig.id_actividad_economica) into v_id_actividad_economica
            from vef.tventa_detalle vd
              inner join vef.tsucursal_producto sp on vd.id_sucursal_producto = sp.id_sucursal_producto
              inner join param.tconcepto_ingas cig on  cig.id_concepto_ingas = sp.id_concepto_ingas
            where vd.id_venta = v_venta.id_venta and vd.estado_reg = 'activo';



            select d.* into v_dosificacion
            from vef.tdosificacion d
            where d.estado_reg = 'activo' and d.fecha_inicio_emi <= v_venta.fecha and
                  d.fecha_limite >= v_venta.fecha and d.tipo = 'F' and d.tipo_generacion = 'computarizada' and
                  d.id_sucursal = v_venta.id_sucursal and
                  d.id_activida_economica @> v_id_actividad_economica FOR UPDATE;


            v_nro_factura = v_dosificacion.nro_siguiente;


            if (v_dosificacion is null) then
              raise exception 'No existe una dosificacion activa para emitir la factura';
            end if;
            --validar que el nro de factura no supere el maximo nro de factura de la dosificaiocn
            if (exists(    select 1
                         from vef.tventa ven
                         where ven.nro_factura =  v_nro_factura and ven.id_dosificacion = v_dosificacion.id_dosificacion)) then
              raise exception 'El numero de factura ya existe para esta dosificacion. Por favor comuniquese con el administrador del sistema';
            end if;


            update vef.tventa v set
              nro_factura = v_nro_factura,
              id_dosificacion = v_dosificacion.id_dosificacion
            where v.id_venta = v_parametros.id_venta;

            update vef.tdosificacion
            set nro_siguiente = nro_siguiente + 1
            where id_dosificacion = v_dosificacion.id_dosificacion;



          ELSE
            --validar que la actividad economica no varie con respecto la insertada inicialmente que la fecha n



            select
              *
            into
              v_dosificacion
            from vef.tdosificacion dos
            where dos.id_dosificacion = v_venta.id_dosificacion_venta;



            IF exists(select 1
                      from vef.tventa_detalle vd
                        inner join vef.tsucursal_producto sp on vd.id_sucursal_producto = sp.id_sucursal_producto
                        inner join param.tconcepto_ingas cig on  cig.id_concepto_ingas = sp.id_concepto_ingas
                      where vd.id_venta = v_venta.id_venta and vd.estado_reg = 'activo'
                            AND  cig.id_actividad_economica != ANY(v_dosificacion.id_activida_economica)

            ) THEN


              raise exception 'El nro de facura fue generado para la actividad economica: no puede introducir otros conceptos pertenecientes a otra actividad';

            END IF;

          END IF;



          --si es factura de exportacion minera insertamos descripcion por defecto
          IF   v_venta.tipo_factura in ('computarizadaexpomin','computarizadamin') THEN



            FOR v_reg_tipo_desc in (select
                                      td.*
                                    from vef.ttipo_descripcion td
                                    where td.id_sucursal = v_venta.id_sucursal and td.estado_reg = 'activo') LOOP


              --si el valor no exite lo insertamos
              IF  not exists (select 1 from vef.tvalor_descripcion vd
              where vd.id_tipo_descripcion =   v_reg_tipo_desc.id_tipo_descripcion
                    and vd.id_venta  =  v_venta.id_venta)   THEN


                INSERT INTO  vef.tvalor_descripcion
                (
                  id_usuario_reg,
                  fecha_reg,
                  estado_reg,
                  id_venta,
                  id_tipo_descripcion
                )
                VALUES (
                  p_id_usuario,
                  now(),
                  'activo',
                  v_venta.id_venta,
                  v_reg_tipo_desc.id_tipo_descripcion
                );


              END IF;


            END LOOP;

          END IF;


        END IF;

        --#123   29/09/2018
        --... se migran solo facturas con autizacion, nro y fecha  por que al no tener numero  de factura el LCV no permite registrar mas de dos facturas en borrador
        --    Se insertar el LCV al momento de generar el numero de la factura y codigo de control solamente

        if (pxp.f_get_variable_global('vef_integracion_lcv') = 'si')   AND   v_venta.tipo_factura in ('computarizadaexpo','computarizadaexpomin','computarizadamin','computarizadareg') THEN
          v_res = vef.f_inserta_lcv(p_administrador,p_id_usuario,p_tabla,'INS',v_parametros.id_venta);
        end if;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Venta Validada');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);

        if (v_venta.estado =ANY(string_to_array(vef_estados_validar_fp,',')) and v_suma_fp > 0)then
          v_resp = pxp.f_agrega_clave(v_resp,'cambio',(v_suma_fp::varchar || ' ' || v_venta.moneda)::varchar);
        end if;

        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VEF_ANTEVE_IME'
     #DESCRIPCION:    Transaccion utilizada  pasar a  estados anterior en la venta
                    segun la operacion definida
     #AUTOR:        JRR
     #FECHA:        17-10-2014 12:12:51
    ***********************************/

    elseif(p_transaccion='VEF_ANTEVE_IME')then
      begin

        --------------------------------------------------
        --Retrocede al estado inmediatamente anterior
        -------------------------------------------------
       --recuperaq estado anterior segun Log del WF
        SELECT
          ps_id_tipo_estado,
          ps_id_funcionario,
          ps_id_usuario_reg,
          ps_id_depto,
          ps_codigo_estado,
          ps_id_estado_wf_ant
        into
          v_id_tipo_estado,
          v_id_funcionario,
          v_id_usuario_reg,
          v_id_depto,
          v_codigo_estado,
          v_id_estado_wf_ant
        FROM wf.f_obtener_estado_ant_log_wf(v_parametros.id_estado_wf);
        --
        select
          ew.id_proceso_wf
        into
          v_id_proceso_wf
        from wf.testado_wf ew
        where ew.id_estado_wf= v_id_estado_wf_ant;

        --configurar acceso directo para la alarma
        v_acceso_directo = '';
        v_clase = '';
        v_parametros_ad = '';
        v_tipo_noti = 'notificacion';
        v_titulo  = 'Notificacion';

        -- registra nuevo estado
        v_id_estado_actual = wf.f_registra_estado_wf(
            v_id_tipo_estado,
            v_id_funcionario,
            v_parametros.id_estado_wf,
            v_id_proceso_wf,
            p_id_usuario,
            v_parametros._id_usuario_ai,
            v_parametros._nombre_usuario_ai,
            v_id_depto,
            '[RETROCESO] '|| v_parametros.obs,
            v_acceso_directo,
            v_clase,
            v_parametros_ad,
            v_tipo_noti,
            v_titulo);

        IF  vef.f_fun_regreso_venta_wf(p_id_usuario,
                                       v_parametros._id_usuario_ai,
                                       v_parametros._nombre_usuario_ai,
                                       v_id_estado_actual,
                                       v_id_proceso_wf,
                                       v_codigo_estado) THEN

        END IF;

        -- si hay mas de un estado disponible  preguntamos al usuario
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado)');
        v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

        --Devuelve la respuesta
        return v_resp;
      end;
    /*********************************
     #TRANSACCION:  'VEF_SIGEVE_IME'
     #DESCRIPCION:    funcion que controla el cambio al Siguiente estado de las ventas, integrado  con el WF
     #AUTOR:        JRR
     #FECHA:        17-10-2014 12:12:51
    ***********************************/

    elseif(p_transaccion='VEF_SIGEVE_IME')then
      begin

        /*   PARAMETROS

       $this->setParametro('id_proceso_wf_act','id_proceso_wf_act','int4');
       $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
       $this->setParametro('id_funcionario_wf','id_funcionario_wf','int4');
       $this->setParametro('id_depto_wf','id_depto_wf','int4');
       $this->setParametro('obs','obs','text');
       $this->setParametro('json_procesos','json_procesos','text');
       */


        select
          ew.id_tipo_estado ,
          ew.id_estado_wf
        into
          v_id_tipo_estado,
          v_id_estado_wf


        from wf.testado_wf ew
          inner join wf.ttipo_estado te on te.id_tipo_estado = ew.id_tipo_estado
        where ew.id_estado_wf =  v_parametros.id_estado_wf_act;

        select
           v.*,s.id_entidad,tv.tipo_base into v_venta
        from vef.tventa v
          inner join vef.tsucursal s on s.id_sucursal = v.id_sucursal
          inner join vef.ttipo_venta tv on tv.codigo = v.tipo_factura and tv.estado_reg = 'activo'
          left  join vef.tcliente c on c.id_cliente = v.id_cliente
        where v.id_proceso_wf = v_parametros.id_proceso_wf_act;


        -- #123  identifica si es una nota de credito o no
        IF v_venta.id_venta_fk is null THEN
           v_ncd = 'no';
        ELSE
           v_ncd = 'si';
        END IF;

       IF v_venta.nit is null THEN
          raise exception 'el nit no puede ser nulo';  --NIT internacional
       END IF;

       -- raise EXCEPTION 'sasdasd  1 %, 2 %, 3 %, 4 %, 5 %', coalesce(v_parametros._nombre_usuario_ai,'x'), v_parametros._id_usuario_ai   ,v_venta.id_venta ,v_venta.tipo_factura, v_venta.id_venta_fk  ;

       v_tabla = pxp.f_crear_parametro(ARRAY[    '_nombre_usuario_ai',
                                                '_id_usuario_ai',
                                                'id_venta',
                                                'tipo_factura',
                                                'id_venta_fk'
                                              ],
                                       ARRAY[    coalesce(v_parametros._nombre_usuario_ai,''),
                                                coalesce(v_parametros._id_usuario_ai::varchar,''),
                                                v_venta.id_venta::varchar,
                                                v_venta.tipo_factura,
                                                coalesce(v_venta.id_venta_fk::varchar,'')::varchar
                                            ],
                                      ARRAY[    'varchar',
                                                'integer',
                                                'integer',
                                                'varchar',
                                                'integer'
                                            ]
        );


        v_resp = vef.ft_venta_ime(p_administrador,p_id_usuario,v_tabla,'VF_VENVALI_MOD');

        -- obtener datos tipo estado

        select
          te.codigo,te.fin
        into
          v_codigo_estado_siguiente,v_es_fin
        from wf.ttipo_estado te
        where te.id_tipo_estado = v_parametros.id_tipo_estado;

        IF  pxp.f_existe_parametro(p_tabla,'id_depto_wf') THEN

          v_id_depto = v_parametros.id_depto_wf;

        END IF;

        IF  pxp.f_existe_parametro(p_tabla,'obs') THEN
          v_obs=v_parametros.obs;
        ELSE
          v_obs='---';
        END IF;

        --configurar acceso directo para la alarma
        v_acceso_directo = '';
        v_clase = '';
        v_parametros_ad = '';
        v_tipo_noti = 'notificacion';
        v_titulo  = 'Visto Bueno';

        -- hay que recuperar el supervidor que seria el estado inmediato,...
        v_id_estado_actual =  wf.f_registra_estado_wf(v_parametros.id_tipo_estado,
                                                      v_parametros.id_funcionario_wf,
                                                      v_parametros.id_estado_wf_act,
                                                      v_parametros.id_proceso_wf_act,
                                                      p_id_usuario,
                                                      v_parametros._id_usuario_ai,
                                                      v_parametros._nombre_usuario_ai,
                                                      v_id_depto,
                                                      v_obs,
                                                      v_acceso_directo ,
                                                      v_clase,
                                                      v_parametros_ad,
                                                      v_tipo_noti,
                                                      v_titulo);


        IF  vef.f_fun_inicio_venta_wf(p_id_usuario,
                                      v_parametros._id_usuario_ai,
                                      v_parametros._nombre_usuario_ai,
                                      v_id_estado_actual,
                                      v_parametros.id_proceso_wf_act,
                                      v_codigo_estado_siguiente) THEN

        END IF;

        if (v_venta.tipo_base = 'computarizada' and v_es_fin = 'si') then

          IF v_venta.tipo_factura not in ('computarizadaexpo','computarizadaexpomin','computarizadamin', 'computarizadareg') THEN
            v_fecha_venta = now()::date;
            if (EXISTS(    select 1
                         from vef.tventa v
                         where v.fecha > v_fecha_venta and v.tipo_factura = 'computarizada' and
                               v.estado_reg = 'activo' and v.estado = 'finalizado'))THEN
              raise exception 'Existen facturas emitidas con fechas posterior a la actual. Por favor revise la fecha y hora del sistema';
            end if;
          ELSE
            v_fecha_venta = v_venta.fecha;
          --no validamos la fecha en las facturas de exportacion
          --por que  valida al insertar la factura, donde se genera el nro de la factura
          END IF;

          -- #123 si no es una nota de credito botiene de sucusal producto la actividad economica
          IF v_ncd = 'no' THEN
              select array_agg(distinct cig.id_actividad_economica) into v_id_actividad_economica
              from vef.tventa_detalle vd
                inner join vef.tsucursal_producto sp on vd.id_sucursal_producto = sp.id_sucursal_producto
                inner join param.tconcepto_ingas cig on  cig.id_concepto_ingas = sp.id_concepto_ingas
              where vd.id_venta = v_venta.id_venta and vd.estado_reg = 'activo';
              v_tipo_dosificacion = 'F';-- #123
          ELSE
             --#123 para notas de credito la actividad economica se dereiva de la factura relacionada

             select array_agg(distinct cig.id_actividad_economica) into v_id_actividad_economica
             from vef.tventa_detalle vd
                inner join vef.tventa_detalle vdo on vdo.id_venta_detalle = vd.id_venta_detalle_fk
                inner join vef.tsucursal_producto sp on vdo.id_sucursal_producto = sp.id_sucursal_producto
                inner join param.tconcepto_ingas cig on  cig.id_concepto_ingas = sp.id_concepto_ingas
             where vd.id_venta = v_venta.id_venta and vd.estado_reg = 'activo';
             v_tipo_dosificacion = 'N';

             --raise exception 'v_id_actividad_economica.....(%)',v_id_actividad_economica;

          END IF;
          --genera el numero de factura

          IF v_venta.tipo_factura not in ('computarizadaexpo','computarizadaexpomin','computarizadamin','computarizadareg') THEN  --#123 se agrega el tipo computarizadareg

                select d.* into v_dosificacion
                from vef.tdosificacion d
                where d.estado_reg = 'activo' and d.fecha_inicio_emi <= v_venta.fecha and
                      d.fecha_limite >= v_venta.fecha and d.tipo = v_tipo_dosificacion and d.tipo_generacion = 'computarizada' and    --#123 considerar tipo de sosificcion
                      d.id_sucursal = v_venta.id_sucursal and
                      d.id_activida_economica @> v_id_actividad_economica FOR UPDATE;

                v_nro_factura = v_dosificacion.nro_siguiente;


                if (v_dosificacion is null) then
                  raise exception 'No existe una dosificacion activa para emitir la factura';
                end if;
                --validar que el nro de factura no supere el maximo nro de factura de la dosificaiocn
                if (exists(    select 1
                             from vef.tventa ven
                             where ven.nro_factura =  v_dosificacion.nro_siguiente and ven.id_dosificacion = v_dosificacion.id_dosificacion)) then
                  raise exception 'El numero de factura ya existe para esta dosificacion. Por favor comuniquese con el administrador del sistema';
                end if;

                IF  v_ncd = 'no' THEN --#123  si no es una nota de credito el codigo de control se genera con el total de la venta
                   v_importe_codigo_control = v_venta.total_venta;
                ELSE    --#123 si es una nota de credito el codigo de contro se genera con el 13% de la total devuelto
                   v_importe_codigo_control = v_venta.total_venta * 0.13;
                END IF;

                --la factura de exportacion no altera la fecha
                update vef.tventa  set
                  id_dosificacion = v_dosificacion.id_dosificacion,
                  nro_factura = v_nro_factura,
                  fecha = v_fecha_venta,
                  cod_control = pxp.f_gen_cod_control(v_dosificacion.llave,
                                                      v_dosificacion.nroaut,
                                                      v_nro_factura::varchar,
                                                      pxp.f_iif((v_venta.nit_internacional = 'no') ,v_venta.nit, '0'),  --#123  cuadno es internacional el codigo de control se debe generrar con  cero
                                                      to_char(v_fecha_venta,'YYYYMMDD')::varchar,
                                                      round(v_venta.total_venta,0))
                where id_venta = v_venta.id_venta;


                update vef.tdosificacion
                set nro_siguiente = nro_siguiente + 1
                where id_dosificacion = v_dosificacion.id_dosificacion;


          ELSE
              -- en las facturas de exportacion y minera  el numero se genera al inserta
              -- #123 amtien la facturas computarizadareg,    añaden la fecha al isnertar el documento en borrador
              v_nro_factura =  v_venta.nro_factura;

              select
                *
              into  v_dosificacion
              from  vef.tdosificacion d where d.id_dosificacion = v_venta.id_dosificacion;

              --raise exception '...  %  - ID %',v_dosificacion, v_venta.id_dosificacion;


              --la factura de exportacion no altera la fecha
              update vef.tventa  set
                  cod_control = pxp.f_gen_cod_control(v_dosificacion.llave,
                                                      v_dosificacion.nroaut,
                                                      v_nro_factura::varchar,
                                                      pxp.f_iif((v_venta.nit_internacional = 'no') ,v_venta.nit, '0'),  --#123  cuadno es internacional el codigo de control se debe generrar con  cero
                                                      to_char(v_fecha_venta,'YYYYMMDD')::varchar,
                                                      round(v_venta.total_venta_msuc,0))
              where id_venta = v_venta.id_venta;


          END IF;

        end if;

        if (v_es_fin = 'si' and pxp.f_get_variable_global('vef_tiene_apertura_cierre') = 'si') then

          if (exists(    select 1
                       from vef.tapertura_cierre_caja acc
                       where acc.id_usuario_cajero = p_id_usuario and
                             acc.fecha_apertura_cierre = v_venta.fecha and
                             acc.estado_reg = 'activo' and acc.estado = 'cerrado' and
                             (acc.id_punto_venta = v_venta.id_punto_venta or
                              acc.id_sucursal = v_venta.id_sucursal))) then
            raise exception 'La caja ya fue cerrada, necesita tener la caja abierta para poder finalizar la venta';
          end if;


          if (not exists(    select 1
                           from vef.tapertura_cierre_caja acc
                           where acc.id_usuario_cajero = p_id_usuario and
                                 acc.fecha_apertura_cierre = v_venta.fecha and
                                 acc.estado_reg = 'activo' and acc.estado = 'abierto' and
                                 (acc.id_punto_venta = v_venta.id_punto_venta or
                                  acc.id_sucursal = v_venta.id_sucursal))) then
            raise exception 'Antes de finalizar una venta debe realizar una apertura de caja';
          end if;
          update vef.tventa set id_usuario_cajero = p_id_usuario
          where id_venta = v_venta.id_venta;
        end if;

        --inserta o modifical el libro de ventas
        if (pxp.f_get_variable_global('vef_integracion_lcv') = 'si' and v_es_fin = 'si') then
         v_res = vef.f_inserta_lcv(p_administrador,p_id_usuario,p_tabla,'FIN',v_venta.id_venta);
        end if;

        --raise exception 'pasa..... % , %', v_es_fin , pxp.f_get_variable_global('vef_integracion_lcv');

        -- si hay mas de un estado disponible  preguntamos al usuario
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado de la planilla)');
        v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');
        v_resp = pxp.f_agrega_clave(v_resp,'estado',v_codigo_estado_siguiente);


        -- Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_VENANU_MOD'
     #DESCRIPCION:    Anulacion de Venta
     #AUTOR:        RAC
     #FECHA:        19-02-2013 12:12:51
    ***********************************/

    elsif(p_transaccion='VF_VENANU_MOD')then

      begin

        --obtener el tipo de usuario, la fecha de venta, etc

        select id_venta,fecha,id_sucursal,id_punto_venta into v_venta
        from vef.tventa v
        where v.id_venta = v_parametros.id_venta;

        v_tipo_usuario = 'vendedor';

        if (v_venta.id_punto_venta is null) then
          select  su.tipo_usuario into v_tipo_usuario
          from vef.tsucursal_usuario su
          where id_sucursal = v_venta.id_sucursal and  su.tipo_usuario = 'administrador';
        else
          select  su.tipo_usuario into v_tipo_usuario
          from vef.tsucursal_usuario su
          where su.id_punto_venta = v_venta.id_punto_venta and  su.tipo_usuario = 'administrador';
        end if;

        --#123
        -- solo pueden borrar en estos casos :
        --  es un usuario adminsitrador de sistemas p_administrador != 1
        --  es un usuario administrador del punto de venta v_tipo_usuario = administrador
        -- o es un usuario vendedor , epro solo si es el mismo dia



        IF     (v_tipo_usuario = 'vendedor' and v_venta.fecha = now()::date)
            or (v_tipo_usuario = 'administrador' )
            or (p_administrador = 1) THEN

              --obtenemos datos basicos
              select
                ven.id_estado_wf,
                ven.id_proceso_wf,
                ven.estado,
                ven.id_venta,
                ven.nro_tramite
              into
                v_registros
              from vef.tventa ven
              where ven.id_venta = v_parametros.id_venta;


              v_res = vef.f_anula_venta(p_administrador,p_id_usuario,p_tabla, v_registros.id_proceso_wf,v_registros.id_estado_wf, v_parametros.id_venta);

              --Definicion de la respuesta
              v_resp = pxp.f_agrega_clave(v_resp,'mensaje','venta anulada');
              v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);

        ELSE
            raise exception 'La venta solo puede ser anulada el mismo dia o por un administrador';
        END IF;

        --Devuelve la respuesta
        return v_resp;

      end;
    /*********************************
     #TRANSACCION:  'VF_VENCONTA_MOD'
     #DESCRIPCION:    Vuelve contabilizable una venta no contabilizable
     #AUTOR:        JRR
     #FECHA:        19-02-2013 12:12:51
    ***********************************/

    elsif(p_transaccion='VF_VENCONTA_MOD')then

      begin

        --obtener el tipo de usuario, la fecha de venta, etc

        update vef.tventa set contabilizable = 'si'
        where id_venta = v_parametros.id_venta;
        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','venta anulada');
        v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);

        --Devuelve la respuesta
        return v_resp;

      end;
    /*********************************
     #TRANSACCION:  'VF_VENVERELA_MOD'
     #DESCRIPCION:    Vuelve contabilizable una venta no contabilizable
     #AUTOR:        JRR
     #FECHA:        19-02-2013 12:12:51
    ***********************************/

    elsif(p_transaccion='VF_VENVERELA_MOD')then

      begin

        --obtener el tipo de usuario, la fecha de venta, etc

        select pxp.list_unique(v.correlativo_venta || '<br>') into v_ventas
        from vef.tventa_detalle vd
          inner join vef.tventa v on v.id_venta = vd.id_venta
        where vd.descripcion is not null and vd.id_boleto is null
                and pxp.f_is_positive_integer(vd.descripcion) and
              v.estado = 'finalizado' and vd.descripcion != ''  and vd.estado_reg = 'activo' and v.id_punto_venta = v_parametros.id_punto_venta
              and v.tipo_factura = v_parametros.tipo_factura;
        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Relacion verificada');

        v_resp = pxp.f_agrega_clave(v_resp,'ventas',v_ventas);
        --Devuelve la respuesta
        return v_resp;

      end;
     /*********************************
 	#TRANSACCION:  'VEF_VALIMUL_IME'
 	#DESCRIPCION:	funcion que controla el cambio al Siguiente estado de las ventas de varios Registros
 	#AUTOR:	EGS
 	#FECHA:		29/10/2019
    #ISSUE:     #7
	***********************************/

    elseif(p_transaccion='VEF_VALIMUL_IME')then
      begin
          j_data_json = v_parametros.data_json;

          v_id_punto_venta = 0;
          v_id_tipo_estado = 0;
          v_estado='';

          FOR v_parametros IN(
                --convertimos el dato json en record
                select * from json_to_recordset(j_data_json::json) as x(id_proceso_wf int,id_estado_wf int,id_venta int,id_punto_venta int,estado varchar)
          )LOOP

              SELECT
                ve.correlativo_venta
              INTO
                v_record_venta
              FROM vef.tventa ve
              WHERE ve.id_venta = v_parametros.id_venta;

              SELECT
                  es.id_tipo_estado,
                  ties.codigo
              INTO
                   v_id_tipo_estado,
                   v_estado
              FROM wf.testado_wf es
              LEFT JOIN wf.ttipo_estado ties on es.id_tipo_estado = ties.id_tipo_estado
              WHERE es.id_estado_wf = v_parametros.id_estado_wf;

              IF v_estado <> 'borrador' THEN
                    RAISE EXCEPTION 'Uno de los registros no esta en estado Borrador (%)',v_record_venta.correlativo_venta;
              END IF;

              IF v_id_punto_venta = 0 THEN
                   v_id_punto_venta = v_parametros.id_punto_venta;
              END IF;

              IF v_parametros.id_punto_venta <> v_id_punto_venta THEN
                    RAISE EXCEPTION 'Los Registros No tienen el Mismo Punto de Venta';
              END IF;



              SELECT
                   *
                into
                  va_id_tipo_estado,
                  va_codigo_estado,
                  va_disparador,
                  va_regla,
                  va_prioridad

              FROM wf.f_obtener_estado_wf(v_parametros.id_proceso_wf, v_parametros.id_estado_wf,NULL,'siguiente');

              IF va_codigo_estado[2] is not null THEN

               raise exception 'El proceso de WF esta mal parametrizado,  solo admite un estado siguiente para el estado: %', v_parametros.estado;

              END IF;

               IF va_codigo_estado[1] is  null THEN

               raise exception 'El proceso de WF esta mal parametrizado, no se encuentra el estado siguiente,  para el estado: %', v_parametros.estado;
              END IF;


           END LOOP;


        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','almacenado(a) con exito');
        v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_estado',va_id_tipo_estado[1]::varchar);
        v_resp = pxp.f_agrega_clave(v_resp,'id_estado_wf',v_parametros.id_estado_wf::varchar);

        return v_resp;

       end;
     /*********************************
 	#TRANSACCION:  'VEF_SIGESTMUL_IME'
 	#DESCRIPCION:	cambio de estado de varios registros de borrador a pendiente
 	#AUTOR:	EGS
 	#FECHA:		29/10/2019
    #ISSUE:     #7
	***********************************/

    elseif(p_transaccion='VEF_SIGESTMUL_IME')then
      begin
          j_data_json = v_parametros.data_json;
          v_id_funcionario_wf =v_parametros.id_funcionario_wf;

          FOR v_parametros IN(
                --convertimos el dato json en record
                select * from json_to_recordset(j_data_json::json) as x(id_proceso_wf int,id_estado_wf int,id_venta int,id_punto_venta int,estado varchar)
          )LOOP

              SELECT
                   *
                into
                  va_id_tipo_estado,
                  va_codigo_estado,
                  va_disparador,
                  va_regla,
                  va_prioridad

              FROM wf.f_obtener_estado_wf(v_parametros.id_proceso_wf, v_parametros.id_estado_wf,NULL,'siguiente');

              IF va_codigo_estado[2] is not null THEN

               raise exception 'El proceso de WF esta mal parametrizado,  solo admite un estado siguiente para el estado: %', v_parametros.estado;

              END IF;

               IF va_codigo_estado[1] is  null THEN

               raise exception 'El proceso de WF esta mal parametrizado, no se encuentra el estado siguiente,  para el estado: %', v_parametros.estado;
              END IF;


            p_id_usuario_ai = null;
            p_usuario_ai = null;

              -- estado siguiente
           v_id_estado_actual =  wf.f_registra_estado_wf(va_id_tipo_estado[1],
                                                             v_id_funcionario_wf,
                                                             v_parametros.id_estado_wf,
                                                             v_parametros.id_proceso_wf,
                                                             p_id_usuario,
                                                             p_id_usuario_ai, -- id_usuario_ai
                                                             p_usuario_ai, -- usuario_ai
                                                             NULL,
                                                             'Pendiente de Emision');

              -- actualiza estado de la venta
               update vef.tventa pp  set
                           id_estado_wf = v_id_estado_actual,
                           estado = va_codigo_estado[1],
                           id_usuario_mod=p_id_usuario,
                           fecha_mod=now(),
                           id_usuario_ai = p_id_usuario_ai,
                           usuario_ai = p_usuario_ai
                         where id_venta  = v_parametros.id_venta;


           END LOOP;

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