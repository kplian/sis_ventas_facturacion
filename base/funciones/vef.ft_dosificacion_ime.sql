CREATE OR REPLACE FUNCTION vef.ft_dosificacion_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
  /**************************************************************************
   SISTEMA:		Sistema de Ventas
   FUNCION: 		vef.ft_dosificacion_ime
   DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tdosificacion'
   AUTOR: 		 (jrivera)
   FECHA:	        07-10-2015 13:00:56
   COMENTARIOS:
  ***************************************************************************
   HISTORIAL DE MODIFICACIONES:

   DESCRIPCION:
   AUTOR:
   FECHA:
  ***************************************************************************/

  DECLARE

    v_nro_requerimiento    	integer;
    v_parametros           	record;
    v_id_requerimiento     	integer;
    v_resp		            varchar;
    v_nombre_funcion        text;
    v_mensaje_error         text;
    v_id_dosificacion	integer;
    v_mensaje			text;
    v_nit				varchar;
    v_cod_control		varchar;
    v_nombre_sucursal	varchar;
    v_codigo_sucursal	varchar;

  BEGIN

    v_nombre_funcion = 'vef.ft_dosificacion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

    /*********************************
     #TRANSACCION:  'VF_DOS_INS'
     #DESCRIPCION:	Insercion de registros
     #AUTOR:		jrivera
     #FECHA:		07-10-2015 13:00:56
    ***********************************/

    if(p_transaccion='VF_DOS_INS')then

      begin
        --Sentencia de la insercion
        insert into vef.tdosificacion(
          id_sucursal,
          final,
          tipo,
          fecha_dosificacion,
          nro_siguiente,
          nroaut,
          fecha_inicio_emi,
          fecha_limite,
          tipo_generacion,
          glosa_impuestos,
          id_activida_economica,
          llave,
          inicial,
          estado_reg,
          glosa_empresa,
          id_usuario_ai,
          fecha_reg,
          usuario_ai,
          id_usuario_reg,
          fecha_mod,
          id_usuario_mod
        ) values(
          v_parametros.id_sucursal,
          v_parametros.final,
          v_parametros.tipo,
          v_parametros.fecha_dosificacion,
          1,
          v_parametros.nroaut,
          v_parametros.fecha_inicio_emi,
          v_parametros.fecha_limite,
          v_parametros.tipo_generacion,
          v_parametros.glosa_impuestos,
          string_to_array(v_parametros.id_activida_economica, ',')::integer[],
          v_parametros.llave,
          v_parametros.inicial,
          'activo',
          v_parametros.glosa_empresa,
          v_parametros._id_usuario_ai,
          now(),
          v_parametros._nombre_usuario_ai,
          p_id_usuario,
          null,
          null



        )RETURNING id_dosificacion into v_id_dosificacion;

        select e.nit,s.nombre,s.codigo into v_nit,v_nombre_sucursal,v_codigo_sucursal
        from vef.tsucursal s
          inner join param.tentidad e on e.id_entidad = s.id_entidad
        where s.id_sucursal = v_parametros.id_sucursal;

        v_cod_control = pxp.f_gen_cod_control(
            v_parametros.llave,
            v_parametros.nroaut,
            '1'::varchar,
            '196560027'::varchar,
            to_char(v_parametros.fecha_inicio_emi,'YYYYMMDD')::varchar,
            1::numeric
        );

        v_mensaje = '
            	Dosificacion registrada con exito para la sucursal ' || v_nombre_sucursal || '-' || v_codigo_sucursal || '.<br> Por favor valide la siguiente informacion en <b><a href="http://ov.impuestos.gob.bo/Paginas/Publico/VerificacionFactura.aspx">Impuestos</a></b>:<br><br>
            		NIT Emisor : ' || v_nit || '<br>
                    Numero Factura : 1 <br>
                    Numero autorizacion : ' || v_parametros.nroaut || ' <br>
                    Fecha de Emision : 	' || to_char(v_parametros.fecha_inicio_emi,'DD/MM/YYYY') || ' <br>
                    NIT Comprador : 196560027 <br>
                    Total : 1 <br>
                    Codigo Control : ' || v_cod_control || '<br><br>
                <b>Esto garantizara que la informacion de la dosificacion se ha registrado correctamente.</b>
            ';

        --Definicion de la respuesta

        v_resp = pxp.f_agrega_clave(v_resp,'id_dosificacion',v_id_dosificacion::varchar);
        v_resp = pxp.f_agrega_clave(v_resp,'prueba',v_mensaje);
        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_DOS_MOD'
     #DESCRIPCION:	Modificacion de registros
     #AUTOR:		jrivera
     #FECHA:		07-10-2015 13:00:56
    ***********************************/

    elsif(p_transaccion='VF_DOS_MOD')then

      begin

        --Sentencia de la modificacion
        update vef.tdosificacion set
          id_sucursal = v_parametros.id_sucursal,
          final = v_parametros.final,
          tipo = v_parametros.tipo,
          fecha_dosificacion = v_parametros.fecha_dosificacion,
          nroaut = v_parametros.nroaut,
          fecha_inicio_emi = v_parametros.fecha_inicio_emi,
          fecha_limite = v_parametros.fecha_limite,
          tipo_generacion = v_parametros.tipo_generacion,
          glosa_impuestos = v_parametros.glosa_impuestos,
          id_activida_economica = string_to_array(v_parametros.id_activida_economica, ',')::integer[],
          llave = v_parametros.llave,
          inicial = v_parametros.inicial,
          glosa_empresa = v_parametros.glosa_empresa,
          fecha_mod = now(),
          id_usuario_mod = p_id_usuario,
          id_usuario_ai = v_parametros._id_usuario_ai,
          usuario_ai = v_parametros._nombre_usuario_ai
        where id_dosificacion=v_parametros.id_dosificacion;

        select e.nit,s.nombre,s.codigo into v_nit,v_nombre_sucursal,v_codigo_sucursal
        from vef.tsucursal s
          inner join param.tentidad e on e.id_entidad = s.id_entidad
        where s.id_sucursal = v_parametros.id_sucursal;

        v_cod_control = pxp.f_gen_cod_control(
            v_parametros.llave,
            v_parametros.nroaut,
            '1'::varchar,
            '196560027'::varchar,
            to_char(v_parametros.fecha_inicio_emi,'YYYYMMDD')::varchar,
            1::numeric
        );

        v_mensaje = '
            	Dosificacion modificada con exito para la sucursal ' || v_nombre_sucursal || '-' || v_codigo_sucursal || '.<br> Por favor valide la siguiente informacion en <b><a href="http://ov.impuestos.gob.bo/Paginas/Publico/VerificacionFactura.aspx">Impuestos</a></b>:<br><br>
            		NIT Emisor : ' || v_nit || '<br>
                    Numero Factura : 1 <br>
                    Numero autorizacion : ' || v_parametros.nroaut || ' <br>
                    Fecha de Emision : 	' || to_char(v_parametros.fecha_inicio_emi,'DD/MM/YYYY') || ' <br>
                    NIT Comprador : 196560027 <br>
                    Total : 1 <br>
                    Codigo Control : ' || v_cod_control || '<br><br>
                <b>Esto garantizara que la informacion de la dosificacion se ha registrado correctamente.</b>
            ';

        --Definicion de la respuesta

        v_resp = pxp.f_agrega_clave(v_resp,'id_dosificacion',v_parametros.id_dosificacion::varchar);
        v_resp = pxp.f_agrega_clave(v_resp,'prueba',v_mensaje);
        --Devuelve la respuesta
        return v_resp;

      end;

    /*********************************
     #TRANSACCION:  'VF_DOS_ELI'
     #DESCRIPCION:	Eliminacion de registros
     #AUTOR:		jrivera
     #FECHA:		07-10-2015 13:00:56
    ***********************************/

    elsif(p_transaccion='VF_DOS_ELI')then

      begin
        --Sentencia de la eliminacion
        delete from vef.tdosificacion
        where id_dosificacion=v_parametros.id_dosificacion;

        --Definicion de la respuesta
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Dosificación eliminado(a)');
        v_resp = pxp.f_agrega_clave(v_resp,'id_dosificacion',v_parametros.id_dosificacion::varchar);

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