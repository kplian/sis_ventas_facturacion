--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.f_inserta_factura (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
DECLARE

	v_nombre_funcion   	 		text;
    v_resp    			 		varchar;
    v_resp_2    			 	varchar;
    v_resp_3    			 	varchar;
    v_mensaje 					varchar;
	
    v_codigo_trans				varchar;
    v_codigo_trans_2			varchar;
    v_codigo_trans_3			varchar;
    
    v_parametros           		record;
    v_id_tipo_compra_venta		integer;
    v_tabla						varchar;
    v_tabla_2					varchar;
    v_tabla_3					varchar;


    v_id_doc_compra_venta		INTEGER;
    
    v_id_estado_actual  		integer;
    
    va_id_tipo_estado 			integer[];
    va_codigo_estado 			varchar[];
    va_disparador    			varchar[];
    va_regla         			varchar[]; 
    va_prioridad     			integer[];
    	
     
    
    v_id_tipo_estado  			integer;
 
    
    item						record;
    item_detalle				record;
    item_validacion_detalle		record;
    v_tipo_cambio 				numeric;
    v_tipo_cambio_mt 	    	numeric;
    v_tipo_cambio_ma			numeric;
    v_id_venta       			varchar;
    v_id_venta_det				varchar;
    
    
    p_id_usuario  				integer;
    p_id_usuario_ai 			integer;
    p_usuario_ai 				varchar;
   
	v_registros					record;
    v_record_proveedor			record;
    v_record_centro_costo		record;
    v_record_persona			record;
    v_record_punto_venta		record;
    v_record_contrato			record;
    
    v_record_data_excel			record;
    v_record_data_excel_det		record;
    
    v_registros_factura			record;

    
    v_razon_social				varchar;
    v_nit						varchar;
    v_cantidad_det				integer;
    v_unidad					varchar;
    v_detalle					varchar;
    v_precio_uni_usd			numeric;
	v_precio_uni_bs				numeric;
    v_precio_total_usd			numeric;
    v_precio_total_bs			numeric;
    v_centro_costo				varchar;
    v_clase_costo				varchar;
    v_nro_factura				varchar;
    v_observaciones				varchar;
    v_fecha						date;
    v_id_punto_venta			integer;
    v_tipo_factura				varchar;
    v_nro_contrato				varchar;
    v_id_factura_excel	 		integer;
    v_id_factura_excel_det 		integer;
    
    
    v_bandera_validacion		BOOLEAN;
    
    
BEGIN

	 v_nombre_funcion = 'vef.f_inserta_factura';
	 v_parametros = pxp.f_get_record(p_tabla);
     
	/*********************************
     #TRANSACCION:  'VF_INSTEM_INS'
     #DESCRIPCION:	Insercion de registros
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

    if(p_transaccion='VF_INSTEM_INS')then
    	begin  
          SELECT
              usu.id_usuario,
              fun.id_funcionario,
              fun.id_persona
            INTO
            v_record_persona
          FROM orga.tfuncionario fun
          left join segu.tusuario usu on usu.id_persona = fun.id_persona
          WHERE fun.id_funcionario = v_parametros.id_funcionario_usu;
        
         IF  pxp.f_existe_parametro(p_tabla,'razon_social')  THEN  
         	v_razon_social = UPPER(v_parametros.razon_social);
         ELSE
         	raise exception  'Falta razon social en un Dato del Excel' ;
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'nro_factura')  THEN  
         	v_nro_factura = v_parametros.nro_factura;
         ELSE
         	raise exception  'Debe ingresar un Nro de Factura relacionado con la razon social %',v_parametros.razon_social ;
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'nit')  THEN  
         	v_nit = v_parametros.nit;
         ELSE
         	v_nit = ' ';
		 END IF; 
         
         IF  pxp.f_existe_parametro(p_tabla,'cantidad_det')  THEN  
         	v_cantidad_det = v_parametros.cantidad_det;
         ELSE
         	raise exception  'Debe ingresar una Cantidad relacionado con la razon social % y Nro Factura: % ',v_parametros.razon_social, v_nro_factura;
		 END IF;
         
        IF  pxp.f_existe_parametro(p_tabla,'unidad')  THEN  
         	v_unidad = v_parametros.unidad;
         ELSE
         	v_unidad = ' ';
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'detalle')  THEN  
         	v_detalle = UPPER(v_parametros.detalle);
         ELSE
            raise exception  'Falta el Detalle relacionado con la razon social % y Nro Factura: % ',v_parametros.razon_social, v_nro_factura;

		 END IF;          
         IF  pxp.f_existe_parametro(p_tabla,'precio_uni_usd') and v_parametros.precio_uni_usd is not null THEN  
         	v_precio_uni_usd = v_parametros.precio_uni_usd;
         ELSE
         	v_precio_uni_usd = 0;
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'precio_uni_bs') and v_parametros.precio_uni_bs is not null  THEN  
         	v_precio_uni_bs = v_parametros.precio_uni_bs;
         ELSE
         	raise exception  'Falta un  Precio uni BS relacionado con la razon social % y Nro Factura: % ',v_parametros.razon_social, v_nro_factura;
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'precio_total_usd')  and v_parametros.precio_total_usd is not null THEN  
         	v_precio_total_usd = v_parametros.precio_total_usd;
         ELSE
         	v_precio_total_usd = 0;
		 END IF;
        
        IF  pxp.f_existe_parametro(p_tabla,'precio_total_bs') and v_parametros.precio_total_bs is not null  THEN  
         	v_precio_total_bs = v_parametros.precio_total_bs;
         ELSE
         	v_precio_total_bs = 0;
		 END IF;
         
          IF  pxp.f_existe_parametro(p_tabla,'centro_costo')  THEN  
         	v_centro_costo = v_parametros.centro_costo;
         ELSE
         	raise exception  'Falta un  Centro de Costo  relacionado con la razon social % y Nro Factura: % ',v_parametros.razon_social, v_nro_factura;
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'clase_costo')  THEN  
         	v_clase_costo = v_parametros.clase_costo;
         ELSE
         	v_clase_costo = ' ';
		 END IF;
         
        
         IF  pxp.f_existe_parametro(p_tabla,'observaciones')  THEN  
         	v_observaciones = UPPER(v_parametros.observaciones);
         ELSE
         	v_observaciones = ' ';
		 END IF;  
         
         IF  pxp.f_existe_parametro(p_tabla,'fecha')  THEN  
         	v_fecha = v_parametros.fecha;
         ELSE
         	raise exception  'Falta la Fecha relacionado con la razon social % y Nro Factura: % ',v_parametros.razon_social, v_nro_factura;
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'id_punto_venta')  THEN  
         	v_id_punto_venta = v_parametros.id_punto_venta;
         ELSE
         	v_id_punto_venta = 0 ;
		 END IF;     
          
         IF  pxp.f_existe_parametro(p_tabla,'tipo_factura')  THEN  
         	v_tipo_factura = v_parametros.tipo_factura;
         ELSE
         	v_tipo_factura ='computarizada';
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'nro_contrato')  THEN  
         	v_nro_contrato = v_parametros.nro_contrato;
         ELSE
         	v_nro_contrato =' ';
		 END IF;    
          
          
       	SELECT
        	tfe.id_factura_excel,
         	tfe.nro_factura,
            tfe.fecha,
            tfe.fecha_reg::date 
        INTO
        	v_record_data_excel
        FROM vef.ttemp_factura_excel tfe
        WHERE  tfe.nro_factura = v_parametros.nro_factura and tfe.fecha_reg::date = NOW()::date and tfe.fecha = v_parametros.fecha;
      
    	
       FOR item_validacion_detalle IN(  
       SELECT
        	tfed.fecha_reg::date	,
            tfed.cantidad_det		,		
            tfed.unidad				,
            tfed.detalle			,
            tfed.precio_uni_usd		,
            tfed.precio_uni_bs 		,
            tfed.fecha				,
            tfed.nro_factura 		,
            tfed.observaciones 		,
            tfed.tipo_factura		
         FROM vef.ttemp_factura_detalle_excel tfed
        WHERE  tfed.nro_factura = v_parametros.nro_factura and tfed.fecha_reg::date = NOW()::date and tfed.fecha = v_parametros.fecha)LOOP
        			
        		IF(item_validacion_detalle.fecha_reg::date	<> NOW()::DATE	or
            	item_validacion_detalle.cantidad_det <>	v_cantidad_det	or		
            	item_validacion_detalle.unidad	<>	v_unidad			or
            	item_validacion_detalle.detalle	<>	v_detalle			or
            	item_validacion_detalle.precio_uni_usd	<>	v_precio_uni_usd 	or
            	item_validacion_detalle.precio_uni_bs 	<>	v_precio_uni_bs		or
            	item_validacion_detalle.fecha			<> v_fecha 				or
            	item_validacion_detalle.nro_factura 	<> v_nro_factura		 or
            	item_validacion_detalle.observaciones 	<> v_observaciones		or
            	item_validacion_detalle.tipo_factura	<> v_tipo_factura )THEN
                	v_bandera_validacion = true;
                ELSE
                	v_bandera_validacion=false;
                END IF;
        
        
        END LOOP;

         IF(v_record_data_excel.nro_factura is null)THEN                    
            INSERT INTO vef.ttemp_factura_excel(
                                        id_usuario_reg		,		
                                        id_funcionario_usu	,
                                        razon_social 		,
                                        nit 				,
                                        cantidad_det		,		
                                        unidad				,
                                        detalle				,
                                        precio_uni_usd		,
                                        precio_uni_bs 		,
                                        precio_total_usd	,
                                        precio_total_bs		,
                                        centro_costo 		,
                                        clase_costo 		,
                                        nro_factura 		,
                                        observaciones 		,
                                        fecha				,
                                        id_punto_venta		,
                                        tipo_factura		,
                                        nro_contrato		
                                   )VALUES
                                    (	
                                        v_record_persona.id_usuario		,
                                        v_parametros.id_funcionario_usu	,
                                        v_razon_social 		,
                                        v_nit 				,
                                        v_cantidad_det		,		
                                        v_unidad			,
                                        v_detalle			,
                                        v_precio_uni_usd	,
                                        v_precio_uni_bs 	,
                                        v_precio_total_usd	,
                                        v_precio_total_bs	,
                                        v_centro_costo 		,
                                        v_clase_costo 		,
                                        v_nro_factura 		,
                                        v_observaciones 	,
                                        v_fecha				,
                                        v_id_punto_venta	,
                                        v_tipo_factura		,
                                        v_nro_contrato		
               ) returning id_factura_excel into v_id_factura_excel;
               
               --raise exception 'id_factura_excel %',v_id_factura_excel;
               
               INSERT INTO vef.ttemp_factura_detalle_excel(
                                        id_usuario_reg		,
                                        cantidad_det		,		
                                        unidad				,
                                        detalle				,
                                        precio_uni_usd		,
                                        precio_uni_bs 		,
                                        fecha				,
                                        nro_factura 		,
                                        observaciones 		,
                                        tipo_factura		,
                                        id_factura_excel_fk	
                                   )VALUES
                                    (	
                                        v_record_persona.id_usuario		,
                                        v_cantidad_det			,		
                                        v_unidad				,
                                        v_detalle				,
                                        v_precio_uni_usd		,
                                        v_precio_uni_bs 		,
                                        v_fecha					,
                                        v_nro_factura 			,
                                        v_observaciones 		,                                                                
                                        v_tipo_factura			,
                                        v_id_factura_excel
               )returning id_factura_excel_det into v_id_factura_excel_det;
             /*
           if v_parametros.conteo = 2 then
            raise exception 'hola %',v_parametros.id_punto_venta;
           end if;*/
           select  
                pdv.id_sucursal
           into 
                v_record_punto_venta
           from vef.tpunto_venta pdv
           where pdv.id_punto_venta = v_id_punto_venta;
            --raise	exception 'sucursal %',v_record_punto_venta.id_sucursal;
         	
          update vef.ttemp_factura_excel
          set id_sucursal = v_record_punto_venta.id_sucursal
          where  id_factura_excel = v_id_factura_excel;
           
            --recuperando el proveedor
                select
                    vpro.id_proveedor,
                    vpro.desc_proveedor
                INTO
                v_record_proveedor
                from param.vproveedor	vpro
                where vpro.desc_proveedor = v_razon_social;
            
            --RAISE EXCEPTION 'id_proveedor %',v_record_proveedor.id_proveedor;
            
            update vef.ttemp_factura_excel
            set id_proveedor = v_record_proveedor.id_proveedor
            where  id_factura_excel = v_id_factura_excel;
        
            --recuperando el centro de costo

                SELECT
                    cc.id_centro_costo,
                    cc.codigo_tcc,
                    cc.codigo_cc,
                    (date_part('year'::text,'2018-01-01'::date))as gestion
                INTO
                v_record_centro_costo
                FROM param.vcentro_costo  cc
                WHERE estado_reg='activo' and cc.codigo_tcc = v_centro_costo;
                
            update vef.ttemp_factura_excel
            set id_centro_costo = v_record_centro_costo.id_centro_costo
            where  id_factura_excel = v_id_factura_excel;
            	
            --RAISE EXCEPTION 'centro_costo %',v_record_centro_costo.id_centro_costo;

                --recuperando el contrato
               SELECT 
                    ct.id_contrato,
                    ct.numero
               INTO
                    v_record_contrato
               FROM leg.tcontrato ct
               WHERE  ct.numero =  v_nro_contrato;
               
            update vef.ttemp_factura_excel
            set id_contrato = v_record_contrato.id_contrato
            where  id_factura_excel = v_id_factura_excel;         
            --RAISE EXCEPTION 'contrato %',v_record_contrato.id_contrato;
                 /*     
       	  if v_parametros.conteo = 2 then
      			 raise	exception 'id sucursal % id proveedor % id centro costo % id contrato % ',v_record_punto_venta.id_sucursal,v_record_proveedor.id_proveedor,v_record_centro_costo.id_centro_costo,v_record_contrato.id_contrato;
       	  end if;    */
            ELSIF( v_bandera_validacion = true) THEN
                
                --raise exception 'fecha_reg %',NOW()::DATE;
              
             INSERT INTO vef.ttemp_factura_detalle_excel(
                                        id_usuario_reg		,
                                        cantidad_det		,		
                                        unidad				,
                                        detalle				,
                                        precio_uni_usd		,
                                        precio_uni_bs 		,
                                        fecha				,
                                        nro_factura 		,
                                        observaciones 		,
                                        tipo_factura		,
                                        id_factura_excel_fk	
                                   )VALUES
                                    (	
                                        v_record_persona.id_usuario		,
                                        v_cantidad_det			,		
                                        v_unidad				,
                                        v_detalle				,
                                        v_precio_uni_usd		,
                                        v_precio_uni_bs 		,
                                        v_fecha					,
                                        v_nro_factura 			,
                                        v_observaciones 		,                                                                
                                        v_tipo_factura			,
                                        v_record_data_excel.id_factura_excel
               )returning id_factura_excel_det into v_id_factura_excel_det;
            
            END IF;
           	
		RETURN   v_resp;
    	END;

	/*********************************
     #TRANSACCION:  'VF_INSFAC_INS'
     #DESCRIPCION:	Insercion de registros
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

    elsif(p_transaccion='VF_INSFAC_INS')then
    	begin
      
       	 --raise exception 'venta ';
      
   
          SELECT
              usu.id_usuario,
              fun.id_funcionario,
              fun.id_persona
            INTO
            v_record_persona
          FROM orga.tfuncionario fun
          left join segu.tusuario usu on usu.id_persona = fun.id_persona
          WHERE fun.id_funcionario = v_parametros.id_funcionario_usu;
 
			p_id_usuario=v_record_persona.id_usuario;
    	
       FOR item IN(
       			SELECT
					 tfe.id_factura_excel	,
                     tfe.razon_social 		,
                     tfe.nit 				,
                     tfe.cantidad_det		,		
                     tfe.unidad				,
                     tfe.detalle			,
                     tfe.precio_uni_usd		,
                     tfe.precio_uni_bs 		,
                     tfe.precio_total_usd	,
                     tfe.precio_total_bs	,
                     tfe.centro_costo 		,
                     tfe.clase_costo 		,
                     tfe.nro_factura 		,
                     tfe.observaciones 		,
                     tfe.fecha				,
                     tfe.id_punto_venta		,
                     tfe.tipo_factura		,
                     tfe.nro_contrato		,
                     tfe.id_sucursal		,
                     tfe.id_proveedor		,
                     tfe.id_centro_costo	,
                     tfe.id_contrato		,
                     tfe.fecha_reg::date			
                     			
                FROM vef.ttemp_factura_excel  tfe
       )LOOP
           --raise exception 'record %',item;
          --crear tabla       
            v_codigo_trans = 'VF_VEN_INS';
            v_tabla = pxp.f_crear_parametro(ARRAY[
            					'_nombre_usuario_ai',
                                '_id_usuario_ai',	 
            				   --'id_cliente',
                                'nit',
                                'id_sucursal',       
                                'nro_tramite',
                                --'a_cuenta',	
                                'total_venta',
                                'fecha_estimada_entrega',
                                'id_punto_venta',
                                'id_forma_pago',
                                'monto_forma_pago', 
                                'numero_tarjeta',
                                'codigo_tarjeta',
                                'tipo_tarjeta',
                                --'porcentaje_descuento', 
                                --'id_vendedor_medico',
                                --'comision',
                                'observaciones',
                                'tipo_factura',
                                --'fecha',
                                'nro_factura', 
                                'id_dosificacion', 
                                --'excento',
                                --'id_moneda',
                                --'tipo_cambio_venta',
                                --'total_venta_msuc',
                                'transporte_fob',
                                'seguros_fob',
                                'otros_fob',
                                'transporte_cif',
                                'seguros_cif',
                                'otros_cif',
                                'valor_bruto',
                                'descripcion_bulto',
                                'id_cliente_destino',
                                --'hora_estimada_entrega',
                                --'forma_pedido',	
                                'id_proveedor',					
                                'id_centro_costo',
                                'id_contrato',
                                'codigo_aplicacion'
                                --'id_venta_fk' 
                                ],
            				ARRAY[	
                           		'NULL', ---'_nombre_usuario_ai',
                                ''::varchar,  -----'_id_usuario_ai',
                               	--'',--'id_cliente',
                                item.nit::varchar,--'nit',
                                item.id_sucursal::varchar,--'id_sucursal',       
                                '',--'nro_tramite',
                                --'0'::varchar,--'a_cuenta',	
                                '',--'total_venta',
                                '',--'fecha_estimada_entrega',
                                item.id_punto_venta::varchar,--'id_punto_venta'
                                '2'::varchar,--'id_forma_pago',
                                item.precio_total_bs::varchar,--'monto_forma_pago' 
                                '',--'numero_tarjeta',
                                '',--'codigo_tarjeta',
                                '',--'tipo_tarjeta',
                               -- '',--'porcentaje_descuento', 
                               -- '',--'id_vendedor_medico',
                                --'',--'comision',
                                item.observaciones::varchar,--'observaciones',
                                item.tipo_factura::varchar,--'tipo_factura',
                                --'',--'fecha',
                                '',--'nro_factura', 
                                '',--'id_dosificacion', 
                                --'0'::varchar,--'excento',
                                --'',--'id_moneda',
                                --'',--'tipo_cambio_venta',
                                --'',--'total_venta_msuc',
                                '',--'transporte_fob',
                                '',--'seguros_fob',
                                '',--'otros_fob',
                                '',--'transporte_cif',
                                '',--'seguros_cif',
                                '',--'otros_cif',
                                '',--'valor_bruto',
                                '',--'descripcion_bulto',
                                '',--'id_cliente_destino',
                                --'',--'hora_estimada_entrega',
                                --'',--'forma_pedido',					
                                item.id_proveedor::varchar,--'id_proveedor',					
                                item.id_centro_costo::varchar,--'id_centro_costo',
                                item.id_contrato::varchar,--'id_contrato',
                                'peaje'::varchar--'codigo_aplicacion',
								--''--'id_venta_fk' 
                                ],
                            ARRAY[
                            		'varchar',
                                	'integer',
                          			--'varchar',
									'varchar',
            						'int4',    
            						'varchar',
            						--'numeric',	
	               					'numeric',
        							'date',
									'int4',
									'int4',
									'numeric', 
									'varchar', 
									'varchar', 
									'varchar', 
            						--'integer', 
            						--'varchar', 
									--'numeric', 
									'text',
									'varchar',
									--'date',
            						'varchar', 
                                    'integer',
                                    --'numeric',
                                    --'int4',
                                    --'numeric',
                                    --'numeric',
                                    'numeric',			
                                    'numeric',
                                    'numeric',
                                    'numeric',
                                    'numeric',
                                    'numeric',
                                    'numeric',
                                    'varchar',
                                    'varchar',
                                    --'varchar',
                                    --'varchar',
                                    'varchar',					
                                    'int4', 
                                    'int4',
                                    'varchar'
									--'int4'
                               ]
                            );
 			
            v_resp = vef.ft_venta_ime(p_administrador,p_id_usuario,v_tabla,v_codigo_trans);
            
            --raise exception '%',v_resp;
            
          	v_id_venta = pxp.f_recupera_clave(v_resp,'id_venta');
            v_id_venta	=  split_part(v_id_venta, '{', 2);
            v_id_venta	=  split_part(v_id_venta, '}', 1);
          -- raise exception 'venta%',v_id_venta;
         	
          	v_codigo_trans_2 = 'VF_VEDET_INS';
       		
            FOR item_detalle IN(
            	SELECT
                		tfed.id_usuario_reg		,
                        tfed.cantidad_det		,		
                        tfed.unidad				,
                        tfed.detalle			,
                        tfed.precio_uni_usd		,
                        tfed.precio_uni_bs 		,
                        tfed.fecha				,
                        tfed.nro_factura 		,
                        tfed.observaciones 		,
                        tfed.tipo_factura		,
                        tfed.id_factura_excel_fk	
	
                FROM  vef.ttemp_factura_detalle_excel tfed
            	where tfed.nro_factura = item.nro_factura and tfed.fecha_reg::date = now()::date 
            )LOOP
            
            	

                --crear tabla 
            v_tabla_2 = pxp.f_crear_parametro(ARRAY[	
            			'id_venta',
                		'id_item',
               			'id_producto',
               			'id_formula',
                		'tipo',
               			'estado_reg',
               			'cantidad_det',
                		'precio',
                		'sw_porcentaje_formula',
                		--'porcentaje_descuento',        
              			--'id_vendedor_medico',
						'descripcion',
						'id_unidad_medida',
						--'bruto',
						--'ley',
						--'kg_fino',
						'tipo_factura'
						--'id_venta_fk'  
                                    ],
            				ARRAY[	
                          	v_id_venta::varchar,--'id_venta',
                            '',--'id_item',
                            '15'::varchar,--'id_producto',
                            '',--'id_formula',
                            'servicio'::varchar,--'tipo',
                            '',--'estado_reg',
                            item_detalle.cantidad_det::varchar,--'cantidad',
                            item_detalle.precio_uni_bs::varchar,--'precio',
                            '',--'sw_porcentaje_formula',
                           --'',--'porcentaje_descuento',        
                           -- '',--'id_vendedor_medico',
                            '',--'obs'::varchar,'descripcion',
                            '',--'id_unidad_medida',
                            --'',--'bruto',
                            --'',--'ley',
                            --'',--'kg_fino',
                            item_detalle.tipo_factura::varchar--'tipo_factura'
                            --''--'id_venta_fk'        
                                ],
                            ARRAY[          
                                  'int4',
                                  'int4',
                                  'int4',
                                  'int4',
                                  'varchar',
                                  'varchar',
                                  'numeric',
                                  'numeric',
                                  'varchar', 
                                 -- 'int4',             
                                  --'varchar',
                                  'text',
                                  'int4',
                                  --'varchar',
                                  --'varchar',
                                  --'varchar',
                                  'varchar'
                                  --'int4'
                               ]
                            );
           	 --raise exception 'v_tabla_2 %',p_id_usuario;
            
            v_resp_2 = vef.ft_venta_detalle_ime(p_administrador,p_id_usuario,v_tabla_2,v_codigo_trans_2);
            v_id_venta_det = pxp.f_recupera_clave(v_resp_2,'id_venta_detalle');
            v_id_venta_det	=  split_part(v_id_venta_det, '{', 2);
            v_id_venta_det	=  split_part(v_id_venta_det, '}', 1);
            --raise exception 'venta det %',v_id_venta_det;
            
			--raise exception 'venta vali %',v_id_venta;
			v_codigo_trans_3 ='VF_VENVALI_MOD';
            
            
                --crear tabla 
            v_tabla_3 = pxp.f_crear_parametro(ARRAY[	
            			'id_venta',
                		'tipo_factura',
               			'id_venta_fk'
               			
                                    ],
            				ARRAY[	
                             v_id_venta::varchar,--'id_venta',
                             'computarizada'::varchar--'tipo_factura',
               				--'id_venta_fk'
                                ],
                            ARRAY[          
                                  'int4',
                                  'varchar'                                 
                                  --'int4'
                               ]
                            );
           	 --raise exception 'v_tabla_3 %',p_id_usuario;
		            
            v_resp_3 = vef.ft_venta_ime(p_administrador,p_id_usuario,v_tabla_3,v_codigo_trans_3);
            --raise exception 'fin';
            END LOOP;
       END LOOP;
		RETURN   v_resp;
    END;

END IF;
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