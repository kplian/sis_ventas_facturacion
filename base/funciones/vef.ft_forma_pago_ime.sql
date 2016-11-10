CREATE OR REPLACE FUNCTION vef.ft_forma_pago_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_forma_pago_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tforma_pago'
 AUTOR: 		 (jrivera)
 FECHA:	        08-10-2015 13:29:06
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
	v_id_forma_pago	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_forma_pago_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_FORPA_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		08-10-2015 13:29:06
	***********************************/

	if(p_transaccion='VF_FORPA_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tforma_pago(
			estado_reg,
			codigo,
			nombre,
			id_entidad,
			id_moneda,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
			defecto,
			registrar_tarjeta,
			registrar_cc,
            registrar_tipo_tarjeta
          	) values(
			'activo',
			v_parametros.codigo,
			v_parametros.nombre,
			v_parametros.id_entidad,
			v_parametros.id_moneda,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null,
			v_parametros.defecto,
			v_parametros.registrar_tarjeta,
			v_parametros.registrar_cc,
            v_parametros.registrar_tipo_tarjeta	
			
			)RETURNING id_forma_pago into v_id_forma_pago;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Forma de Pago almacenado(a) con exito (id_forma_pago'||v_id_forma_pago||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_forma_pago',v_id_forma_pago::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_FORPA_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		08-10-2015 13:29:06
	***********************************/

	elsif(p_transaccion='VF_FORPA_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tforma_pago set
			codigo = v_parametros.codigo,
			nombre = v_parametros.nombre,
			id_entidad = v_parametros.id_entidad,
			id_moneda = v_parametros.id_moneda,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
			defecto = v_parametros.defecto,
			registrar_tarjeta = v_parametros.registrar_tarjeta,
			registrar_cc = v_parametros.registrar_cc,
            registrar_tipo_tarjeta = v_parametros.registrar_tipo_tarjeta
			where id_forma_pago=v_parametros.id_forma_pago;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Forma de Pago modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_forma_pago',v_parametros.id_forma_pago::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_FORPA_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		08-10-2015 13:29:06
	***********************************/

	elsif(p_transaccion='VF_FORPA_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tforma_pago
            where id_forma_pago=v_parametros.id_forma_pago;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Forma de Pago eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_forma_pago',v_parametros.id_forma_pago::varchar);
              
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
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;