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

<<<<<<< HEAD
		end;	
=======
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
>>>>>>> 3bc7616c154dde3c057a95c28720a79c8e75cd3c
    				
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