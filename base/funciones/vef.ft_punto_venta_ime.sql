CREATE OR REPLACE FUNCTION vef.ft_punto_venta_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_punto_venta_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tpunto_venta'
 AUTOR: 		 (jrivera)
 FECHA:	        07-10-2015 21:02:00
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
	v_id_punto_venta	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_punto_venta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_PUVE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-10-2015 21:02:00
	***********************************/

	if(p_transaccion='VF_PUVE_INS')then
					
        begin
        	if (pxp.f_get_variable_global('vef_tiene_punto_venta') != 'true') then
        		raise exception 'No se habilito el manejo de puntos de venta en esta instancia, solicite al administrador del sistema dicha habilitacion';
        	end if;
        	
        	--Sentencia de la insercion
        	insert into vef.tpunto_venta(
			estado_reg,
			id_sucursal,
			nombre,
			descripcion,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
            codigo,
            habilitar_comisiones,
            tipo
          	) values(
			'activo',
			v_parametros.id_sucursal,
			v_parametros.nombre,
			v_parametros.descripcion,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.codigo,
            v_parametros.habilitar_comisiones,
            v_parametros.tipo
							
			
			
			)RETURNING id_punto_venta into v_id_punto_venta;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Punto de Venta almacenado(a) con exito (id_punto_venta'||v_id_punto_venta||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_punto_venta',v_id_punto_venta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_PUVE_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-10-2015 21:02:00
	***********************************/

	elsif(p_transaccion='VF_PUVE_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tpunto_venta set
			id_sucursal = v_parametros.id_sucursal,
			nombre = v_parametros.nombre,
			descripcion = v_parametros.descripcion,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            codigo = v_parametros.codigo,
            habilitar_comisiones = v_parametros.habilitar_comisiones,
            tipo = v_parametros.tipo
			where id_punto_venta=v_parametros.id_punto_venta;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Punto de Venta modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_punto_venta',v_parametros.id_punto_venta::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_PUVE_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		jrivera	
 	#FECHA:		07-10-2015 21:02:00
	***********************************/

	elsif(p_transaccion='VF_PUVE_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tpunto_venta
            where id_punto_venta=v_parametros.id_punto_venta;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Punto de Venta eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_punto_venta',v_parametros.id_punto_venta::varchar);
              
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