CREATE OR REPLACE FUNCTION "vef"."ft_proveedor_cuenta_banco_cobro_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_proveedor_cuenta_banco_cobro_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tproveedor_cuenta_banco_cobro'
 AUTOR: 		 (m.mamani)
 FECHA:	        22-11-2018 22:19:44
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				22-11-2018 22:19:44								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tproveedor_cuenta_banco_cobro'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_proveedor_cuenta_banco_cobro	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_proveedor_cuenta_banco_cobro_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_PCC_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		m.mamani	
 	#FECHA:		22-11-2018 22:19:44
	***********************************/

	if(p_transaccion='VF_PCC_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tproveedor_cuenta_banco_cobro(
			id_proveedor,
			estado_reg,
			tipo,
			id_cuenta_bancaria,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_proveedor,
			'activo',
			v_parametros.tipo,
			v_parametros.id_cuenta_bancaria,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_proveedor_cuenta_banco_cobro into v_id_proveedor_cuenta_banco_cobro;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Proveedor Cuenta Banco Cobro almacenado(a) con exito (id_proveedor_cuenta_banco_cobro'||v_id_proveedor_cuenta_banco_cobro||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_proveedor_cuenta_banco_cobro',v_id_proveedor_cuenta_banco_cobro::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_PCC_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		m.mamani	
 	#FECHA:		22-11-2018 22:19:44
	***********************************/

	elsif(p_transaccion='VF_PCC_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tproveedor_cuenta_banco_cobro set
			id_proveedor = v_parametros.id_proveedor,
			tipo = v_parametros.tipo,
			id_cuenta_bancaria = v_parametros.id_cuenta_bancaria,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_proveedor_cuenta_banco_cobro=v_parametros.id_proveedor_cuenta_banco_cobro;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Proveedor Cuenta Banco Cobro modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_proveedor_cuenta_banco_cobro',v_parametros.id_proveedor_cuenta_banco_cobro::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_PCC_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		m.mamani	
 	#FECHA:		22-11-2018 22:19:44
	***********************************/

	elsif(p_transaccion='VF_PCC_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tproveedor_cuenta_banco_cobro
            where id_proveedor_cuenta_banco_cobro=v_parametros.id_proveedor_cuenta_banco_cobro;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Proveedor Cuenta Banco Cobro eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_proveedor_cuenta_banco_cobro',v_parametros.id_proveedor_cuenta_banco_cobro::varchar);
              
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
ALTER FUNCTION "vef"."ft_proveedor_cuenta_banco_cobro_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
