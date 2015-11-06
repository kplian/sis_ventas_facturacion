CREATE OR REPLACE FUNCTION vef.ft_venta_detalle_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_venta_detalle_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'vef.tventa_detalle'
 AUTOR: 		 (admin)
 FECHA:	        01-06-2015 09:21:07
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_nro_requerimiento    		integer;
	v_parametros           		record;
	v_id_requerimiento     		integer;
	v_resp		            	varchar;
	v_nombre_funcion        	text;
	v_mensaje_error         	text;
	v_id_venta_detalle			integer;
	v_precio					numeric;
	v_sucursal_define_precio	varchar;
	v_id_item_sucursal			integer;
    v_id_venta					integer;
    v_tiene_formula				varchar;
    v_id_formula				integer;
    v_id_item					integer;
    v_id_sucursal_producto		integer;
    
	
			    
BEGIN

    v_nombre_funcion = 'vef.ft_venta_detalle_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_VEDET_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	if(p_transaccion='VF_VEDET_INS')then
					
        begin
        	v_tiene_formula = 'no';
        	if (v_parametros.tipo = 'formula') then
        		v_tiene_formula = 'si';
        		select sum(i.precio_ref*fd.cantidad) into v_precio
        		from vef.tformula_detalle fd
        		inner join alm.titem i on i.id_item = fd.id_item
        		where fd.id_formula = v_parametros.id_producto and fd.estado_reg = 'activo';
        		
        		v_id_formula = v_parametros.id_producto;
        	
        	elsif (v_parametros.tipo = 'servicio' or 
        		(v_parametros.tipo = 'producto_terminado' and pxp.f_get_variable_global('vef_integracion_almacenes') = 'false'))then
        		select sp.precio into v_precio
        		from vef.tsucursal_producto sp 
        		where sp.id_sucursal_producto = v_parametros.id_producto;
        		
        		v_id_sucursal_producto = v_parametros.id_producto;
        	else
        		select tiene_precios_x_sucursal,sp.precio into v_sucursal_define_precio, v_precio
        		from vef.tventa v
        		inner join vef.tsucursal s on s.id_sucursal = v.id_sucursal
        		left join vef.tsucursal_producto sp on sp.id_sucursal = s.id_sucursal 
        		where v.id_venta = v_parametros.id_venta and sp.id_item = v_parametros.id_producto;
        		
        		if (v_sucursal_define_precio = 'si') then
        			if (v_precio is null) then
        				raise exception 'El item seleccionado no tiene precio definido en la sucursal';
        			end if;
        		else
        			select precio_ref into v_precio
        			from alm.titem i
        			where i.id_item = v_parametros.id_producto;
        			if (v_precio is null) then
        				raise exception 'El item seleccionado no tiene precio referencial';
        			end if;
        			
        		
        		end if;  
        		v_id_item = v_id_producto;      		
        	
        	end if;
        	--Sentencia de la insercion
        	insert into vef.tventa_detalle(
			id_venta,
			id_item,
			id_sucursal_producto,
			id_formula,
			tipo,
			estado_reg,
			cantidad,
			precio,
			fecha_reg,
			id_usuario_reg,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.id_venta,
			v_id_item,
			v_id_sucursal_producto,
			v_id_formula,
			v_parametros.tipo,
			'activo',
			v_parametros.cantidad_det,
			v_precio,
			now(),
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_venta_detalle into v_id_venta_detalle;
			
            update vef.tventa
            set total_venta = (select sum(precio * cantidad) from vef.tventa_detalle where id_venta = v_parametros.id_venta),
            tiene_formula = v_tiene_formula
            where id_venta = v_parametros.id_venta;
			
            --Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle de Venta almacenado(a) con exito (id_venta_detalle'||v_id_venta_detalle||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta_detalle',v_id_venta_detalle::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'VF_VEDET_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	elsif(p_transaccion='VF_VEDET_MOD')then

		begin
        	v_tiene_formula = 'no';
			if (v_parametros.tipo = 'formula') then
        		v_tiene_formula = 'si';
        		select sum(i.precio_ref*fd.cantidad) into v_precio
        		from vef.tformula_detalle fd
        		inner join alm.titem i on i.id_item = fd.id_item
        		where fd.id_formula = v_parametros.id_formula and fd.estado_reg = 'activo';
        		
        	
        	elsif (v_parametros.tipo = 'servicio') then
        		select sp.precio into v_precio
        		from vef.tsucursal_producto sp 
        		where sp.id_sucursal_producto = v_parametros.id_sucursal_producto;
        	else
        		select tiene_precios_x_sucursal,sp.precio into v_sucursal_define_precio, v_precio
        		from vef.tventa v
        		inner join vef.tsucursal s on s.id_sucursal = v.id_sucursal
        		left join vef.tsucursal_producto sp on sp.id_sucursal = s.id_sucursal 
        		where v.id_venta = v_parametros.id_venta and sp.id_item = v_parametros.id_item;
        		
        		if (v_sucursal_define_precio = 'si') then
        			if (v_precio is null) then
        				raise exception 'El item seleccionado no tiene precio definido en la sucursal';
        			end if;
        		else
        			select precio_ref into v_precio
        			from alm.titem i
        			where i.id_item = v_parametros.id_item;
        			if (v_precio is null) then
        				raise exception 'El item seleccionado no tiene precio referencial';
        			end if;
        			
        		
        		end if;        		
        	
        	end if;
        	
			--Sentencia de la modificacion
			update vef.tventa_detalle set
			id_venta = v_parametros.id_venta,
			id_item = v_parametros.id_item,
			id_sucursal_producto = v_parametros.id_sucursal_producto,
			id_formula = v_parametros.id_formula,
			tipo = v_parametros.tipo,
			cantidad = v_parametros.cantidad_det,
			precio = v_precio,
			sw_porcentaje_formula = v_parametros.sw_porcentaje_formula,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_venta_detalle=v_parametros.id_venta_detalle;
            
            update vef.tventa
            set total_venta = (select sum(precio * cantidad) from vef.tventa_detalle where id_venta = v_parametros.id_venta),
            tiene_formula = v_tiene_formula
            where id_venta = v_parametros.id_venta;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle de Venta modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta_detalle',v_parametros.id_venta_detalle::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VEDET_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	elsif(p_transaccion='VF_VEDET_ELI')then

		begin
        	select id_venta into v_id_venta
            from vef.tventa_detalle
            where id_venta_detalle = v_parametros.id_venta_detalle;
			
            --Sentencia de la eliminacion
			delete from vef.tventa_detalle
            where id_venta_detalle=v_parametros.id_venta_detalle;
            /*Verificar si todavia existe una formula*/
            v_tiene_formula = 'no';
            if (exists (select 1 from vef.tventa_detalle where id_venta = v_id_venta
            				and tipo = 'formula')) then
           		v_tiene_formula = 'si';
            end if;           
            
            update vef.tventa
            set total_venta = coalesce((select sum(precio * cantidad) from vef.tventa_detalle where id_venta = v_id_venta),0),
            tiene_formula = v_tiene_formula
            where id_venta = v_id_venta;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Detalle de Venta eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_venta_detalle',v_parametros.id_venta_detalle::varchar);
              
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