CREATE OR REPLACE FUNCTION vef.ft_tipo_venta_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_tipo_venta_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.ttipo_venta'
 AUTOR: 		 (jrivera)
 FECHA:	        22-03-2016 15:29:00
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
	v_id_tipo_venta	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_tipo_venta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_TIPVEN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 15:29:00
	***********************************/

	if(p_transaccion='VF_TIPVEN_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.ttipo_venta(
			codigo_relacion_contable,
			nombre,
			tipo_base,
			estado_reg,
			codigo,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
            id_plantilla
          	) values(
			v_parametros.codigo_relacion_contable,
			v_parametros.nombre,
			v_parametros.tipo_base,
			'activo',
			v_parametros.codigo,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.id_plantilla
							
			
			
			)RETURNING id_tipo_venta into v_id_tipo_venta;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo de Venta almacenado(a) con exito (id_tipo_venta'||v_id_tipo_venta||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_venta',v_id_tipo_venta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_TIPVEN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 15:29:00
	***********************************/

	elsif(p_transaccion='VF_TIPVEN_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.ttipo_venta set
			codigo_relacion_contable = v_parametros.codigo_relacion_contable,
			nombre = v_parametros.nombre,
			tipo_base = v_parametros.tipo_base,
			codigo = v_parametros.codigo,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            id_plantilla = v_parametros.id_plantilla
			where id_tipo_venta=v_parametros.id_tipo_venta;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo de Venta modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_venta',v_parametros.id_tipo_venta::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_TIPVEN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		22-03-2016 15:29:00
	***********************************/

	elsif(p_transaccion='VF_TIPVEN_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.ttipo_venta
            where id_tipo_venta=v_parametros.id_tipo_venta;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Tipo de Venta eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_venta',v_parametros.id_tipo_venta::varchar);
              
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