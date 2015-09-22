CREATE OR REPLACE FUNCTION "vef"."ft_sucursal_moneda_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_moneda_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tsucursal_moneda'
 AUTOR: 		 (admin)
 FECHA:	        22-09-2015 06:11:27
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
	v_id_sucursal_moneda	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_sucursal_moneda_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SUCMON_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		22-09-2015 06:11:27
	***********************************/

	if(p_transaccion='VF_SUCMON_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tsucursal_moneda(
			id_moneda,
			id_sucursal,
			estado_reg,
			tipo_moneda,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.id_moneda,
			v_parametros.id_sucursal,
			'activo',
			v_parametros.tipo_moneda,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_sucursal_moneda into v_id_sucursal_moneda;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Monedas por sucursal almacenado(a) con exito (id_sucursal_moneda'||v_id_sucursal_moneda||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_moneda',v_id_sucursal_moneda::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUCMON_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		22-09-2015 06:11:27
	***********************************/

	elsif(p_transaccion='VF_SUCMON_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tsucursal_moneda set
			id_moneda = v_parametros.id_moneda,
			id_sucursal = v_parametros.id_sucursal,
			tipo_moneda = v_parametros.tipo_moneda,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_sucursal_moneda=v_parametros.id_sucursal_moneda;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Monedas por sucursal modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_moneda',v_parametros.id_sucursal_moneda::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUCMON_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		22-09-2015 06:11:27
	***********************************/

	elsif(p_transaccion='VF_SUCMON_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tsucursal_moneda
            where id_sucursal_moneda=v_parametros.id_sucursal_moneda;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Monedas por sucursal eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_moneda',v_parametros.id_sucursal_moneda::varchar);
              
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
ALTER FUNCTION "vef"."ft_sucursal_moneda_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
