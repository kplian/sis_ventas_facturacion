CREATE OR REPLACE FUNCTION vef.ft_sucursal_producto_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_producto_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tsucursal_producto'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 03:18:44
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
	v_id_sucursal_producto	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_sucursal_producto_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SPROD_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 03:18:44
	***********************************/

	if(p_transaccion='VF_SPROD_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into vef.tsucursal_producto(
			id_sucursal,
			id_item,
			descripcion_producto,
			precio,
			nombre_producto,
			estado_reg,
			tipo_producto,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_sucursal,
			v_parametros.id_item,
			v_parametros.descripcion_producto,
			v_parametros.precio,
			v_parametros.nombre_producto,
			'activo',
			v_parametros.tipo_producto,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_sucursal_producto into v_id_sucursal_producto;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Productos almacenado(a) con exito (id_sucursal_producto'||v_id_sucursal_producto||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_producto',v_id_sucursal_producto::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_SPROD_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 03:18:44
	***********************************/

	elsif(p_transaccion='VF_SPROD_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tsucursal_producto set
			id_sucursal = v_parametros.id_sucursal,
			id_item = v_parametros.id_item,
			descripcion_producto = v_parametros.descripcion_producto,
			precio = v_parametros.precio,
			nombre_producto = v_parametros.nombre_producto,
			tipo_producto = v_parametros.tipo_producto,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_sucursal_producto=v_parametros.id_sucursal_producto;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Productos modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_producto',v_parametros.id_sucursal_producto::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SPROD_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 03:18:44
	***********************************/

	elsif(p_transaccion='VF_SPROD_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tsucursal_producto
            where id_sucursal_producto=v_parametros.id_sucursal_producto;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Productos eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal_producto',v_parametros.id_sucursal_producto::varchar);
              
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