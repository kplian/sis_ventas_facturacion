CREATE OR REPLACE FUNCTION vef.ft_apertura_cierre_caja_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_apertura_cierre_caja_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tapertura_cierre_caja'
 AUTOR: 		 (jrivera)
 FECHA:	        07-07-2016 14:16:20
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_consulta    				varchar;
	v_parametros  				record;
	v_nombre_funcion   			text;
	v_resp						varchar;
    v_fecha						date;
    v_id_pv						integer;
    v_id_sucursal				integer;
    v_id_moneda_base			integer;
    v_id_moneda_tri				integer;
    v_tiene_dos_monedas			varchar;
    v_tipo_cambio				numeric;
    v_moneda_extranjera			varchar;
    v_moneda_local				varchar;
    v_cod_moneda_extranjera		varchar;
    v_cod_moneda_local			varchar;
    
			    
BEGIN

	v_nombre_funcion = 'vef.ft_apertura_cierre_caja_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_APCIE_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		jrivera	
 	#FECHA:		07-07-2016 14:16:20
	***********************************/

	if(p_transaccion='VF_APCIE_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						apcie.id_apertura_cierre_caja,
						apcie.id_sucursal,
						apcie.id_punto_venta,
						apcie.id_usuario_cajero,
						apcie.id_moneda,
						apcie.obs_cierre,
						apcie.monto_inicial,
						apcie.obs_apertura,
						apcie.monto_inicial_moneda_extranjera,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        apcie.estado,
                        apcie.fecha_apertura_cierre,
                        apcie.fecha_hora_cierre,
                        pv.nombre as nombre_punto_venta,
                        suc.nombre as nombre_sucursal,
                        apcie.arqueo_moneda_local,
                        apcie.arqueo_moneda_extranjera
						from vef.tapertura_cierre_caja apcie
						inner join segu.tusuario usu1 on usu1.id_usuario = apcie.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = apcie.id_usuario_mod
				        left join vef.tpunto_venta pv on pv.id_punto_venta = apcie.id_punto_venta
                        left join vef.tsucursal suc on suc.id_sucursal = apcie.id_sucursal
                        
                        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_APCIE_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-07-2016 14:16:20
	***********************************/

	elsif(p_transaccion='VF_APCIE_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_apertura_cierre_caja)
					    from vef.tapertura_cierre_caja apcie
					    inner join segu.tusuario usu1 on usu1.id_usuario = apcie.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = apcie.id_usuario_mod
					    left join vef.tpunto_venta pv on pv.id_punto_venta = apcie.id_punto_venta
                        left join vef.tsucursal suc on suc.id_sucursal = apcie.id_sucursal
                        
                        where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************    
 	#TRANSACCION:  'VF_REPAPCIE_SEL'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-07-2016 14:16:20
	***********************************/

	elsif(p_transaccion='VF_REPAPCIE_SEL')then

		begin
        
        	select acc.id_punto_venta,acc.id_sucursal,acc.id_moneda,acc.fecha_apertura_cierre into v_id_pv,v_id_sucursal,v_id_moneda_base, v_fecha
            from vef.tapertura_cierre_caja acc
            where acc.id_apertura_cierre_caja = v_parametros.id_apertura_cierre_caja;
            
            select m.id_moneda,m.codigo_internacional,m.moneda || ' (' || m.codigo_internacional || ')' into v_id_moneda_tri,v_cod_moneda_extranjera,v_moneda_extranjera
            from param.tmoneda m
            where m.estado_reg = 'activo' and m.triangulacion = 'si';
            
            select m.codigo_internacional,m.moneda || ' (' || m.codigo_internacional || ')' into v_cod_moneda_local,v_moneda_local
            from param.tmoneda m
            where m.id_moneda = v_id_moneda_base ;
            
            v_tiene_dos_monedas = 'no';
            v_tipo_cambio = 1;
            if (v_id_moneda_tri != v_id_moneda_base) then
            	v_tiene_dos_monedas = 'si';
                v_tipo_cambio = param.f_get_tipo_cambio_v2(v_id_moneda_base, v_id_moneda_tri,v_fecha,'O');
            end if;
            
			--Sentencia de la consulta de conteo de registros
			v_consulta:='with forma_pago as (
                          select fp.id_forma_pago,fp.id_moneda,
                          (case when fp.codigo like ''CA%'' then
                              ''CASH''
                          when fp.codigo like ''CC%'' then
                              ''CC''
                          when fp.codigo like ''CT%'' then
                              ''CT''
                          when fp.codigo like ''MCO%'' then
                              ''MCO''
                          else
                              ''OTRO''
                          end)::varchar as codigo
                          
                          from obingresos.tforma_pago fp
                          

                      )
                      select u.desc_persona::varchar, to_char(acc.fecha_apertura_cierre,''DD/MM/YYYY'')::varchar, 
                      coalesce(ppv.codigo,ps.codigo)::varchar as pais, COALESCE(lpv.codigo,ls.codigo)::varchar as estacion,
                      coalesce(pv.codigo || ''-'' || pv.nombre, s.codigo || ''-'' || s.nombre)::varchar as punto_venta,
                      acc.obs_cierre::varchar, acc.arqueo_moneda_local,acc.arqueo_moneda_extranjera,acc.monto_inicial,acc.monto_inicial_moneda_extranjera,
                      ' || v_tipo_cambio || '::numeric as tipo_cambio, ''' || v_tiene_dos_monedas || '''::varchar as tiene_dos_monedas,
                      ''' || v_moneda_local || '''::varchar as moneda_local,''' || v_moneda_extranjera || '''::varchar as moneda_extranjera,
                       ''' || v_cod_moneda_local || '''::varchar as cod_moneda_local,''' || v_cod_moneda_extranjera || '''::varchar as cod_moneda_extranjera, 
                      sum(case  when fp.codigo = ''CASH'' and fp.id_moneda = ' || v_id_moneda_base  || ' then
                              bfp.importe
                          else
                              0
                          end)as efectivo_boletos_ml,
                      sum(case  when fp.codigo = ''CASH'' and fp.id_moneda = ' || v_id_moneda_tri  || ' then
                              bfp.importe
                          else
                              0
                          end)as efectivo_boletos_me,
                      sum(case  when fp.codigo = ''CC'' and fp.id_moneda = ' || v_id_moneda_base  || ' then
                              bfp.importe
                          else
                              0
                          end)as tarjeta_boletos_ml,
                      sum(case  when fp.codigo = ''CC'' and fp.id_moneda = ' || v_id_moneda_tri  || ' then
                              bfp.importe
                          else
                              0
                          end)as tarjeta_boletos_me,
                      sum(case  when fp.codigo = ''CT'' and fp.id_moneda = ' || v_id_moneda_base  || ' then
                              bfp.importe
                          else
                              0
                          end)as cuenta_corriente_boletos_ml,
                      sum(case  when fp.codigo = ''CT'' and fp.id_moneda = ' || v_id_moneda_tri  || ' then
                              bfp.importe
                          else
                              0
                          end)as cuenta_corriente_boletos_me,
                      sum(case  when fp.codigo = ''MCO'' and fp.id_moneda = ' || v_id_moneda_base  || ' then
                              bfp.importe
                          else
                              0
                          end)as mco_boletos_ml,
                      sum(case  when fp.codigo = ''MCO'' and fp.id_moneda = ' || v_id_moneda_tri  || ' then
                              bfp.importe
                          else
                              0
                          end)as mco_boletos_me,
                      sum(case  when fp.codigo = ''OTRO'' and fp.id_moneda = ' || v_id_moneda_base  || ' then
                              bfp.importe
                          else
                              0
                          end)as otro_boletos_ml,
                      sum(case  when fp.codigo like ''OTRO'' and fp.id_moneda = ' || v_id_moneda_tri  || ' then
                              bfp.importe
                          else
                              0
                          end)as otro_boletos_me,
                          
                          
                      sum(case  when fp2.codigo = ''CASH'' and fp2.id_moneda = ' || v_id_moneda_base  || ' then
                              vfp.monto_mb_efectivo
                          else
                              0
                          end)as efectivo_ventas_ml,
                      sum(case  when fp2.codigo = ''CASH'' and fp2.id_moneda = ' || v_id_moneda_tri  || ' then
                              vfp.monto_mb_efectivo/' || v_tipo_cambio || '
                          else
                              0
                          end)as efectivo_ventas_me,
                      sum(case  when fp2.codigo = ''CC'' and fp2.id_moneda = ' || v_id_moneda_base  || ' then
                              vfp.monto_mb_efectivo
                          else
                              0
                          end)as tarjeta_ventas_ml,
                      sum(case  when fp2.codigo = ''CC'' and fp2.id_moneda = ' || v_id_moneda_tri  || ' then
                              vfp.monto_mb_efectivo/' || v_tipo_cambio || '
                          else
                              0
                          end)as tarjeta_vetas_me,
                      sum(case  when fp2.codigo = ''CT'' and fp2.id_moneda = ' || v_id_moneda_base  || ' then
                              vfp.monto_mb_efectivo
                          else
                              0
                          end)as cuenta_corriente_ventas_ml,
                      sum(case  when fp2.codigo = ''CT'' and fp2.id_moneda = ' || v_id_moneda_tri  || ' then
                              vfp.monto_mb_efectivo/' || v_tipo_cambio || '
                          else
                              0
                          end)as cuenta_corriente_ventas_me,
                      sum(case  when fp2.codigo = ''MCO'' and fp2.id_moneda = ' || v_id_moneda_base  || ' then
                              vfp.monto_mb_efectivo
                          else
                              0
                          end)as mco_ventas_ml,
                      sum(case  when fp2.codigo = ''MCO'' and fp2.id_moneda = ' || v_id_moneda_tri  || ' then
                              vfp.monto_mb_efectivo/' || v_tipo_cambio || '
                          else
                              0
                          end)as mco_ventas_me,
                      sum(case  when fp2.codigo = ''OTRO'' and fp2.id_moneda = ' || v_id_moneda_base  || ' then
                              vfp.monto_mb_efectivo
                          else
                              0
                          end)as otro_ventas_ml,
                      sum(case  when fp2.codigo like ''OTRO'' and fp2.id_moneda = ' || v_id_moneda_tri  || ' then
                              vfp.monto_mb_efectivo/' || v_tipo_cambio || '
                          else
                              0
                          end)as otro_ventas_me,
                      (	select sum(ven.comision) from vef.tventa ven 
                          where coalesce(ven.comision,0) > 0 and ven.id_moneda = ' || v_id_moneda_base  || ' and 
                                  ven.fecha = acc.fecha_apertura_cierre and ven.id_punto_venta= acc.id_punto_venta  
                                  and ven.id_usuario_cajero = acc.id_usuario_cajero and
                                  ven.estado = ''finalizado'') +
                       
                      (	select sum(bol.comision) from obingresos.tboleto bol 
                          where coalesce(bol.comision,0) > 0 and bol.id_moneda_boleto = ' || v_id_moneda_base  || ' and 
                                  bol.fecha_emision = acc.fecha_apertura_cierre and bol.id_punto_venta=acc.id_punto_venta  
                                  and bol.id_usuario_cajero = acc.id_usuario_cajero and
                                  bol.estado = ''pagado'') as comisiones_ml,

                      (	select sum(ven.comision) from vef.tventa ven 
                          where coalesce(ven.comision,0) > 0 and ven.id_moneda = ' || v_id_moneda_tri  || ' and 
                                  ven.fecha = acc.fecha_apertura_cierre and ven.id_punto_venta=acc.id_punto_venta  
                                  and ven.id_usuario_cajero = acc.id_usuario_cajero and
                                  ven.estado = ''finalizado'') +
                       
                      (	select sum(bol.comision) from obingresos.tboleto bol 
                          where coalesce(bol.comision,0) > 0 and bol.id_moneda_boleto = ' || v_id_moneda_tri  || ' and 
                                  bol.fecha_emision = acc.fecha_apertura_cierre and bol.id_punto_venta= acc.id_punto_venta 
                                   and bol.id_usuario_cajero = acc.id_usuario_cajero and
                                  bol.estado = ''pagado'')   as comisiones_me
                         
                      from vef.tapertura_cierre_caja acc 
                      inner join segu.vusuario u on u.id_usuario = acc.id_usuario_cajero
                      left join vef.tsucursal s on acc.id_sucursal = s.id_sucursal
                      left join vef.tpunto_venta pv on pv.id_punto_venta = acc.id_punto_venta
                      left join vef.tsucursal spv on spv.id_sucursal = pv.id_sucursal
                      left join param.tlugar lpv on lpv.id_lugar = spv.id_lugar
                      left join param.tlugar ls on ls.id_lugar = s.id_lugar
                      left join param.tlugar ppv on ppv.id_lugar = param.f_get_id_lugar_pais(lpv.id_lugar)
                      left join param.tlugar ps on ps.id_lugar = param.f_get_id_lugar_pais(ls.id_lugar)
                      left join obingresos.tboleto b on b.id_usuario_cajero = u.id_usuario
                                                      and b.fecha_reg::date = acc.fecha_apertura_cierre and
                                                      b.id_punto_venta = acc.id_punto_venta and b.estado = ''pagado''

                      left join obingresos.tboleto_forma_pago bfp on bfp.id_boleto = b.id_boleto
                      left join forma_pago fp on fp.id_forma_pago = bfp.id_forma_pago
                      left join vef.tventa v on v.id_usuario_cajero = u.id_usuario
                                                      and v.fecha = acc.fecha_apertura_cierre and
                                                      v.id_punto_venta = acc.id_punto_venta and v.estado = ''finalizado''

                      left join vef.tventa_forma_pago vfp on vfp.id_venta = v.id_venta
                      left join forma_pago fp2 on fp2.id_forma_pago = vfp.id_forma_pago     
                      where acc.id_apertura_cierre_caja = ' || v_parametros.id_apertura_cierre_caja  || '
                      group by u.desc_persona, acc.fecha_apertura_cierre, 
                      ppv.codigo,ps.codigo,lpv.codigo,ls.codigo,
                      pv.codigo , pv.nombre, s.codigo ,s.nombre,acc.id_punto_venta,
                      acc.id_usuario_cajero,acc.obs_cierre, acc.arqueo_moneda_local,
                      acc.arqueo_moneda_extranjera,acc.monto_inicial,acc.monto_inicial_moneda_extranjera';
			
			--Definicion de la respuesta		    
			
			raise notice '%',v_consulta;
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