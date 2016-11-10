CREATE OR REPLACE FUNCTION "vef"."ft_punto_venta_producto_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_punto_venta_producto_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tpunto_venta_producto'
 AUTOR: 		 (jrivera)
 FECHA:	        07-10-2015 21:02:03
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
	v_id_punto_venta_producto	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_punto_venta_producto_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_PUVEPRO_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-10-2015 21:02:03
	***********************************/

	if(p_transaccion='VF_PUVEPRO_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tpunto_venta_producto(
			estado_reg,
			id_sucursal_producto,
			id_punto_venta,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.id_sucursal_producto,
			v_parametros.id_punto_venta,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_punto_venta_producto into v_id_punto_venta_producto;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Punto de Venta Producto almacenado(a) con exito (id_punto_venta_producto'||v_id_punto_venta_producto||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_punto_venta_producto',v_id_punto_venta_producto::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_PUVEPRO_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-10-2015 21:02:03
	***********************************/

	elsif(p_transaccion='VF_PUVEPRO_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tpunto_venta_producto set
			id_sucursal_producto = v_parametros.id_sucursal_producto,
			id_punto_venta = v_parametros.id_punto_venta,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_punto_venta_producto=v_parametros.id_punto_venta_producto;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Punto de Venta Producto modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_punto_venta_producto',v_parametros.id_punto_venta_producto::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_PUVEPRO_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-10-2015 21:02:03
	***********************************/

	elsif(p_transaccion='VF_PUVEPRO_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tpunto_venta_producto
            where id_punto_venta_producto=v_parametros.id_punto_venta_producto;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Punto de Venta Producto eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_punto_venta_producto',v_parametros.id_punto_venta_producto::varchar);
              
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
ALTER FUNCTION "vef"."ft_punto_venta_producto_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
