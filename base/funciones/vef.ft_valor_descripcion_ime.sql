CREATE OR REPLACE FUNCTION "vef"."ft_valor_descripcion_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_valor_descripcion_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tvalor_descripcion'
 AUTOR: 		 (admin)
 FECHA:	        23-04-2016 14:24:45
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
	v_id_valor_descripcion	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_valor_descripcion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_vald_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		23-04-2016 14:24:45
	***********************************/

	if(p_transaccion='VF_vald_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tvalor_descripcion(
			estado_reg,
			valor,
			id_tipo_descripcion,
			obs,
			id_venta,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.valor,
			v_parametros.id_tipo_descripcion,
			v_parametros.obs,
			v_parametros.id_venta,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_valor_descripcion into v_id_valor_descripcion;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Valores almacenado(a) con exito (id_valor_descripcion'||v_id_valor_descripcion||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_valor_descripcion',v_id_valor_descripcion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_vald_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		23-04-2016 14:24:45
	***********************************/

	elsif(p_transaccion='VF_vald_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tvalor_descripcion set
			valor = v_parametros.valor,
			id_tipo_descripcion = v_parametros.id_tipo_descripcion,
			obs = v_parametros.obs,
			id_venta = v_parametros.id_venta,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_valor_descripcion=v_parametros.id_valor_descripcion;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Valores modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_valor_descripcion',v_parametros.id_valor_descripcion::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_vald_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		23-04-2016 14:24:45
	***********************************/

	elsif(p_transaccion='VF_vald_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tvalor_descripcion
            where id_valor_descripcion=v_parametros.id_valor_descripcion;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Valores eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_valor_descripcion',v_parametros.id_valor_descripcion::varchar);
              
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
ALTER FUNCTION "vef"."ft_valor_descripcion_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
