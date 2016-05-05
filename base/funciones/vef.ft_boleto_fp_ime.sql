CREATE OR REPLACE FUNCTION "vef"."ft_boleto_fp_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_boleto_fp_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tboleto_fp'
 AUTOR: 		 (jrivera)
 FECHA:	        26-11-2015 22:03:35
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
	v_id_boleto_fp	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_boleto_fp_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_BOLFP_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		26-11-2015 22:03:35
	***********************************/

	if(p_transaccion='VF_BOLFP_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tboleto_fp(
			id_boleto,
			id_forma_pago,
			estado_reg,
			monto,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.id_boleto,
			v_parametros.id_forma_pago,
			'activo',
			v_parametros.monto,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_boleto_fp into v_id_boleto_fp;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Boleto Forma de Pago almacenado(a) con exito (id_boleto_fp'||v_id_boleto_fp||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_boleto_fp',v_id_boleto_fp::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_BOLFP_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		26-11-2015 22:03:35
	***********************************/

	elsif(p_transaccion='VF_BOLFP_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tboleto_fp set
			id_boleto = v_parametros.id_boleto,
			id_forma_pago = v_parametros.id_forma_pago,
			monto = v_parametros.monto,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_boleto_fp=v_parametros.id_boleto_fp;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Boleto Forma de Pago modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_boleto_fp',v_parametros.id_boleto_fp::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_BOLFP_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		26-11-2015 22:03:35
	***********************************/

	elsif(p_transaccion='VF_BOLFP_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tboleto_fp
            where id_boleto_fp=v_parametros.id_boleto_fp;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Boleto Forma de Pago eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_boleto_fp',v_parametros.id_boleto_fp::varchar);
              
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
ALTER FUNCTION "vef"."ft_boleto_fp_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
