CREATE OR REPLACE FUNCTION vef.ft_venta_forma_pago_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_venta_forma_pago_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tventa_forma_pago'
 AUTOR: 		 (jrivera)
 FECHA:	        22-10-2015 14:49:46
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
	v_id_venta_forma_pago	integer;
    v_forma_pago			record;
    v_res					varchar;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_venta_forma_pago_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_VENFP_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-10-2015 14:49:46
	***********************************/

	if(p_transaccion='VF_VENFP_INS')then
					
        begin
        	select * into v_forma_pago
            from vef.tforma_pago fp 
            where fp.id_forma_pago = v_parametros.id_forma_pago;
        	--Sentencia de la insercion
        	insert into vef.tventa_forma_pago(
			id_forma_pago,
			id_venta,
			monto_mb_efectivo,
			estado_reg,
			cambio,
			monto_transaccion,
			monto,			
			fecha_reg,
			id_usuario_reg,			
			fecha_mod,
			id_usuario_mod,
			numero_tarjeta,
			codigo_tarjeta,
			tipo_tarjeta
          	) values(
			v_parametros.id_forma_pago,
			v_parametros.id_venta,
			0,
			'activo',
			0,
			v_parametros.valor,
			0,			
			now(),
			p_id_usuario,			
			null,
			null,
			v_parametros.numero_tarjeta,
			v_parametros.codigo_tarjeta,
			v_parametros.tipo_tarjeta
							
			
			
			)RETURNING id_venta_forma_pago into v_id_venta_forma_pago;
            
            if (v_forma_pago.registrar_tarjeta = 'si' and v_forma_pago.registrar_tipo_tarjeta = 'no')then
            	v_res = pxp.f_valida_numero_tarjeta_credito(v_parametros.numero_tarjeta,substring(v_forma_pago.codigo from 3 for 2));
            elsif (v_forma_pago.registrar_tarjeta = 'si' and v_forma_pago.registrar_tipo_tarjeta = 'si')then
            	v_res = pxp.f_valida_numero_tarjeta_credito(v_parametros.numero_tarjeta,v_parametros.tipo_tarjeta);
            end if; 
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Forma de Pago almacenado(a) con exito (id_venta_forma_pago'||v_id_venta_forma_pago||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta_forma_pago',v_id_venta_forma_pago::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_VENFP_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-10-2015 14:49:46
	***********************************/

	elsif(p_transaccion='VF_VENFP_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tventa_forma_pago set
			id_forma_pago = v_parametros.id_forma_pago,
			id_venta = v_parametros.id_venta,
			monto_mb_efectivo = v_parametros.monto_mb_efectivo,
			cambio = v_parametros.cambio,
			monto_transaccion = v_parametros.monto_transaccion,
			monto = v_parametros.monto,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_venta_forma_pago=v_parametros.id_venta_forma_pago;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Forma de Pago modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta_forma_pago',v_parametros.id_venta_forma_pago::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VENFP_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-10-2015 14:49:46
	***********************************/

	elsif(p_transaccion='VF_VENFP_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tventa_forma_pago
            where id_venta_forma_pago=v_parametros.id_venta_forma_pago;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Forma de Pago eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta_forma_pago',v_parametros.id_venta_forma_pago::varchar);
              
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