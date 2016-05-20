CREATE OR REPLACE FUNCTION vef.ft_sucursal_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_sucursal_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tsucursal'
 AUTOR: 		 (admin)
 FECHA:	        20-04-2015 15:07:50
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
	v_id_sucursal	integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_sucursal_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_SUC_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 15:07:50
	***********************************/

	if(p_transaccion='VF_SUC_INS')then
					
        begin
        	
        	--Sentencia de la insercion
        	insert into vef.tsucursal(
			correo,
			nombre,
			telefono,
			tiene_precios_x_sucursal,
			estado_reg,
			clasificaciones_para_formula,
			codigo,
			clasificaciones_para_venta,
			id_usuario_ai,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			id_usuario_mod,
			fecha_mod,
			id_entidad,
			plantilla_documento_factura,
			plantilla_documento_recibo,
			formato_comprobante,
			direccion,
			lugar,
            habilitar_comisiones,
            id_lugar,
            tipo_interfaz,
            id_depto,
            nombre_comprobante
          	) values(
			v_parametros.correo,
			v_parametros.nombre,
			v_parametros.telefono,
			v_parametros.tiene_precios_x_sucursal,
			'activo',
            string_to_array(v_parametros.id_clasificaciones_para_formula,',')::INTEGER[],			
			v_parametros.codigo,
			string_to_array(v_parametros.id_clasificaciones_para_venta,',')::INTEGER[],
			v_parametros._id_usuario_ai,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			null,
			null,
			v_parametros.id_entidad,
			v_parametros.plantilla_documento_factura,
			v_parametros.plantilla_documento_recibo,
			v_parametros.formato_comprobante,
			v_parametros.direccion,
			v_parametros.lugar,
            v_parametros.habilitar_comisiones,		
			v_parametros.id_lugar,
            string_to_array(v_parametros.tipo_interfaz, ','),
            v_parametros.id_depto,
            v_parametros.nombre_comprobante
			)RETURNING id_sucursal into v_id_sucursal;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Sucursal almacenado(a) con exito (id_sucursal'||v_id_sucursal||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal',v_id_sucursal::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUC_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 15:07:50
	***********************************/

	elsif(p_transaccion='VF_SUC_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tsucursal set
			correo = v_parametros.correo,
			nombre = v_parametros.nombre,
			telefono = v_parametros.telefono,
			tiene_precios_x_sucursal = v_parametros.tiene_precios_x_sucursal,
			clasificaciones_para_formula = string_to_array(v_parametros.id_clasificaciones_para_formula,',')::INTEGER[],
			codigo = v_parametros.codigo,
			clasificaciones_para_venta = string_to_array(v_parametros.id_clasificaciones_para_venta,',')::INTEGER[],
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
			plantilla_documento_factura = v_parametros.plantilla_documento_factura,
			plantilla_documento_recibo = v_parametros.plantilla_documento_recibo,
			formato_comprobante = v_parametros.formato_comprobante,
			direccion = v_parametros.direccion,
			lugar = v_parametros.lugar,
            habilitar_comisiones = v_parametros.habilitar_comisiones,
            id_lugar = v_parametros.id_lugar,
            tipo_interfaz = string_to_array(v_parametros.tipo_interfaz, ','),
            id_depto = v_parametros.id_depto,
            nombre_comprobante = v_parametros.nombre_comprobante
			where id_sucursal=v_parametros.id_sucursal;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Sucursal modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal',v_parametros.id_sucursal::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_SUC_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		20-04-2015 15:07:50
	***********************************/

	elsif(p_transaccion='VF_SUC_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tsucursal
            where id_sucursal=v_parametros.id_sucursal;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Sucursal eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_sucursal',v_parametros.id_sucursal::varchar);
              
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