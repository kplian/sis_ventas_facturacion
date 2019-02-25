CREATE OR REPLACE FUNCTION vef.ft_repventa_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
  /**************************************************************************
   SISTEMA:		Sistema de Ventas
   FUNCION: 		vef.ft_repventa_sel
   DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tventa'
   AUTOR: 		 (admin)
   FECHA:	        01-06-2015 05:58:00
   COMENTARIOS:
  ***************************************************************************
   HISTORIAL DE MODIFICACIONES:

   ISSUE        	FECHA: 			 AUTOR:					DESCRIPCION:
    #1				22/10/2018		 EGS					se aumento las transacciones para facturas en pdf
    #2	endeEtr		23/01/2019		 EGS					se agrego reporte con lista de productos activos por puntos de venta

  ***************************************************************************/

  DECLARE

    v_consulta    		varchar;
    v_parametros  		record;
    v_nombre_funcion   	text;
    v_resp				varchar;
    v_id_funcionario_usuario	integer;
    v_sucursales		varchar;
    v_filtro			varchar;
    v_join				varchar;
    v_select			varchar;
    v_historico			varchar;
    v_id_sucursal		integer;
    v_id_moneda			integer;
    v_id_moneda_usd		integer;
    v_cod_moneda		varchar;
    v_group_by			varchar;
    v_id_pais			integer;
    
 

    v_join_destino		varchar;
    v_columnas_destino	varchar;

  BEGIN

    v_nombre_funcion = 'vef.ft_repventa_sel';
    v_parametros = pxp.f_get_record(p_tabla);

    /*********************************
     #TRANSACCION:  'VF_CONSUC_SEL'
     #DESCRIPCION:	Obtencion de conceptos de gasto por punto de venta o sucursal
     #AUTOR:		admin
     #FECHA:		01-06-2015 05:58:00
    ***********************************/

    if(p_transaccion='VF_CONSUC_SEL')then

      begin
        IF  pxp.f_existe_parametro(p_tabla,'id_punto_venta') THEN

          select param.f_get_id_lugar_pais(s.id_lugar),mon.codigo_internacional into v_id_pais, v_cod_moneda
          from vef.tpunto_venta pv
            inner join vef.tsucursal s on s.id_sucursal = pv.id_sucursal
            inner join vef.tsucursal_moneda sm on sm.id_sucursal = s.id_sucursal
            inner join param.tmoneda mon on mon.id_moneda = sm.id_moneda
          where pv.id_punto_venta = v_parametros.id_punto_venta;

          if ( v_cod_moneda = 'USD') then
            v_select = 'select ''CASH USD''::varchar,''4MONEDA1''::varchar as tipo UNION ALL
                    			select ''OTRO USD''::varchar ''4MONEDA1''::varchar as tipo';
          else
            v_select = 'select ''CASH USD''::varchar,''4MONEDA1''::varchar as tipo UNION ALL
                    			select ''OTRO USD''::varchar, ''4MONEDA1''::varchar as tipo UNION ALL
                    			select ''CASH ' || v_cod_moneda || '''::varchar,''4MONEDA2''::varchar as tipo UNION ALL
                                select ''OTRO ' || v_cod_moneda || '''::varchar,''4MONEDA2''::varchar as tipo';
          end if;

          v_filtro = ' id_punto_venta = ' || v_parametros.id_punto_venta;

          v_consulta:='(' || v_select || ' UNION ALL select ''TARIFA NETA'',''1TARIFA''::varchar as tipo
                			UNION ALL
                			select cig.desc_ingas,''3CONCEPTO''::varchar as tipo
                			 from vef.tventa v
                             inner join vef.tventa_detalle vd 
                             	on vd.id_venta = v.id_venta
                             inner join vef.tsucursal_producto sp 
                             	on vd.id_sucursal_producto = sp.id_sucursal_producto
                             inner join param.tconcepto_ingas cig
                             	on cig.id_concepto_ingas = sp.id_concepto_ingas
                             where v.id_punto_venta = ' || v_parametros.id_punto_venta || ' and 
             			(v.fecha between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''')
                        group by cig.desc_ingas';
        ELSE
          select param.f_get_id_lugar_pais(s.id_lugar),mon.codigo_internacional into v_id_pais, v_cod_moneda
          from vef.tsucursal  s
            inner join vef.tsucursal_moneda sm on sm.id_sucursal = s.id_sucursal
            inner join param.tmoneda mon on mon.id_moneda = sm.id_moneda
          where s.id_sucursal = v_parametros.id_sucursal;

          if ( v_cod_moneda = 'USD') then
            v_select = 'select ''USD'',''4MONEDA''::varchar as tipo';
          else
            v_select = 'select ''USD'',''4MONEDA''::varchar as tipo UNION ALL
                    			select ''' || v_cod_moneda || ''',''4MONEDA''::varchar as tipo';
          end if;

          v_filtro = ' id_sucursal = ' || v_parametros.id_sucursal;

          v_consulta:='(' || v_select || ' UNION ALL select ''TARIFA NETA'',''1TARIFA''::varchar as tipo
                			UNION ALL
                			select cig.desc_ingas,''3CONCEPTO''::varchar as tipo
                			 from vef.tventa v
                             inner join vef.tventa_detalle vd 
                             	on vd.id_venta = v.id_venta
                             inner join vef.tsucursal_producto sp 
                             	on vd.id_sucursal_producto = sp.id_sucursal_producto
                             inner join param.tconcepto_ingas cig
                             	on cig.id_concepto_ingas = sp.id_concepto_ingas
                             where v.id_sucursal = ' || v_parametros.id_sucursal || ' and 
             			(v.fecha between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''')
                        group by cig.desc_ingas';
        END IF;

        v_consulta:= v_consulta || ')
                        UNION ALL
                        (select imp.codigo,''2IMPUESTO''::varchar as tipo
                 from  obingresos.tboleto_impuesto bimp
                 inner join obingresos.tboleto b on b.id_boleto = bimp.id_boleto
                 inner join obingresos.timpuesto imp on imp.id_impuesto = bimp.id_impuesto
                where ' || v_filtro || ' and 
             			(b.fecha_emision between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''') 
                group by imp.codigo
                order by imp.codigo)
                order by 2,1';

        --Devuelve la respuesta
        return v_consulta;

      end;

    /*********************************
     #TRANSACCION:  'VF_REPDETBOA_SEL'
     #DESCRIPCION:	Reporte de Boa para detalle de ventas
     #AUTOR:		admin
     #FECHA:		01-06-2015 05:58:00
    ***********************************/

    elsif(p_transaccion='VF_REPDETBOA_SEL')then

      begin
        IF  pxp.f_existe_parametro(p_tabla,'id_punto_venta') THEN
          v_filtro = ' id_punto_venta = ' || v_parametros.id_punto_venta;
          select id_sucursal into v_id_sucursal
          from vef.tpunto_venta
          where id_punto_venta = v_parametros.id_punto_venta;
        else
          v_filtro = ' id_sucursal = ' || v_parametros.id_sucursal;
          v_id_sucursal = v_parametros.id_sucursal;
        end if;

        select	mon.codigo_internacional,mon.id_moneda into v_cod_moneda,v_id_moneda
        from vef.tsucursal_moneda sm
          inner join param.tmoneda mon on mon.id_moneda = sm.id_moneda
        where sm.tipo_moneda = 'moneda_base' and id_sucursal = v_id_sucursal;

        select id_moneda into v_id_moneda_usd
        from param.tmoneda
        where codigo_internacional = 'USD';

        v_consulta:='
            ( WITH ';
        if (v_cod_moneda != 'USD') then
          v_consulta = v_consulta || ' forma_pago_usd  AS(
                      select vfp.id_venta,
                      round(sum(	case when fp.codigo = ''CA'' then 
                                          vfp.monto_transaccion -(vfp.cambio /
                                           param.f_get_tipo_cambio(fp.id_moneda, v.fecha::date, ''O''))
                             			ELSE
                                        	0
                             			end), 2) as monto_cash_usd,
                             round(sum(	case when fp.codigo != ''CA'' then 
                                          vfp.monto_transaccion -(vfp.cambio /
                                           param.f_get_tipo_cambio(fp.id_moneda, v.fecha::date, ''O''))
                             			ELSE
                                        	0
                             			end), 2) as monto_otro_usd,
                      pxp.list(fp.nombre) as forma_pago
                      from  vef.tventa_forma_pago vfp
                      inner join vef.tforma_pago fp on vfp.id_forma_pago = fp.id_forma_pago
                      inner join vef.tventa v on v.id_venta = vfp.id_venta
                      where fp.id_moneda = ' || v_id_moneda_usd || ' and (v.fecha::date between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''')
                      group by vfp.id_venta
                  ),';
        end if;

        v_consulta = v_consulta || ' forma_pago_mb AS(
                      select vfp.id_venta,
                      sum(CASE when fp.codigo = ''CA'' then 
                             		vfp.monto_transaccion - vfp.cambio
                             	 ELSE
                                 	0
                                 END) as monto_cash_mb,
                             sum(CASE when fp.codigo != ''CA'' then 
                             		vfp.monto_transaccion - vfp.cambio
                             	 ELSE
                                 	0
                                 END) as monto_otro_mb,  
                      pxp.list(fp.nombre) as forma_pago
                      from  vef.tventa_forma_pago vfp
                      inner join vef.tforma_pago fp on vfp.id_forma_pago = fp.id_forma_pago
                      inner join vef.tventa v on v.id_venta = vfp.id_venta
                      where fp.id_moneda = ' || v_id_moneda || ' and (v.fecha::date between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''')
                      group by vfp.id_venta
                  )
                  select ''' || v_cod_moneda || '''::varchar as moneda_emision, ''venta''::varchar as tipo,v.fecha::date as fecha,v.correlativo_venta,cli.nombre_factura,v.observaciones::varchar as boleto,''''::varchar as ruta,string_agg(cig.desc_ingas,''|'')::varchar as conceptos,
                  ';
        if (v_cod_moneda != 'USD') then
          v_consulta = v_consulta || ' coalesce(fpusd.forma_pago || '','','''')|| coalesce(fpmb.forma_pago,'''') as forma_pago,
                    			coalesce(fpusd.monto_cash_usd, 0) as monto_cash_usd,
                         		coalesce(fpusd.monto_otro_usd, 0) as monto_otro_usd,';
          v_group_by = ' ,fpusd.forma_pago, fpusd.monto_cash_usd,fpusd.monto_otro_usd';
        else
          v_group_by = '';
          v_consulta = v_consulta || ' fpmb.forma_pago as forma_pago,
                    						coalesce(fpmb.monto_cash_mb, 0) as monto_cash_usd,
                                            coalesce(fpmb.monto_otro_mb, 0) as monto_otro_usd,';
        end if;
        v_consulta = v_consulta || '
                  coalesce(fpmb.monto_cash_mb, 0) as monto_cash_mb,
                  coalesce(fpmb.monto_otro_mb, 0) as monto_otro_mb,
                  0::numeric,
                  string_agg((vd.precio*vd.cantidad)::text,''|'')::varchar as precios_detalles,
                  NULL::varchar as mensaje_error
                  from vef.tventa v
                  inner join vef.tventa_detalle vd 
                      on v.id_venta = vd.id_venta and vd.estado_reg = ''activo''
                  inner join vef.tsucursal_producto sp 
                      on sp.id_sucursal_producto = vd.id_sucursal_producto
                  inner join param.tconcepto_ingas cig 
                      on cig.id_concepto_ingas = sp.id_concepto_ingas
                  inner join vef.tcliente cli 
                      on cli.id_cliente = v.id_cliente';
        if (v_cod_moneda != 'USD') then
          v_consulta = v_consulta || ' left join forma_pago_usd fpusd
                      on v.id_venta = fpusd.id_venta ';

        end if;

        v_consulta = v_consulta || ' left join forma_pago_mb fpmb
                      on v.id_venta = fpmb.id_venta
                  where v.estado = ''finalizado'' and ' || v_filtro || ' and
                  	(v.fecha::date between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''')
                  group by v.fecha,v.correlativo_venta,cli.nombre_factura,v.observaciones,
                  			fpmb.forma_pago, fpmb.monto_cash_mb,fpmb.monto_otro_mb,v.total_venta_msuc ' || v_group_by || ' 
                  )
		union ALL
	 		(WITH ';
        if (v_cod_moneda != 'USD') then
          v_consulta = v_consulta || ' bol_forma_pago_usd  AS(
        			select bfp.id_boleto,
                    sum(case when fp.codigo = ''CA'' then 
                           			bfp.importe
                           		ELSE
                                	0
                                END) as monto_cash_usd,
                           sum(case when fp.codigo != ''CA'' then 
                           			bfp.importe
                           		ELSE
                                	0
                                END) as monto_otro_usd,
                    pxp.list(fp.nombre) as forma_pago
                    from  obingresos.tboleto_forma_pago bfp
                    inner join obingresos.tboleto b on b.id_boleto = bfp.id_boleto
                    inner join obingresos.tforma_pago fp on bfp.id_forma_pago = fp.id_forma_pago
                    where ' || v_filtro || ' and 
             			(b.fecha_emision between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''') and fp.id_moneda = ' || v_id_moneda_usd || '
                    group by bfp.id_boleto
        			),';
        end if;

        v_consulta = v_consulta || 'bol_forma_pago_mb AS(
                select bfp.id_boleto,
                sum(case when fp.codigo = ''CA'' then 
                           			bfp.importe
                           		ELSE
                                	0
                                END) as monto_cash_mb,
                           sum(case when fp.codigo != ''CA'' then 
                           			bfp.importe
                           		ELSE
                                	0
                                END) as monto_otro_mb,
                pxp.list(fp.nombre) as forma_pago
                 from  obingresos.tboleto_forma_pago bfp
                 inner join obingresos.tboleto b on b.id_boleto = bfp.id_boleto
                 inner join obingresos.tforma_pago fp on bfp.id_forma_pago = fp.id_forma_pago
                where ' || v_filtro || ' and 
             			(b.fecha_emision between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''') and fp.id_moneda = ' || v_id_moneda || '
                group by bfp.id_boleto
            ), bol_impuesto AS(
            	select bimp.id_boleto,string_agg(bimp.importe::text,''|'')::varchar as monto_impuesto,string_agg(imp.codigo,''|'')::varchar as impuesto
                 from  obingresos.tboleto_impuesto bimp
                 inner join obingresos.tboleto b on b.id_boleto = bimp.id_boleto
                 inner join obingresos.timpuesto imp on imp.id_impuesto = bimp.id_impuesto
                where ' || v_filtro || ' and 
             			(b.fecha_emision between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''') 
                group by bimp.id_boleto
            )
             SELECT b.moneda::varchar as moneda_emision,''boleto''::varchar as tipo ,b.fecha_emision, ''''::varchar as correlativo_venta,b.pasajero::varchar as nombre_factura,b.nro_boleto as boleto,b.ruta_completa as ruta ,
             imp.impuesto as conceptos,
             ';
        if (v_cod_moneda != 'USD') then
          v_consulta = v_consulta || ' coalesce(fpusd.forma_pago || '','','''')|| coalesce(fpmb.forma_pago,'''') as forma_pago,
                    		coalesce(fpusd.monto_cash_usd,0) as monto_cash_usd,
                            coalesce(fpusd.monto_otro_usd,0) as monto_otro_usd,';
          v_group_by = ' ,fpusd.forma_pago, fpusd.monto_cash_usd,fpusd.monto_otro_usd ';
        else
          v_consulta = v_consulta || ' fpmb.forma_pago as forma_pago,
                    					coalesce(fpmb.monto_cash_mb,0) as monto_cash_usd,
                                        coalesce(fpmb.monto_otro_mb,0) as monto_otro_usd,';
          v_group_by = '';
        end if;
        v_consulta = v_consulta ||  '
             coalesce(fpmb.monto_cash_mb,0) as monto_cash_mb,
             coalesce(fpmb.monto_otro_mb,0) as monto_otro_mb,
             b.neto,
             imp.monto_impuesto as precios_conceptos,
             b.mensaje_error
             from obingresos.tboleto b
             ';
        if (v_cod_moneda != 'USD') then
          v_consulta = v_consulta || ' left join bol_forma_pago_usd fpusd
                      on b.id_boleto = fpusd.id_boleto ';
        end if;

        v_consulta = v_consulta || '
             left join bol_forma_pago_mb fpmb
                on fpmb.id_boleto = b.id_boleto
             left join bol_impuesto imp
                on imp.id_boleto = b.id_boleto
             
             
             where b.estado_reg = ''activo'' and ' || v_filtro || ' and 
             (b.fecha_emision between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''')
             
             group by b.fecha_emision,b.pasajero, b.nro_boleto,b.mensaje_error,b.ruta_completa,b.moneda,b.neto,imp.impuesto,
             		imp.monto_impuesto,fpmb.forma_pago,fpmb.monto_cash_mb,fpmb.monto_otro_mb '|| v_group_by || ')
             order by fecha,boleto';
        raise notice '%',v_consulta;
        --Devuelve la respuesta
        return v_consulta;

      end;
    /*********************************    
 	#TRANSACCION:  'VF_REPRESBOA_SEL'
 	#DESCRIPCION:	Reporte de Boa para resumen de ventas
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

    elsif(p_transaccion='VF_REPRESBOA_SEL')then

      begin
        IF  pxp.f_existe_parametro(p_tabla,'id_punto_venta') THEN
          v_filtro = ' id_punto_venta = ' || v_parametros.id_punto_venta;
        else
          v_filtro = ' id_sucursal = ' || v_parametros.id_sucursal;
        end if;



        v_consulta:='
            ( WITH forma_pago_cc  AS(
                      select vfp.id_venta,vfp.monto_mb_efectivo as monto_tarjeta
                      from  vef.tventa_forma_pago vfp
                      inner join vef.tforma_pago fp on vfp.id_forma_pago = fp.id_forma_pago
                      where fp.codigo = ''CCSUS''
                  ),
                  forma_pago_cash AS(
                      select vfp.id_venta,vfp.monto_mb_efectivo as monto_efectivo
                      from  vef.tventa_forma_pago vfp
                      inner join vef.tforma_pago fp on vfp.id_forma_pago = fp.id_forma_pago
                      where fp.codigo = ''EFESUS''
                  )
                  select v.fecha_reg::date as fecha,cig.desc_ingas,
                  sum(round(((coalesce(fpcc.monto_tarjeta,0)/v.total_venta)*vd.cantidad*vd.precio),2)) as precio_cc,
                  sum(round(((coalesce(fpcash.monto_efectivo,0)/v.total_venta)*vd.cantidad*vd.precio),2)) as precio_cash,                 
                  sum(vd.cantidad*vd.precio) as monto
                  from vef.tventa v
                  inner join vef.tventa_detalle vd 
                      on v.id_venta = vd.id_venta and vd.estado_reg = ''activo''
                  inner join vef.tsucursal_producto sp 
                      on sp.id_sucursal_producto = vd.id_sucursal_producto
                  inner join param.tconcepto_ingas cig 
                      on cig.id_concepto_ingas = sp.id_concepto_ingas                  
                  left join forma_pago_cc fpcc
                      on v.id_venta = fpcc.id_venta
                  left join forma_pago_cash fpcash
                      on v.id_venta = fpcash.id_venta
                  where v.estado = ''finalizado'' and ' || v_filtro || ' and
                  	(v.fecha_reg::date between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''')
                  group by v.fecha_reg,cig.desc_ingas)
		union ALL
	 		(WITH bol_forma_pago_cc  AS(
        			select vfp.id_boleto,vfp.monto as monto_tarjeta
                    from  vef.tboleto_fp vfp
                    inner join vef.tforma_pago fp on vfp.id_forma_pago = fp.id_forma_pago
                    where fp.codigo = ''CCSUS''
        	),
            bol_forma_pago_cash AS(
                select vfp.id_boleto,vfp.monto as monto_efectivo
                from  vef.tboleto_fp vfp
                inner join vef.tforma_pago fp on vfp.id_forma_pago = fp.id_forma_pago
                where fp.codigo = ''EFESUS''
            )
             SELECT b.fecha, 
             ''TARIFA NETA''::varchar as concepto,
             sum(coalesce(fpcc.monto_tarjeta,0)) as precio_tarjeta,
             sum(coalesce(fpcash.monto_efectivo,0)) as precio_cash,
             sum(coalesce(fpcc.monto_tarjeta,0) + coalesce(fpcash.monto_efectivo,0)) as monto
             
             from vef.tboleto b
             left join bol_forma_pago_cc fpcc 
                on fpcc.id_boleto = b.id_boleto
             left join bol_forma_pago_cash fpcash 
                on fpcash.id_boleto = b.id_boleto
             where ' || v_filtro || ' and 
             (b.fecha between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || ''')
             group by b.fecha)
             order by fecha';

        --Devuelve la respuesta
        return v_consulta;

      end;
    /*********************************
     #TRANSACCION:  'VF_VENCONF_SEL'
     #DESCRIPCION:	Obtener configuraciones basicas para sistema de ventas
     #AUTOR:		admin
     #FECHA:		01-06-2015 05:58:00
    ***********************************/

    elsif(p_transaccion='VF_VENCONF_SEL')then

      begin

        --Sentencia de la consulta de conteo de registros
        v_consulta:='	select variable, valor
						 	from pxp.variable_global
						 	where variable like ''vef_%'' 
						 union all
						 	select ''sucursales''::varchar,pxp.list(id_sucursal::text)::varchar
						 	from vef.tsucursal_usuario 
						 	where estado_reg = ''activo'' and id_usuario = ' || p_id_usuario || '
						 	and id_sucursal is not null and id_punto_venta is null
						 union all
						 	select ''puntos_venta''::Varchar,pxp.list(id_punto_venta::text)::varchar
						 	from vef.tsucursal_usuario 
						 	where estado_reg = ''activo'' and id_usuario = ' || p_id_usuario || '
						 	and id_sucursal is null and id_punto_venta is not null
                         union all
						 	select ''fecha'',to_char(now(),''DD/MM/YYYY'')::varchar
						 ';

        --Definicion de la respuesta
        return v_consulta;


      end;

    /*********************************    
 	#TRANSACCION:  'VF_REPXPROD_SEL'
 	#DESCRIPCION:	Detalle de ventas para una lista de productos
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

    elsif(p_transaccion='VF_REPXPROD_SEL')then

      begin
        v_filtro = ' v.estado in (''finalizado'',''anulado'') and v.id_sucursal = ' || v_parametros.id_sucursal || ' and v.fecha >='''
                   || v_parametros.fecha_desde || ''' and v.fecha <= ''' || v_parametros.fecha_hasta ||
                   ''' and vd.id_sucursal_producto in(' || v_parametros.id_productos || ')' ;

        --Sentencia de la consulta de conteo de registros
        v_consulta:='	select
                            
                            (tdcv.codigo||'' - ''||tdcv.nombre)::varchar as desc_tipo_doc_compra_venta,                           
                            pla.desc_plantilla::varchar,                           
                            to_char(dcv.fecha,''DD/MM/YYYY'')::varchar as fecha,
                            dcv.nro_autorizacion::varchar,
                            dcv.nit::varchar,
                            dcv.razon_social::varchar,
                            pxp.list(cig.desc_ingas)::varchar,
                            dcv.nro_documento,
                            COALESCE(dcv.importe_doc,0)::numeric as importe_doc,
                            COALESCE(dcv.importe_neto,0)::numeric as importe_neto,
                            
                            COALESCE(dcv.importe_iva,0)::numeric as importe_iva,
                            COALESCE(dcv.importe_it,0)::numeric as importe_it,
                            COALESCE(dcv.importe_neto,0)::numeric - COALESCE(dcv.importe_iva,0) as ingreso
                                                  
                        
						from vef.tventa v 
                        inner join vef.tventa_detalle vd on vd.id_venta = v.id_venta
                        inner join vef.tsucursal_producto sp on sp.id_sucursal_producto = vd.id_sucursal_producto
                        inner join param.tconcepto_ingas cig on cig.id_concepto_ingas = sp.id_concepto_ingas
                        inner join conta.tdoc_compra_venta dcv on dcv.id_origen = v.id_venta and dcv.tabla_origen = ''vef.tventa''
                          
                          inner join param.tplantilla pla on pla.id_plantilla = dcv.id_plantilla                          
                          inner join conta.ttipo_doc_compra_venta tdcv on tdcv.id_tipo_doc_compra_venta = dcv.id_tipo_doc_compra_venta
                          where ' || v_filtro || '
                          group by dcv.estado,                            
                            pla.desc_plantilla,                           
                            dcv.fecha,
                            dcv.nro_autorizacion,
                            dcv.nit,
                            dcv.razon_social,
                            dcv.nro_documento,
                            dcv.importe_doc,
                            dcv.importe_neto,
                            
                            dcv.importe_iva,
                            dcv.importe_it,
                            tdcv.codigo,
                            tdcv.nombre
                          order by dcv.fecha, dcv.nro_documento::integer
						 ';

        --Definicion de la respuesta

        raise notice '%',v_consulta;
        --Devuelve la respuesta
        return v_consulta;

      end;



    /*********************************
     #TRANSACCION:  'VF_NOTAVEND_SEL'
     #DESCRIPCION:	lista el detalle de la nota de venta
     #AUTOR:		admin
     #FECHA:		01-06-2015 05:58:00
    ***********************************/

    ELSIF(p_transaccion='VF_NOTAVEND_SEL')then

      begin
        --Sentencia de la consulta
        v_consulta:='select
						 
                              vd.id_venta,
                              vd.id_venta_detalle,
                              COALESCE(vd.precio,0) as precio,
                              vd.tipo,
                              vd.cantidad,
                              (vd.cantidad * COALESCE(vd.precio,0)) as precio_total,
                              i.codigo as codigo_nombre,
                              i.nombre as item_nombre,
                              sp.nombre_producto,
                              fo.id_formula,	
                              fd.id_formula_detalle,
                              fd.cantidad as cantidad_df,
                              ifo.nombre as item_nombre_df,
                              fo.nombre as nombre_formula



                            from vef.tventa_detalle vd
                            left join alm.titem i on i.id_item = vd.id_item
                            left join vef.tformula fo on fo.id_formula = vd.id_formula
                            left join vef.vmedico me on me.id_medico = fo.id_medico
                            left join vef.tformula_detalle fd on fd.id_formula = fo.id_formula
                            left join alm.titem ifo on ifo.id_item = fd.id_item
                            left join vef.tsucursal_producto sp on sp.id_sucursal_producto = vd.id_sucursal_producto
                        where  
                               vd.estado_reg = ''activo'' and
                               vd.id_venta = '||v_parametros.id_venta::varchar;

        --Definicion de la respuesta
        v_consulta:=v_consulta||' order by vd.id_venta_detalle, fd.id_formula_detalle';

        --Devuelve la respuesta
        return v_consulta;

      end;
    /*********************************    
 	#TRANSACCION:  'VF_NOTAVEND_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

    elsif(p_transaccion='VF_NOTAVEND_CONT')then

      begin
        --Sentencia de la consulta de conteo de registros
        v_consulta:='select
                            count(vd.id_venta_detalle) as total,
                            SUM(vd.cantidad*COALESCE(vd.precio,0)) as suma_total
                         from vef.tventa_detalle vd
                         where  id_venta = '||v_parametros.id_venta::varchar||' 
                              and vd.estado_reg = ''activo''
                          group by vd.id_venta ';

        --Definicion de la respuesta


        --Devuelve la respuesta
        return v_consulta;

      end;
    /*********************************    
 	#TRANSACCION:  'VF_NOTVEN_SEL'
 	#DESCRIPCION:   Lista de la cabecera de la nota de venta
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

    elsif(p_transaccion='VF_NOTVEN_SEL')then

      begin

        --Sentencia de la consulta
        v_consulta:='select
						ven.id_venta,
						ven.id_cliente,
						ven.id_sucursal,
						ven.id_proceso_wf,
						ven.id_estado_wf,
						ven.estado_reg,
						ven.nro_tramite,
						ven.a_cuenta,
						ven.total_venta,
						ven.fecha_estimada_entrega,
						ven.usuario_ai,
						ven.fecha_reg,
						ven.id_usuario_reg,
						ven.id_usuario_ai,
						ven.id_usuario_mod,
						ven.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        ven.estado,
                        cli.nombre_completo,
                        suc.nombre,
                        suc.direccion,
                        suc.correo,
                        suc.telefono,
                        pxp.f_convertir_num_a_letra(ven.total_venta) as total_string
                        	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
				        inner join vef.vcliente cli on cli.id_cliente = ven.id_cliente
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                       where  id_venta = '||v_parametros.id_venta::varchar;


        --Devuelve la respuesta
        return v_consulta;

      end;
      
      	/*********************************    
 	#TRANSACCION:  'VF_VEN_SEL'
 	#DESCRIPCION:	Consulta de datos reporte de ventas
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_REPVEN_SEL')then
     				
    	begin
        	--raise exception 'Ingrese una Sucursal Por Favor %',v_parametros.tipo_reporte;
        
        	IF (v_parametros.tipo_reporte='sucursal' or v_parametros.tipo_reporte= 'punto_venta') and v_parametros.id_sucursal is null THEN
            		
            		raise exception 'Ingrese una Sucursal Por Favor';
            
            END IF;
        	
				--Sentencia de la consulta
			v_consulta:='with forma_pago_temporal as(
					    	select count(*)as cantidad_forma_pago,vfp.id_venta,
					        	pxp.list(fp.id_forma_pago::text) as id_forma_pago, pxp.list(fp.nombre) as forma_pago,
                                sum(monto_transaccion) as monto_transaccion,pxp.list(vfp.numero_tarjeta) as numero_tarjeta,
                                pxp.list(vfp.codigo_tarjeta) as codigo_tarjeta,pxp.list(vfp.tipo_tarjeta) as tipo_tarjeta
					        from vef.tventa_forma_pago vfp
					        inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
					        group by vfp.id_venta        
					    )
						select
						ven.id_venta,
						ven.id_cliente,
						ven.id_sucursal,
						ven.id_proceso_wf,
						ven.id_estado_wf,
						ven.estado_reg,
						ven.correlativo_venta,
						ven.a_cuenta,
						ven.total_venta,
						ven.fecha_estimada_entrega,
						ven.usuario_ai,
						ven.fecha_reg,
						ven.id_usuario_reg,
						ven.id_usuario_ai,
						ven.id_usuario_mod,
						ven.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        ven.estado,
                        suc.nombre as nombre_sucursal,
                        puve.id_punto_venta,
                        puve.nombre as nombre_punto_venta,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::integer
                        else
                        	forpa.id_forma_pago::integer
                        end) as id_forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''DIVIDIDO''::varchar
                        else
                        	forpa.forma_pago::varchar
                        end) as forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::numeric
                        else
                        	forpa.monto_transaccion::numeric
                        end) as monto_forma_pago,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.numero_tarjeta::varchar
                        end) as numero_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.codigo_tarjeta::varchar
                        end) as codigo_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.tipo_tarjeta::varchar
                        end) as tipo_tarjeta,
                        ven.porcentaje_descuento,
                        ven.id_vendedor_medico,
                        ven.comision,
                        ven.observaciones,
                        ven.fecha,
                        ven.nro_factura,
                        ven.excento,
                        ven.cod_control,
                        ven.id_moneda,
                        ven.total_venta_msuc,
                        ven.transporte_fob,
                        ven.seguros_fob,
                        ven.otros_fob,
                        ven.transporte_cif,
                        ven.seguros_cif,
                        ven.otros_cif,
                        ven.tipo_cambio_venta,
                        mon.moneda as desc_moneda,
                        ven.valor_bruto,
                        ven.descripcion_bulto,
                        ven.contabilizable,
                        to_char(ven.hora_estimada_entrega,''HH24:MI'')::varchar,
                        ven.forma_pedido,
                        ven.id_cliente_destino,
                        ''''::varchar as cliente_destino,
                        ven.nro_tramite,
                        ven.id_proveedor,
                        ven.id_contrato,
                        ven.id_doc_compra_venta,
                        ven.id_venta_fk,
                        ven.ncd,
                        vpro.desc_proveedor,
                        aux.codigo_auxiliar,
                        dcv.importe_doc,
                        dcv.importe_pendiente,
                        dcv.importe_anticipo,
                        dcv.importe_retgar,
                        dcv.nit,
                        venfk.nro_factura as nro_factura_fk                        	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join forma_pago_temporal forpa on forpa.id_venta = ven.id_venta
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf
                        left join param.vproveedor vpro on vpro.id_proveedor = ven.id_proveedor
                        left join conta.tdoc_compra_venta dcv on dcv.id_doc_compra_venta = ven.id_doc_compra_venta
                        left join conta.tauxiliar aux on aux.id_auxiliar = dcv.id_auxiliar
                        left join vef.tventa venfk on venfk.id_venta = ven.id_venta_fk
                          left join param.tperiodo per on per.id_periodo =  dcv.id_periodo
                        where ven.estado_reg = ''activo'' and ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            
            
            --raise notice 'CONSULTA.... %',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;
        
        /*********************************    
 	#TRANSACCION:  'VF_REPVEN_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_REPVEN_CONT')then

		begin

			--Sentencia de la consulta de conteo de registros
			v_consulta:='
                     with forma_pago_temporal as(
					    	select count(*)as cantidad_forma_pago,vfp.id_venta,
					        	pxp.list(fp.id_forma_pago::text) as id_forma_pago, pxp.list(fp.nombre) as forma_pago,
                                sum(monto_transaccion) as monto_transaccion,pxp.list(vfp.numero_tarjeta) as numero_tarjeta,
                                pxp.list(vfp.codigo_tarjeta) as codigo_tarjeta,pxp.list(vfp.tipo_tarjeta) as tipo_tarjeta
					        from vef.tventa_forma_pago vfp
					        inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
					        group by vfp.id_venta        
					    )
            		select count(id_venta)
					    from vef.tventa ven
					    inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join forma_pago_temporal forpa on forpa.id_venta = ven.id_venta
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf
                        left join param.vproveedor vpro on vpro.id_proveedor = ven.id_proveedor
                        left join conta.tdoc_compra_venta dcv on dcv.id_doc_compra_venta = ven.id_doc_compra_venta
                        left join conta.tauxiliar aux on aux.id_auxiliar = dcv.id_auxiliar
                          left join param.tperiodo per on per.id_periodo =  dcv.id_periodo
                        where ven.estado_reg = ''activo'' and ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
        
              
      	/*********************************    
 	#TRANSACCION:  'VF_VENREPORT_SEL'
 	#DESCRIPCION:	Consulta de datos de la grilla
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VENGRID_SEL')then
     				
    	begin
        	--raise exception 'Ingrese una Sucursal Por Favor %',v_parametros.tipo_reporte;
        
       	
				--Sentencia de la consulta
			v_consulta:='with forma_pago_temporal as(
					    	select count(*)as cantidad_forma_pago,vfp.id_venta,
					        	pxp.list(fp.id_forma_pago::text) as id_forma_pago, pxp.list(fp.nombre) as forma_pago,
                                sum(monto_transaccion) as monto_transaccion,pxp.list(vfp.numero_tarjeta) as numero_tarjeta,
                                pxp.list(vfp.codigo_tarjeta) as codigo_tarjeta,pxp.list(vfp.tipo_tarjeta) as tipo_tarjeta
					        from vef.tventa_forma_pago vfp
					        inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
					        group by vfp.id_venta        
					    )
						select
						ven.id_venta,
						ven.id_cliente,
						ven.id_sucursal,
						ven.id_proceso_wf,
						ven.id_estado_wf,
						ven.estado_reg,
						ven.correlativo_venta,
						ven.a_cuenta,
						ven.total_venta,
						ven.fecha_estimada_entrega,
						ven.usuario_ai,
						ven.fecha_reg,
						ven.id_usuario_reg,
						ven.id_usuario_ai,
						ven.id_usuario_mod,
						ven.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        ven.estado,
                        suc.nombre as nombre_sucursal,
                        puve.id_punto_venta,
                        puve.nombre as nombre_punto_venta,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::integer
                        else
                        	forpa.id_forma_pago::integer
                        end) as id_forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''DIVIDIDO''::varchar
                        else
                        	forpa.forma_pago::varchar
                        end) as forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::numeric
                        else
                        	forpa.monto_transaccion::numeric
                        end) as monto_forma_pago,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.numero_tarjeta::varchar
                        end) as numero_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.codigo_tarjeta::varchar
                        end) as codigo_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.tipo_tarjeta::varchar
                        end) as tipo_tarjeta,
                        ven.porcentaje_descuento,
                        ven.id_vendedor_medico,
                        ven.comision,
                        ven.observaciones,
                        ven.fecha,
                        ven.nro_factura,
                        ven.excento,
                        ven.cod_control,
                        ven.id_moneda,
                        ven.total_venta_msuc,
                        ven.transporte_fob,
                        ven.seguros_fob,
                        ven.otros_fob,
                        ven.transporte_cif,
                        ven.seguros_cif,
                        ven.otros_cif,
                        ven.tipo_cambio_venta,
                        mon.moneda as desc_moneda,
                        ven.valor_bruto,
                        ven.descripcion_bulto,
                        ven.contabilizable,
                        to_char(ven.hora_estimada_entrega,''HH24:MI'')::varchar,
                        ven.forma_pedido,
                        ven.id_cliente_destino,
                        ''''::varchar as cliente_destino,
                        ven.nro_tramite,
                        ven.id_proveedor,
                        ven.id_contrato,
                        ven.id_doc_compra_venta,
                        ven.id_venta_fk,
                        ven.ncd,
                        vpro.desc_proveedor,
                        aux.codigo_auxiliar,
                        dcv.importe_doc,
                        dcv.importe_pendiente,
                        dcv.importe_anticipo,
                        dcv.importe_retgar,
                        dcv.nit,
                        venfk.nro_factura as nro_factura_fk                        	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join forma_pago_temporal forpa on forpa.id_venta = ven.id_venta
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf
                        left join param.vproveedor vpro on vpro.id_proveedor = ven.id_proveedor
                        left join conta.tdoc_compra_venta dcv on dcv.id_doc_compra_venta = ven.id_doc_compra_venta
                        left join conta.tauxiliar aux on aux.id_auxiliar = dcv.id_auxiliar
                        left join vef.tventa venfk on venfk.id_venta = ven.id_venta_fk
                         left join param.tperiodo per on per.id_periodo =  dcv.id_periodo
                        where ven.estado_reg = ''activo'' and ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            
            
            --raise notice 'CONSULTA.... %',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;
        
        /*********************************    
 	#TRANSACCION:  'VF_VENGRID_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VENGRID_CONT')then

		begin

			--Sentencia de la consulta de conteo de registros
			v_consulta:='
                     with forma_pago_temporal as(
					    	select count(*)as cantidad_forma_pago,vfp.id_venta,
					        	pxp.list(fp.id_forma_pago::text) as id_forma_pago, pxp.list(fp.nombre) as forma_pago,
                                sum(monto_transaccion) as monto_transaccion,pxp.list(vfp.numero_tarjeta) as numero_tarjeta,
                                pxp.list(vfp.codigo_tarjeta) as codigo_tarjeta,pxp.list(vfp.tipo_tarjeta) as tipo_tarjeta
					        from vef.tventa_forma_pago vfp
					        inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
					        group by vfp.id_venta        
					    )
            		select count(ven.id_venta)
					    from vef.tventa ven
					    inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join forma_pago_temporal forpa on forpa.id_venta = ven.id_venta
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf
                        left join param.vproveedor vpro on vpro.id_proveedor = ven.id_proveedor
                        left join conta.tdoc_compra_venta dcv on dcv.id_doc_compra_venta = ven.id_doc_compra_venta
                        left join conta.tauxiliar aux on aux.id_auxiliar = dcv.id_auxiliar
                        left join param.tperiodo per on per.id_periodo =  dcv.id_periodo
                        where ven.estado_reg = ''activo'' and ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
             /*********************************    
            #TRANSACCION:  'VF_VENDET_SEL'
            #DESCRIPCION:   Reporte Detalle de Recibo o Factura
            #AUTOR:		admin	
            #FECHA:		01-06-2015 05:58:00
            ***********************************/

            elsif(p_transaccion='VF_VENDET_SEL')then
             				
                begin
                    --Sentencia de la consulta
                    v_consulta:='
                    		select
                                vedet.id_venta_detalle,
                    			vedet.id_venta,
				                (case when vedet.id_item is not null then
                                    item.nombre
                                when vedet.id_sucursal_producto is not null then
                                    cig.desc_ingas
                                when vedet.id_formula is not null then
                                    form.nombre
                                end) as concepto,
                                vedet.cantidad::numeric,   
                                vedet.precio,
                                vedet.precio*vedet.cantidad,
                                um.codigo,
                                cig.nandina,
                                vedet.bruto,
                                vedet.ley,
                                vedet.kg_fino,
                                vedet.descripcion,
                                umcig.codigo as unidad_concepto,
                                sum(vedet.precio*vedet.cantidad) OVER (PARTITION BY vedet.descripcion) as precio_grupo
                                from vef.tventa_detalle vedet						
                                left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
                                left join vef.tformula form on form.id_formula = vedet.id_formula
                                left join alm.titem item on item.id_item = vedet.id_item
                                left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
                                left join param.tunidad_medida um on um.id_unidad_medida = vedet.id_unidad_medida
                                left join param.tunidad_medida umcig on umcig.id_unidad_medida = cig.id_unidad_medida			        
                               where';
                               
                  v_consulta:=v_consulta||v_parametros.filtro;
      			  v_consulta:=v_consulta||'order by vedet.descripcion,vedet.id_venta_detalle asc';        			        
                            
                  --Devuelve la respuesta
                  return v_consulta;
      						
              end; 
               /*********************************    
            #TRANSACCION:  'VF_VENDET_CONT'
            #DESCRIPCION:   count Reporte Detalle de Recibo o Factura
            #AUTOR:		admin	
            #FECHA:		01-06-2015 05:58:00
            ***********************************/

            elsif(p_transaccion='VF_VENDET_CONT')then
             				
                begin
                    --Sentencia de la consulta
                    v_consulta:='
                    		select
                                count(vedet.id_venta_detalle)
                                from vef.tventa_detalle vedet						
                                left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
                                left join vef.tformula form on form.id_formula = vedet.id_formula
                                left join alm.titem item on item.id_item = vedet.id_item
                                left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
                                left join param.tunidad_medida um on um.id_unidad_medida = vedet.id_unidad_medida
                                left join param.tunidad_medida umcig on umcig.id_unidad_medida = cig.id_unidad_medida			        
                               where';
                               
                  v_consulta:=v_consulta||v_parametros.filtro;
      			      			        
                            
                  --Devuelve la respuesta
                  return v_consulta;
      						
              end; 
        /*********************************    
            #TRANSACCION:  'VF_VENINGASPRO_SEL'
            #DESCRIPCION:   lista los productos activos en un punto de venta  
            #AUTOR:		EGS	
            #ISSUE      #2
            #FECHA:		21/01/2019
            ***********************************/

            elsif(p_transaccion='VF_VENINGASPRO_SEL')then
             				
                begin
                    --Sentencia de la consulta
                    v_consulta:='
                    		 SELECT 
                                    ptv.id_punto_venta,
                                    ptv.codigo as codigo_punto_de_venta,
                                    ptv.nombre as nombre_punto_de_venta,
                                    pvp.id_punto_venta_producto,
                                    suc.id_sucursal,
                                    suc.nombre as nombre_sucursal,
                                    pvp.id_sucursal_producto,
                                    supr.id_concepto_ingas,
                                    cing.codigo as codigo_ingas,
                                    cing.desc_ingas
                            FROM vef.tpunto_venta_producto  pvp
                            left join vef.tpunto_venta ptv on ptv.id_punto_venta = pvp.id_punto_venta
                            left join vef.tsucursal_producto supr on supr.id_sucursal_producto = pvp.id_sucursal_producto
                            left join vef.tsucursal suc on suc.id_sucursal = supr.id_sucursal 
                            left join param.tconcepto_ingas cing on cing.id_concepto_ingas = supr.id_concepto_ingas
                            order by suc.id_sucursal,ptv.id_punto_venta';
                /*               
                  v_consulta:=v_consulta||v_parametros.filtro;
      			  v_consulta:=v_consulta||'order by vedet.descripcion,vedet.id_venta_detalle asc';    */    			        
                            
                  --Devuelve la respuesta
                  return v_consulta;
      						
              end; 
               /*********************************    
            #TRANSACCION:  'VF_VENINGASPRO_CONT'
            #DESCRIPCION:   count los productos activos en un punto de venta  
            #AUTOR:		EGS	
            #ISSUE      #2
            #FECHA:		21/01/2019
            ***********************************/

            elsif(p_transaccion='VF_VENINGASPRO_CONT')then
             				
                begin
                    --Sentencia de la consulta
                    v_consulta:='
                            SELECT 
                                   count(ptv.id_punto_venta)
                            FROM vef.tpunto_venta_producto  pvp
                            left join vef.tpunto_venta ptv on ptv.id_punto_venta = pvp.id_punto_venta
                            left join vef.tsucursal_producto supr on supr.id_sucursal_producto = pvp.id_sucursal_producto
                            left join vef.tsucursal suc on suc.id_sucursal = supr.id_sucursal 
                            left join param.tconcepto_ingas cing on cing.id_concepto_ingas = supr.id_concepto_ingas
                            ';
                  /*             
                  v_consulta:=v_consulta||v_parametros.filtro;*/
      			      			        
                            
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