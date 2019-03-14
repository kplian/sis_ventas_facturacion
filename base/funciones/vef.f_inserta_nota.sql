CREATE OR REPLACE FUNCTION vef.f_inserta_nota (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas y facturacion
 FUNCION: 		vef.f_inserta_Nota
 DESCRIPCION:   Funcion que gestiona la Validacion e insercion desde un excel a un punto de punto de venta en el sistema
 AUTOR: 		 (EGS)
 FECHA:	        08/11/2018
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
ISSUE  						AUTHOR  				FECHA   					DESCRIPCION
#22                         EGS                  08-11-2018                   Creación 	
#4 endeETR                  EGS                  20/02/2019                  modificaciones punto de venta
***************************************************************************/

DECLARE
	
	v_consulta					varchar;
    count						integer;
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


    v_id_temporal_data			integer;

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
    v_record_factura			record;
    v_record_dosificacion		record;
    
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
    v_codigo					varchar;
    v_detalle					varchar;
    v_precio_uni_usd			numeric;
	v_precio_uni_bs				numeric;
    v_precio_total_usd			numeric;
    v_precio_total_bs			numeric;
    v_centro_costo				varchar;
    v_clase_costo				varchar;
    v_nro						varchar;
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
    
    v_nro_autori_fac			varchar;
    v_detalle_existe			boolean;
    v_id_venta_detalle_fk		integer;
    v_record_detalle_factura    varchar;
    v_count						integer;
    v_vef_por_per_ncd			varchar;
    v_total_venta           	numeric; 
    v_total_venta_ncd       	numeric;
    v_error						varchar;
    v_id_gestion                integer;
    
BEGIN

	 v_nombre_funcion = 'vef.f_inserta_nota';
	 v_parametros = pxp.f_get_record(p_tabla);
             
    /*********************************
     #TRANSACCION:  'VF_INSNOT_INS'
     #DESCRIPCION:	Insercion de registros
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

   	 IF(p_transaccion='VF_INSNOT_INS')then
    	begin  	
        v_bandera_validacion = false;
     
     --raise exception 'ENTRA %' ,v_parametros.nro_autori_fac;
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
        
         IF  pxp.f_existe_parametro(p_tabla,'nro')  THEN  
         	v_nro = v_parametros.nro;
         ELSE
         	raise exception  'Debe ingresar un Nro de Fac/Doc relacionado con la razon social %',v_parametros.razon_social ;
		 END IF;
         

        
         IF  pxp.f_existe_parametro(p_tabla,'nro')  THEN  
         	v_nro = v_parametros.nro;
         ELSE
         	raise exception  'Debe ingresar un Nro de Fac/Doc relacionado con la razon social %',v_parametros.razon_social ;
		 END IF;
         
                 
         IF  pxp.f_existe_parametro(p_tabla,'nro_factura')  THEN  
         	v_nro_factura = v_parametros.nro_factura;
         ELSE
         	raise exception  'Debe ingresar un Nro de Factura relacionado con el Nro %',v_nro_factura ;
		 END IF;
        
     	 IF  pxp.f_existe_parametro(p_tabla,'nro_autori_fac')  THEN  
         	v_nro_autori_fac = v_parametros.nro_autori_fac;
         ELSE
         	raise exception  'Falta nro de Autoriazacion para la factura  %',v_nro_factura ;
		 END IF;
                 
         IF  pxp.f_existe_parametro(p_tabla,'id_punto_venta')  THEN  
         	v_id_punto_venta = v_parametros.id_punto_venta;
         ELSE
         	v_id_punto_venta = 0 ;
		 END IF;     
        
        --verificamos si el nro de fact/doc ya ingreso el dia del registro 	
         SELECT 
         		teff.id_factura_excel,
         		teff.nro,
                teff.razon_social,
                teff.venta_generada
         INTO
          		v_record_data_excel
         FROM vef.ttemp_factura_excel teff
         WHERE	teff.nro = v_nro and teff.fecha_reg::date = now()::date and teff.ncd = TRUE and teff.id_punto_venta = v_id_punto_venta;--#4
        	
         IF v_record_data_excel.nro = v_nro and  v_record_data_excel.razon_social <> v_razon_social THEN
         	raise exception  'El Nro  % ya ingreso con la razon zocial % y ya no ingresara con  % ',v_nro,v_record_data_excel.razon_social,v_razon_social ;
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
                    select
                        vpro.nit
                    INTO
                    v_nit
                    from param.vproveedor	vpro
                    where upper(vpro.codigo) = v_razon_social;
                
                     if v_nit is null then
                         raise exception  'No tiene un Nit Registrado en Sistema ni en el excel con razon social % y nro %  ',v_parametros.razon_social, v_nro;
                     end if;
                end if;
		 END IF; 
         
         IF  pxp.f_existe_parametro(p_tabla,'cantidad_det')  THEN  
         	v_cantidad_det = v_parametros.cantidad_det;
         ELSE
         	raise exception  'Debe ingresar una Cantidad relacionado con la razon social % y Nro: % ',v_parametros.razon_social, v_nro;
		 END IF;
         
        IF  pxp.f_existe_parametro(p_tabla,'unidad')  THEN  
         	v_unidad = v_parametros.unidad;
         ELSE
         	v_unidad = ' ';
		 END IF;
         
         IF  pxp.f_existe_parametro(p_tabla,'codigo')  THEN  
         	v_codigo = UPPER(v_parametros.codigo);
         ELSE
            raise exception  'Falta el Codigo relacionado con la razon social % y Nro : % ',v_parametros.razon_social, v_nro;

		 END IF;  
         /*        
         IF  pxp.f_existe_parametro(p_tabla,'precio_uni_usd') and v_parametros.precio_uni_usd is not null THEN  
         	v_precio_uni_usd = v_parametros.precio_uni_usd;
         ELSE
         	v_precio_uni_usd = 0;
		 END IF;*/
         
         v_precio_uni_usd = 0;
         
         IF  pxp.f_existe_parametro(p_tabla,'precio_uni_bs') and v_parametros.precio_uni_bs is not null  THEN  
         	v_precio_uni_bs = v_parametros.precio_uni_bs;
         ELSE
         	raise exception  'Falta un  Precio uni BS relacionado con la razon social % y Nro : % ',v_razon_social, v_nro;
		 END IF;
         /*
         IF  pxp.f_existe_parametro(p_tabla,'precio_total_usd')  and v_parametros.precio_total_usd is not null THEN  
         	v_precio_total_usd = v_parametros.precio_total_usd;
         ELSE
         	v_precio_total_usd = 0;
		 END IF;*/
         
         v_precio_total_usd = 0;
        
        IF  pxp.f_existe_parametro(p_tabla,'precio_total_bs') and v_parametros.precio_total_bs is not null  THEN  
         	v_precio_total_bs = v_parametros.precio_total_bs;
         ELSE
         	raise exception  'Falta un  Precio Total BS relacionado con la razon social % y Nro : % ',v_parametros.razon_social, v_nro;
		 END IF;
         
          IF  pxp.f_existe_parametro(p_tabla,'centro_costo')  THEN  
         	v_centro_costo = v_parametros.centro_costo;
         ELSE
         	raise exception  'Falta un  Centro de Costo  relacionado con la razon social % y Nro : % ',v_parametros.razon_social, v_nro;
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
         
         
        -----verificando si existe el nro de autoriazacion 
         SELECT
         	dosi.nroaut
         INTO
         	v_record_dosificacion
         FROM vef.tdosificacion dosi
         Where dosi.nroaut = v_nro_autori_fac;
         
         IF v_record_dosificacion.nroaut is null THEN
         	RAISE EXCEPTION 'El numero de Autorizacion  % ingresado no  existe de la factura %',v_nro_autori_fac,v_nro_factura;          
         END IF; 

         --verificando si existe la factura  y que el nro de autoriazacion este relacionada con la factura
         SELECT
         	ven.id_venta,
         	ven.nro_factura,
            dosi.nroaut
         INTO
          	v_record_factura
         FROM vef.tventa ven
         Left join vef.tdosificacion dosi on dosi.id_dosificacion = ven.id_dosificacion
         WHERE ven.nro_factura=v_nro_factura::integer and dosi.nroaut = v_nro_autori_fac ;
         
         
         IF v_record_factura.nro_factura is null THEN
         	RAISE EXCEPTION 'El numero de Factura  % ingresado no  existe o la dosificacion no corresponde a esta factura',v_nro_factura;          
         END IF; 
         
         --Verificando si el codigo de concepto de gasto existe en la factura
        
         

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
       
         --validadon producto si existe el concepto de gatos
         SElECT 
          cing.id_concepto_ingas,
          cing.codigo,
          cing.desc_ingas
         INTO 
         v_record_concepto_ingas
         FROM param.tconcepto_ingas cing
         WHERE	UPPER(cing.codigo) = UPPER(v_codigo) ;

         IF v_record_concepto_ingas is  null THEN
         	RAISE EXCEPTION 'El codigo del concepto de gasto % no esta registrado como un Conceptos-ingas  ',v_codigo;          
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
      		RAISE EXCEPTION 'El codigo de concepto de gasto % no esta registrado como Producto en el Modulo de Ventas %',v_codigo,v_record_sucursal.nombre_sucursal;          
        END IF;

        v_detalle = v_record_concepto_ingas.desc_ingas;
        
        ---recuperando id de forma de pago      
            SELECT
            		fp.id_forma_pago,
                    fp.codigo,
                    fp.nombre
            INTO
                v_record_forma_pago
            FROM vef.tforma_pago  fp
            WHERE UPPER(fp.codigo) = v_forma_pago;
            
            IF v_record_forma_pago.codigo is null THEN
            RAISE EXCEPTION 'No existe la forma de pago  %',v_forma_pago;          
            END IF; 
         
           ---recuperando codigo de aplicacion   
         	SELECT
            		cat.codigo,
                    cat.descripcion
            INTO
                v_record_aplicacion
            FROM param.tcatalogo  cat
            WHERE upper(cat.codigo) = UPPER(v_aplicacion);
            
            IF v_record_aplicacion.codigo is null THEN
            RAISE EXCEPTION 'No existe la Aplicacion o Finalidad   %',v_aplicacion;          
            END IF;

			  
    	  ----RECUPERANDO IDS

            --recuperando el proveedor por razon social
                select
                    vpro.id_proveedor,
                    vpro.desc_proveedor,
                    vpro.codigo
                INTO
                v_record_proveedor
                from param.vproveedor	vpro
                where UPPER(vpro.desc_proveedor) = v_razon_social;

            
            IF v_record_proveedor.id_proveedor is null THEN
             	--Si no se encontro con la razon social se busca por codigo del proveedor
               select
                    vpro.id_proveedor,
                    vpro.desc_proveedor,
                    vpro.codigo
                INTO
                v_record_proveedor
                from param.vproveedor	vpro
                where UPPER(vpro.codigo) = v_razon_social;
                --Si no existe ni por razon social ni codigo se notifica
            	 IF v_record_proveedor.id_proveedor is null THEN
           				RAISE EXCEPTION 'no existe proveedor registrado para esta razon social %',v_razon_social;          
          		 END IF; 
           END IF;
           --#4
             --Los datos Quemados '1','7' pertenecen a los codigos de Recursos e Ingresos Egresos de la tabla de tipo de presupuesto 
             --estos parametros son las que se usan en listarCentroCostoFiltradoXUsuario los cuales se envia cuando se listan
             --centros de costo en el formulario de llenado de una venta 
             --recuperando el centro de costo
			   select
                per.id_gestion
                into
                v_id_gestion
                from param.tperiodo per
                where per.fecha_ini <=v_fecha and per.fecha_fin >= v_fecha
                limit 1 offset 0;	
                SELECT
                    cc.id_centro_costo,
                    cc.codigo_tcc,
                    cc.codigo_cc/*,
                    (date_part('year'::text,now()::date))as gestion*/
                INTO
                v_record_centro_costo
                FROM param.vcentro_costo  cc
                left join pre.vpresupuesto_cc pcec on pcec.id_centro_costo = cc.id_centro_costo
                WHERE cc.estado_reg='activo' and cc.codigo_tcc = v_centro_costo and cc.id_gestion = v_id_gestion and pcec.tipo_pres in ('1','7') and pcec.estado = 'aprobado' ;
            
            IF v_record_centro_costo.id_centro_costo is null THEN
            RAISE EXCEPTION 'No existe centro de costo registrado % para la gestion %',v_centro_costo,(date_part('year'::text,v_fecha::date));          
            END IF;    
            	  
            	
			
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

        ---verificando si existe la fila del excel en base datos el dia de la creacion  
       	SELECT
        	tfe.id_factura_excel,
         	tfe.nro,
            tfe.fecha,
            tfe.fecha_reg::date,
            tfe.ncd,
            tfe.venta_generada  
        INTO
        	v_record_data_excel
        FROM vef.ttemp_factura_excel tfe
        WHERE  tfe.nro = v_parametros.nro and tfe.fecha_reg::date = NOW()::date and  tfe.ncd = true and tfe.id_punto_venta = v_id_punto_venta; --#4
      
        
        IF v_record_data_excel.nro is not null and v_record_data_excel.venta_generada = true  THEN
            v_error = 'Este Nro ya genero una venta el dia de hoy';
            SELECT 
                td.id_temporal_data
                INTO
                v_id_temporal_data
            FROM vef.ttemporal_data td
            WHERE td.nro = v_nro and td.fecha_reg::date = NOW()::date and td.error = v_error and td.id_punto_venta = v_id_punto_venta; --#4
            
               IF v_id_temporal_data is null THEN 
               
                       INSERT INTO vef.ttemporal_data(
                                                  nro				,
                                                  razon_social		,
                                                  error             ,
                                                  id_punto_venta
                                             )VALUES
                                              (	
                                                  v_nro		,
                                                  v_razon_social,
                                                  v_error,
                                                  v_id_punto_venta                   
                                                  			
                                                 
                         )returning id_temporal_data into v_id_temporal_data;
               END IF;
        END IF;
        
			--verificando si el codigo existe en el detalle de la factura y relaciona cada detalle de la nota con cada detalle de la factura
       	  
          v_detalle_existe = false;
          v_record_detalle_factura = ' ';
          v_count = 0;
          FOR ITEM IN (
            WITH temp_detalle(	id_venta_detalle
                        )AS(
                        SELECT
                            tfde.id_venta_detalle_fk        	
                        FROM vef.ttemp_factura_detalle_excel tfde       
                        )
            SELECT	
            		vend.id_venta_detalle,
                    tempd.id_venta_detalle,
            	 	vend.id_sucursal_producto,
                    cing.codigo,
                    vend.precio
            FROM	vef.tventa_detalle vend
            left join vef.tsucursal_producto sp on sp.id_sucursal_producto = vend.id_sucursal_producto
            left join param.tconcepto_ingas cing on cing.id_concepto_ingas = sp.id_concepto_ingas
            left join temp_detalle tempd  on tempd.id_venta_detalle = vend.id_venta_detalle
            WHERE  tempd.id_venta_detalle is null and vend.id_venta_detalle_fk is null and vend.id_venta = v_record_factura.id_venta 
         	)LOOP
           
            IF upper(item.codigo) = upper(v_codigo)  THEN
            	v_count = v_count+1;
            	v_id_venta_detalle_fk = item.id_venta_detalle;
                v_detalle_existe = true;             
            END IF;

          END LOOP;
     		--RAISE EXCEPTION '%',v_count;
            IF v_detalle_existe = false  THEN
                RAISE EXCEPTION 'El codigo de concepto de gasto % no existe como detalle en la factura % o este detalle ya se encuentra en una nota de Credito registrada ',v_codigo,v_nro_factura;          
            END IF;
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
            tfed.nro		 		,
            tfed.observaciones 		,
            tfed.tipo_factura		
        FROM vef.ttemp_factura_detalle_excel tfed
        left join vef.ttemp_factura_excel teff on teff.id_factura_excel = tfed.id_factura_excel_fk
        WHERE  tfed.nro = v_parametros.nro and tfed.fecha_reg::date = NOW()::date and teff.id_punto_venta = v_id_punto_venta)LOOP --#4
        			
        IF (	item_validacion_detalle.fecha_reg::date	= NOW()::DATE	and
            	item_validacion_detalle.cantidad_det =	v_cantidad_det	and		
            	item_validacion_detalle.unidad	=	v_unidad			and
            	item_validacion_detalle.detalle	=	v_detalle			and
            	item_validacion_detalle.precio_uni_usd	=	v_precio_uni_usd 	and
            	item_validacion_detalle.precio_uni_bs 	=	v_precio_uni_bs		and
            	item_validacion_detalle.nro			 	= v_nro				and
            	item_validacion_detalle.observaciones 	= v_observaciones		and
            	item_validacion_detalle.tipo_factura	= v_tipo_factura )THEN
                v_bandera_validacion = true;

         END IF;

        END LOOP;
		------insertando la fila del excel si este no existe en base de datos
        
         IF(v_record_data_excel.nro is null)THEN 

            INSERT INTO vef.ttemp_factura_excel(
                                        id_usuario_reg		,		
                                        id_funcionario_usu	,
                                        razon_social 		,
                                        nit 				,
                                        precio_total_usd	,
                                        precio_total_bs		,
                                        centro_costo 		,
                                        clase_costo 		,
                                        nro			 		,
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
                                        codigo_aplicacion	,
                                        nro_factura			,
                                        ncd					,
                                        id_venta_fk		    
                                        
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
                                        v_nro	 		,
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
                                        v_record_aplicacion.codigo,
                                        v_nro_factura			,
                                        TRUE					,
                                        v_record_factura.id_venta
                                        
               ) returning id_factura_excel into v_id_factura_excel;
               
               --la misma fila la primera vez se le considera parte del detalle e insertando la misma
               
               INSERT INTO vef.ttemp_factura_detalle_excel(
                                        id_usuario_reg		,
                                        cantidad_det		,		
                                        unidad				,
                                        detalle				,
                                        precio_uni_usd		,
                                        precio_uni_bs 		,
                                        fecha				,
                                        nro			 		,
                                        observaciones 		,
                                        tipo_factura		,
                                        id_factura_excel_fk	,
                                        id_producto			,
                                        id_venta_detalle_fk ,
                                        nro_factura			,
                                        id_venta_fk			,
                                        codigo_ingas      
                                   )VALUES
                                    (	
                                        v_record_persona.id_usuario		,
                                        v_cantidad_det			,		
                                        v_unidad				,
                                        v_detalle				,
                                        v_precio_uni_usd		,
                                        v_precio_uni_bs 		,
                                        v_fecha					,
                                        v_nro		 			,
                                        v_observaciones 		,                                                                
                                        v_tipo_factura			,
                                        v_id_factura_excel		,
                                        v_record_sucursal_producto.id_sucursal_producto,
                                        v_id_venta_detalle_fk	,
                                        v_nro_factura			,
                                        v_record_factura.id_venta,
                                        v_codigo              
                                        
               )returning id_factura_excel_det into v_id_factura_excel_det;
           
            ELSIF ( v_bandera_validacion = false) THEN
                
             --si el detalle no es repetido se inserta 
             INSERT INTO vef.ttemp_factura_detalle_excel(
                                        id_usuario_reg		,
                                        cantidad_det		,		
                                        unidad				,
                                        detalle				,
                                        precio_uni_usd		,
                                        precio_uni_bs 		,
                                        fecha				,
                                        nro			 		,
                                        observaciones 		,
                                        tipo_factura		,
                                        id_factura_excel_fk	,
                                        id_producto			,
                                        id_venta_detalle_fk ,
                                        nro_factura			,
                                        id_venta_fk			,
                                        codigo_ingas        
                                   )VALUES
                                    (	
                                        v_record_persona.id_usuario	,
                                        v_cantidad_det			,		
                                        v_unidad				,
                                        v_detalle				,
                                        v_precio_uni_usd		,
                                        v_precio_uni_bs 		,
                                        v_fecha					,
                                        v_nro		 			,
                                        v_observaciones 		,                                                                
                                        v_tipo_factura			,
                                        v_record_data_excel.id_factura_excel,
                                        v_record_sucursal_producto.id_sucursal_producto,
                                        v_id_venta_detalle_fk	,
                                        v_nro_factura			,
                                        v_record_factura.id_venta,
                                        v_codigo                
               )returning id_factura_excel_det into v_id_factura_excel_det;
            
            END IF;
           
        	
		RETURN   v_resp;
    	END;
        
     /*********************************
     #TRANSACCION:  'VF_VALINOT_INS'
     #DESCRIPCION:	validacion de los registros de los totales de  excel insertados en la tabla
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

    ELSIF(p_transaccion='VF_VALINOT_INS')THEN
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
                     tffe.nro 		,
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
                     tffe.codigo_aplicacion	,
                     tffe.id_venta_fk
                     			
                FROM vef.ttemp_factura_excel  tffe
                WHERE tffe.venta_generada = FALSE
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
                    ttd.id_temporal_data,
            		ttd.nro,
                    ttd.razon_social,
                    ttd.fecha_reg
            FROM	vef.ttemporal_data ttd
            INTO
            v_record_data_temporal
            WHERE	ttd.nro = v_item.nro and ttd.razon_social= v_item.razon_social and ttd.fecha_reg::date = now()::date and ttd.id_punto_venta = v_item.id_punto_venta  ;
           
           
            --validando precios unitarios de cada uno de los detalles de la nota contra la factura
            	v_bandera_validacion = FALSE;
            	FOR item in(
                	SELECT
                    tfeed.id_venta_detalle_fk,
                    tfeed.nro,
                    tfeed.nro_factura,
                    tfeed.detalle, 
                    tfeed.precio_uni_bs,
                    tfeed.precio_uni_usd
                    FROM vef.ttemp_factura_detalle_excel tfeed
                  )LOOP
                	SELECT
                    	vefd.precio
                    into
                    v_precio_uni_bs
                    FROM vef.tventa_detalle vefd
                    WHERE  vefd.id_venta_detalle = item.id_venta_detalle_fk;
                  	IF item.precio_uni_bs > v_precio_uni_bs  THEN
                    		v_error = 'El precio '||item.precio_uni_bs||' registrado en el excel del producto '||item.detalle||' del nro '||item.nro||' es mayor al precio del producto registrado en la factura '||item.nro_factura||' que es '||v_precio_uni_bs||'';
                          --insertando a la tabla de eliminacion si se encuentra un error en los totales
                          -- no inserta si este ya existe en la tabla el dia de ingreso del excel
                            IF  v_record_data_temporal is null THEN
                            
                             INSERT INTO vef.ttemporal_data(
                                                        nro	,
                                                        razon_social,
                                                        total_venta,
                                                        total_detalle,
                                                        error   ,
                                                        id_punto_venta
                                                   )VALUES
                                                    (	
                                                        v_item.nro,
                                                        v_item.razon_social,
                                                        v_record_precio_total.precio_total_bs,
                                                        v_record_precio_uni_cant.precio_bs,
                                                        v_error,
                                                        v_item.id_punto_venta
                                            
                               )returning id_temporal_data into v_id_temporal_data;
                     
                            END IF;  
                           
                           
                           --Elimina los datos de las tablas si este se encuentra con error  
                            Delete from vef.ttemp_factura_excel
                                where id_factura_excel = v_item.id_factura_excel;
            				
                            Delete from vef.ttemp_factura_detalle_excel
                                where id_factura_excel_fk = v_item.id_factura_excel;
                  
				            	  							                    	
                            END IF;
                    
                    
                    
                END LOOP;

            ------------validando  totales sean igual a la suma de lo detalles
            IF (v_record_precio_total.precio_total_bs <  v_record_precio_uni_cant.precio_bs )THEN
            		v_error = 'El total de la nota es menor a la suma de los detalles';
          		--insertando a la tabla de eliminacion si se encuentra un error en los totales
                -- no inserta si este ya existe en la tabla el dia de ingreso del excel
                  IF  v_record_data_temporal is null THEN
                  
                   INSERT INTO vef.ttemporal_data(
                                              nro				,
                                              razon_social		,
                                              total_venta		,
                                              total_detalle		,
                                              error             ,
                                              id_punto_venta
                                         )VALUES
                                          (	
                                              v_item.nro		,
                                              v_item.razon_social,
                                              v_record_precio_total.precio_total_bs,
                                              v_record_precio_uni_cant.precio_bs	,
                                              v_error,
                                              v_item.id_punto_venta			
                                             
                     )returning id_temporal_data into v_id_temporal_data;
                   ELSE  
                     UPDATE vef.ttemporal_data SET
                            total_venta	= v_record_precio_total.precio_total_bs	,
                           total_detalle = v_record_precio_uni_cant.precio_bs	,
                           error = v_error
                    WHERE id_temporal_data = v_record_data_temporal.id_temporal_data; 
                   END IF;  
                 --Elimina de las tablas si este se encuentra con error  
                Delete from vef.ttemp_factura_excel
 					where id_factura_excel = v_item.id_factura_excel;
				
                Delete from vef.ttemp_factura_detalle_excel
 					where id_factura_excel_fk = v_item.id_factura_excel;
                                   
            ELSIF(v_record_precio_total.precio_total_bs >  v_record_precio_uni_cant.precio_bs )THEN
			  	v_error = 'El total de la nota es mayor a la suma de los detalles';
              	--insertando a la tabla de eliminacion si se encuentra un error en los totales 
                -- no inserta si este ya existe en la tabla el dia de ingreso del excel
                 IF v_record_data_temporal is  null THEN
                  INSERT INTO vef.ttemporal_data(
                                            nro				,
                                            razon_social	,
                                            total_venta		,
                                            total_detalle	,
                                            error           ,
                                            id_punto_venta
                                       )VALUES
                                        (	
                                            v_item.nro		,
                                            v_item.razon_social,
                                            v_record_precio_total.precio_total_bs,
                                            v_record_precio_uni_cant.precio_bs	,
                                            v_error,
                                            v_item.id_punto_venta		
                                           
                   )returning id_temporal_data into v_id_temporal_data;
                 ELSE  
                    UPDATE vef.ttemporal_data SET
                            total_venta	= v_record_precio_total.precio_total_bs	,
                           total_detalle = v_record_precio_uni_cant.precio_bs	,
                           error = v_error
                    WHERE id_temporal_data = v_record_data_temporal.id_temporal_data;
                        
                 END IF;  
               --Elimina de los datos de las tablas si este se encuentra con error 
                 delete from vef.ttemp_factura_excel
 					where id_factura_excel = v_item.id_factura_excel;
                 delete from vef.ttemp_factura_detalle_excel
 					where id_factura_excel_fk = v_item.id_factura_excel;               
                                 
        	END IF;
            
           -------------------------validacion para que no se sobrepase el porcentaje de nota -------------------------------
           --esta validacion se realiza aunque la misma ya existe al insertar la nota por que es necesario para borrar los datos 
           --de las tablas que almacenan los datos erroreneos y poder volver a ingresarlos correctamente 
           v_vef_por_per_ncd =  pxp.f_get_variable_global('vef_porcentaje_permitodo_ncd'); 
           
           --calcula el porcetaje de la factura vinculada
           select 
              vef.total_venta into v_total_venta
           from vef.tventa vef
           where vef.estado_reg = 'activo' and vef.id_venta = v_item.id_venta_fk;
           
           --calcularmos todas las notas de credito
           select 
              sum(vef.total_venta) into v_total_venta_ncd
           from vef.tventa vef
           where vef.estado_reg = 'activo' and vef.id_venta_fk = v_item.id_venta_fk;
           
           IF v_total_venta_ncd is null THEN
           		v_total_venta_ncd = 0;
           END IF;
          
           SELECT
           	sum(tedf.precio_uni_bs)
           into
           	v_precio_total_bs
           FROM vef.ttemp_factura_detalle_excel tedf
           WHERE tedf.id_venta_fk = v_item.id_venta_fk  ;
           
           --si el total de la notas de credito sobre pasa el porcetaje permitido, cargamos un error 
           IF  (v_total_venta_ncd + v_precio_total_bs ) > (v_total_venta * (v_vef_por_per_ncd::numeric))  THEN
           	    v_error = 'El total de NCD no puede superar el '||(v_vef_por_per_ncd::numeric)*100||' %';
                 IF v_record_data_temporal is  null THEN
                    INSERT INTO vef.ttemporal_data(
                                                nro				,
                                                razon_social	,
                                                total_venta		,
                                                total_detalle	,
                                                error           ,
                                                id_punto_venta       			
                                           )VALUES
                                            (	
                                                v_item.nro		,
                                                v_item.razon_social,
                                                v_record_precio_total.precio_total_bs,
                                                v_record_precio_uni_cant.precio_bs,
                                                v_error,
                                                v_item.id_punto_venta	
                                               
                       )returning id_temporal_data into v_id_temporal_data;
                 ELSE
                    UPDATE vef.ttemporal_data SET
                           total_venta	= v_record_precio_total.precio_total_bs	,
                           total_detalle = v_record_precio_uni_cant.precio_bs	,
                           error = v_error
                    WHERE id_temporal_data = v_record_data_temporal.id_temporal_data;
                 END IF;
                delete from vef.ttemp_factura_excel
 				where id_factura_excel = v_item.id_factura_excel;
                delete from vef.ttemp_factura_detalle_excel
 				where id_factura_excel_fk = v_item.id_factura_excel;
            ELSE  
                 DELETE FROM vef.ttemporal_data 
                 WHERE id_temporal_data = v_record_data_temporal.id_temporal_data; 
            END IF;

       END LOOP;
   
       RETURN   v_resp;
    END;

	/*********************************
     #TRANSACCION:  'VF_INSFACNOT_INS'
     #DESCRIPCION:	Insercion de registros en la tabla de ventas como una nota
     #AUTOR:		admin
     #FECHA:		01-06-2015 09:21:07
    ***********************************/

    elsif(p_transaccion='VF_INSFACNOT_INS')then
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
                     tfe.nro		 		,
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
                     tfe.forma_pago			,
                     tfe.aplicacion			,
                     tfe.id_forma_pago		,
                     tfe.codigo_aplicacion	,
                     tfe.nro_factura		,
                     tfe.id_venta_fk		
                     
                     			
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
                                --'id_contrato',
                                'codigo_aplicacion',
                                'id_venta_fk' 
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
                               ---item.id_contrato::varchar,--'id_contrato',
                                item.codigo_aplicacion::varchar,--'codigo_aplicacion',
								item.id_venta_fk::varchar--'id_venta_fk' 
                                ],
                            ARRAY[
                            		'varchar',---'_nombre_usuario_ai',
                                	'integer', -----'_id_usuario_ai',
                          			--'varchar',--'id_cliente',
									'varchar',--'nit',
            						'int4', --'id_sucursal',   
            						'varchar',--'nro_tramite',
            						--'numeric',--'a_cuenta',		
	               					'numeric',--'total_venta',
        							'date',--'fecha_estimada_entrega',
									'int4',--'id_punto_venta'
									'int4',--'id_forma_pago',
									'numeric', --'monto_forma_pago' 
									'varchar', --'numero_tarjeta',
									'varchar', --'codigo_tarjeta',
									'varchar', --'tipo_tarjeta',
            						--'integer', --'porcentaje_descuento', 
            						--'varchar', --'id_vendedor_medico',
									--'numeric', --'comision',
									'text',--'observaciones',
									'varchar',--'tipo_factura',
									--'date',--'fecha',
            						'varchar', --'nro_factura', 
                                    'integer',--'id_dosificacion', 
                                    --'numeric',--'excento',
                                    --'int4',--'id_moneda',
                                    --'numeric',--'tipo_cambio_venta',
                                    --'numeric',--'total_venta_msuc',
                                    'numeric',	--'transporte_fob',		
                                    'numeric',--'seguros_fob',
                                    'numeric',--'otros_fob',
                                    'numeric',--'transporte_cif',
                                    'numeric',--'seguros_cif',
                                    'numeric',--'otros_cif',
                                    'numeric',--'valor_bruto',
                                    'varchar',--'descripcion_bulto',
                                    'varchar',--'id_cliente_destino',
                                    --'varchar',--'hora_estimada_entrega'
                                    --'varchar',--'forma_pedido',	
                                    'varchar',	--'id_proveedor',				
                                    'int4', --'id_centro_costo',
                                  -- 'int4',--'id_contrato'
                                    'varchar',--'codigo_aplicacion',
									'int4'--'id_venta_fk' 
                               ]
                            );
 			  --raise exception '%',v_tabla;
            v_resp = vef.ft_venta_ime(p_administrador,p_id_usuario,v_tabla,v_codigo_trans);
            
            --raise exception '%',v_resp;
            
          	v_id_venta = pxp.f_recupera_clave(v_resp,'id_venta');
            v_id_venta	=  split_part(v_id_venta, '{', 2);
            v_id_venta	=  split_part(v_id_venta, '}', 1);
          
     
            --raise exception 'venta %',v_id_venta;
       
         	
          
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
                        tfed.nro		 		,
                        tfed.observaciones 		,
                        tfed.tipo_factura		,
                        tfed.id_factura_excel_fk,
                        tfed.id_producto		,
                        tfed.id_venta_detalle_fk,
                        tfed.id_venta_fk		
	
                FROM  vef.ttemp_factura_detalle_excel tfed
            	where   tfed.id_factura_excel_fk = item.id_factura_excel
            )LOOP
            /*
            if v_contador = 2 then
            	raise exception 'conteo % ,id venta %, item %' ,item_detalle,v_id_venta,item;
            end if ;*/
            
            --raise exception 'item %' ,item_detalle;

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
						'tipo_factura',
						'id_venta_fk'  
                                    ],
            				ARRAY[	
                          	v_id_venta::varchar,--'id_venta',
                            '',--'id_item',
                            item_detalle.id_venta_detalle_fk::varchar,--'id_producto',
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
                            item_detalle.tipo_factura::varchar,--'tipo_factura'
                            item_detalle.id_venta_fk::varchar--'id_venta_fk'        
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
                                  'varchar',
                                  'int4'
                               ]
                            );
           	 --raise exception 'v_tabla_2 %',p_id_usuario;
            
            v_resp_2 = vef.ft_venta_detalle_ime(p_administrador,p_id_usuario,v_tabla_2,v_codigo_trans_2);
            v_id_venta_det = pxp.f_recupera_clave(v_resp_2,'id_venta_detalle');
            v_id_venta_det	=  split_part(v_id_venta_det, '{', 2);
            v_id_venta_det	=  split_part(v_id_venta_det, '}', 1);
            /*
             if v_contador = 2 then
              raise exception 'venta det %',v_id_venta_det;
             end if;
			*/
            
            
                --crear tabla 
          
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
                             'computarizada'::varchar,--'tipo_factura',
               				item.id_venta_fk::varchar --'id_venta_fk'
                                ],
                            ARRAY[          
                                  'int4',
                                  'varchar',                                 
                                  'int4'
                               ]
                            );
           	 --raise exception 'v_tabla_3 %',p_id_usuario;
		            
            v_resp_3 = vef.ft_venta_ime(p_administrador,p_id_usuario,v_tabla_3,v_codigo_trans_3);
            
              --raise exception 'fin';
            
            UPDATE vef.ttemp_factura_excel 
            SET venta_generada = TRUE
            WHERE id_factura_excel = item.id_factura_excel;
            
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