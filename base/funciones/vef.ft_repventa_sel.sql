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

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
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
            	v_consulta:='select ''TARIFA NETA''
                			UNION ALL
                			select cig.desc_ingas
                			 from vef.tpunto_venta_producto pvp
                             inner join vef.tsucursal_producto sp 
                             	on sp.id_sucursal_producto = pvp.id_sucursal_producto
                             inner join param.tconcepto_ingas cig
                             	on cig.id_concepto_ingas = sp.id_concepto_ingas
                             where pvp.estado_reg = ''activo'' and sp.estado_reg = ''activo''
                             and cig.estado_reg = ''activo'' and 
                             pvp.id_punto_venta = ' || v_parametros.id_punto_venta;     
            ELSE            
            	v_consulta:='select ''TARIFA NETA''
                			UNION ALL
                			select cig.desc_ingas
                			 from vef.tsucursal_producto sp 
                             	on sp.id_sucursal_producto = pvp.id_sucursal_producto
                             inner join param.tconcepto_ingas cig
                             	on cig.id_concepto_ingas = sp.id_concepto_ingas
                             where sp.estado_reg = ''activo''
                             and cig.estado_reg = ''activo'' and 
                             sp.id_sucursal = ' || v_parametros.id_sucursal;           
            END IF;
        	
            
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
                  select v.fecha_reg::date as fecha,v.correlativo_venta,cli.nombre_factura,vd.descripcion::varchar as boleto,''''::varchar as ruta,cig.desc_ingas,
                  round(((coalesce(fpcc.monto_tarjeta,0)/v.total_venta)*vd.cantidad*vd.precio),2) as precio_cc,
                  round(((coalesce(fpcash.monto_efectivo,0)/v.total_venta)*vd.cantidad*vd.precio),2) as precio_cash,
                  vd.cantidad*vd.precio as monto
                  from vef.tventa v
                  inner join vef.tventa_detalle vd 
                      on v.id_venta = vd.id_venta and vd.estado_reg = ''activo''
                  inner join vef.tsucursal_producto sp 
                      on sp.id_sucursal_producto = vd.id_sucursal_producto
                  inner join param.tconcepto_ingas cig 
                      on cig.id_concepto_ingas = sp.id_concepto_ingas
                  inner join vef.tcliente cli 
                      on cli.id_cliente = v.id_cliente
                  left join forma_pago_cc fpcc
                      on v.id_venta = fpcc.id_venta
                  left join forma_pago_cash fpcash
                      on v.id_venta = fpcash.id_venta
                  where v.estado = ''finalizado'' and ' || v_filtro || ' and
                  	(v.fecha_reg::date between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || '''))
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
             SELECT b.fecha, ''''::varchar as correlativo_venta,''''::varchar as nombre_factura,b.numero as boleto,b.ruta,
             ''TARIFA NETA''::varchar as concepto,
             fpcc.monto_tarjeta as precio_tarjeta,
             fpcash.monto_efectivo as precio_cash,
             coalesce(fpcc.monto_tarjeta,0) + coalesce(fpcash.monto_efectivo,0) as monto
             
             from vef.tboleto b
             left join bol_forma_pago_cc fpcc 
                on fpcc.id_boleto = b.id_boleto
             left join bol_forma_pago_cash fpcash 
                on fpcash.id_boleto = b.id_boleto
             where ' || v_filtro || ' and 
             (b.fecha between ''' || v_parametros.fecha_desde || ''' and ''' || v_parametros.fecha_hasta || '''))
             order by fecha,boleto';     

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