CREATE OR REPLACE FUNCTION vef.ft_formula_detalle_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_formula_detalle_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tformula_detalle'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 13:16:56
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
	v_id_formula_detalle	integer;
    v_id_concepto_ingas		integer;
    v_id_item				INTEGER;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_formula_detalle_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_FORDET_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 13:16:56
	***********************************/

	if(p_transaccion='VF_FORDET_INS')then
					
        begin
        	if (v_parametros.tipo = 'item') then
            	v_id_item = v_parametros.id_producto;
                v_id_concepto_ingas = NULL;
            else
            	v_id_item = NULL;
                v_id_concepto_ingas = v_parametros.id_producto;
            end if;
        	--Sentencia de la insercion
        	insert into vef.tformula_detalle(
			id_item,
            id_concepto_ingas,
			id_formula,
			cantidad,
			estado_reg,
			fecha_reg,			
			id_usuario_reg,			
			fecha_mod,
			id_usuario_mod
          	) values(
			v_id_item,
            v_id_concepto_ingas,
			v_parametros.id_formula,
			v_parametros.cantidad_det,
			'activo',
			now(),			
			p_id_usuario,			
			null,
			null
							
			
			
			)RETURNING id_formula_detalle into v_id_formula_detalle;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Formula Detalle almacenado(a) con exito (id_formula_detalle'||v_id_formula_detalle||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_formula_detalle',v_id_formula_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_FORDET_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 13:16:56
	***********************************/

	elsif(p_transaccion='VF_FORDET_MOD')then

		begin
			--Sentencia de la modificacion
			update vef.tformula_detalle set
			id_item = v_parametros.id_item,
			id_formula = v_parametros.id_formula,
			cantidad = v_parametros.cantidad_det,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_formula_detalle=v_parametros.id_formula_detalle;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Formula Detalle modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_formula_detalle',v_parametros.id_formula_detalle::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_FORDET_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 13:16:56
	***********************************/

	elsif(p_transaccion='VF_FORDET_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tformula_detalle
            where id_formula_detalle=v_parametros.id_formula_detalle;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Formula Detalle eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_formula_detalle',v_parametros.id_formula_detalle::varchar);
              
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