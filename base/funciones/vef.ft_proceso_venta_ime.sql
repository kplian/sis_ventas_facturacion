CREATE OR REPLACE FUNCTION "vef"."ft_proceso_venta_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_proceso_venta_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tproceso_venta'
 AUTOR: 		 (jrivera)
 FECHA:	        22-03-2016 21:50:14
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
	v_id_proceso_venta	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_proceso_venta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_PROCON_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 21:50:14
	***********************************/

	if(p_transaccion='VF_PROCON_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tproceso_venta(
			tipos,
			estado_reg,
			fecha_desde,
			id_int_comprobante,
			fecha_hasta,
			estado,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.tipos,
			'activo',
			v_parametros.fecha_desde,
			v_parametros.id_int_comprobante,
			v_parametros.fecha_hasta,
			v_parametros.estado,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_proceso_venta into v_id_proceso_venta;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Proceso de contabilizacion almacenado(a) con exito (id_proceso_venta'||v_id_proceso_venta||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_proceso_venta',v_id_proceso_venta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_PROCON_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 21:50:14
	***********************************/

	elsif(p_transaccion='VF_PROCON_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tproceso_venta set
			tipos = v_parametros.tipos,
			fecha_desde = v_parametros.fecha_desde,
			id_int_comprobante = v_parametros.id_int_comprobante,
			fecha_hasta = v_parametros.fecha_hasta,
			estado = v_parametros.estado,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_proceso_venta=v_parametros.id_proceso_venta;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Proceso de contabilizacion modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_proceso_venta',v_parametros.id_proceso_venta::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_PROCON_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 21:50:14
	***********************************/

	elsif(p_transaccion='VF_PROCON_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tproceso_venta
            where id_proceso_venta=v_parametros.id_proceso_venta;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Proceso de contabilizacion eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_proceso_venta',v_parametros.id_proceso_venta::varchar);
              
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
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "vef"."ft_proceso_venta_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
