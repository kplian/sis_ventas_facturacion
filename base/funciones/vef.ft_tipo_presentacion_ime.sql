CREATE OR REPLACE FUNCTION "vef"."ft_tipo_presentacion_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_tipo_presentacion_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.ttipo_presentacion'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 09:00:49
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
	v_id_tipo_presentacion	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_tipo_presentacion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_TIPRE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:00:49
	***********************************/

	if(p_transaccion='VF_TIPRE_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.ttipo_presentacion(
			estado_reg,
			nombre,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.nombre,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_tipo_presentacion into v_id_tipo_presentacion;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo de Presentacion almacenado(a) con exito (id_tipo_presentacion'||v_id_tipo_presentacion||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_presentacion',v_id_tipo_presentacion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_TIPRE_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:00:49
	***********************************/

	elsif(p_transaccion='VF_TIPRE_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.ttipo_presentacion set
			nombre = v_parametros.nombre,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_tipo_presentacion=v_parametros.id_tipo_presentacion;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo de Presentacion modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_presentacion',v_parametros.id_tipo_presentacion::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_TIPRE_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:00:49
	***********************************/

	elsif(p_transaccion='VF_TIPRE_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.ttipo_presentacion
            where id_tipo_presentacion=v_parametros.id_tipo_presentacion;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo de Presentacion eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_presentacion',v_parametros.id_tipo_presentacion::varchar);
              
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
ALTER FUNCTION "vef"."ft_tipo_presentacion_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
