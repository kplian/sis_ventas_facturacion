CREATE OR REPLACE FUNCTION vef.ft_temporal_data_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_temporal_data_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.ttemporal_data'
 AUTOR: 		 (eddy.gutierrez)
 FECHA:	        06-11-2018 20:39:08
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				06-11-2018 20:39:08								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.ttemporal_data'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_temporal_data	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_temporal_data_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_dad_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		eddy.gutierrez	
 	#FECHA:		06-11-2018 20:39:08
	***********************************/

	if(p_transaccion='VF_dad_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.ttemporal_data(
			razon_social,
			estado_reg,
			nro_factura,
			id_usuario_ai,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.razon_social,
			'activo',
			v_parametros.nro_factura,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			null,
			null
							
			
			
			)RETURNING id_temporal_data into v_id_temporal_data;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','dad almacenado(a) con exito (id_dato_temporal'||v_id_temporal_data||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_temporal_data',v_id_temporal_data::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_dad_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		eddy.gutierrez	
 	#FECHA:		06-11-2018 20:39:08
	***********************************/

	elsif(p_transaccion='VF_dad_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.ttemporal_data set
			razon_social = v_parametros.razon_social,
			nro_factura = v_parametros.nro_factura,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_temporal_data=v_parametros.id_temporal_data;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','dad modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_temporal_data',v_parametros.id_temporal_data::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_dad_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		eddy.gutierrez	
 	#FECHA:		06-11-2018 20:39:08
	***********************************/

	elsif(p_transaccion='VF_dad_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.ttemporal_data
            where id_temporal_data=v_parametros.id_temporal_data;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(id_temporal_data,'dad eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_temporal_data',v_parametros.id_temporal_data::varchar);
              
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