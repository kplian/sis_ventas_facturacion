--------------- SQL ---------------

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

 
  ***************************************************************************************************   
    

    HISTORIAL DE MODIFICACIONES:
   	
 ISSUE            FECHA:		      AUTOR                 DESCRIPCION
 #0              17/10/2014            JRR  KPLIAN        Creacion
 #123            24/09/2018            RAC  KPLIAN        SE adciona el envio de concepto de gasto y auxiliar contable , migracin de notas de credito sobre ventas 
 
*
*/

DECLARE

	v_nombre_funcion   	     text;
    v_resp    			     varchar;
    v_mensaje 			     varchar;
    v_id_tipo_estado		 integer;
    v_venta 			     record;
    v_id_funcionario_inicio	 integer;
    v_id_estado_actual		 integer;
    v_parametros             record;
    v_id_tipo_compra_venta	    integer;
    v_tabla			            varchar;
    v_codigo_tipo_compra_venta	varchar;
    v_descuento			        numeric;
    v_descuento_porc	        numeric;
    v_iva				        numeric;
    v_it				        numeric;
    v_ice				        numeric;
    v_id_depto_conta	        integer;
    v_id_doc_compra_venta       INTEGER;
    v_codigo_trans	            varchar;
    v_id_auxiliar               integer;
    v_rec_venta_forma_pago      record;
    v_importe_pendiente			numeric;
    v_importe_anticipo			numeric;
    v_importe_retgar			numeric;
    v_det_factura               record;   --  #123 
    v_tabla_det			        varchar;
    va_id_doc_compra_venta       varchar[];
    v_id_doc_compra_venta_fo     integer; --  #123 
    v_consulta_mig_det	         varchar; -- #123
    v_tipo_doc_mig               varchar; -- #123
    v_liquido                    numeric; -- #123
    v_codigo_trans_det           varchar; -- #123
    va_id_doc_concepto	         varchar[]; -- #123

    
BEGIN

	 v_nombre_funcion = 'vef.f_inserta_lcv';
	 v_parametros = pxp.f_get_record(p_tabla);
	 v_resp	= 'exito';
	 
     --#123 se recupera el nit y el nomber de la factura directamente de la venta y ya  no del cliente o proveedor, ya que cone stos datos fueron emitida la factura
     
	 select tv.id_plantilla,sm.id_moneda as id_moneda_sucursal,
            v.id_venta, v.excento, v.fecha, v.nro_factura, 
            v.total_venta_msuc, v.observaciones, v.cod_control,
     	    d.nroaut, s.id_depto, v.nit, v.codigo_aplicacion, v.id_contrato, v.id_centro_costo, 
            v.nombre_factura, v.id_proveedor , v.nit_internacional, v.id_venta_fk , v.ncd into v_venta --#123 adiciona nit y nombre de factura
	 from vef.tventa v
     inner join vef.ttipo_venta tv on tv.codigo = v.tipo_factura
     inner join vef.tsucursal s on s.id_sucursal = v.id_sucursal
     left join vef.tdosificacion d on d.id_dosificacion = v.id_dosificacion
     inner join vef.tsucursal_moneda sm on sm.id_sucursal = s.id_sucursal and sm.tipo_moneda = 'moneda_base'      
	 where id_venta = p_id_venta;
     
    
     
     
     IF  v_venta.id_venta_fk is  null and v_venta.ncd = 'si'  THEN
        raise exception 'La notas de credito sobre ventas deben indicar a que factura estan relacionadas';
     END IF;
     
     --#123 si es una nota de cretio recuperamos  el doc_compra_venta origina
     IF  v_venta.id_venta_fk is not null  THEN
        select 
          vo.id_doc_compra_venta 
          into  
          v_id_doc_compra_venta_fo
        from vef.tventa vo 
        where vo.id_venta = v_venta.id_venta_fk; 
     
     END IF;
     
     --obtener el depto conta para la sucursal
     select d.id_depto into v_id_depto_conta
     from param.tdepto_depto dd
     inner join param.tdepto d on d.id_depto = dd.id_depto_destino
     inner join segu.tsubsistema s on d.id_subsistema = s.id_subsistema
     where dd.id_depto_origen = v_venta.id_depto and dd.estado_reg = 'activo' and
     	s.codigo = 'CONTA';
     
     if ( v_id_depto_conta is null) then
     	raise exception 'No se puede generar el libro de ventas debido a que no existe un depto contable parametrizado (%)',v_venta.id_depto;
     end if;
     --verificar si existe el documento
     select dcv.id_doc_compra_venta 
     into v_id_doc_compra_venta
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
             
             IF  v_venta.ncd = 'no' THEN
                 --obtener iva
                 select  
                   ps_monto_porc
                 into
                  v_iva
                 FROM  conta.f_get_detalle_plantilla_calculo(v_venta.id_plantilla, 'IVA-DF');
             
             ELSE
                 --obtener iva
                 select  
                   ps_monto_porc
                 into
                  v_iva
                 FROM  conta.f_get_detalle_plantilla_calculo(v_venta.id_plantilla, 'IVA-CF');
             
             END IF;
             
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
             
             
             --#123 determinar el auxiliar contable proveedor, mediante el nit buscamos en los proveedores registrados
             select 
               aux.id_auxiliar
              into
                v_id_auxiliar
             from param.tproveedor pro
             inner join conta.tauxiliar aux on pro.id_auxiliar = aux.id_auxiliar
             where  pro.id_proveedor  = v_venta.id_proveedor;
             
             IF v_id_auxiliar is null THEN
               raise exception 'Error al determinar el auxuiliar del cliente, revisse la configuracion';
             END IF;
             
             --#listar las formas de pago
             v_importe_pendiente = 0;
             v_importe_anticipo	 = 0;
             v_importe_retgar	 = 0;
             
             -- #123  TODO considerar posibles descuentos al contabilizar ....
             v_liquido =  coalesce((v_venta.total_venta_msuc - (v_venta.total_venta_msuc * v_descuento_porc)),0);--'importe_pago_liquido';  
             FOR  v_rec_venta_forma_pago in (
                                                  SELECT 
                                                   fp.codigo,
                                                   monto,
                                                   monto_transaccion,
                                                   cambio,
                                                   monto_mb_efectivo
                                                FROM 
                                                  vef.tventa_forma_pago vfp
                                                  inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
                                                  
                                                 where vfp.id_venta = p_id_venta  and vfp.monto > 0  )LOOP
               
                     
                     IF v_rec_venta_forma_pago.codigo = 'CXC'  THEN  --cuenta por cobrar
                         -- #123  determinar el monto pendiente (cuenta por cobrar)
                         v_importe_pendiente = v_rec_venta_forma_pago.monto;
                         v_liquido = v_liquido - v_importe_pendiente;
                     ELSEIF v_rec_venta_forma_pago.codigo = 'RETGAR'   THEN
                        -- #123  determinar retencioens de garantia
                        v_importe_retgar = v_rec_venta_forma_pago.monto;
                        v_liquido = v_liquido - v_importe_retgar;
                     ELSEIF v_rec_venta_forma_pago.codigo = 'ANTICIPO'   THEN
                        -- #123  determinar anticipo
                        v_importe_anticipo = v_rec_venta_forma_pago.monto;
                        v_liquido = v_liquido - v_importe_anticipo;
                     ELSE
                       raise exception 'forma de pago no identificada %', v_rec_venta_forma_pago.codigo ;
                     END IF; 
               
               
               END LOOP;
               
               
               --raise exception 'importes  % ,  %,  %,   %', v_liquido, v_importe_pendiente, v_importe_retgar,  v_importe_anticipo;
               
                          
             
             
             --#123  defini el tipo de documento apra hacer la migracion ...si es un nota de credito sobre ventas -> usar tipo compra
              IF  v_venta.ncd = 'no' THEN
                 v_tipo_doc_mig = 'venta';
              ELSE
                 v_tipo_doc_mig = 'compra';
              END IF;
             
             
             
            --raise exception 'llega';
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
                                'id_tipo_compra_venta',
                                'codigo_aplicacion',
                                'id_contrato',
                                'id_doc_compra_venta_fk'    -- #123 para notas de credito se manda el ide de la factura original
                                
                                ],
            				ARRAY[	coalesce(v_parametros._nombre_usuario_ai,''),
                                coalesce(v_parametros._id_usuario_ai::varchar,''),
                                'si',--'revisado',
                                'no',--'movil',
                                v_tipo_doc_mig,--'tipo',
                                coalesce(v_venta.excento::varchar,'0'),--'importe_excento',
                                v_venta.id_plantilla::varchar,--'id_plantilla',
                                to_char(v_venta.fecha,'DD/MM/YYYY'),--'fecha',
                               COALESCE(v_venta.nro_factura,'0')::varchar,--'nro_documento',
                                coalesce(pxp.f_iif((v_venta.nit_internacional = 'no'),v_venta.nit, '0' ),''),--'nit',     --#123  empresas con nit internacional se registran con NIT 0
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
                                (v_venta.total_venta_msuc * COALESCE(v_it,0))::varchar,--'importe_it',
                                coalesce(v_venta.nombre_factura,''),--'razon_social',
                                (v_venta.total_venta_msuc * v_descuento_porc)::varchar,--'importe_descuento_ley',
                                v_liquido::varchar, --#123   remplaza por variable    .....   coalesce((v_venta.total_venta_msuc - (v_venta.total_venta_msuc * v_descuento_porc))::varchar,''),--'importe_pago_liquido',
                                '0',--'nro_dui',
                                v_venta.id_moneda_sucursal::varchar,--'id_moneda',							
                                v_importe_pendiente::varchar,--'importe_pendiente',
                                v_importe_anticipo::varchar,--'importe_anticipo',
                                v_importe_retgar::varchar,--'importe_retgar',
                                (v_venta.total_venta_msuc - (v_venta.total_venta_msuc * v_descuento_porc))::varchar,--'importe_neto',--
                                v_id_auxiliar::varchar,--'id_auxiliar',
                                coalesce(v_id_doc_compra_venta::varchar,''),--id_doc_compra_venta
                                v_id_tipo_compra_venta::varchar,
                                v_venta.codigo_aplicacion::varchar, 
                                coalesce(v_venta.id_contrato::varchar,''),
                                coalesce(v_id_doc_compra_venta_fo::varchar,'')       -- #123    apra notas de credito mandamos la factura orignal
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
                                'integer',
                                'varchar',
                                'integer',
                                'integer'     --#123
                                ]
                            );
            --inserta o modifica eldoc_compra_venta
            
           --raise exception 'llega';
            
            v_resp = conta.ft_doc_compra_venta_ime(p_administrador,p_id_usuario,v_tabla,v_codigo_trans);
            
            --recueprar id_doc_compra_venta
             va_id_doc_compra_venta = pxp.f_recupera_clave(v_resp,'id_doc_compra_venta');
            
           
            -- updata venta
            update vef.tventa set
              id_doc_compra_venta = va_id_doc_compra_venta[1]::integer
            where id_venta = p_id_venta;
           
            
            ---------------------------------------------
            --  Registro de concepto de gasto para LCV
            ---------------------------------------------
            
            IF v_venta.ncd = 'no' THEN
               
                v_consulta_mig_det = 'SELECT                                   
                                            vd.id_venta_detalle,      
                                            vd.id_sucursal_producto,
                                            vd.precio,
                                            vd.cantidad,
                                            vd.precio_sin_descuento,
                                            vd.porcentaje_descuento, 
                                            vd.descripcion,       
                                            vd.estado,
                                            vd.obs,
                                            vd.serie,
                                            sp.id_concepto_ingas,
                                            vd.id_doc_concepto
                                          FROM 
                                            vef.tventa_detalle vd
                                          INNER JOIN vef.tsucursal_producto sp on sp.id_sucursal_producto = vd.id_sucursal_producto
                                          WHERE vd.id_venta =' || p_id_venta::varchar;
            
            ELSE    --consulta para recuperar el detalle desde la not ade credito debito
            
              v_consulta_mig_det = 'SELECT                                   
                                            vd.id_venta_detalle,      
                                            vd.id_sucursal_producto,
                                            vd.precio,
                                            vd.cantidad,
                                            vd.precio_sin_descuento,
                                            vd.porcentaje_descuento, 
                                            vd.descripcion,       
                                            vd.estado,
                                            vd.obs,
                                            vd.serie,
                                            sp.id_concepto_ingas,
                                            vd.id_doc_concepto
                                          FROM 
                                            vef.tventa_detalle vd
                                          INNER JOIN vef.tventa_detalle  vdo on vdo.id_venta_detalle = vd.id_venta_detalle_fk
                                          INNER JOIN vef.tsucursal_producto sp on sp.id_sucursal_producto = vdo.id_sucursal_producto
                                          WHERE vd.id_venta =' || p_id_venta::varchar;
            
            
            END IF;
            
            
            -- #123  solo migra el detalle de la factura al llegar al estado emitido
            IF p_opcion = 'FIN' THEN
           
                -- borrar todos los cconcepto previos
                  delete from conta.tdoc_concepto 
                  where id_doc_compra_venta = va_id_doc_compra_venta[1]::integer;
                --listar el detalle de la factura
                
                FOR v_det_factura in execute(v_consulta_mig_det)LOOP
                    
                  
       
                    -- insertar concepto de la factura
                    
                    v_codigo_trans_det = 'CONTA_DOCC_INS';
                    
                    
                    v_tabla_det = pxp.f_crear_parametro(ARRAY[	
                                        '_nombre_usuario_ai',
                                        '_id_usuario_ai',
                                        'estado_reg',
                                        'id_doc_compra_venta',
                                        'id_orden_trabajo',
                                        'id_centro_costo',
                                        'id_concepto_ingas',
                                        'descripcion',
                                        'cantidad_sol',
                                        'precio_unitario',
                                        'precio_total',
                                        'precio_total_final',
                                        'id_doc_concepto'],
                                    ARRAY[	
                                        coalesce(v_parametros._nombre_usuario_ai,''),
                                        coalesce(v_parametros._id_usuario_ai::varchar,''),
                                        'activo',  -- estado_reg
                                         va_id_doc_compra_venta[1],--'id_doc_compra_venta',
                                        '',--'id_orden_trabajo',
                                        v_venta.id_centro_costo::varchar,--'id_centro_costo',
                                        v_det_factura.id_concepto_ingas::varchar,--'id_concepto_ingas',
                                        v_det_factura.descripcion::varchar,--'descripcion',
                                        v_det_factura.cantidad::varchar,--'cantidad_sol',
                                        v_det_factura.precio::varchar,--'precio_unitario',
                                        (v_det_factura.cantidad *  v_det_factura.precio)::varchar,--'precio_total'
                                        (v_det_factura.cantidad *  v_det_factura.precio)::varchar,--precio_total_final
                                         coalesce(v_det_factura.id_doc_concepto::varchar,'')
                                       
                                        ],
                                    ARRAY[
                                        'varchar',
                                        'int4',  
                                        'varchar',
                                        'int4',                              			
                                        'int4',
                                        'int4',
                                        'int4',                               
                                        'text',                               
                                        'numeric',
                                        'numeric',
                                        'numeric',
                                        'numeric',
                                        'int4']
                                    );
                    
                        
                    v_resp = conta.ft_doc_concepto_ime(p_administrador,p_id_usuario,v_tabla_det,v_codigo_trans_det);
                    
                    --recueprar id_doc_compra_venta
                    va_id_doc_concepto = pxp.f_recupera_clave(v_resp,'id_doc_concepto');
                    
                    -- updata venta
                   update vef.tventa_detalle   set
                      id_doc_concepto = va_id_doc_concepto[1]::integer
                   where id_venta_detalle = v_det_factura.id_venta_detalle;        
                                          
                    
                END LOOP; 
         END IF;  --FIN de la opracion
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