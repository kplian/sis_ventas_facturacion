CREATE OR REPLACE FUNCTION "vef"."ft_cuf_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_cuf_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tcuf'
 AUTOR: 		 (admin)
 FECHA:	        21-01-2019 15:18:42
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				21-01-2019 15:18:42								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tcuf'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_cuf	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_cuf_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_CUF_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-01-2019 15:18:42
	***********************************/

	if(p_transaccion='VF_CUF_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tcuf(
			nro_factura,
			codigo_documento_fiscal,
			nit,
			base11,
			sucursal,
			punto_venta,
			fecha_emision,
			modalidad,
			codigo_autoverificador,
			tipo_documento_sector,
			tipo_emision,
			base16,
			estado_reg,
			concatenacion,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.nro_factura,
			v_parametros.codigo_documento_fiscal,
			v_parametros.nit,
			v_parametros.base11,
			v_parametros.sucursal,
			v_parametros.punto_venta,
			v_parametros.fecha_emision,
			v_parametros.modalidad,
			v_parametros.codigo_autoverificador,
			v_parametros.tipo_documento_sector,
			v_parametros.tipo_emision,
			v_parametros.base16,
			'activo',
			v_parametros.concatenacion,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_cuf into v_id_cuf;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','CUF almacenado(a) con exito (id_cuf'||v_id_cuf||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuf',v_id_cuf::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_CUF_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-01-2019 15:18:42
	***********************************/

	elsif(p_transaccion='VF_CUF_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tcuf set
			nro_factura = v_parametros.nro_factura,
			codigo_documento_fiscal = v_parametros.codigo_documento_fiscal,
			nit = v_parametros.nit,
			base11 = v_parametros.base11,
			sucursal = v_parametros.sucursal,
			punto_venta = v_parametros.punto_venta,
			fecha_emision = v_parametros.fecha_emision,
			modalidad = v_parametros.modalidad,
			codigo_autoverificador = v_parametros.codigo_autoverificador,
			tipo_documento_sector = v_parametros.tipo_documento_sector,
			tipo_emision = v_parametros.tipo_emision,
			base16 = v_parametros.base16,
			concatenacion = v_parametros.concatenacion,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_cuf=v_parametros.id_cuf;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','CUF modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuf',v_parametros.id_cuf::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_CUF_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-01-2019 15:18:42
	***********************************/

	elsif(p_transaccion='VF_CUF_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tcuf
            where id_cuf=v_parametros.id_cuf;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','CUF eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cuf',v_parametros.id_cuf::varchar);
              
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
ALTER FUNCTION "vef"."ft_cuf_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
