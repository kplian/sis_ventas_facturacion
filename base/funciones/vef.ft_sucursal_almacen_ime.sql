CREATE OR REPLACE FUNCTION "vef"."ft_sucursal_almacen_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_almacen_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tsucursal_almacen'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 07:33:41
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
	v_id_sucursal_almacen	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_sucursal_almacen_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SUCALM_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:41
	***********************************/

	if(p_transaccion='VF_SUCALM_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tsucursal_almacen(
			id_sucursal,
			id_almacen,
			tipo_almacen,
			estado_reg,
			id_usuario_ai,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_sucursal,
			v_parametros.id_almacen,
			v_parametros.tipo_almacen,
			'activo',
			v_parametros._id_usuario_ai,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_sucursal_almacen into v_id_sucursal_almacen;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Almacenes almacenado(a) con exito (id_sucursal_almacen'||v_id_sucursal_almacen||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_almacen',v_id_sucursal_almacen::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUCALM_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:41
	***********************************/

	elsif(p_transaccion='VF_SUCALM_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tsucursal_almacen set
			id_sucursal = v_parametros.id_sucursal,
			id_almacen = v_parametros.id_almacen,
			tipo_almacen = v_parametros.tipo_almacen,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_sucursal_almacen=v_parametros.id_sucursal_almacen;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Almacenes modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_almacen',v_parametros.id_sucursal_almacen::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUCALM_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 07:33:41
	***********************************/

	elsif(p_transaccion='VF_SUCALM_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tsucursal_almacen
            where id_sucursal_almacen=v_parametros.id_sucursal_almacen;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Almacenes eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_almacen',v_parametros.id_sucursal_almacen::varchar);
              
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
ALTER FUNCTION "vef"."ft_sucursal_almacen_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
