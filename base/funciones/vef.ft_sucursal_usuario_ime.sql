CREATE OR REPLACE FUNCTION "vef"."ft_sucursal_usuario_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_usuario_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tsucursal_usuario'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 07:33:37
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
	v_id_sucursal_usuario	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_sucursal_usuario_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SUCUSU_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:37
	***********************************/

	if(p_transaccion='VF_SUCUSU_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tsucursal_usuario(
			id_sucursal,
			id_usuario,
			estado_reg,
			tipo_usuario,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
			id_punto_venta
          	) values(
			v_parametros.id_sucursal,
			v_parametros.id_usuario,
			'activo',
			v_parametros.tipo_usuario,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null,
			v_parametros.id_punto_venta
							
			
			
			)RETURNING id_sucursal_usuario into v_id_sucursal_usuario;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Usuarios almacenado(a) con exito (id_sucursal_usuario'||v_id_sucursal_usuario||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_usuario',v_id_sucursal_usuario::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUCUSU_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:37
	***********************************/

	elsif(p_transaccion='VF_SUCUSU_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tsucursal_usuario set			
			id_usuario = v_parametros.id_usuario,
			tipo_usuario = v_parametros.tipo_usuario,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_sucursal_usuario=v_parametros.id_sucursal_usuario;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Usuarios modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_usuario',v_parametros.id_sucursal_usuario::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUCUSU_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:37
	***********************************/

	elsif(p_transaccion='VF_SUCUSU_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tsucursal_usuario
            where id_sucursal_usuario=v_parametros.id_sucursal_usuario;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Usuarios eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_usuario',v_parametros.id_sucursal_usuario::varchar);
              
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
ALTER FUNCTION "vef"."ft_sucursal_usuario_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
