CREATE OR REPLACE FUNCTION "vef"."ft_tipo_descripcion_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_tipo_descripcion_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.ttipo_descripcion'
 AUTOR: 		 (admin)
 FECHA:	        23-04-2016 02:03:14
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
	v_id_tipo_descripcion	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_tipo_descripcion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_TDE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		23-04-2016 02:03:14
	***********************************/

	if(p_transaccion='VF_TDE_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.ttipo_descripcion(
			fila,
			estado_reg,
			columna,
			nombre,
			obs,
			codigo,
			id_sucursal,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.fila,
			'activo',
			v_parametros.columna,
			v_parametros.nombre,
			v_parametros.obs,
			v_parametros.codigo,
			v_parametros.id_sucursal,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_tipo_descripcion into v_id_tipo_descripcion;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo de Descripción almacenado(a) con exito (id_tipo_descripcion'||v_id_tipo_descripcion||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_descripcion',v_id_tipo_descripcion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_TDE_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		23-04-2016 02:03:14
	***********************************/

	elsif(p_transaccion='VF_TDE_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.ttipo_descripcion set
			fila = v_parametros.fila,
			columna = v_parametros.columna,
			nombre = v_parametros.nombre,
			obs = v_parametros.obs,
			codigo = v_parametros.codigo,
			id_sucursal = v_parametros.id_sucursal,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_tipo_descripcion=v_parametros.id_tipo_descripcion;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo de Descripción modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_descripcion',v_parametros.id_tipo_descripcion::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_TDE_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		23-04-2016 02:03:14
	***********************************/

	elsif(p_transaccion='VF_TDE_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.ttipo_descripcion
            where id_tipo_descripcion=v_parametros.id_tipo_descripcion;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo de Descripción eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_descripcion',v_parametros.id_tipo_descripcion::varchar);
              
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
ALTER FUNCTION "vef"."ft_tipo_descripcion_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
