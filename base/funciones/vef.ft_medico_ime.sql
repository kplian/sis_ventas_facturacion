CREATE OR REPLACE FUNCTION vef.ft_medico_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_medico_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tmedico'
 AUTOR: 		 (admin)
 FECHA:	        20-04-2015 11:17:42
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
	v_id_medico	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_medico_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_MED_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 11:17:42
	***********************************/

	if(p_transaccion='VF_MED_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tmedico(
			correo,
			telefono_fijo,
			estado_reg,
			segundo_apellido,
			porcentaje,
			telefono_celular,
			primer_apellido,
			otros_correos,
			otros_telefonos,
			nombres,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod,
			fecha_nacimiento,
            especialidad
          	) values(
			v_parametros.correo,
			v_parametros.telefono_fijo,
			'activo',
			v_parametros.segundo_apellido,
			v_parametros.porcentaje,
			v_parametros.telefono_celular,
			v_parametros.primer_apellido,
			v_parametros.otros_correos,
			v_parametros.otros_telefonos,
			v_parametros.nombres,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			v_parametros._id_usuario_ai,
			null,
			null,
			v_parametros.fecha_nacimiento,
            v_parametros.especialidad
							
			
			
			)RETURNING id_medico into v_id_medico;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Medico almacenado(a) con exito (id_medico'||v_id_medico||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_medico',v_id_medico::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_MED_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 11:17:42
	***********************************/

	elsif(p_transaccion='VF_MED_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tmedico set
			correo = v_parametros.correo,
			telefono_fijo = v_parametros.telefono_fijo,
			segundo_apellido = v_parametros.segundo_apellido,
			porcentaje = v_parametros.porcentaje,
			telefono_celular = v_parametros.telefono_celular,
			primer_apellido = v_parametros.primer_apellido,
			otros_correos = v_parametros.otros_correos,
			otros_telefonos = v_parametros.otros_telefonos,
			nombres = v_parametros.nombres,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
			fecha_nacimiento = v_parametros.fecha_nacimiento,
            especialidad = v_parametros.especialidad
			where id_medico=v_parametros.id_medico;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Medico modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_medico',v_parametros.id_medico::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_MED_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 11:17:42
	***********************************/

	elsif(p_transaccion='VF_MED_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tmedico
            where id_medico=v_parametros.id_medico;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Medico eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_medico',v_parametros.id_medico::varchar);
              
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