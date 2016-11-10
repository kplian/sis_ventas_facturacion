CREATE OR REPLACE FUNCTION vef.f_inserta_lcv (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_opcion varchar,
  p_id_venta integer
)
RETURNS varchar AS
$body$
/*
*
*  Autor:   JRR
*  DESC:    funcion que inserta lcv en el sistema de contabilidad
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
    v_codigo_tipo_compra_venta	varchar;
    v_descuento			numeric;
    v_descuento_porc	numeric;
    v_iva				numeric;
    v_it				numeric;
    v_ice				numeric;
    v_id_depto_conta	integer;
    v_id_doc_compra_venta	INTEGER;
    v_codigo_trans		varchar;
    
BEGIN

	 v_nombre_funcion = 'vef.f_inserta_lcv';
	 v_parametros = pxp.f_get_record(p_tabla);
	 v_resp	= 'exito';
	 
	 select v.*,tv.id_plantilla,sm.id_moneda as id_moneda_sucursal,
     	c.nit,d.nroaut,c.nombre_factura,s.id_depto into v_venta
	 from vef.tventa v
     inner join vef.ttipo_venta tv on tv.codigo = v.tipo_factura
     inner join vef.tsucursal s on s.id_sucursal = v.id_sucursal
     left join vef.tdosificacion d on d.id_dosificacion = v.id_dosificacion
     inner join vef.tsucursal_moneda sm on sm.id_sucursal = s.id_sucursal and sm.tipo_moneda = 'moneda_base'
     inner join vef.tcliente c on c.id_cliente = v.id_cliente
	 where id_venta = p_id_venta;
     
     --obtener el depto conta para la sucursal
     select d.id_depto into v_id_depto_conta
     from param.tdepto_depto dd
     inner join param.tdepto d on d.id_depto = dd.id_depto_destino
     inner join segu.tsubsistema s on d.id_subsistema = s.id_subsistema
     where dd.id_depto_origen = v_venta.id_depto and dd.estado_reg = 'activo' and
     	s.codigo = 'CONTA';
     
     if ( v_id_depto_conta is null) then
     	raise exception 'No se puede generar el libro de ventas debido a que no existe un depto contable parametrizado';
     end if;
     --verificar si existe el documento
     select dcv.id_doc_compra_venta into v_id_doc_compra_venta
     from conta.tdoc_compra_venta dcv
     where dcv.tabla_origen = 'vef.tventa' and dcv.id_origen = v_venta.id_venta and
     	dcv.estado_reg = 'activo';
	-- si es finalizacion
	 if (p_opcion = 'FIN') then
		--el documento entra validado
        v_codigo_tipo_compra_venta = 'V';
        --si no existe el documento se inserta
        if (v_id_doc_compra_venta is null) then
        	
        	v_codigo_trans = 'CONTA_DCV_INS';
        --si existe se modifica
        else        
        	v_codigo_trans = 'CONTA_DCV_MOD';
        end if;
	 else
     	if (v_id_doc_compra_venta is null) then
        	
        	v_codigo_trans = 'CONTA_DCV_INS';
        --si existe se modifica
        else        
        	v_codigo_trans = 'CONTA_DCV_MOD';
        end if;
		v_codigo_tipo_compra_venta = 'A';
	 end if;     
	 
	select tcv.id_tipo_doc_compra_venta into v_id_tipo_compra_venta
	from conta.ttipo_doc_compra_venta tcv
	where tcv.codigo = v_codigo_tipo_compra_venta and tcv.estado_reg = 'activo';
	--solo si tiene plantilla se inserta en el libro de ventas
    if (v_venta.id_plantilla is not null) then
    
        if (v_id_tipo_compra_venta is null) then
            raise exception 'No se encontro el tipo compra venta para insertar al LCV';
        else
        	--obtener descuento porcentaje
        	select  
               ps_descuento_porc,
               ps_descuento
             into
              v_descuento_porc,
              v_descuento
             FROM  conta.f_get_descuento_plantilla_calculo(v_venta.id_plantilla);
            
            --obtener iva
             select  
               ps_monto_porc
             into
              v_iva
             FROM  conta.f_get_detalle_plantilla_calculo(v_venta.id_plantilla, 'IVA-DF');
             
           
            --recupera IT           
            select  
               ps_monto_porc
             into
              v_it
             FROM  conta.f_get_detalle_plantilla_calculo(v_venta.id_plantilla, 'IT');
            
            --recupera ICE            
            select  
               ps_monto_porc
             into
              v_ice
             FROM  conta.f_get_detalle_plantilla_calculo(v_venta.id_plantilla, 'ICE');
          
            --crear tabla 
            v_tabla = pxp.f_crear_parametro(ARRAY[	'_nombre_usuario_ai',
                                '_id_usuario_ai',
                                'revisado',
                                'movil',
                                'tipo',
                                'importe_excento',
                                'id_plantilla',
                                'fecha',
                                'nro_documento',
                                'nit',
                                'importe_ice',
                                'nro_autorizacion',
                                'importe_iva',
                                'importe_descuento',
                                'importe_doc',
                                'sw_contabilizar',
                                'tabla_origen',
                                'estado',
                                'id_depto_conta',
                                'id_origen',
                                'obs',
                                'estado_reg',
                                'codigo_control',
                                'importe_it',
                                'razon_social',
                                'importe_descuento_ley',
                                'importe_pago_liquido',
                                'nro_dui',
                                'id_moneda',							
                                'importe_pendiente',
                                'importe_anticipo',
                                'importe_retgar',
                                'importe_neto',
                                'id_auxiliar',
                                'id_doc_compra_venta',
                                'id_tipo_compra_venta'],
            				ARRAY[	coalesce(v_parametros._nombre_usuario_ai,''),
                                coalesce(v_parametros._id_usuario_ai::varchar,''),
                                'si',--'revisado',
                                'no',--'movil',
                                'venta',--'tipo',
                                coalesce(v_venta.excento::varchar,'0'),--'importe_excento',
                                v_venta.id_plantilla::varchar,--'id_plantilla',
                                to_char(v_venta.fecha,'DD/MM/YYYY'),--'fecha',
                               v_venta.nro_factura::varchar,--'nro_documento',
                                coalesce(v_venta.nit,''),--'nit',
                                v_venta.total_venta_msuc::varchar,--'importe_ice',
                                coalesce(v_venta.nroaut,''), --'nro_autorizacion',
                                (v_venta.total_venta_msuc * v_iva)::varchar,--'importe_iva',
                                '0',--'importe_descuento',
                                (v_venta.total_venta_msuc )::varchar,--'importe_doc',
                                'no',--'sw_contabilizar',
                                'vef.tventa',--'tabla_origen',
                                'validado',--'estado',
                                v_id_depto_conta::varchar,--'id_depto_conta',
                                v_venta.id_venta::varchar,--'id_origen',
                                coalesce(v_venta.observaciones,''),--'obs',
                                'activo',--'estado_reg',
                                coalesce(v_venta.cod_control,''),--'codigo_control',
                                (v_venta.total_venta_msuc * v_it)::varchar,--'importe_it',
                                coalesce(v_venta.nombre_factura,''),--'razon_social',
                                (v_venta.total_venta_msuc * v_descuento_porc)::varchar,--'importe_descuento_ley',
                                coalesce((v_venta.total_venta_msuc - (v_venta.total_venta_msuc * v_descuento_porc))::varchar,''),--'importe_pago_liquido',
                                '0',--'nro_dui',
                                v_venta.id_moneda_sucursal::varchar,--'id_moneda',							
                                '0',--'importe_pendiente',
                                '0',--'importe_anticipo',
                                '0',--'importe_retgar',
                                (v_venta.total_venta_msuc - (v_venta.total_venta_msuc * v_descuento_porc))::varchar,--'importe_neto',--
                                '',--'id_auxiliar',
                                coalesce(v_id_doc_compra_venta::varchar,''),--id_doc_compra_venta
                                v_id_tipo_compra_venta::varchar
                                ],
                            ARRAY['varchar',
                                'integer',	
                            	'varchar',
                                'varchar',
                                'varchar',
                                'numeric',
                                'int4',
                                'date',
                                'varchar',
                                'varchar',
                                'numeric',
                                'varchar',
                                'numeric',
                                'numeric',
                                'numeric',
                                'varchar',
                                'varchar',
                                'varchar',
                                'int4',
                                'int4',
                                'varchar',
                                'varchar',
                                'varchar',
                                'numeric',
                                'varchar',
                                'numeric',
                                'numeric',
                                'varchar',
                                'int4',							
                                'numeric',
                                'numeric',
                                'numeric',
                                'numeric',
                                'integer',
                                'integer',
                                'integer']
                            );
            --inserta o modifica eldoc_compra_venta
            
            v_resp = conta.ft_doc_compra_venta_ime(p_administrador,p_id_usuario,v_tabla,v_codigo_trans);
    		
        end if;
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