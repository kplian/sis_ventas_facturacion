CREATE OR REPLACE FUNCTION "vef"."ft_actividad_economica_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_actividad_economica_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tactividad_economica'
 AUTOR: 		 (jrivera)
 FECHA:	        06-10-2015 21:23:23
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
	v_id_actividad_economica	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_actividad_economica_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_ACTECO_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		06-10-2015 21:23:23
	***********************************/

	if(p_transaccion='VF_ACTECO_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tactividad_economica(
			codigo,
			estado_reg,
			descripcion,
			nombre,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.codigo,
			'activo',
			v_parametros.descripcion,
			v_parametros.nombre,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_actividad_economica into v_id_actividad_economica;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Actividad Economica almacenado(a) con exito (id_actividad_economica'||v_id_actividad_economica||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_actividad_economica',v_id_actividad_economica::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_ACTECO_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		06-10-2015 21:23:23
	***********************************/

	elsif(p_transaccion='VF_ACTECO_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tactividad_economica set
			codigo = v_parametros.codigo,
			descripcion = v_parametros.descripcion,
			nombre = v_parametros.nombre,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_actividad_economica=v_parametros.id_actividad_economica;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Actividad Economica modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_actividad_economica',v_parametros.id_actividad_economica::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_ACTECO_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		06-10-2015 21:23:23
	***********************************/

	elsif(p_transaccion='VF_ACTECO_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tactividad_economica
            where id_actividad_economica=v_parametros.id_actividad_economica;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Actividad Economica eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_actividad_economica',v_parametros.id_actividad_economica::varchar);
              
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
ALTER FUNCTION "vef"."ft_actividad_economica_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
