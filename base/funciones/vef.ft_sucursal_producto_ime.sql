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
    v_id_concepto			integer;
    v_id_entidad			integer;
    v_desc_ingas			varchar;
    v_id_sucursal			integer;
			    
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
        	if (v_parametros.id_sucursal is not null) then         
                
                v_id_sucursal = v_parametros.id_sucursal;
            else
            	SELECT su.id_sucursal into v_id_sucursal
                from vef.tsucursal_usuario su
                where su.id_usuario = p_id_usuario and su.estado_reg = 'activo'
                limit 1 offset 0;
                
                if (v_id_sucursal is null) then
                	raise exception 'El usuario no tiene ninguna sucursal asignada';
                end if;
            end if;
            
        	select id_entidad into v_id_entidad
            from vef.tsucursal
            where id_sucursal = v_id_sucursal;
            
            
            
            if (v_parametros.tipo_producto != 'item_almacen') then
                if (pxp.f_is_positive_integer(v_parametros.nombre_producto)) then
                    v_id_concepto = v_parametros.nombre_producto;
                    
                    if (exists (select 1 from vef.tsucursal_producto
                    			where id_concepto_ingas = v_id_concepto and estado_reg = 'activo' and id_sucursal = v_id_sucursal))  then
                    	raise exception 'El producto o servicio ya se encuentra registrado en esta sucursal';
                    end if;
                    
                    update param.tconcepto_ingas
                    set tipo = (case when v_parametros.tipo_producto = 'servicio' then
                      	'Servicio'
                      else
                      	'Bien'
                      end),
                      id_entidad = v_id_entidad,
                      descripcion_larga = v_parametros.descripcion_producto,
                      id_actividad_economica = v_parametros.id_actividad_economica,
                      id_unidad_medida = v_parametros.id_unidad_medida,
                      nandina = v_parametros.nandina,
                      codigo = v_parametros.codigo
                    where id_concepto_ingas = v_id_concepto;
                else
                    --insertar el concepto de gasto
                    INSERT INTO 
                      param.tconcepto_ingas
                    (
                      id_usuario_reg,                  
                      fecha_reg,                  
                      estado_reg,
                      id_usuario_ai,
                      usuario_ai,                  
                      tipo,
                      desc_ingas,
                      movimiento,                  
                      id_entidad,
                      descripcion_larga,
                      id_actividad_economica,
                      id_unidad_medida,
                      nandina,
                      codigo
                    )
                    VALUES (
                      p_id_usuario,                  
                      now(),                  
                      'activo',
                      v_parametros._id_usuario_ai,
                      v_parametros._nombre_usuario_ai,          
                      (case when v_parametros.tipo_producto = 'servicio' then
                      	'Servicio'
                      else
                      	'Bien'
                      end),
                      v_parametros.nombre_producto,
                      'recurso',                  
                      v_id_entidad,
                      v_parametros.descripcion_producto,
                      v_parametros.id_actividad_economica,
                      v_parametros.id_unidad_medida,
                      v_parametros.nandina,
                      v_parametros.codigo
                    ) returning id_concepto_ingas into v_id_concepto;
                end if;
            end if;
            
        	--Sentencia de la insercion
        	insert into vef.tsucursal_producto(

			id_sucursal,
			id_item,			
			precio,			
			estado_reg,
			tipo_producto,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod,           
            id_concepto_ingas,
            requiere_descripcion,
            id_moneda,
            contabilizable,
            excento
          	) values(
			v_id_sucursal,
			v_parametros.id_item,			
			v_parametros.precio,			
			'activo',
			v_parametros.tipo_producto,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null,            
            v_id_concepto,
            v_parametros.requiere_descripcion,
            v_parametros.id_moneda,
            v_parametros.contabilizable,
            v_parametros.excento	

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
        	if (v_parametros.id_sucursal is not null) then         
                
                v_id_sucursal = v_parametros.id_sucursal;
            else
            	SELECT su.id_sucursal into v_id_sucursal
                from vef.tsucursal_usuario su
                where su.id_usuario = p_id_usuario and su.estado_reg = 'activo'
                limit 1 offset 0;
                
                if (v_id_sucursal is null) then
                	raise exception 'El usuario no tiene ninguna sucursal asignada';
                end if;
            end if;
        	
        	select id_entidad into v_id_entidad
            from vef.tsucursal
            where id_sucursal = v_id_sucursal;
            
            select cig.id_concepto_ingas, cig.desc_ingas into v_id_concepto,v_desc_ingas
            from param.tconcepto_ingas cig
            inner join vef.tsucursal_producto sp on sp.id_concepto_ingas = cig.id_concepto_ingas
            where sp.id_sucursal_producto = v_parametros.id_sucursal_producto;
            
        	if (v_parametros.tipo_producto != 'item_almacen') then
                if (pxp.f_is_positive_integer(v_parametros.nombre_producto)) then
                    v_id_concepto = v_parametros.nombre_producto;
                    
                    if (exists (select 1 from vef.tsucursal_producto
                    			where id_concepto_ingas = v_id_concepto and estado_reg = 'activo'
                                and id_sucursal_producto != v_parametros.id_sucursal_producto))  then
                    	raise exception 'El producto o servicio ya se encuentra registrado en esta sucursal';
                    end if;
                    
                    update param.tconcepto_ingas
                    set tipo = (case when v_parametros.tipo_producto = 'servicio' then
                      	'Servicio'
                      else
                      	'Bien'
                      end),
                      id_entidad = v_id_entidad,
                      descripcion_larga = v_parametros.descripcion_producto,
                      id_actividad_economica = v_parametros.id_actividad_economica,
                      id_unidad_medida = v_parametros.id_unidad_medida,
                      nandina = v_parametros.nandina
                    where id_concepto_ingas = v_id_concepto;
                else 
                    
                    if (v_desc_ingas = v_parametros.nombre_producto) then
                    	update param.tconcepto_ingas
                        set tipo = (case when v_parametros.tipo_producto = 'servicio' then
                            'Servicio'
                          else
                            'Bien'
                          end),
                          id_entidad = v_id_entidad,
                          descripcion_larga = v_parametros.descripcion_producto,
                          id_actividad_economica = v_parametros.id_actividad_economica,
                          id_unidad_medida = v_parametros.id_unidad_medida,
                          nandina = v_parametros.nandina,
                          codigo = v_parametros.codigo
                        where id_concepto_ingas = v_id_concepto;
                    else                    
                    
                        --insertar el concepto de gasto
                        INSERT INTO 
                          param.tconcepto_ingas
                        (
                          id_usuario_reg,                  
                          fecha_reg,                  
                          estado_reg,
                          id_usuario_ai,
                          usuario_ai,                  
                          tipo,
                          desc_ingas,
                          movimiento,                  
                          id_entidad,
                          descripcion_larga,
                          id_actividad_economica,
                          id_unidad_medida,
                          nandina
                        )
                        VALUES (
                          p_id_usuario,                  
                          now(),                  
                          'activo',
                          v_parametros._id_usuario_ai,
                          v_parametros._nombre_usuario_ai,          
                          (case when v_parametros.tipo_producto = 'servicio' then
                            'Servicio'
                          else
                            'Bien'
                          end),
                          v_parametros.nombre_producto,
                          'recurso',                  
                          v_id_entidad,
                          v_parametros.descripcion_producto,
                          v_parametros.id_actividad_economica,
                          v_parametros.id_unidad_medida,
                          v_parametros.nandina
                        ) returning id_concepto_ingas into v_id_concepto;
                    end if;
                end if;
            end if;
			--Sentencia de la modificacion
			update vef.tsucursal_producto set
			id_sucursal = v_id_sucursal,
			id_item = v_parametros.id_item,			
			precio = v_parametros.precio,			
			tipo_producto = v_parametros.tipo_producto,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            id_concepto_ingas = v_id_concepto,
            requiere_descripcion = v_parametros.requiere_descripcion,
            id_moneda = v_parametros.id_moneda,
            contabilizable = v_parametros.contabilizable,
            excento = v_parametros.excento
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