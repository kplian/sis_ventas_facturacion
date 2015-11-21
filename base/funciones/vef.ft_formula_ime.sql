CREATE OR REPLACE FUNCTION vef.ft_formula_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_formula_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tformula'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2015 09:14:49
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
	v_id_formula			integer;
    v_registros				record;
    v_id_medico				integer;
    v_id_unidad_medida		integer;
    v_cantidad				integer;
			    
BEGIN

    v_nombre_funcion = 'vef.ft_formula_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_FORM_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:14:49
	***********************************/

	if(p_transaccion='VF_FORM_INS')then
					
        begin
        	
        	if (pxp.f_existe_parametro(p_tabla,'id_medico')) then
                v_id_medico = v_parametros.id_medico;
            else
                v_id_medico = NULL;
            end if;
            
            if (pxp.f_existe_parametro(p_tabla,'id_unidad_medida')) then
                v_id_unidad_medida = v_parametros.id_unidad_medida;
            else
                v_id_unidad_medida = NULL;
            end if;
            
            if (pxp.f_existe_parametro(p_tabla,'cantidad_form')) then
                v_cantidad = v_parametros.cantidad_form;
            else
                v_cantidad = NULL;
            end if;
            
            
        	--Sentencia de la insercion
        	insert into vef.tformula(
			--id_tipo_presentacion,
			id_unidad_medida,
			id_medico,
			nombre,
			cantidad,
			estado_reg,
			descripcion,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			--v_parametros.id_tipo_presentacion,
			v_id_unidad_medida,
			v_id_medico,
			v_parametros.nombre,
			v_cantidad,
			'activo',
			v_parametros.descripcion,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
			)RETURNING id_formula into v_id_formula;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Fórmula almacenado(a) con exito (id_formula'||v_id_formula||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_formula',v_id_formula::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_FORM_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:14:49
	***********************************/

	elsif(p_transaccion='VF_FORM_MOD')then

		begin
        	if (pxp.f_existe_parametro(p_tabla,'id_medico')) then
                v_id_medico = v_parametros.id_medico;
            else
                v_id_medico = NULL;
            end if;
            
            if (pxp.f_existe_parametro(p_tabla,'id_unidad_medida')) then
                v_id_unidad_medida = v_parametros.id_unidad_medida;
            else
                v_id_unidad_medida = NULL;
            end if;
            
            if (pxp.f_existe_parametro(p_tabla,'cantidad_form')) then
                v_cantidad = v_parametros.cantidad_form;
            else
                v_cantidad = NULL;
            end if;
        
        	--if ((pxp.f_existe_parametro(p_tabla,'duplicar') =true and v_parametros.duplicar = 'no')
            --		or pxp.f_existe_parametro(p_tabla,'duplicar') =FALSE) then
                  --Sentencia de la modificacion
                  update vef.tformula set
                  --id_tipo_presentacion = v_parametros.id_tipo_presentacion,
                  id_unidad_medida = v_id_unidad_medida,
                  id_medico = v_id_medico,
                  nombre = v_parametros.nombre,
                  cantidad = v_cantidad,
                  descripcion = v_parametros.descripcion,
                  fecha_mod = now(),
                  id_usuario_mod = p_id_usuario,
                  id_usuario_ai = v_parametros._id_usuario_ai,
                  usuario_ai = v_parametros._nombre_usuario_ai
                  where id_formula=v_parametros.id_formula;
           /* else
            		--Sentencia de la insercion
                  insert into vef.tformula(
                  --id_tipo_presentacion,
                  id_unidad_medida,
                  id_medico,
                  nombre,
                  cantidad,
                  estado_reg,
                  descripcion,
                  usuario_ai,
                  fecha_reg,
                  id_usuario_reg,
                  id_usuario_ai,
                  fecha_mod,
                  id_usuario_mod
                  ) values(
                  --v_parametros.id_tipo_presentacion,
                  v_parametros.id_unidad_medida,
                  v_parametros.id_medico,
                  v_parametros.nombre,
                  v_parametros.cantidad_form,
                  'activo',
                  v_parametros.descripcion,
                  v_parametros._nombre_usuario_ai,
                  now(),
                  p_id_usuario,
                  v_parametros._id_usuario_ai,
                  null,
                  null
                  )RETURNING id_formula into v_id_formula;
                  
                  for v_registros in (select * from vef.tformula_detalle 
                  					  where id_formula = v_parametros.id_formula) loop
                                      
            	  		--Sentencia de la insercion
                        insert into vef.tformula_detalle(
                        id_item,
                        id_formula,
                        cantidad,
                        estado_reg,
                        fecha_reg,			
                        id_usuario_reg,			
                        fecha_mod,
                        id_usuario_mod
                        ) values(
                        v_registros.id_item,
                        v_id_formula,
                        v_registros.cantidad,
                        'activo',
                        now(),			
                        p_id_usuario,			
                        null,
                        null
                        );
                  end loop;
            
            end if;*/
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Fórmula modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_formula',v_parametros.id_formula::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;
    /*********************************    
 	#TRANSACCION:  'VF_FORALLDET_ELI'
 	#DESCRIPCION:	Eliminacion de los detalles relacionados a una formula
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_FORALLDET_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tformula_detalle
            where id_formula=v_parametros.id_formula;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas detalle eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_formula',v_parametros.id_formula::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_FORM_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2015 09:14:49
	***********************************/

	elsif(p_transaccion='VF_FORM_ELI')then

		begin
			--Sentencia de la eliminacion
            
            delete from vef.tformula_detalle
            where id_formula=v_parametros.id_formula;
            
			delete from vef.tformula
            where id_formula=v_parametros.id_formula;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Fórmula eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_formula',v_parametros.id_formula::varchar);
              
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