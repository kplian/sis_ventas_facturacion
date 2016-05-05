CREATE OR REPLACE FUNCTION vef.f_anula_venta (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_id_proceso_wf integer,
  p_id_estado_wf integer,
  p_id_venta integer
)
RETURNS varchar AS
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
    v_id_tipo_estado		 integer;
    v_venta 			 record;
    v_id_funcionario_inicio	 integer;
    v_id_estado_actual		 integer;
    
    v_parametros           	record;
    v_id_tipo_compra_venta	integer;
    v_tabla			varchar;
    v_id_doc_compra_venta	integer;
       
	
    
BEGIN

	 v_nombre_funcion = 'vef.f_anula_venta';
	 v_parametros = pxp.f_get_record(p_tabla);
	 v_resp	= 'exito';
	 select 
      te.id_tipo_estado
     into
      v_id_tipo_estado
     from wf.tproceso_wf pw 
     inner join wf.ttipo_proceso tp on pw.id_tipo_proceso = tp.id_tipo_proceso
     inner join wf.ttipo_estado te on te.id_tipo_proceso = tp.id_tipo_proceso and te.codigo = 'anulado'               
     where pw.id_proceso_wf = p_id_proceso_wf;

     select * into v_venta
     from vef.tventa
     where id_venta = p_id_venta;
                   
                  
     IF v_id_tipo_estado is NULL  THEN             
        raise exception 'No se parametrizo el estado "anulado" para la venta';
     END IF;
                 
       select f.id_funcionario into  v_id_funcionario_inicio
      from segu.tusuario u
      inner join orga.tfuncionario f on f.id_persona = u.id_persona
      where u.id_usuario = p_id_usuario;
                              
       -- pasamos la solicitud  al siguiente anulado
               
       v_id_estado_actual =  wf.f_registra_estado_wf(v_id_tipo_estado, 
                                                   v_id_funcionario_inicio, 
                                                   p_id_estado_wf, 
                                                   p_id_proceso_wf,
                                                   p_id_usuario,
                                                   NULL,
                                                   NULL,
                                                   NULL,
                                                   'Anulacion de venta');
                
                 
         -- actualiza estado en la solicitud
                  
         update vef.tventa  set 
           id_estado_wf =  v_id_estado_actual,
           estado = 'anulado',
           id_usuario_mod=p_id_usuario,
           fecha_mod=now()
         where id_venta  = p_id_venta;
                   
		
         if (pxp.f_get_variable_global('vef_integracion_lcv') = 'si') then
         	
         	select dcv.id_doc_compra_venta into v_id_doc_compra_venta
             from conta.tdoc_compra_venta dcv
             where dcv.tabla_origen = 'vef.tventa' and dcv.id_origen = v_venta.id_venta and
                dcv.estado_reg = 'activo';
            
            if (v_id_doc_compra_venta is not null) then    
                select id_tipo_doc_compra_venta into v_id_tipo_compra_venta
                from conta.ttipo_doc_compra_venta tcv
                where tcv.codigo = 'A' and tcv.estado_reg = 'activo';
				
                if (v_id_tipo_compra_venta is null) then
                    raise exception 'No se encontro el tipo compra venta para anulacion';
                else
                    v_tabla = pxp.f_crear_parametro(ARRAY[	'_nombre_usuario_ai',
                                        '_id_usuario_ai',
                                        'id_doc_compra_venta',
                                        'id_tipo_doc_compra_venta'],
                                    ARRAY[	coalesce(v_parametros._nombre_usuario_ai,''),
                                        coalesce(v_parametros._id_usuario_ai::varchar,''),
                                        v_id_doc_compra_venta::varchar,
                                        v_id_tipo_compra_venta::varchar],
                                    ARRAY[	'varchar',
                                        'integer',
                                        'integer',
                                        'integer']
                                    );
                    v_resp = conta.ft_doc_compra_venta_ime(p_administrador,p_id_usuario,v_tabla,'CONTA_DCVBASIC_MOD');
    			end if;		
			end if;
			--llamar funcion conta.ft_doc_compra_venta_ime transaccion transaccion CONTA_DCVBASIC_MOD id_doc_compra_venta y id_tipo_doc_compra_venta
			--buscando el tipo_compra_venta con codigo A para insertar el codigo debe ser V			
                   end if;

	RETURN   v_resp;

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