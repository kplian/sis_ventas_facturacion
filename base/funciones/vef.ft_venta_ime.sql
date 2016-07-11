CREATE OR REPLACE FUNCTION vef.ft_venta_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_venta_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tventa'
 AUTOR: 		 (admin)
 FECHA:	        01-06-2015 05:58:00
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_res					varchar;
	v_parametros           	record;
    v_reg_tipo_desc			record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_venta				integer;
	v_num_tramite			varchar;
    v_id_proceso_wf			integer;
    v_id_estado_wf			integer;
    v_codigo_estado			varchar; 
    v_id_gestion			integer;
    v_codigo_proceso		varchar;
    v_id_tipo_estado		integer;
    v_id_funcionario		integer;
    v_id_usuario_reg		integer;
    v_id_depto				integer;
   
    v_id_estado_wf_ant		integer;
    v_acceso_directo		varchar;
    v_clase					varchar;
    v_parametros_ad			varchar;
    v_tipo_noti				varchar;
    v_titulo				varchar; 
    v_id_estado_actual		integer;
    v_codigo_estado_siguiente varchar;
    v_obs					text;
    v_id_cliente			integer;
    v_venta					record;
    v_suma_fp				numeric;
    v_suma_det				numeric;
    v_registros				record;    
    v_id_sucursal			integer;
    v_cantidad_fp			integer;
    v_acumulado_fp			numeric;
    v_monto_fp				numeric;
    v_a_cuenta				numeric;
    v_fecha_estimada_entrega date;
    vef_estados_validar_fp	varchar;
    v_id_punto_venta			integer;
    v_porcentaje_descuento	integer;
    v_id_vendedor_medico	varchar;
    v_comision				numeric;
    v_id_funcionario_inicio	integer;
    v_codigo_tabla			varchar;
    v_num_ven				varchar;
    v_id_periodo			integer;
    v_tipo_factura			varchar;
    v_fecha					date;
    v_excento				numeric;
    v_id_dosificacion		integer;
    v_nro_factura			integer;
    v_id_actividad_economica	integer[];
    v_dosificacion			record;
    v_tipo_base				varchar;
    v_id_moneda_venta		integer;
    v_id_moneda_suc			integer;
    v_total_venta_ms		numeric;
    v_fecha_venta 			date;
    v_nombre_ae				varchar;
    v_id_activida_economica		integer;
    v_transporte_fob		numeric;    
    v_seguros_fob			numeric;
    v_otros_fob				numeric;
    v_transporte_cif		numeric;
    v_seguros_cif			numeric;
    v_otros_cif				numeric;
    v_tipo_cambio_venta		numeric;
    v_es_fin				varchar;
    v_valor_bruto			numeric;
    v_descripcion_bulto		varchar;	
    
			    
BEGIN

    v_nombre_funcion = 'vef.ft_venta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_VEN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	if(p_transaccion='VF_VEN_INS')then
					
        begin
        --obtener correlativo
        
        select id_periodo into v_id_periodo from
                        param.tperiodo per 
                       where per.fecha_ini <= now()::date 
                         and per.fecha_fin >=  now()::date
                         limit 1 offset 0;
    	
        if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
        	select pv.codigo into v_codigo_tabla
            from vef.tpunto_venta pv
            where id_punto_venta = v_parametros.id_punto_venta;
        else
        	select pv.codigo into v_codigo_tabla
            from vef.tsucursal pv
            where id_sucursal = v_parametros.id_sucursal;
        end if;

        if (pxp.f_existe_parametro(p_tabla,'id_moneda')) then
        	v_id_moneda_venta = v_parametros.id_moneda;
        else
        	select sm.id_moneda into v_id_moneda_venta
        	from vef.tsucursal_moneda sm
        	where sm.id_sucursal = v_parametros.id_sucursal 
			and sm.estado_reg = 'activo' and sm.tipo_moneda = 'moneda_base';
        end if;
        
        if (pxp.f_existe_parametro(p_tabla,'tipo_factura')) then
        	v_tipo_factura = v_parametros.tipo_factura;
        else
        	v_tipo_factura = 'recibo';
        end if;
        
        SELECT tv.tipo_base into v_tipo_base
        from vef.ttipo_venta tv
        where tv.codigo = v_tipo_factura and tv.estado_reg = 'activo';
        
        if (v_tipo_base is null) then
        	raise exception 'No existe un tipo de venta con el codigo: % consulte con el administrador del sistema',v_tipo_factura;
        end if;        
        
        v_excento = 0;
        
        
     
       
        
        if (v_tipo_base = 'recibo') THEN
        	v_fecha = now()::date;
        ELSIF(v_tipo_base = 'manual') then
        
       
         
        	v_fecha = v_parametros.fecha;
            v_nro_factura = v_parametros.nro_factura;
            v_excento = v_parametros.excento;
            v_id_dosificacion = v_parametros.id_dosificacion;
            
            --validaciones de factura manual
            --validar que no exista el mismo nro para la dosificacion
            if (exists(	select 1 
            			from vef.tventa ven
            			where ven.nro_factura = v_parametros.nro_factura::integer and ven.id_dosificacion = v_parametros.id_dosificacion)) then
            	raise exception 'Ya existe el mismo numero de factura en otra venta y con la misma dosificacion. Por favor revise los datos';
            end if;
            
            --validar que el nro de factura no supere el maximo nro de factura de la dosificaiocn
            if (exists(	select 1 
            			from vef.tdosificacion dos
            			where v_parametros.nro_factura::integer > dos.final and dos.id_dosificacion = v_parametros.id_dosificacion)) then
            	raise exception 'El numero de factura supera el maximo permitido para esta dosificacion';
            end if;
            
            --validar que la fecha de factura no sea superior a la fecha limite de emision            
            if (exists(	select 1 
            			from vef.tdosificacion dos
            			where dos.fecha_limite < v_parametros.fecha and dos.id_dosificacion = v_parametros.id_dosificacion)) then
            	raise exception 'La fecha de la factura supera la fecha limite de emision de la dosificacion';
            end if;
        
       ELSE 
        
              IF   v_tipo_factura in ('computarizadaexpo','computarizadaexpomin','computarizadamin')  THEN
                 -- la fecha es abierta
                  v_fecha = v_parametros.fecha;
                    
             ELSE
        	  v_fecha = now()::date;
              v_excento = v_parametros.excento;
            END IF;
            
        end if;
        
      
        
       --raise exception 'pasa ... %',v_tipo_base ;
             
        
        if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
        	v_id_punto_venta = v_parametros.id_punto_venta;
        else
        	v_id_punto_venta = NULL;
        end if;
        
        -- obtener correlativo
         v_num_ven =   param.f_obtener_correlativo(
                  'VEN', 
                   v_id_periodo,-- par_id, 
                   NULL, --id_uo 
                   NULL,    -- id_depto
                   p_id_usuario, 
                   'VEF', 
                   NULL,
                   0,
                   0,
                   (case when v_id_punto_venta is not null then
                   		'vef.tpunto_venta'
                   else
                   		'vef.tsucursal'
                   end),
                   (case when v_id_punto_venta is not null then
                   		v_id_punto_venta
                   else
                   		v_parametros.id_sucursal
                   end),
                   v_codigo_tabla                   
                   );
      
        --fin obtener correlativo
        IF (v_num_ven is NULL or v_num_ven ='') THEN
           raise exception 'No se pudo obtener un numero correlativo para la venta consulte con el administrador';
        END IF;
        
        v_porcentaje_descuento = 0;
        
        --  verificar si existe porcentaje de descuento
        if (pxp.f_existe_parametro(p_tabla,'porcentaje_descuento')) then
            v_porcentaje_descuento = v_parametros.porcentaje_descuento;
        end if;
                  
        v_id_vendedor_medico = NULL;
        if (pxp.f_existe_parametro(p_tabla,'id_vendedor_medico')) then
        	v_id_vendedor_medico = v_parametros.id_vendedor_medico;            
        end if;
        
        if (v_id_punto_venta is not null) then
        	select id_sucursal into v_id_sucursal
        	from vef.tpunto_venta
        	where id_punto_venta = v_id_punto_venta;
        else
	        v_id_sucursal = v_parametros.id_sucursal;
	        
        end if;
       
        
        
        
        if (pxp.f_existe_parametro(p_tabla,'a_cuenta')) then
        	v_a_cuenta = v_parametros.a_cuenta;
        else
        	v_a_cuenta = 0;
        end if;
        
        if (pxp.f_existe_parametro(p_tabla,'comision')) then
        	v_comision = v_parametros.comision;
        else
        	v_comision = 0;
        end if;
        
        if (pxp.f_existe_parametro(p_tabla,'fecha_estimada_entrega')) then
        	v_fecha_estimada_entrega = v_parametros.fecha_estimada_entrega;
        else
        	v_fecha_estimada_entrega = now();
        end if;
        
        
        
        if (pxp.f_is_positive_integer(v_parametros.id_cliente)) THEN
        	v_id_cliente = v_parametros.id_cliente::integer;
            
            update vef.tcliente
            set nit = v_parametros.nit
            where id_cliente = v_id_cliente;
        else
        	INSERT INTO 
              vef.tcliente
            (
              id_usuario_reg,              
              fecha_reg,              
              estado_reg, 
              nombre_factura,
              nit
            )
            VALUES (
              p_id_usuario,
              now(),
              'activo',              
              v_parametros.id_cliente,
              v_parametros.nit
            ) returning id_cliente into v_id_cliente;
        	
        end if;
        --obtener gestion a partir de la fecha actual
        select id_gestion into v_id_gestion
        from param.tgestion
        where gestion = extract(year from now())::integer;
        
        select nextval('vef.tventa_id_venta_seq') into v_id_venta;
        
        v_codigo_proceso = 'VEN-' || v_id_venta;
        	-- inciiar el tramite en el sistema de WF
        
        select f.id_funcionario into  v_id_funcionario_inicio
        from segu.tusuario u
        inner join orga.tfuncionario f on f.id_persona = u.id_persona
        where u.id_usuario = p_id_usuario;
        
       SELECT 
             ps_num_tramite ,
             ps_id_proceso_wf ,
             ps_id_estado_wf ,
             ps_codigo_estado 
          into
             v_num_tramite,
             v_id_proceso_wf,
             v_id_estado_wf,
             v_codigo_estado   
              
        FROM wf.f_inicia_tramite(
             p_id_usuario, 
             v_parametros._id_usuario_ai,
             v_parametros._nombre_usuario_ai,
             v_id_gestion, 
             'VEN', 
             v_id_funcionario_inicio,
             NULL,
             NULL,
             v_codigo_proceso);
             
             
       if (pxp.f_existe_parametro(p_tabla,'transporte_fob')) then
        	v_transporte_fob = v_parametros.transporte_fob;
            v_seguros_fob = v_parametros.seguros_fob;
            v_otros_fob = v_parametros.otros_fob;
            v_transporte_cif = v_parametros.transporte_cif;
            v_seguros_cif = v_parametros.seguros_cif;
            v_otros_cif = v_parametros.otros_cif;
            v_valor_bruto = v_parametros.valor_bruto;
            v_descripcion_bulto = v_parametros.descripcion_bulto;
       end if;
       
       if (pxp.f_existe_parametro(p_tabla,'tipo_cambio_venta')) then 
          v_tipo_cambio_venta = v_parametros.tipo_cambio_venta;
       end if;
       
       
         
            
             
      --Sentencia de la insercion
      insert into vef.tventa(
            id_venta,
			id_cliente,
			id_sucursal,
			id_proceso_wf,
			id_estado_wf,
			estado_reg,
			nro_tramite,
			a_cuenta,			
			fecha_estimada_entrega,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod,
			estado,
			id_punto_venta,
            id_vendedor_medico,
            porcentaje_descuento,
            comision,
            observaciones,
            correlativo_venta,
            tipo_factura,
            fecha,
            nro_factura,
            id_dosificacion,
            excento,
            
            id_moneda,
            transporte_fob,
            seguros_fob,
            otros_fob,
            transporte_cif,
            seguros_cif,
            otros_cif,
            tipo_cambio_venta,
            valor_bruto,
            descripcion_bulto
            
            
          	) values(
            v_id_venta,
			v_id_cliente,
			v_id_sucursal,
			v_id_proceso_wf,
			v_id_estado_wf,
			'activo',
			v_num_tramite,
			v_a_cuenta,			
			v_fecha_estimada_entrega,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null,
			v_codigo_estado,
			v_id_punto_venta,
            v_id_vendedor_medico,
            v_porcentaje_descuento,
            v_comision,
            v_parametros.observaciones,
            v_num_ven,
            v_tipo_factura,
            v_fecha,
            v_nro_factura,
            v_id_dosificacion,
            v_excento,
            
            
            v_id_moneda_venta,            
	        COALESCE(v_transporte_fob,0),
            COALESCE(v_seguros_fob,0),
            COALESCE(v_otros_fob,0),
            COALESCE(v_transporte_cif,0),
            COALESCE(v_seguros_cif,0),
            COALESCE(v_otros_cif,0),
            COALESCE(v_tipo_cambio_venta,0)	,
            COALESCE(v_valor_bruto,0),
            COALESCE(v_descripcion_bulto,'')
            	
			
			) returning id_venta into v_id_venta;
			
			if (v_parametros.id_forma_pago != 0 ) then
				
				insert into vef.tventa_forma_pago(
					usuario_ai,
					fecha_reg,
					id_usuario_reg,
					id_usuario_ai,
					estado_reg,
					id_forma_pago,
					id_venta,
					monto_transaccion,
					monto,
					cambio,
					monto_mb_efectivo,
					numero_tarjeta,
					codigo_tarjeta,
					tipo_tarjeta
				)	
				values(
					v_parametros._nombre_usuario_ai,
					now(),
					p_id_usuario,
					v_parametros._id_usuario_ai,
					'activo',
                    v_parametros.id_forma_pago,
					v_id_venta,   
                    v_parametros.monto_forma_pago,                 
					0,
					0,
					0,
					v_parametros.numero_tarjeta,
					v_parametros.codigo_tarjeta,
					v_parametros.tipo_tarjeta
				);
			end if;
            
            
            
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas almacenado(a) con exito (id_venta'||v_id_venta||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_id_venta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_VEN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VEN_MOD')then

		begin
        	select 
                v.* 
             into 
              v_registros  
             from vef.tventa v 
            where v.id_venta = v_parametros.id_venta;
            
            if (pxp.f_existe_parametro(p_tabla,'id_punto_venta')) then
                v_id_punto_venta = v_parametros.id_punto_venta;
            else
                v_id_punto_venta = NULL;
            end if;
            
            SELECT * into v_venta FROM vef.tventa v where v.id_venta = v_parametros.id_venta;  
        	
			if (v_id_punto_venta is not null) then
	        	select id_sucursal into v_id_sucursal
	        	from vef.tpunto_venta
	        	where id_punto_venta = v_id_punto_venta;
	        else
	        	v_id_sucursal = v_parametros.id_sucursal;
	        end if;
            
            if (pxp.f_existe_parametro(p_tabla,'tipo_factura')) then
                v_tipo_factura = v_parametros.tipo_factura;
            else
                v_tipo_factura = 'recibo';
            end if;
            
            SELECT tv.tipo_base into v_tipo_base
        	from vef.ttipo_venta tv
        	where tv.codigo = v_tipo_factura and tv.estado_reg = 'activo';
            
            if (v_tipo_base is null) then
        		raise exception 'No existe un tipo de venta con el codigo: % consulte con el administrador del sistema',v_tipo_factura;
       		end if; 
            
            v_excento = 0;
            
            if (pxp.f_existe_parametro(p_tabla,'id_moneda')) then
                v_id_moneda_venta = v_parametros.id_moneda;
            else
                select sm.id_moneda into v_id_moneda_venta
                from vef.tsucursal_moneda sm
                where sm.id_sucursal = v_parametros.id_sucursal 
                and sm.estado_reg = 'activo' and sm.tipo_moneda = 'moneda_base';
            end if;
            
            if (pxp.f_existe_parametro(p_tabla,'transporte_fob')) then
                  v_transporte_fob = v_parametros.transporte_fob;
                  v_seguros_fob = v_parametros.seguros_fob;
                  v_otros_fob = v_parametros.otros_fob;
                  v_transporte_cif = v_parametros.transporte_cif;
                  v_seguros_cif = v_parametros.seguros_cif;
                  v_otros_cif = v_parametros.otros_cif;
                  v_descripcion_bulto = v_parametros.descripcion_bulto;
                  v_valor_bruto = v_parametros.valor_bruto;
                  
            end if;
            
             
            if (pxp.f_existe_parametro(p_tabla,'tipo_cambio_venta')) then 
                v_tipo_cambio_venta = v_parametros.tipo_cambio_venta;
            end if;
            
            
            IF(v_tipo_base = 'manual') then
                v_fecha = v_parametros.fecha;
                v_nro_factura = v_parametros.nro_factura;
                v_excento = v_parametros.excento;
                v_id_dosificacion = v_parametros.id_dosificacion;
            
            elsif (v_tipo_base = 'computarizada')  then            
                
                IF   v_tipo_factura in ('computarizadaexpo','computarizadaexpomin','computarizadamin')  THEN
                    v_fecha = v_parametros.fecha;
                    v_nro_factura = v_venta.nro_factura;
                    v_id_dosificacion = v_venta.id_dosificacion;
                    
                ELSE
        	        v_excento = v_parametros.excento;
                END IF;
           
            end if;
            
            /* Lanzar exception al tratar de modificar la fecha de una venta computarizada*/
            if (v_fecha is not null and v_fecha != v_registros.fecha and v_tipo_base = 'computarizada') then
            	raise exception 'No es posible modificar la fecha de una venta computarizada';
            end if;
            
            if (pxp.f_existe_parametro(p_tabla,'a_cuenta')) then
                v_a_cuenta = v_parametros.a_cuenta;
            else
                v_a_cuenta = 0;
            end if;
            
             if (pxp.f_existe_parametro(p_tabla,'comision')) then
                v_comision = v_parametros.comision;
            else
                v_comision = 0;
            end if;
            
            v_porcentaje_descuento = 0;
            --verificar si existe porcentaje de descuento
            if (pxp.f_existe_parametro(p_tabla,'porcentaje_descuento')) then
                v_porcentaje_descuento = v_parametros.porcentaje_descuento;
            end if;
                      
            v_id_vendedor_medico = NULL;
            if (pxp.f_existe_parametro(p_tabla,'id_vendedor_medico')) then
                v_id_vendedor_medico = v_parametros.id_vendedor_medico;            
            end if;
            
            if (pxp.f_existe_parametro(p_tabla,'fecha_estimada_entrega')) then
                v_fecha_estimada_entrega = v_parametros.fecha_estimada_entrega;
            else
                v_fecha_estimada_entrega = now();
            end if;
            
			if (pxp.f_is_positive_integer(v_parametros.id_cliente)) THEN
	        	v_id_cliente = v_parametros.id_cliente::integer;
	            
	            update vef.tcliente
	            set nit = v_parametros.nit
	            where id_cliente = v_id_cliente;
	        else
	        	INSERT INTO 
	              vef.tcliente
	            (
	              id_usuario_reg,              
	              fecha_reg,              
	              estado_reg, 
	              nombre_factura,
	              nit
	            )
	            VALUES (
	              p_id_usuario,
	              now(),
	              'activo',              
	              v_parametros.id_cliente,
	              v_parametros.nit
	            ) returning id_cliente into v_id_cliente;
	        	
	        end if;
	        
	        
	        
			--Sentencia de la modificacion
			update vef.tventa set
              id_cliente = v_id_cliente,
              id_sucursal = v_id_sucursal,
              a_cuenta = v_a_cuenta,
              fecha_estimada_entrega = v_fecha_estimada_entrega,
              id_usuario_mod = p_id_usuario,
              fecha_mod = now(),
              id_usuario_ai = v_parametros._id_usuario_ai,
              usuario_ai = v_parametros._nombre_usuario_ai,
              id_punto_venta = v_id_punto_venta,
              id_vendedor_medico = v_id_vendedor_medico,
              porcentaje_descuento = v_porcentaje_descuento,
              comision = v_comision,
              observaciones = v_parametros.observaciones,
              fecha = (case when v_fecha is null then 
                          fecha 
                      else
                          v_fecha
                      end),
              nro_factura = v_nro_factura,
              id_dosificacion = v_id_dosificacion,
              excento = v_excento,
              
              id_moneda = v_id_moneda_venta,
              transporte_fob = COALESCE(v_transporte_fob,0),
              seguros_fob = COALESCE(v_seguros_fob,0),
              otros_fob = COALESCE(v_otros_fob,0),
              transporte_cif = COALESCE(v_transporte_cif,0),
              seguros_cif = COALESCE(v_seguros_cif,0),
              otros_cif = COALESCE(v_otros_cif,0),
              tipo_cambio_venta = COALESCE(v_tipo_cambio_venta,1),
              valor_bruto = COALESCE(v_valor_bruto,0),
              descripcion_bulto = COALESCE(v_descripcion_bulto,'')
            
            
			where id_venta=v_parametros.id_venta;
            
            
			
			if (v_parametros.id_forma_pago != 0 ) then
				               
                delete from vef.tventa_forma_pago 
                where id_venta = v_parametros.id_venta;
                
				insert into vef.tventa_forma_pago(
					usuario_ai,
					fecha_reg,
					id_usuario_reg,
					id_usuario_ai,
					estado_reg,
					id_forma_pago,
					id_venta,
					monto_transaccion,
					monto,
					cambio,
					monto_mb_efectivo,
                    numero_tarjeta,
					codigo_tarjeta,
					tipo_tarjeta
				)	
				values(
					v_parametros._nombre_usuario_ai,
					now(),
					p_id_usuario,
					v_parametros._id_usuario_ai,
					'activo',
                    v_parametros.id_forma_pago,
					v_parametros.id_venta,
					v_parametros.monto_forma_pago,
					0,
					0,
					0,
                    v_parametros.numero_tarjeta,
					v_parametros.codigo_tarjeta,
					v_parametros.tipo_tarjeta
				);
			end if;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VEN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VEN_ELI')then

		begin
			
            
             select 
                v.* 
             into 
              v_registros  from vef.tventa v 
            where v.id_venta = v_parametros.id_venta;
            
            
            
            IF  v_registros.tipo_factura not in  ('computarizadaexpo','computarizadaexpomin','computarizadamin') THEN
            
              
                
                --Sentencia de la eliminacion
                delete from vef.tventa_forma_pago
                where id_venta=v_parametros.id_venta;
                
                delete from vef.tventa_detalle
                where id_venta=v_parametros.id_venta;
                
                update vef.tventa
                set estado_reg = 'inactivo'
                where id_venta=v_parametros.id_venta;
            ELSE
				v_res = vef.f_anula_venta(p_administrador,p_id_usuario,p_tabla, v_registros.id_proceso_wf,v_registros.id_estado_wf, v_parametros.id_venta);
            
            END IF;   
            
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
	
	/*********************************    
 	#TRANSACCION:  'VF_VEALLFORPA_ELI'
 	#DESCRIPCION:	Eliminacion de formas de pago relacionadas a una venta
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VEALLFORPA_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tventa_forma_pago
            where id_venta=v_parametros.id_venta;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas forma de pago eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
	
	/*********************************    
 	#TRANSACCION:  'VF_VEALLDET_ELI'
 	#DESCRIPCION:	Eliminacion de los detalles relacionados a una venta
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VEALLDET_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from vef.tventa_detalle
            where id_venta=v_parametros.id_venta;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Ventas detalle eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
		
	/*********************************    
 	#TRANSACCION:  'VF_VENVALI_MOD'
 	#DESCRIPCION:	Validacion de montos en una venta
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VENVALI_MOD')then

		begin
        	 vef_estados_validar_fp = pxp.f_get_variable_global('vef_estados_validar_fp');
            --obtener datos de la venta y la moneda base 
			
            select 
                 v.* ,
                 sm.id_moneda as id_moneda_base,
                 m.codigo  as moneda ,
                 v.id_dosificacion as id_dosificacion_venta
              into 
                 v_venta
			from vef.tventa v
			inner join vef.tsucursal suc on suc.id_sucursal = v.id_sucursal
			inner join vef.tsucursal_moneda sm on suc.id_sucursal = sm.id_sucursal and sm.tipo_moneda = 'moneda_base'
			inner join param.tmoneda m on m.id_moneda = sm.id_moneda
            where id_venta = v_parametros.id_venta;
            
            --si es venta de exportacion operamos con la moneda especificada por el usuario
            IF  v_venta.tipo_factura in ('computarizadaexpo','computarizadaexpomin') THEN            
              v_id_moneda_venta = v_venta.id_moneda;             
            ELSE
              v_id_moneda_venta = v_venta.id_moneda_base;              
            END IF;
            
            v_id_moneda_suc = v_venta.id_moneda_base;
            
			
            --si es un estado para validar la forma de pago
            if (v_venta.estado =ANY(string_to_array(vef_estados_validar_fp,',')))then
            	
                select count(*) into v_cantidad_fp
                from vef.tventa_forma_pago
                where id_venta =   v_parametros.id_venta;
    			
                --lo que ya se pago es igual a lo que se tenia a cuenta, suponiendo q esta en la moneda base                
                v_acumulado_fp = v_venta.a_cuenta;
    			
                for v_registros in (select vfp.id_venta_forma_pago, fp.id_moneda,vfp.monto_transaccion
                                    from vef.tventa_forma_pago vfp
                                    inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago								
                                    where vfp.id_venta = v_parametros.id_venta)loop
                    --si la moneda de la forma de pago es distinta a al moneda base de la sucursal convertimos a moneda base
                    
                    if (v_registros.id_moneda != v_id_moneda_venta) then
                    
                        IF  v_venta.tipo_cambio_venta is not null THEN
                          v_monto_fp = param.f_convertir_moneda(v_registros.id_moneda,v_id_moneda_venta,v_registros.monto_transaccion,v_venta.fecha::date,'CUS',2, v_venta.tipo_cambio_venta,'si');
                        ELSE
                          v_monto_fp = param.f_convertir_moneda(v_registros.id_moneda,v_id_moneda_venta,v_registros.monto_transaccion,v_venta.fecha::date,'O',2,NULL,'si');
                        END IF;
                    else
                        v_monto_fp = v_registros.monto_transaccion;
                    end if;
    				--si el monto de una d elas formas de pago es mayor q el total de la venta y la cantidad de formas de pago es mayor a 1 lanzo excepcion
                    if (v_monto_fp >= v_venta.total_venta and v_cantidad_fp > 1) then
                        raise exception 'Se ha definido mas de una forma de pago, pero existe una que supera el valor de la venta(solo se requiere una forma de pago)';
                    end if;
    				
                    update vef.tventa_forma_pago set
                    monto = v_monto_fp,
                    cambio = (case when (v_monto_fp + v_acumulado_fp - v_venta.total_venta) > 0 then
                                (v_monto_fp + v_acumulado_fp - v_venta.total_venta)
                             else
                                0
                             end),
                    monto_mb_efectivo = (case when (v_monto_fp + v_acumulado_fp - v_venta.total_venta) > 0 then
                                            v_monto_fp - (v_monto_fp + v_acumulado_fp - v_venta.total_venta)
                                         else
                                            v_monto_fp
                                         end)
                    where id_venta_forma_pago = v_registros.id_venta_forma_pago;
                    v_acumulado_fp = v_acumulado_fp + v_monto_fp;
                end loop;
    			
                select sum(round(monto_mb_efectivo,2)) into v_suma_fp
                from vef.tventa_forma_pago
                where id_venta =   v_parametros.id_venta;
                
                select sum(round(cantidad*precio,2)) into v_suma_det
                from vef.tventa_detalle
                where id_venta =   v_parametros.id_venta;
                
                
               
                IF v_parametros.tipo_factura != 'computarizadaexpo' THEN
                   v_suma_det = COALESCE(v_suma_det,0) + COALESCE(v_venta.transporte_fob ,0)  + COALESCE(v_venta.seguros_fob ,0)+ COALESCE(v_venta.otros_fob ,0) + COALESCE(v_venta.transporte_cif ,0) +  COALESCE(v_venta.seguros_cif ,0) + COALESCE(v_venta.otros_cif ,0);
                END IF;
             
             
                
                if (v_suma_fp < v_venta.total_venta) then
                    raise exception 'El importe recibido es menor al valor de la venta, falta %', v_venta.total_venta - v_suma_fp;
                end if;
                
                if (v_suma_fp > v_venta.total_venta) then
                    raise exception 'El total de la venta no coincide con la división por forma de pago%',v_suma_fp;
                end if;
                
                if (v_suma_det != v_venta.total_venta) then
                    raise exception 'El total de la venta no coincide con la suma de los detalles';
                end if;
            end if;
            
            select sum(cambio) into v_suma_fp
            from vef.tventa_forma_pago
            where id_venta =   v_parametros.id_venta;
            
      
            
            --calcula el total de la venta en moenda de la sucursal
            
             IF  v_venta.tipo_cambio_venta is not null THEN
                 v_total_venta_ms = param.f_convertir_moneda(v_id_moneda_venta,v_id_moneda_suc,v_venta.total_venta,v_venta.fecha,'CUS',2, v_venta.tipo_cambio_venta,'si');
             ELSE
                v_total_venta_ms = param.f_convertir_moneda(v_id_moneda_venta,v_id_moneda_suc,v_registros.monto_transaccion,v_venta.fecha::date,'O',2,NULL,'si');
             END IF;
            
            update vef.tventa v set
              total_venta_msuc = v_total_venta_ms
            where v.id_venta = v_parametros.id_venta;
            
            --si es factura comercial de exportacion generamos el numero de factura y validamos la fecha
            IF  v_venta.tipo_factura in ('computarizadaexpo','computarizadaexpomin','computarizadamin') THEN 
                    IF  v_venta.tipo_factura in ('computarizadaexpo','computarizadaexpomin') THEN
                    	update vef.tventa v set
			              excento = total_venta_msuc
			            where v.id_venta = v_parametros.id_venta;
                    END IF;
                    -- si es eidicion ya tendremos un numeor de factura que no debemos cambiar
                    IF  v_venta.nro_factura is null THEN
                    
                    
                      
                          if (EXISTS(	select 1
                                          from vef.tventa v
                                          where v.fecha > v_venta.fecha and v.tipo_factura = v_venta.tipo_factura 
                                          and v.estado != 'anulado'
                                          and v.estado_reg = 'activo'))THEN
                              raise exception 'Existen facturas emitidas con fechas posterior a la registrada (%). Por favor revise la fecha y hora del sistema',v_fecha;
                          end if;
                          
                            
                           
                           select array_agg(distinct cig.id_actividad_economica) into v_id_actividad_economica
                           from vef.tventa_detalle vd
                           inner join vef.tsucursal_producto sp on vd.id_sucursal_producto = sp.id_sucursal_producto
                           inner join param.tconcepto_ingas cig on  cig.id_concepto_ingas = sp.id_concepto_ingas
                           where vd.id_venta = v_venta.id_venta and vd.estado_reg = 'activo';
                  
                       
                      
                          select d.* into v_dosificacion
                          from vef.tdosificacion d
                          where d.estado_reg = 'activo' and d.fecha_inicio_emi <= v_venta.fecha and
                            d.fecha_limite >= v_venta.fecha and d.tipo = 'F' and d.tipo_generacion = 'computarizada' and
                            d.id_sucursal = v_venta.id_sucursal and
                            d.id_activida_economica @> v_id_actividad_economica FOR UPDATE;
                            
                           v_nro_factura = v_dosificacion.nro_siguiente;
                           
                           
                           if (v_dosificacion is null) then
                              raise exception 'No existe una dosificacion activa para emitir la factura';
                            end if;
                           --validar que el nro de factura no supere el maximo nro de factura de la dosificaiocn
                           if (exists(	select 1 
                                      from vef.tventa ven
                                      where ven.nro_factura =  v_nro_factura and ven.id_dosificacion = v_dosificacion.id_dosificacion)) then
                              raise exception 'El numero de factura ya existe para esta dosificacion. Por favor comuniquese con el administrador del sistema';
                           end if;
                           
                        
                           update vef.tventa v set
                             nro_factura = v_nro_factura,
                             id_dosificacion = v_dosificacion.id_dosificacion
                           where v.id_venta = v_parametros.id_venta;
                            
                           update vef.tdosificacion 
                            set nro_siguiente = nro_siguiente + 1
                           where id_dosificacion = v_dosificacion.id_dosificacion;
			   			
                    		
                   
                   ELSE
                          --validar que la actividad economica no varie con respecto la insertada inicialmente que la fecha n   
                          
                           
                          
                          select 
                              *
                          into
                             v_dosificacion
                          from vef.tdosificacion dos
                          where dos.id_dosificacion = v_venta.id_dosificacion_venta;
                          
                          
                          
                          IF exists(select 1
                               from vef.tventa_detalle vd
                               inner join vef.tsucursal_producto sp on vd.id_sucursal_producto = sp.id_sucursal_producto
                               inner join param.tconcepto_ingas cig on  cig.id_concepto_ingas = sp.id_concepto_ingas
                               where vd.id_venta = v_venta.id_venta and vd.estado_reg = 'activo'
                                     AND  cig.id_actividad_economica != ANY(v_dosificacion.id_activida_economica)
                               
                               ) THEN
                               
                               
                               raise exception 'El nro de facura fue generado para la actividad economica: no puede introducir otros conceptos pertenecientes a otra actividad';
                               
                          END IF;
                           
                    END IF;
                   
                   
                  
                   --si es factura de exportacion minera insertamos descripcion por defecto
                    IF   v_venta.tipo_factura in ('computarizadaexpomin','computarizadamin') THEN
                       
                    
                    
                      FOR v_reg_tipo_desc in (select 
                                                 td.*
                                              from vef.ttipo_descripcion td 
                                              where td.id_sucursal = v_venta.id_sucursal and td.estado_reg = 'activo') LOOP
                                              
                           
                                 --si el valor no exite lo insertamos
                                 IF  not exists (select 1 from vef.tvalor_descripcion vd 
                                 				where vd.id_tipo_descripcion =   v_reg_tipo_desc.id_tipo_descripcion
                                                       and vd.id_venta  =  v_venta.id_venta)   THEN
                                 
                                 
                                     INSERT INTO  vef.tvalor_descripcion
                                                  (
                                                    id_usuario_reg,                                                   
                                                    fecha_reg,                                                   
                                                    estado_reg,                                                    
                                                    id_venta,
                                                    id_tipo_descripcion
                                                  )
                                                  VALUES (
                                                    p_id_usuario,                                                    
                                                    now(),                                                   
                                                    'activo',              
                                                    v_venta.id_venta,
                                                    v_reg_tipo_desc.id_tipo_descripcion
                                                  );
                                       
                                 
                                 END IF;
                      
                      
                      END LOOP;
                    
                    END IF;
            
            
            END IF;
            if (pxp.f_get_variable_global('vef_integracion_lcv') = 'si') then
                v_res = vef.f_inserta_lcv(p_administrador,p_id_usuario,p_tabla,'INS',v_parametros.id_venta);
            end if;	 
             
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Venta Validada'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);
            	
            if (v_venta.estado =ANY(string_to_array(vef_estados_validar_fp,',')) and v_suma_fp > 0)then
            	v_resp = pxp.f_agrega_clave(v_resp,'cambio',(v_suma_fp::varchar || ' ' || v_venta.moneda)::varchar);
            end if; 
             
            --Devuelve la respuesta
            return v_resp;

		end;
    
    /*********************************    
 	#TRANSACCION:  'VEF_ANTEVE_IME'
 	#DESCRIPCION:	Transaccion utilizada  pasar a  estados anterior en la venta
                    segun la operacion definida
 	#AUTOR:		JRR	
 	#FECHA:		17-10-2014 12:12:51
	***********************************/

	elseif(p_transaccion='VEF_ANTEVE_IME')then   
        begin
        
        --------------------------------------------------
        --Retrocede al estado inmediatamente anterior
        -------------------------------------------------
       --recuperaq estado anterior segun Log del WF
          SELECT  
             ps_id_tipo_estado,
             ps_id_funcionario,
             ps_id_usuario_reg,
             ps_id_depto,
             ps_codigo_estado,
             ps_id_estado_wf_ant
          into
             v_id_tipo_estado,
             v_id_funcionario,
             v_id_usuario_reg,
             v_id_depto,
             v_codigo_estado,
             v_id_estado_wf_ant 
          FROM wf.f_obtener_estado_ant_log_wf(v_parametros.id_estado_wf);
          --
          select 
               ew.id_proceso_wf 
            into 
               v_id_proceso_wf
          from wf.testado_wf ew
          where ew.id_estado_wf= v_id_estado_wf_ant;          
          
         --configurar acceso directo para la alarma   
             v_acceso_directo = '';
             v_clase = '';
             v_parametros_ad = '';
             v_tipo_noti = 'notificacion';
             v_titulo  = 'Notificacion';
             
          -- registra nuevo estado                      
          v_id_estado_actual = wf.f_registra_estado_wf(
              v_id_tipo_estado, 
              v_id_funcionario, 
              v_parametros.id_estado_wf, 
              v_id_proceso_wf, 
              p_id_usuario,
              v_parametros._id_usuario_ai,
              v_parametros._nombre_usuario_ai,
              v_id_depto,
              '[RETROCESO] '|| v_parametros.obs,
              v_acceso_directo,
              v_clase,
              v_parametros_ad,
              v_tipo_noti,
              v_titulo);
               
            IF  vef.f_fun_regreso_venta_wf(p_id_usuario, 
           									v_parametros._id_usuario_ai, 
                                            v_parametros._nombre_usuario_ai, 
                                            v_id_estado_actual, 
                                            v_id_proceso_wf, 
                                            v_codigo_estado) THEN
                                            
          END IF; 
                         
         -- si hay mas de un estado disponible  preguntamos al usuario
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');
                             
          --Devuelve la respuesta
            return v_resp;
        end;
    /*********************************    
 	#TRANSACCION:  'VEF_SIGEVE_IME'
 	#DESCRIPCION:	funcion que controla el cambio al Siguiente estado de las ventas, integrado  con el WF
 	#AUTOR:		JRR	
 	#FECHA:		17-10-2014 12:12:51
	***********************************/

	elseif(p_transaccion='VEF_SIGEVE_IME')then   
        
        begin
        	
           /*   PARAMETROS
           
          $this->setParametro('id_proceso_wf_act','id_proceso_wf_act','int4');
          $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
          $this->setParametro('id_funcionario_wf','id_funcionario_wf','int4');
          $this->setParametro('id_depto_wf','id_depto_wf','int4');
          $this->setParametro('obs','obs','text');
          $this->setParametro('json_procesos','json_procesos','text');
          */        
           
          select 
            ew.id_tipo_estado ,            
            ew.id_estado_wf
           into 
            v_id_tipo_estado,            
            v_id_estado_wf
            
          from wf.testado_wf ew
          inner join wf.ttipo_estado te on te.id_tipo_estado = ew.id_tipo_estado
          where ew.id_estado_wf =  v_parametros.id_estado_wf_act;
          
          select v.*,s.id_entidad,c.nit,tv.tipo_base into v_venta
          from vef.tventa v
          inner join vef.tsucursal s on s.id_sucursal = v.id_sucursal 
          inner join vef.tcliente c on c.id_cliente = v.id_cliente
          inner join vef.ttipo_venta tv on tv.codigo = v.tipo_factura and tv.estado_reg = 'activo'
          where v.id_proceso_wf = v_parametros.id_proceso_wf_act;
          
           -- obtener datos tipo estado
                
              select
                 te.codigo,te.fin
               into
                 v_codigo_estado_siguiente,v_es_fin
              from wf.ttipo_estado te
              where te.id_tipo_estado = v_parametros.id_tipo_estado;
                
             IF  pxp.f_existe_parametro(p_tabla,'id_depto_wf') THEN
                 
               v_id_depto = v_parametros.id_depto_wf;
                
             END IF;
                
             IF  pxp.f_existe_parametro(p_tabla,'obs') THEN
                  v_obs=v_parametros.obs;
             ELSE
                 v_obs='---';
             END IF;
               
             --configurar acceso directo para la alarma   
             v_acceso_directo = '';
             v_clase = '';
             v_parametros_ad = '';
             v_tipo_noti = 'notificacion';
             v_titulo  = 'Visto Bueno';
             
             -- hay que recuperar el supervidor que seria el estado inmediato,...
             v_id_estado_actual =  wf.f_registra_estado_wf(v_parametros.id_tipo_estado, 
                                                             v_parametros.id_funcionario_wf, 
                                                             v_parametros.id_estado_wf_act, 
                                                             v_parametros.id_proceso_wf_act,
                                                             p_id_usuario,
                                                             v_parametros._id_usuario_ai,
                                                             v_parametros._nombre_usuario_ai,
                                                             v_id_depto,
                                                             v_obs,
                                                             v_acceso_directo ,
                                                             v_clase,
                                                             v_parametros_ad,
                                                             v_tipo_noti,
                                                             v_titulo);
                
                   
          IF  vef.f_fun_inicio_venta_wf(p_id_usuario, 
           									v_parametros._id_usuario_ai, 
                                            v_parametros._nombre_usuario_ai, 
                                            v_id_estado_actual, 
                                            v_parametros.id_proceso_wf_act, 
                                            v_codigo_estado_siguiente) THEN
                                            
          END IF;  
          
          if (v_venta.tipo_base = 'computarizada' and v_es_fin = 'si') then
                
                IF v_venta.tipo_factura not in ('computarizadaexpo','computarizadaexpomin','computarizadamin') THEN
                   v_fecha_venta = now()::date;
                   if (EXISTS(	select 1
                                    from vef.tventa v
                                    where v.fecha > v_fecha_venta and v.tipo_factura = 'computarizada' and
                                    v.estado_reg = 'activo' and v.estado = 'finalizado'))THEN
                        raise exception 'Existen facturas emitidas con fechas posterior a la actual. Por favor revise la fecha y hora del sistema';
                   end if;
                ELSE
                   v_fecha_venta = v_venta.fecha;
                   --no validamos la fecha en las facturas de exportacion
                   --por que  valida al insertar la factura, donde se genera el nro de la factura
                END IF;  
                
                select array_agg(distinct cig.id_actividad_economica) into v_id_actividad_economica
                from vef.tventa_detalle vd
                inner join vef.tsucursal_producto sp on vd.id_sucursal_producto = sp.id_sucursal_producto
                inner join param.tconcepto_ingas cig on  cig.id_concepto_ingas = sp.id_concepto_ingas
                where vd.id_venta = v_venta.id_venta and vd.estado_reg = 'activo';
                
                --genera el numero de factura
                
                IF v_venta.tipo_factura not in ('computarizadaexpo','computarizadaexpomin','computarizadamin') THEN
                
                    select d.* into v_dosificacion
                    from vef.tdosificacion d
                    where d.estado_reg = 'activo' and d.fecha_inicio_emi <= v_venta.fecha and
                      d.fecha_limite >= v_venta.fecha and d.tipo = 'F' and d.tipo_generacion = 'computarizada' and
                      d.id_sucursal = v_venta.id_sucursal and
                      d.id_activida_economica @> v_id_actividad_economica FOR UPDATE;
                      
                      v_nro_factura = v_dosificacion.nro_siguiente;
                      
                       
                       
                       if (v_dosificacion is null) then
                          raise exception 'No existe una dosificacion activa para emitir la factura';
                        end if;
                       --validar que el nro de factura no supere el maximo nro de factura de la dosificaiocn
                       if (exists(	select 1 
                                  from vef.tventa ven
                                  where ven.nro_factura =  v_dosificacion.nro_siguiente and ven.id_dosificacion = v_dosificacion.id_dosificacion)) then
                          raise exception 'El numero de factura ya existe para esta dosificacion. Por favor comuniquese con el administrador del sistema';
                       end if;
                       
                       --la factura de exportacion no altera la fecha 
                      update vef.tventa  set 
                        id_dosificacion = v_dosificacion.id_dosificacion,
                        nro_factura = v_nro_factura,
                        fecha = v_fecha_venta,
                        cod_control = pxp.f_gen_cod_control(v_dosificacion.llave,
                                                            v_dosificacion.nroaut,
                                                            v_nro_factura::varchar,
                                                            v_venta.nit,
                                                            to_char(v_fecha_venta,'YYYYMMDD')::varchar,
                                                            round(v_venta.total_venta,0))
                      where id_venta = v_venta.id_venta;
                      
                      
                       update vef.tdosificacion 
                       set nro_siguiente = nro_siguiente + 1
                       where id_dosificacion = v_dosificacion.id_dosificacion;
			
                
                ELSE
                    -- en las facturas de exportacion y minera  el numero se genera al inserta
                     v_nro_factura =  v_venta.nro_factura;
                      
                     select 
                        *
                     into  v_dosificacion 
                     from  vef.tdosificacion d where d.id_dosificacion = v_venta.id_dosificacion;
                     
                    	                       
                      --la factura de exportacion no altera la fecha 
                      update vef.tventa  set 
                        cod_control = pxp.f_gen_cod_control(v_dosificacion.llave,
                                                            v_dosificacion.nroaut,
                                                            v_nro_factura::varchar,
                                                            v_venta.nit,
                                                            to_char(v_fecha_venta,'YYYYMMDD')::varchar,
                                                            round(v_venta.total_venta_msuc,0))
                      where id_venta = v_venta.id_venta;
                
                
                END IF;
          end if;  
          
          --inserta o modifical el libro de ventas
          	if (pxp.f_get_variable_global('vef_integracion_lcv') = 'si' and v_es_fin = 'si') then
				v_res = vef.f_inserta_lcv(p_administrador,p_id_usuario,p_tabla,'FIN',v_venta.id_venta);
			end if;    
          
          -- si hay mas de un estado disponible  preguntamos al usuario
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado de la planilla)'); 
          v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');
          
          
          -- Devuelve la respuesta
          return v_resp;
        
     end;
    
    /*********************************    
 	#TRANSACCION:  'VF_VENANU_MOD'
 	#DESCRIPCION:	Anulacion de Venta
 	#AUTOR:		RAC	
 	#FECHA:		19-02-2013 12:12:51
	***********************************/

	elsif(p_transaccion='VF_VENANU_MOD')then

		begin
          
         
          --obtenemos datos basicos
            select 
            	ven.id_estado_wf,
            	ven.id_proceso_wf,
            	ven.estado,            	
                ven.id_venta,                
                ven.nro_tramite
            into
            	v_registros            
            from vef.tventa ven 
            where ven.id_venta = v_parametros.id_venta;        
            
        
		v_res = vef.f_anula_venta(p_administrador,p_id_usuario,p_tabla, v_registros.id_proceso_wf,v_registros.id_estado_wf, v_parametros.id_venta);
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','venta anulada'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta',v_parametros.id_venta::varchar);
             
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