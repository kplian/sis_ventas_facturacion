CREATE OR REPLACE FUNCTION vef.ft_apertura_cierre_caja_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
  RETURNS varchar AS
  $body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_apertura_cierre_caja_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tapertura_cierre_caja'
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

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_apertura_cierre_caja	integer;
    v_id_moneda				integer;
    v_cod_moneda			varchar;
    v_registro				record;
    v_total_ventas			numeric;
    v_total_boletos			numeric;
    v_id_moneda_usd			integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_apertura_cierre_caja_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_APCIE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-07-2016 14:16:20
	***********************************/

	if(p_transaccion='VF_APCIE_INS')then
					
        begin
        
        	if (exists (select 1 
            			from vef.tapertura_cierre_caja acc 
                        where id_usuario_cajero = p_id_usuario and 
                        (id_punto_venta =v_parametros.id_punto_venta or id_sucursal =v_parametros.id_sucursal) 
        				and fecha_apertura_cierre = now()::date and estado_reg = 'activo')) then
            	raise exception 'La caja ya esta abierta para el usuario. Por favor revise los datos';
            end if; 
            
            if (exists (select 1 
            			from vef.tapertura_cierre_caja acc 
                        where id_usuario_cajero = p_id_usuario and                         
        				estado = 'abierto')) then
            	raise exception 'El usuario ya tiene una caja abierta. Debe cerrarla para poder abrir otra';
            end if;          
            
        	
            if (v_parametros.id_sucursal is not null) then
            	select sm.id_moneda into v_id_moneda
                from vef.tsucursal s
                inner join vef.tsucursal_moneda sm on s.id_sucursal = sm.id_sucursal
                where s.id_sucursal = v_parametros.id_sucursal and sm.tipo_moneda = 'moneda_base';
            
            	if ( not exists (select 1 
                                from vef.tsucursal_usuario su
                                where su.id_sucursal = v_parametros.id_sucursal and su.estado_reg = 'activo' and
                                su.tipo_usuario = 'cajero')) then
                	if (p_administrador = 0) then
                    	raise exception 'El usuario no esta registrado como cajero de la sucursal';
                    end if;
                end if;
            end if;
            
            if (v_parametros.id_punto_venta is not null) then
            	select sm.id_moneda into v_id_moneda
                from vef.tpunto_venta s
                inner join vef.tsucursal_moneda sm on s.id_sucursal = sm.id_sucursal
                where s.id_punto_venta = v_parametros.id_punto_venta and sm.tipo_moneda = 'moneda_base';
            
            	if ( not exists (select 1 
                                from vef.tsucursal_usuario su
                                where su.id_punto_venta = v_parametros.id_punto_venta and su.estado_reg = 'activo' and 
                                su.tipo_usuario = 'cajero')) then
                	if (p_administrador = 0) then
                    	raise exception 'El usuario no esta registrado como cajero del punto de venta';
                    end if;
                end if;
            end if;
            
            
            
            --Sentencia de la insercion
        	insert into vef.tapertura_cierre_caja(
			id_sucursal,
			id_punto_venta,
			id_usuario_cajero,
			id_moneda,			
			monto_inicial,
			obs_apertura,
			monto_inicial_moneda_extranjera,
            id_usuario_reg,
            fecha_apertura_cierre,
            estado
          	) values(
			v_parametros.id_sucursal,
			v_parametros.id_punto_venta,
			p_id_usuario,
			v_id_moneda,			
			v_parametros.monto_inicial,
			v_parametros.obs_apertura,
			v_parametros.monto_inicial_moneda_extranjera,
            p_id_usuario,
            now()::date,
            'abierto'		
			
			)RETURNING id_apertura_cierre_caja into v_id_apertura_cierre_caja;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Apertura de Caja almacenado(a) con exito (id_apertura_cierre_caja'||v_id_apertura_cierre_caja||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_apertura_cierre_caja',v_id_apertura_cierre_caja::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_APCIE_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-07-2016 14:16:20
	***********************************/

	elsif(p_transaccion='VF_APCIE_MOD')then

		begin
        
        	if (exists (select 1 
            			from vef.tapertura_cierre_caja acc 
                        where id_usuario_cajero = p_id_usuario and 
                        (id_punto_venta =v_parametros.id_punto_venta or id_sucursal =v_parametros.id_sucursal) 
        				and fecha_apertura_cierre = now()::date and estado_reg = 'activo' and
                        acc.id_apertura_cierre_caja != v_parametros.id_apertura_cierre_caja )) then
            	raise exception 'La caja ya esta abierta para el usuario. Por favor revise los datos';
            end if;	
        
        
			--Sentencia de la modificacion
			update vef.tapertura_cierre_caja set
			id_sucursal = v_parametros.id_sucursal,
			id_punto_venta = v_parametros.id_punto_venta,
			obs_cierre = v_parametros.obs_cierre,
			monto_inicial = v_parametros.monto_inicial,
			obs_apertura = v_parametros.obs_apertura,
			monto_inicial_moneda_extranjera = v_parametros.monto_inicial_moneda_extranjera,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            estado = (case when v_parametros.accion = 'cerrar' then
            			'cerrado'
            		  else
                      	'abierto'
                      end),
            arqueo_moneda_local = v_parametros.arqueo_moneda_local,
            arqueo_moneda_extranjera = v_parametros.arqueo_moneda_extranjera
			where id_apertura_cierre_caja=v_parametros.id_apertura_cierre_caja;
            
            select * into v_registro 
            from vef.tapertura_cierre_caja
            where id_apertura_cierre_caja=v_parametros.id_apertura_cierre_caja;
            
            --obtener codigo de moneda e id_moneda de la sucursal
            if (v_parametros.id_sucursal is not null) then
            	select m.codigo_internacional,m.id_moneda into v_cod_moneda,v_id_moneda
                from vef.tpunto_venta s
                inner join vef.tsucursal_moneda sm on s.id_sucursal = sm.id_sucursal
                inner join param.tmoneda m on m.id_moneda = sm.id_moneda
                where s.id_sucursal = v_parametros.id_sucursal and sm.tipo_moneda = 'moneda_base';
                      	
            end if;
            --obtener codigo de moneda e id_moneda del putno de venta
            if (v_parametros.id_punto_venta is not null) then
            	select m.codigo_internacional,m.id_moneda into v_cod_moneda,v_id_moneda
                from vef.tpunto_venta s
                inner join vef.tsucursal_moneda sm on s.id_sucursal = sm.id_sucursal
                inner join param.tmoneda m on m.id_moneda = sm.id_moneda
                where s.id_punto_venta = v_parametros.id_punto_venta and sm.tipo_moneda = 'moneda_base';
                        	
            end if;
            
            --Si la moneda de la sucursal es USD quiere decir q no debe haber moneda extranjera
            if (v_cod_moneda = 'USD' and v_parametros.accion = 'cerrar' and coalesce(v_parametros.arqueo_moneda_extranjera,0) > 0 ) then
            	raise exception 'No se maneja importes en moneda extranjera en esta sucursal';
            end if;
            
            --Si la accion es cerrar
            if (v_parametros.accion = 'cerrar') then
            	--obtener el ototal de ventas ya sea por sucursal o por punto de venta
            	if (v_parametros.id_punto_venta is not null) then
                    select coalesce(sum (vfp.monto_mb_efectivo),0) into v_total_ventas
                    from vef.tventa v
                    inner join vef.tventa_forma_pago vfp on vfp.id_venta = v.id_venta
                    inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
                    inner join param.tmoneda mon on mon.id_moneda = fp.id_moneda
                    where v.estado = 'finalizado'  and v.fecha_reg::date = v_registro.fecha_apertura_cierre and 
                        v_parametros.id_punto_venta = v.id_punto_venta and v.id_usuario_cajero = p_id_usuario and
                        fp.codigo like 'CA';
            	else
                	select coalesce(sum (vfp.monto_mb_efectivo),0)  into v_total_ventas
                    from vef.tventa v
                    inner join vef.tventa_forma_pago vfp on vfp.id_venta = v.id_venta
                    inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
                    inner join param.tmoneda mon on mon.id_moneda = fp.id_moneda
                    where v.estado = 'finalizado'  and v.fecha_reg::date = v_registro.fecha_apertura_cierre and 
                        v_parametros.id_sucursal = v.id_sucursal and v.id_usuario_cajero = p_id_usuario and
                        fp.codigo like 'CA';
                end if;
                
                select m.id_moneda into v_id_moneda_usd
                from param.tmoneda m
                where m.codigo_internacional = 'USD';
                    
                --si el sistema de ventas se integra con ingresos de boa obtener el total de bolesto
                if (pxp.f_get_variable_global('vef_integracion_obingresos') = 'true') then
                
                    select sum((case when mon.codigo_internacional = 'USD' and v_cod_moneda != 'USD' then
                                    param.f_convertir_moneda(mon.id_moneda,v_id_moneda,bfp.importe,now()::date,'O',2) 
                                else
                                    bfp.importe
                                end)) into v_total_boletos 
                    from obingresos.tboleto b
                    inner join obingresos.tboleto_forma_pago bfp on bfp.id_boleto = b.id_boleto
                    inner join vef.tforma_pago fp on fp.id_forma_pago = bfp.id_forma_pago
                    inner join param.tmoneda mon on mon.id_moneda = fp.id_moneda
                    where b.estado = 'pagado' and b.fecha_reg::date = v_registro.fecha_apertura_cierre and 
                    b.id_punto_venta = v_parametros.id_punto_venta and b.id_usuario_cajero = p_id_usuario and
                    fp.codigo like 'CA';
                    --raise exception 'llega %,%,%',v_parametros.id_punto_venta,v_registro.fecha_apertura_cierre,p_id_usuario;
            	ELSE
                	v_total_boletos = 0;
                end if;
                
                --si hay un monto de arqueo en moneda extranjera lo convertimos a moneda de sucursal
                if (coalesce(v_parametros.arqueo_moneda_extranjera,0) > 0) then
                	v_parametros.arqueo_moneda_extranjera = param.f_convertir_moneda(v_id_moneda_usd,v_id_moneda,coalesce(v_parametros.arqueo_moneda_extranjera,0),now()::date,'O',2);
                end if;
            	
            	--si el total de ventas y boletos es menor q los arqueos en moneda de sucursal falta dinero!!!
                if (v_total_boletos + v_total_ventas + v_registro.monto_inicial + param.f_convertir_moneda(v_id_moneda_usd,v_id_moneda,coalesce(v_registro.monto_inicial_moneda_extranjera,0),now()::date,'O',2) > v_parametros.arqueo_moneda_local + COALESCE(v_parametros.arqueo_moneda_extranjera,0) ) then
                	raise exception 'Los montos del arqueo en moneda local y extranjera son inferiores al total vendido en efectivo mas el monto inicial: %',v_total_boletos + v_total_ventas + v_registro.monto_inicial;
                end if;
                
            end if;           
            
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Apertura de Caja modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_apertura_cierre_caja',v_parametros.id_apertura_cierre_caja::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_APCIE_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-07-2016 14:16:20
	***********************************/

	elsif(p_transaccion='VF_APCIE_ELI')then

		begin
			--Sentencia de la eliminacion
            
            select * into v_registro
            from vef.tapertura_cierre_caja apc
            where id_apertura_cierre_caja = v_parametros.id_apertura_cierre_caja;
            
            
            
            if (exists (select 1 from vef.tventa v
            			where v.estado_reg = 'activo' and 
                        	(v.id_punto_venta = v_registro.id_punto_venta or v.id_sucursal = v_registro.id_sucursal ) and
                            v.fecha = v_registro.fecha_apertura_cierre and v.id_usuario_cajero = p_id_usuario)) then
				raise exception 'Ya se registraron ventas con esta apertura de caja. Debe eliminar esas ventas para poder eliminar la apertura';
            end if;
            if (exists (select 1 from pg_catalog.pg_namespace where nspname = 'obingresos' ))then
                if (exists (select 1 from obingresos.tboleto b
                            where b.estado_reg = 'activo' and 
                                (b.id_punto_venta = v_registro.id_punto_venta) and
                                b.fecha_emision = v_registro.fecha_apertura_cierre and b.id_usuario_cajero = p_id_usuario)) then
                    raise exception 'Ya se emitieron boletos con esta apertura de caja. Debe eliminar esos boletos para poder eliminar la apertura';
                end if;
            end if;
            
            delete from vef.tapertura_cierre_caja
            where id_apertura_cierre_caja=v_parametros.id_apertura_cierre_caja;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Apertura de Caja eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_apertura_cierre_caja',v_parametros.id_apertura_cierre_caja::varchar);
              
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