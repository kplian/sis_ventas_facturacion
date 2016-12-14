CREATE OR REPLACE FUNCTION vef.ft_cliente_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_cliente_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tcliente'
 AUTOR: 		 (admin)
 FECHA:	        20-04-2015 08:41:29
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
	v_id_cliente	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_cliente_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_CLI_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 08:41:29
	***********************************/

	if(p_transaccion='VF_CLI_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tcliente(
                correo,
                telefono_fijo,
                estado_reg,
                segundo_apellido,
                nombre_factura,
                primer_apellido,
                telefono_celular,
                nit,
                otros_correos,
                otros_telefonos,
                nombres,
                id_usuario_reg,
                fecha_reg,
                usuario_ai,
                id_usuario_ai,
                id_usuario_mod,
                fecha_mod,
                direccion,

                lugar,

                observaciones

          	) values(
                v_parametros.correo,
                v_parametros.telefono_fijo,
                'activo',
                v_parametros.segundo_apellido,
                v_parametros.nombre_factura,
                v_parametros.primer_apellido,
                v_parametros.telefono_celular,
                v_parametros.nit,
                v_parametros.otros_correos,
                v_parametros.otros_telefonos,
                v_parametros.nombres,
                p_id_usuario,
                now(),
                v_parametros._nombre_usuario_ai,
                v_parametros._id_usuario_ai,
                null,
                null,
                v_parametros.direccion,

                v_parametros.lugar,

                v_parametros.observaciones
							
			)RETURNING id_cliente into v_id_cliente;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cliente almacenado(a) con exito (id_cliente'||v_id_cliente||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cliente',v_id_cliente::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_CLI_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 08:41:29
	***********************************/

	elsif(p_transaccion='VF_CLI_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tcliente set
              correo = v_parametros.correo,
              telefono_fijo = v_parametros.telefono_fijo,
              segundo_apellido = v_parametros.segundo_apellido,
              nombre_factura = v_parametros.nombre_factura,
              primer_apellido = v_parametros.primer_apellido,
              telefono_celular = v_parametros.telefono_celular,
              nit = v_parametros.nit,
              otros_correos = v_parametros.otros_correos,
              otros_telefonos = v_parametros.otros_telefonos,
              nombres = v_parametros.nombres,
              id_usuario_mod = p_id_usuario,
              fecha_mod = now(),
              id_usuario_ai = v_parametros._id_usuario_ai,
              usuario_ai = v_parametros._nombre_usuario_ai,
              direccion = v_parametros.direccion,

              lugar = v_parametros.lugar,

              observaciones = v_parametros.observaciones

			where id_cliente=v_parametros.id_cliente;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cliente modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cliente',v_parametros.id_cliente::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_CLI_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 08:41:29
	***********************************/

	elsif(p_transaccion='VF_CLI_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tcliente
            where id_cliente=v_parametros.id_cliente;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cliente eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_cliente',v_parametros.id_cliente::varchar);
              
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