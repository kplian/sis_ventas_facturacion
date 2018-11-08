--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.f_inserta_factura (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas y facturacion
 FUNCION: 		vef.f_inserta_factura
 DESCRIPCION:   Funcion que gestiona la Validacion e insercion desde un excel a un punto de punto de venta en el sistema
 AUTOR: 		 (EGS)
 FECHA:	        08/11/2018
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
ISSUE  						AUTHOR  				FECHA   					DESCRIPCION
#22                         EGS                  08-11-2018                   Creación 	
***************************************************************************/

DECLARE
	
	v_consulta					varchar;
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


    v_id_dato_temporal			integer;

    item						record;
    v_item						record;
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
    v_record_sucursal			record;
    v_record_contrato			record;
    v_record_forma_pago			record;
    v_record_aplicacion			record;
    v_record_concepto_ingas		record;
    v_record_sucursal_producto	record;
    v_record_punto_venta_producto	record;
    
    v_record_data_excel			record;
    v_record_data_excel_det		record;
    
    v_registros_factura			record;
    
    v_record_precio_total		record;
	v_record_precio_uni_cant	record;
    v_record_data_temporal		record;
    
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
    v_forma_pago				varchar;
    v_aplicacion				varchar;
    
    
    v_bandera_validacion		BOOLEAN;
    
    v_contador					integer;
    v_contrato					varchar;
    
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
        v_bandera_validacion = false;
        --raise exception 'forma pago %',v_parametros.forma_pago;
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
         	raise exception  'Debe ingresar un Nro de Fac/Doc relacionado con la razon social %',v_parametros.razon_social ;
		 END IF;
        --verificamos si el nro de fact/doc ya ingreso el dia del registro 	
         SELECT 
         		teff.id_factura_excel,
         		teff.nro_factura,
                teff.razon_social,
                teff.venta_generada
         INTO
          		v_record_data_excel
         FROM vef.ttemp_factura_excel teff
         WHERE	teff.nro_factura = v_nro_factura and teff.fecha_reg::date = now()::date;
        	
         IF v_record_data_excel.nro_factura = v_nro_factura and  v_record_data_excel.razon_social <> v_razon_social THEN
         	raise exception  'El Nro Fac/Doc % ya ingreso con la razon zocial % y ya no ingresara con  % ',v_nro_factura,v_record_data_excel.razon_social,v_razon_social ;
         /*
         ELSIF(v_record_data_excel.nro_factura = v_nro_factura and  v_record_data_excel.razon_social = v_razon_social and v_record_data_excel.venta_generada = true )THEN
  				 delete from vef.ttemp_factura_excel
 					where id_factura_excel = v_record_data_excel.id_factura_excel;
                 delete from vef.ttemp_factura_detalle_excel
 					where id_factura_excel_fk = v_record_data_excel.id_factura_excel;  */    
         END IF;

         IF  pxp.f_existe_parametro(p_tabla,'nit')  THEN  
         	v_nit = v_parametros.nit;
         ELSE
         	    select
                    vpro.nit
                INTO
                v_nit
                from param.vproveedor	vpro
                where upper(vpro.desc_proveedor) = v_razon_social;
                if v_nit is null then
         		raise exception  'No tiene un Nit Registrado en Sistema ni en el excel con razon social % y nro Fac/doc %  ',v_parametros.razon_social, v_nro_factura;
                end if;
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
         	raise exception  'Falta un  Precio uni BS relacionado con la razon social % y Nro Factura: % ',v_razon_social, v_nro_factura;
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'precio_total_usd')  and v_parametros.precio_total_usd is not null THEN  
         	v_precio_total_usd = v_parametros.precio_total_usd;
         ELSE
         	v_precio_total_usd = 0;
		 END IF;
        
        IF  pxp.f_existe_parametro(p_tabla,'precio_total_bs') and v_parametros.precio_total_bs is not null  THEN  
         	v_precio_total_bs = v_parametros.precio_total_bs;
         ELSE
         	raise exception  'Falta un  Precio Total BS relacionado con la razon social % y Nro Factura: % ',v_parametros.razon_social, v_nro_factura;
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
			v_fecha = now()::date;
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
         
         IF  pxp.f_existe_parametro(p_tabla,'nro_contrato') THEN 
         	 
         	v_nro_contrato = v_parametros.nro_contrato;
         ELSE
         	--raise exception 'entra %',pxp.f_existe_parametro(p_tabla,'nro_contrato');
         	v_nro_contrato =' ';
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'forma_pago') THEN 
         	 
         	v_forma_pago = UPPER(v_parametros.forma_pago);
         ELSE
         	v_forma_pago ='Cuenta Por Cobrar/Pagar';
		 END IF;
 

         IF  pxp.f_existe_parametro(p_tabla,'aplicacion') THEN 
         	 
         	v_aplicacion = UPPER(v_parametros.aplicacion);
         ELSE
         	v_aplicacion =' ';
		 END IF;
         
           --recuperando id y nombre sucursal  
           select  
                pdv.id_sucursal,
                sucu.nombre as nombre_sucursal,
                pdv.nombre as nombre_punto_venta
           into 
                v_record_sucursal
           from vef.tpunto_venta pdv
           left join vef.tsucursal sucu on sucu.id_sucursal = pdv.id_sucursal
           where pdv.id_punto_venta = v_id_punto_venta;
            --raise	exception 'sucursal %',v_record_punto_venta.id_sucursal;
         
         --validadon producto si existe el concepto de gatos
         SElECT 
          cing.id_concepto_ingas,
          cing.desc_ingas
         INTO 
         v_record_concepto_ingas
         FROM param.tconcepto_ingas cing
         WHERE	UPPER(cing.desc_ingas) = v_detalle ;
        -- raise	exception 'v_record_concepto_ingas %',v_detalle;

         IF v_record_concepto_ingas is null THEN
         	RAISE EXCEPTION 'El detalle % no esta registrado como un Conceptos-ingas  ',v_detalle;          
         END IF;
         
         --validando si el concepto de gasto esta como un producto de una sucursal
         SELECT
         	sp.id_sucursal_producto,
            sp.id_concepto_ingas
         INTO
         	v_record_sucursal_producto
         FROM vef.tsucursal_producto sp
         WHERE sp.id_concepto_ingas = v_record_concepto_ingas.id_concepto_ingas;
         
      	IF v_record_sucursal_producto is null  THEN
      		RAISE EXCEPTION 'El detalle % no esta registrado como Producto en el Modulo de Ventas %',v_detalle,v_record_sucursal.nombre_sucursal;          
        END IF;
        --validado si el detalle esta activado en un punto de venta
        SELECT
        		pvp.id_punto_venta_producto,
                pvp.id_sucursal_producto
        INTO  	
        		v_record_punto_venta_producto
        FROM vef.tpunto_venta_producto  pvp
        WHERE pvp.id_sucursal_producto = v_record_sucursal_producto.id_sucursal_producto and pvp.id_punto_venta = v_id_punto_venta;
        
        IF v_record_punto_venta_producto is null  THEN
      		RAISE EXCEPTION 'El detalle % no esta activado como Producto en el punto de venta %',v_detalle,v_record_sucursal.nombre_punto_venta;          
        END IF;
         
            ---recuperando id de forma de pago      
            SELECT
            		fp.id_forma_pago,
                    fp.codigo,
                    fp.nombre
            INTO
                v_record_forma_pago
            FROM vef.tforma_pago  fp
            WHERE UPPER(fp.nombre) = v_forma_pago;
            
            IF v_record_forma_pago.nombre is null THEN
            RAISE EXCEPTION 'No existe la forma de pago  %',v_forma_pago;          
            END IF; 
         
           ---recuperando codigo de aplicacion   
         	SELECT
            		cat.codigo,
                    cat.descripcion
            INTO
                v_record_aplicacion
            FROM param.tcatalogo  cat
            WHERE upper(cat.descripcion) = v_aplicacion;
            
            IF v_record_aplicacion.codigo is null THEN
            RAISE EXCEPTION 'No existe la Aplicacion o Finalidad   %',v_aplicacion;          
            END IF;

    	  ----RECUPERANDO IDS

            --recuperando el proveedor
                select
                    vpro.id_proveedor,
                    vpro.desc_proveedor
                INTO
                v_record_proveedor
                from param.vproveedor	vpro
                where UPPER(vpro.desc_proveedor) = v_razon_social;
            
            IF v_record_proveedor.id_proveedor is null THEN
            
            RAISE EXCEPTION 'no existe proveedor registrado para esta razon social %',v_razon_social;          
            END IF;
            --RAISE EXCEPTION 'id_proveedor %',v_record_proveedor.id_proveedor;

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
            
            IF v_record_centro_costo.id_centro_costo is null THEN
            RAISE EXCEPTION 'no existe centro de costo registrado para este dato de centro de costo en el excel %',v_centro_costo;          
            END IF;    
            	
            --RAISE EXCEPTION 'centro_costo %',v_record_centro_costo.id_centro_costo;
			
                --recuperando el contrato
               SELECT 
                    ct.id_contrato,
                    ct.numero
               INTO
                    v_record_contrato
               FROM leg.tcontrato ct
               WHERE  ct.numero =  v_nro_contrato;
              IF v_record_contrato is null THEN
              	v_record_contrato.id_contrato = 0;
              END IF; 
              

            --RAISE EXCEPTION 'contrato %',v_record_contrato.id_contrato;

        ---verificando si existe la fila del excel en base datos el dia de la creacion  
       	SELECT
        	tfe.id_factura_excel,
         	tfe.nro_factura,
            tfe.fecha,
            tfe.fecha_reg::date 
        INTO
        	v_record_data_excel
        FROM vef.ttemp_factura_excel tfe
        WHERE  tfe.nro_factura = v_parametros.nro_factura and tfe.fecha_reg::date = NOW()::date;

    	--validacion que el detalle no se repita si se repite no se inserta 
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
        WHERE  tfed.nro_factura = v_parametros.nro_factura and tfed.fecha_reg::date = NOW()::date)LOOP
        			
        IF (item_validacion_detalle.fecha_reg::date	= NOW()::DATE	and
            	item_validacion_detalle.cantidad_det =	v_cantidad_det	and		
            	item_validacion_detalle.unidad	=	v_unidad			and
            	item_validacion_detalle.detalle	=	v_detalle			and
            	item_validacion_detalle.precio_uni_usd	=	v_precio_uni_usd 	and
            	item_validacion_detalle.precio_uni_bs 	=	v_precio_uni_bs		and
            	item_validacion_detalle.fecha			= v_fecha 				and
            	item_validacion_detalle.nro_factura 	= v_nro_factura		and
            	item_validacion_detalle.observaciones 	= v_observaciones		and
            	item_validacion_detalle.tipo_factura	= v_tipo_factura )THEN
                	
                v_bandera_validacion = true;

         END IF;

        END LOOP;
		------insertando la fila del excel si este no existe en base de datos
        
         IF(v_record_data_excel.nro_factura is null)THEN                    
            INSERT INTO vef.ttemp_factura_excel(
                                        id_usuario_reg		,		
                                        id_funcionario_usu	,
                                        razon_social 		,
                                        nit 				,
                                        precio_total_usd	,
                                        precio_total_bs		,
                                        centro_costo 		,
                                        clase_costo 		,
                                        nro_factura 		,
                                        observaciones 		,
                                        fecha				,
                                        id_punto_venta		,
                                        tipo_factura		,
                                        nro_contrato		,
                                        id_sucursal			,
                                        id_proveedor		,
                                        id_centro_costo		,
                                        id_contrato			,
                                        forma_pago			,
                                        aplicacion			,
                                        id_forma_pago		,
                                        codigo_aplicacion
                                        
                                   )VALUES
                                    (	
                                        v_record_persona.id_usuario		,
                                        v_parametros.id_funcionario_usu	,
                                        v_razon_social 		,
                                        v_nit 				,
                                        v_precio_total_usd	,
                                        v_precio_total_bs	,
                                        v_centro_costo 		,
                                        v_clase_costo 		,
                                        v_nro_factura 		,
                                        v_observaciones 	,
                                        v_fecha				,
                                        v_id_punto_venta	,
                                        v_tipo_factura		,
                                        v_nro_contrato		,
                                        v_record_sucursal.id_sucursal,
                                        v_record_proveedor.id_proveedor ,
                                        v_record_centro_costo.id_centro_costo,
                                        v_record_contrato.id_contrato,
                                        v_forma_pago			,
                                        v_aplicacion			,
                                        v_record_forma_pago.id_forma_pago,
                                        v_record_aplicacion.codigo
                                        
               ) returning id_factura_excel into v_id_factura_excel;
               
               --raise exception 'id_factura_excel %',v_id_factura_excel;
               --la misma fila la primera vez se le considera parte del detalle e insertando la misma
               
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
           
            ELSIF ( v_bandera_validacion = false) THEN
                
               -- raise exception 'v_bandera_validacion %',v_bandera_validacion;
             --si el detalle no es repetido se inserta 
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
                                        v_record_persona.id_usuario	,
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
     #TRANSACCION:  'VF_VALIFAC_INS'
     #DESCRIPCION:	validacion de los registros de los totales de  excel insertados en la tabla
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

    ELSIF(p_transaccion='VF_VALIFAC_INS')THEN
    BEGIN
    	--RAISE EXCEPTION 'hola';
		FOR v_item IN(
       			SELECT
					 tffe.id_factura_excel	,
                     tffe.razon_social 		,
                     tffe.nit 				,
                     tffe.precio_total_usd	,
                     tffe.precio_total_bs	,
                     tffe.centro_costo 		,
                     tffe.clase_costo 		,
                     tffe.nro_factura 		,
                     tffe.observaciones		,
                     tffe.fecha				,
                     tffe.id_punto_venta	,
                     tffe.tipo_factura		,
                     tffe.nro_contrato		,
                     tffe.id_sucursal		,
                     tffe.id_proveedor		,
                     tffe.id_centro_costo	,
                     tffe.id_contrato		,
                     tffe.fecha_reg::date	,
                     tffe.forma_pago		,
                     tffe.aplicacion		,
                     tffe.id_forma_pago		,
                     tffe.codigo_aplicacion
                     			
                FROM vef.ttemp_factura_excel  tffe
       )LOOP	
    
            --recuperanto el total de la factura ingresada   			
       		SELECT
            	tfee.id_factura_excel,	
            	tfee.precio_total_bs,
                tfee.precio_total_usd
           	INTO
            	v_record_precio_total
            FROM vef.ttemp_factura_excel tfee
            WHERE tfee.id_factura_excel = v_item.id_factura_excel;
            
            --recuperando la suma de los detalles
          	SELECT 
           	tfeed.id_factura_excel_fk,
  			sum(tfeed.precio_uni_bs*tfeed.cantidad_det)as precio_bs,
            sum(tfeed.precio_uni_usd*tfeed.cantidad_det)as precio_usd
       		INTO
            	v_record_precio_uni_cant
            FROM vef.ttemp_factura_detalle_excel tfeed
      		WHERE tfeed.id_factura_excel_fk = v_item.id_factura_excel
            GROUP BY id_factura_excel_fk;
            
            --RAISE exception 'v_record_precio_detalle %',v_record_precio_uni_cant.precio_bs;
         
           --verificando si la eliminacion de la factura ya se encuentra en historico del dia
           SELECT	
            		ttd.nro_factura,
                    ttd.razon_social,
                    ttd.fecha_reg
            FROM	vef.ttemporal_data ttd
            INTO
            v_record_data_temporal
            WHERE	ttd.nro_factura = v_item.nro_factura and ttd.razon_social= v_item.razon_social and ttd.fecha_reg::date = now()::date ;
              
            
            
            IF (v_record_precio_total.precio_total_bs <  v_record_precio_uni_cant.precio_bs )THEN
            	
          		--insertando a la tabla de eliminacion si se encuentra un error en los totales
                -- no inserta si este ya existe en la tabla el dia de ingreso del excel
                  IF  v_record_data_temporal is null THEN
                  
                   INSERT INTO vef.ttemporal_data(
                                              nro_factura		,
                                              razon_social		,
                                              total_venta		,
                                              total_detalle
                                         )VALUES
                                          (	
                                              v_item.nro_factura,
                                              v_item.razon_social,
                                              v_record_precio_total.precio_total_bs,
                                              v_record_precio_uni_cant.precio_bs
                                              			
                                             
                     )returning id_dato_temporal into v_id_dato_temporal;
                    
                END IF;  
                 --Elimina de las tablas si este se encuentra con error la relacion suma de detalle = total para su nuevo ingreso  
                Delete from vef.ttemp_factura_excel
 					where id_factura_excel = v_item.id_factura_excel;
				
                Delete from vef.ttemp_factura_detalle_excel
 					where id_factura_excel_fk = v_item.id_factura_excel;
				
                RAISE NOTICE 'El total de la factura % es menor a la suma de los detalles',v_item.nro_factura;
                
            ELSIF(v_record_precio_total.precio_total_bs >  v_record_precio_uni_cant.precio_bs )THEN
			  
              	--insertando a la tabla de eliminacion si se encuentra un error en los totales 
                -- no inserta si este ya existe en la tabla el dia de ingreso del excel
                 IF v_record_data_temporal is  null THEN
                  INSERT INTO vef.ttemporal_data(
                                            nro_factura		,
                                            razon_social	,
                                            total_venta		,
                                            total_detalle	
                                       )VALUES
                                        (	
                                            v_item.nro_factura,
                                            v_item.razon_social,
                                            v_record_precio_total.precio_total_bs,
                                            v_record_precio_uni_cant.precio_bs			
                                           
                   )returning id_dato_temporal into v_id_dato_temporal;
                     
                 END IF;  
               --Elimina de las tablas si este se encuentra con error la relacion suma de detalle = total para su nuevo ingreso  
                  delete from vef.ttemp_factura_excel
 					where id_factura_excel = v_item.id_factura_excel;
                 delete from vef.ttemp_factura_detalle_excel
 					where id_factura_excel_fk = v_item.id_factura_excel;               
                 RAISE NOTICE 'El total de la factura % es mayor a la suma de los detalles',v_item.nro_factura;
                ELSIF(v_record_precio_total.precio_total_bs =  v_record_precio_uni_cant.precio_bs and v_record_precio_total.precio_total_usd  =  v_record_precio_uni_cant.precio_usd)THEN
            
            	--una vez ingresada la fila correctamente del excel y si tiene un historico en filas eliminadas por un mal ingreso de datos en los totales 
                --lo borra de la tabla de eliminados
            	IF v_record_data_temporal IS NOT NULL then
            	 	DELETE FROM  vef.ttemporal_data
 					WHERE nro_factura = v_item.nro_factura and fecha_reg::date = v_item.fecha_reg::date;
				END IF;                       
        	END IF;
            
       END LOOP;
       RETURN   v_resp;
    END;

	/*********************************
     #TRANSACCION:  'VF_INSFAC_INS'
     #DESCRIPCION:	Insercion de registros en la tabla de ventas
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
                     tfe.fecha_reg::date	,
                     tfe.forma_pago		,
                     tfe.aplicacion		,
                     tfe.id_forma_pago		,
                     tfe.codigo_aplicacion
                     			
                FROM vef.ttemp_factura_excel  tfe
                WHERE tfe.venta_generada = FALSE
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
                                item.id_forma_pago::varchar,--'id_forma_pago',
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
                                item.codigo_aplicacion::varchar--'codigo_aplicacion',
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
         	
          
       		v_contador=1;
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
            	where   tfed.id_factura_excel_fk = item.id_factura_excel
            )LOOP
            /*
            if v_contador = 2then
            	raise exception 'conteo % ,id venta %, item %' ,item_detalle,v_id_venta,item;
            end if ;*/
            
            v_codigo_trans_2 = 'VF_VEDET_INS';	

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
                            item_detalle.observaciones,--'obs'::varchar,'descripcion',
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
            
			
            
            
                --crear tabla 
          
            --raise exception 'fin';
            v_contador = 1+v_contador;
            
            END LOOP;
            --raise exception 'venta vali %',v_id_venta;
			v_codigo_trans_3 ='VF_VENVALI_MOD';
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
            
            UPDATE vef.ttemp_factura_excel 
            SET venta_generada = TRUE
            WHERE id_factura_excel = item.id_factura_excel;
            
       END LOOP;
		RETURN   v_resp;
    END;
          
     /*********************************
     #TRANSACCION:  'VF_ELIEXC_SEL'
     #DESCRIPCION:	lista los registros del excel eliminados en dia actual
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

    ELSIF(p_transaccion='VF_ELIEXC_SEL')THEN
    BEGIN
    		--Sentencia de la consulta
			v_consulta:='select
						dad.id_dato_temporal,
						dad.razon_social,
						dad.estado_reg,
						dad.nro_factura,
						dad.id_usuario_ai,
						dad.id_usuario_reg,
						dad.usuario_ai,
						dad.fecha_reg,
						dad.id_usuario_mod,
						dad.fecha_mod,
                        dad.total_venta,
                        dad.total_detalle
						from vef.ttemporal_data dad
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
    END;
    
	/*********************************    
 	#TRANSACCION:  'VF_ELIEXC_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		02-12-2017 02:49:10
	***********************************/

	elsif(p_transaccion='VF_ELIEXC_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_dato_temporal)
					    from vef.ttemporal_data dad
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

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