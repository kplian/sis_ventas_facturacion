CREATE OR REPLACE FUNCTION vef.f_fun_regreso_venta_wf (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_estado_wf integer,
  p_id_proceso_wf integer,
  p_codigo_estado varchar
)
RETURNS boolean AS
$body$
/*
*
*  Autor:   JRR
*  DESC:    funcion que actualiza los estados despues del registro de un siguiente en planilla
*  Fecha:   17/10/2014
*
*/

DECLARE

	v_nombre_funcion   	 text;
    v_resp    			 varchar;
    v_mensaje 			 varchar;
    
    v_venta 			 record;
    
    v_config			record;
    v_registros			record;
    v_id_movimiento		integer;
    v_id_movimiento_tipo integer;
    v_id_almacen_dest 	integer;
    v_id_funcionario 	integer;
	v_id_gestion 		integer;
    v_id_movimiento_det	integer;
    v_record			record;
    v_valores						text[];
    v_respuesta			varchar;
    v_mostrar_alerts	varchar;
    v_alertas			text;
    v_integrar_almacenes	varchar;
   
	
    
BEGIN

	 v_nombre_funcion = 'vef.f_fun_regreso_venta_wf';
    
     select v.*
      into v_venta
      from vef.tventa v
      where id_proceso_wf = p_id_proceso_wf;
     
     select e.tipo_venta_producto into v_integrar_almacenes
      from vef.tsucursal s
      inner join param.tentidad e on e.id_entidad = s.id_entidad
      where s.id_sucursal = v_venta.id_sucursal;
              
     --significa que tiene productos terminados   
     IF ((p_codigo_estado = 'borrador' or p_codigo_estado = 'elaboracion') and 
     		v_venta.id_movimiento is not null and v_integrar_almacenes = 'si') THEN 
     	
        		
        		/*Obtener el funcionario del estado actual*/
                SELECT e.id_funcionario into v_id_funcionario
                from vef.tventa v
                inner join wf.testado_wf e on e.id_estado_wf = v.id_estado_wf
                where v.id_proceso_wf = p_id_proceso_wf;
                
                /*Obtener la gestion de la fecha actual*/
                select g.id_gestion into v_id_gestion
                from param.tgestion g
                where to_char(now()::date,'YYYY')::integer = g.gestion;
                
                /*Obtener el movimiento tipo para salida por venta*/
                select mt.id_movimiento_tipo into v_id_movimiento_tipo
                from alm.tmovimiento_tipo mt
                where mt.codigo = 'DEV' and mt.estado_reg = 'activo';
                
                /*Obtener el almacen para produtos terminados de la sucursal de la venta*/
                select m.id_almacen into v_id_almacen_dest
                from alm.tmovimiento m
                where m.id_movimiento = v_venta.id_movimiento;
                
                select
                v_id_movimiento_tipo as id_movimiento_tipo,
                v_id_almacen_dest as id_almacen,
                v_id_funcionario as id_funcionario, 
                NULL as id_proveedor,
                NULL as id_almacen_dest,
                now() as fecha_mov,
                'Ingreso por devolucion de venta ' || v_venta.nro_tramite,
                NULL as observaciones,
                NULL as id_movimiento_origen,
                v_id_gestion as id_gestion 
                into v_registros;
                
                      
                v_id_movimiento = alm.f_insercion_movimiento(p_id_usuario,hstore(v_registros));
                        
                --Copia el detalle del movimiento de salida por transferencia pero sin costos unitarios.
            for v_registros in (select 
                                movdet.id_movimiento_det,
                                movdet.id_item,
                                movdet.cantidad,
                                movdet.cantidad_solicitada,
                                movdet.costo_unitario,
                                movdet.observaciones
                                from alm.tmovimiento_det movdet
                                where movdet.id_movimiento = v_venta.id_movimiento
                                and movdet.estado_reg = 'activo') loop
                
                insert into alm.tmovimiento_det(
                    id_usuario_reg,
                    fecha_reg,
                    estado_reg,
                    id_movimiento,
                    id_item,
                    cantidad,
                    cantidad_solicitada,
                    costo_unitario,
                    observaciones
                    
                ) values (
                    p_id_usuario,
                    now(),
                    'activo',
                    v_id_movimiento,
                    v_registros.id_item,
                    v_registros.cantidad_solicitada,
                    v_registros.cantidad_solicitada,
                    v_registros.costo_unitario,
                    v_registros.observaciones
                ) returning id_movimiento_det into v_id_movimiento_det;
        	    
                                               
                insert into alm.tmovimiento_det_valorado (
                    id_usuario_reg,
                    fecha_reg,
                    estado_reg,
                    id_movimiento_det,
                    cantidad,
                    costo_unitario
                ) values (
                    p_id_usuario,
                    now(),
                    'activo',
                    v_id_movimiento_det,
                    v_registros.cantidad,
                    v_registros.costo_unitario
                );
            end loop;
                --Cambiar al estado siguiente el movimiento registrado
                --obtener el almacen del movimiento de salida
                select m.id_almacen,m.id_movimiento,'verificar'::varchar as operacion,
                NULL as id_tipo_estado,NULL as id_funcionario_wf,
                p_id_usuario_ai as id_usuario_ai,
                p_usuario_ai as _nombre_usuario_ai,m.estado_mov
                into v_record
                from alm.tmovimiento m            
                where m.id_movimiento = v_id_movimiento;
                
                --primero se llama a la funcion de verificar
                v_respuesta = alm.f_movimiento_workflow_principal(p_id_usuario,hstore(v_record));
                    
                v_valores = pxp.f_recupera_clave(v_respuesta, 'id_tipo_estado_wf');
                                
                v_record.id_tipo_estado = v_valores[1];
                v_record.operacion = 'siguiente';
                
                --ahora se llama a la funcion para pasar al siguiente estado 
                v_respuesta = alm.f_movimiento_workflow_principal(p_id_usuario,hstore(v_record));
                
                --se pasa nuevamente al siguiente estado para ingresos
                v_respuesta = alm.f_movimiento_workflow_principal(p_id_usuario,hstore(v_record));
                    
                v_valores = pxp.f_recupera_clave(v_respuesta, 'id_tipo_estado_wf');
                                
                v_record.id_tipo_estado = v_valores[1];
                v_record.operacion = 'siguiente';
                
                --ahora se llama a la funcion para pasar al siguiente estado 
                v_respuesta = alm.f_movimiento_workflow_principal(p_id_usuario,hstore(v_record));
                    
     end if;   
        
    -- actualiza estado en la solicitud     
    update vef.tventa  t set 
       id_estado_wf =  p_id_estado_wf,
       estado = p_codigo_estado,
       id_usuario_mod=p_id_usuario,
       id_usuario_ai = p_id_usuario_ai,
       usuario_ai = p_usuario_ai,
       fecha_mod=now()                   
    where id_proceso_wf = p_id_proceso_wf;   

	RETURN   TRUE;

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