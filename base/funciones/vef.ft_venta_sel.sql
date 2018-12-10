--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.ft_venta_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_venta_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tventa'
 AUTOR: 		 (admin)
 FECHA:	        01-06-2015 05:58:00
 COMENTARIOS:	
***************************************************************************

  HISTORIAL DE MODIFICACIONES:

 ISSUE            FECHA:		      AUTOR               DESCRIPCION
 #0              01-06-2015        JRR                 Creacion 
 #2              29/10/2016        RAC                 Se aumenta el cliente destino para la interface del tipo pedido
 #1              08/10/2018        RAC                 Se adicionarn datos de provedor y factura_fk para ETR 
 #				11/10/2018			EGS				   Se aumento el campo id_venta_fk la sentencia de facturas recibos
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_id_funcionario_usuario	integer;
    v_sucursales		varchar;
    v_filtro			varchar;
    v_join				varchar;
    v_select			varchar;
    v_historico			varchar;
    v_join_destino		varchar;
    v_columnas_destino	varchar;
			    
BEGIN

	v_nombre_funcion = 'vef.ft_venta_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_VEN_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	if(p_transaccion='VF_VEN_SEL')then
     				
    	begin
        	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN             
            	v_historico =  v_parametros.historico;            
            ELSE            
            	v_historico = 'no';            
            END IF;
        	
            --obtener funcionario del usuario
            select f.id_funcionario into v_id_funcionario_usuario
            from segu.tusuario u
            inner join segu.tpersona p on p.id_persona = u.id_persona
            inner join orga.tfuncionario f on f.id_persona = p.id_persona
            where u.id_usuario = p_id_usuario;
            
            if (v_id_funcionario_usuario is null) then
            	v_id_funcionario_usuario = -1;
            end if;
            
        select coalesce(pxp.list(su.id_sucursal::text),'-1') into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;
                
                if (v_parametros.tipo_usuario = 'vendedor') then
                  v_filtro = ' (ven.id_usuario_reg='||p_id_usuario::varchar||') and ';
                elsif (v_parametros.tipo_usuario = 'cajero') THEN
                  v_filtro = ' (ewf.id_funcionario='||v_id_funcionario_usuario::varchar||') and ';
                ELSE
                  v_filtro = ' 0 = 0 and ';
                end if;           
            else
            	v_filtro = ' 0 = 0 and ';
            end if; 
            
            
            if v_parametros.tipo_factura = 'pedido' then
               v_join_destino = '	inner join vef.vcliente clides on clides.id_cliente = ven.id_cliente_destino';
               v_columnas_destino = ' clides.nombre_factura as cliente_destino';
            else
               v_join_destino = '';
                v_columnas_destino = ' ''''::varchar as cliente_destino';
            end if;         
            
            
    		--Sentencia de la consulta
			v_consulta:='with forma_pago_temporal as(
					    	select count(*)as cantidad_forma_pago,vfp.id_venta,
					        	pxp.list(fp.id_forma_pago::text) as id_forma_pago, pxp.list(fp.nombre) as forma_pago,
                                sum(monto_transaccion) as monto_transaccion,pxp.list(vfp.numero_tarjeta) as numero_tarjeta,
                                pxp.list(vfp.codigo_tarjeta) as codigo_tarjeta,pxp.list(vfp.tipo_tarjeta) as tipo_tarjeta
					        from vef.tventa_forma_pago vfp
					        inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
					        group by vfp.id_venta        
					    ),
					    medico_usuario as(
					    	select (med.id_medico || ''_medico'')::varchar as id_medico_usuario,med.nombre_completo::varchar as nombre
					        from vef.vmedico med
					      union all
					      select (usu.id_usuario || ''_usuario'')::varchar as id_medico_usuario,usu.desc_persona::varchar as nombre
					      from segu.vusuario usu

					    )
			
						select
                            ' || v_select || ',
                            ven.id_cliente,
                            ven.id_sucursal,
                            ven.id_proceso_wf,
                            ven.id_estado_wf,
                            ven.estado_reg,
                            ven.correlativo_venta,
                            ven.a_cuenta,
                            ven.total_venta,
                            ven.fecha_estimada_entrega,
                            ven.usuario_ai,
                            ven.fecha_reg,
                            ven.id_usuario_reg,
                            ven.id_usuario_ai,
                            ven.id_usuario_mod,
                            ven.fecha_mod,
                            usu1.cuenta as usr_reg,
                            usu2.cuenta as usr_mod,
                            ven.estado,
                            cli.nombre_factura,
                            suc.nombre as nombre_sucursal,
                            cli.nit,
                            puve.id_punto_venta,
                            puve.nombre as nombre_punto_venta,
                            (case when (forpa.cantidad_forma_pago > 1) then
                                0::integer
                            else
                                forpa.id_forma_pago::integer
                            end) as id_forma_pago,
                            (case when (forpa.cantidad_forma_pago > 1) then
                                ''DIVIDIDO''::varchar
                            else
                                forpa.forma_pago::varchar
                            end) as forma_pago,
                            (case when (forpa.cantidad_forma_pago > 1) then
                                0::numeric
                            else
                                forpa.monto_transaccion::numeric
                            end) as monto_forma_pago,
                            
                            (case when (forpa.cantidad_forma_pago > 1) then
                                ''''::varchar
                            else
                                forpa.numero_tarjeta::varchar
                            end) as numero_tarjeta,
                            
                            (case when (forpa.cantidad_forma_pago > 1) then
                                ''''::varchar
                            else
                                forpa.codigo_tarjeta::varchar
                            end) as codigo_tarjeta,
                            
                            (case when (forpa.cantidad_forma_pago > 1) then
                                ''''::varchar
                            else
                                forpa.tipo_tarjeta::varchar
                            end) as tipo_tarjeta,
                            ven.porcentaje_descuento,
                            ven.id_vendedor_medico,
                            ven.comision,
                            ven.observaciones,
                            ven.fecha,
                            ven.nro_factura,
                            ven.excento,
                            ven.cod_control,
                            
                            
                            ven.id_moneda,
                            ven.total_venta_msuc,
                            ven.transporte_fob,
                            ven.seguros_fob,
                            ven.otros_fob,
                            ven.transporte_cif,
                            ven.seguros_cif,
                            ven.otros_cif,
                            ven.tipo_cambio_venta,
                            mon.moneda as desc_moneda,
                            ven.valor_bruto,
                            ven.descripcion_bulto,
                            ven.contabilizable,
                            to_char(ven.hora_estimada_entrega,''HH24:MI'')::varchar,
                            mu.nombre as vendedor_medico,
                            ven.forma_pedido,
                            ven.id_cliente_destino,
                            '||v_columnas_destino||',
                            suc.formato_comprobante,
                            ven.tipo_factura
                        	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
						left join medico_usuario mu on mu.id_medico_usuario = ven.id_vendedor_medico
				        inner join vef.vcliente cli on cli.id_cliente = ven.id_cliente
                        '||v_join_destino||'
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join forma_pago_temporal forpa on forpa.id_venta = ven.id_venta
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        ' || v_join || '
                        where ven.estado_reg = ''activo'' and ' || v_filtro;
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            
            
            --raise notice 'CONSULTA.... %',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VEN_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VEN_CONT')then

		begin
        	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN             
            	v_historico =  v_parametros.historico;            
            ELSE            
            	v_historico = 'no';            
            END IF;
        	--obtener funcionario del usuario
            select f.id_funcionario into v_id_funcionario_usuario
            from segu.tusuario u
            inner join segu.tpersona p on p.id_persona = u.id_persona
            inner join orga.tfuncionario f on f.id_persona = p.id_persona
            where u.id_usuario = p_id_usuario;
            
            if (v_id_funcionario_usuario is null) then
            	v_id_funcionario_usuario = -1;
            end if;
            
        select coalesce(pxp.list(su.id_sucursal::text),'-1') into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;
            	
                if (v_parametros.tipo_usuario = 'vendedor') then
                  v_filtro = ' (ven.id_usuario_reg='||p_id_usuario::varchar||') and ';
                elsif (v_parametros.tipo_usuario = 'cajero') THEN
                  v_filtro = ' (ewf.id_funcionario='||v_id_funcionario_usuario::varchar||') and ';
                ELSE
                  v_filtro = ' 0 = 0 and ';
                end if;
           
            else
            	v_filtro = ' 0 = 0 and ';
            end if;
            
            if v_parametros.tipo_factura = 'pedido' then
               v_join_destino = '	inner join vef.vcliente clides on clides.id_cliente = ven.id_cliente_destino';
            else
               v_join_destino = '';
            end if;
            
			--Sentencia de la consulta de conteo de registros
			v_consulta:='
                      with medico_usuario as(
                                      select (med.id_medico || ''_medico'')::varchar as id_medico_usuario,med.nombre_completo::varchar as nombre
                                      from vef.vmedico med
                                    union all
                                    select (usu.id_usuario || ''_usuario'')::varchar as id_medico_usuario,usu.desc_persona::varchar as nombre
                                    from segu.vusuario usu

                                  )
            		select count(' || v_select || ')
					    from vef.tventa ven
					    inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg                        
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
						left join medico_usuario mu on mu.id_medico_usuario = ven.id_vendedor_medico
					    inner join vef.vcliente cli on cli.id_cliente = ven.id_cliente
                        '||v_join_destino||'
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        ' || v_join || '
                        where ven.estado_reg = ''activo'' and ' || v_filtro;
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
	/*********************************    
 	#TRANSACCION:  'VF_VENCONFBAS_SEL'
 	#DESCRIPCION:	Obtener configuraciones basicas para sistema de ventas
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VENCONFBAS_SEL')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='	select variable, valor
						 	from pxp.variable_global
						 	where variable like ''vef_%'' 
						 union all
						 	select ''sucursales''::varchar,pxp.list(id_sucursal::text)::varchar
						 	from vef.tsucursal_usuario 
						 	where estado_reg = ''activo'' and id_usuario = ' || p_id_usuario || '
						 	and id_sucursal is not null and id_punto_venta is null
						 union all
						 	select ''puntos_venta''::Varchar,pxp.list(id_punto_venta::text)::varchar
						 	from vef.tsucursal_usuario 
						 	where estado_reg = ''activo'' and id_usuario = ' || p_id_usuario || '
						 	and id_sucursal is null and id_punto_venta is not null
                         union all
						 	select ''fecha'',to_char(now(),''DD/MM/YYYY'')::varchar
						 ';
			
			--Definicion de la respuesta		    
			

			--Devuelve la respuesta
			return v_consulta;

		end;
	/*********************************    
 	#TRANSACCION:  'VF_NOTAVENDV_SEL'
 	#DESCRIPCION:	lista el detalle de la nota de venta
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	ELSIF(p_transaccion='VF_NOTAVENDV_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						 
                              vd.id_venta,
                              vd.id_venta_detalle,
                              COALESCE(vd.precio,0) as precio,
                              vd.tipo,
                              vd.cantidad,
                              (vd.cantidad * COALESCE(vd.precio,0)) as precio_total,
                              i.codigo as codigo_nombre,
                              i.nombre as item_nombre,
                              sp.nombre_producto,
                              fo.id_formula,	
                              fd.id_formula_detalle,
                              fd.cantidad as cantidad_df,
                              ifo.nombre as item_nombre_df,
                              fo.nombre as nombre_formula



                            from vef.tventa_detalle vd
                            left join alm.titem i on i.id_item = vd.id_item
                            left join vef.tformula fo on fo.id_formula = vd.id_formula
                            left join vef.vmedico me on me.id_medico = fo.id_medico
                            left join vef.tformula_detalle fd on fd.id_formula = fo.id_formula
                            left join alm.titem ifo on ifo.id_item = fd.id_item
                            left join vef.tsucursal_producto sp on sp.id_sucursal_producto = vd.id_sucursal_producto
                        where  
                               vd.estado_reg = ''activo'' and
                               vd.id_venta = '||v_parametros.id_venta::varchar;
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||' order by vd.id_venta_detalle, fd.id_formula_detalle';

			--Devuelve la respuesta
			return v_consulta;
						
		end;
    /*********************************    
 	#TRANSACCION:  'VF_NOTAVENDV_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_NOTAVENDV_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select
                            count(vd.id_venta_detalle) as total,
                            SUM(vd.cantidad*COALESCE(vd.precio,0)) as suma_total
                         from vef.tventa_detalle vd
                         where  id_venta = '||v_parametros.id_venta::varchar||' 
                              and vd.estado_reg = ''activo''
                          group by vd.id_venta ';
			
			--Definicion de la respuesta		    
			

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************    
 	#TRANSACCION:  'VF_NOTVENV_SEL'
 	#DESCRIPCION:   Lista de la cabecera de la nota de venta
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_NOTVENV_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						ven.id_venta,
						ven.id_cliente,
						ven.id_sucursal,
						ven.id_proceso_wf,
						ven.id_estado_wf,
						ven.estado_reg,
						ven.nro_tramite,
						ven.a_cuenta,
						ven.total_venta,
						ven.fecha_estimada_entrega,
						ven.usuario_ai,
						ven.fecha_reg,
						ven.id_usuario_reg,
						ven.id_usuario_ai,
						ven.id_usuario_mod,
						ven.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        ven.estado,
                        cli.nombre_completo,
                        suc.nombre,
                        suc.direccion,
                        suc.correo,
                        suc.telefono,
                        pxp.f_convertir_num_a_letra(ven.total_venta) as total_string
                        	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
				        inner join vef.vcliente cli on cli.id_cliente = ven.id_cliente
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                       where  id_venta = '||v_parametros.id_venta::varchar;
			
			
			--Devuelve la respuesta
			return v_consulta;
						
		end;
    /*********************************    
 	#TRANSACCION:  'VF_VENREP_SEL'
 	#DESCRIPCION:   Reporte de Recibo o Factura
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VENREP_SEL')then
     				
    	begin
        
             if v_parametros.tipo_factura = 'pedido' then
               v_join_destino = '	inner join vef.vcliente clides on clides.id_cliente = ven.id_cliente_destino';
               v_columnas_destino = ' clides.nombre_factura as cliente_destino, clides.lugar as lugar_destino ';
            else
               v_join_destino = '';
                v_columnas_destino = ' ''''::varchar as cliente_destino,''''::varchar as lugar_destino  ';
            end if;
        
        
        ---- #1 11/10/2018 EGS				   Se aumento el campo id_venta_fk en la sentencia
    		--Sentencia de la consulta
			v_consulta:=' with medico_usuario as(
                                  select (med.id_medico || ''_medico'')::varchar as id_medico_usuario,med.nombre_completo::varchar as nombre
                                  from vef.vmedico med
                                union all
                                select (usu.id_usuario || ''_usuario'')::varchar as id_medico_usuario,usu.desc_persona::varchar as nombre
                                from segu.vusuario usu

                              )
                       select
						en.nombre,
                        suc.direccion,
                        suc.telefono,
                        suc.lugar,
                        lug.nombre as departamento_sucursal,
                        to_char(ven.fecha,''DD/MM/YYYY'')::varchar,
                        ven.correlativo_venta,
                        mon.codigo_internacional as moneda,
                        ven.total_venta,    
                        ven.total_venta - coalesce(ven.excento,0),                                            
                        pxp.f_convertir_num_a_letra(ven.total_venta) as total_venta_literal,
                        ven.observaciones,
                        ven.nombre_factura,
                        suc.nombre_comprobante,
                        ven.nro_factura,
                        dos.nroaut,
                        ven.nit,
                        ven.cod_control,
                        to_char(dos.fecha_limite,''DD/MM/YYYY''),
                        dos.glosa_impuestos,
                        dos.glosa_empresa,
                        en.pagina_entidad,
                        ven.id_venta,
                        ven.id_venta_fk,
                        to_char(now(),''HH24:MI:SS''),
                        en.nit,
                        (select pxp.list(nombre)
                        from vef.tactividad_economica
                        where id_actividad_economica =ANY(dos.id_activida_economica))::varchar,
                        to_char(ven.fecha,''MM/DD/YYYY'')::varchar as fecha_venta_recibo,

                        tc.direccion,
                        ven.tipo_cambio_venta,
                        ven.total_venta_msuc,
                        pxp.f_convertir_num_a_letra(ven.total_venta_msuc) as total_venta_msuc_literal,
                        mven.codigo,
                        mon.moneda,
                        mven.moneda,
                        ven.transporte_fob,
                        ven.seguros_fob,
                        ven.otros_fob,
                        ven.transporte_cif,
                        ven.seguros_cif,
                        ven.otros_cif,
                        (to_char(ven.fecha,''DD'')::integer || '' de '' ||param.f_literal_periodo(to_char(ven.fecha,''MM'')::integer) || '' de '' || to_char(ven.fecha,''YYYY''))::varchar as fecha_literal,
			(select count(*) from vef.ttipo_descripcion td where td.estado_reg = ''activo'' and td.id_sucursal = suc.id_sucursal)::integer as descripciones, 
			ven.estado,
            ven.valor_bruto,
            ven.descripcion_bulto,
            (cli.telefono_celular || '' '' || cli.telefono_fijo)::varchar,
            (to_char(ven.fecha_estimada_entrega,''DD/MM/YYYY'') || '' '' || to_char(ven.hora_estimada_entrega,''HH24:MI''))::varchar,
            ven.a_cuenta,
            mu.nombre::varchar as vendedor_medico,
            ven.nro_tramite,
            tc.codigo as codigo_cliente,
            cli.lugar as lugar_cliente,
            
            '||v_columnas_destino||'
            from vef.tventa ven						
              
              '||v_join_destino||'             
              inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
              inner join param.tentidad en on en.id_entidad = suc.id_entidad
              inner join param.tlugar lug on lug.id_lugar = suc.id_lugar
              inner join vef.tsucursal_moneda sucmon on sucmon.id_sucursal = suc.id_sucursal
                  and sucmon.tipo_moneda = ''moneda_base''
              inner join param.tmoneda mon on mon.id_moneda = sucmon.id_moneda
              inner join param.tmoneda mven on mven.id_moneda = ven.id_moneda
              left join vef.vcliente cli on cli.id_cliente = ven.id_cliente
              left join vef.tcliente tc on tc.id_cliente = cli.id_cliente
              left join vef.tdosificacion dos on dos.id_dosificacion = ven.id_dosificacion
                        left join medico_usuario mu on mu.id_medico_usuario = ven.id_vendedor_medico
             where  id_venta = '||v_parametros.id_venta::varchar;
			
			
           -- raise exception '%', v_consulta;
            
			--Devuelve la respuesta
			return v_consulta;
						
		end;
   /*********************************    
 	#TRANSACCION:  'VF_VENDETREP_SEL'
 	#DESCRIPCION:   Reporte Detalle de Recibo o Factura
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VENDETREP_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='
                        select	
                        id_venta_detalle,
                        id_venta_detalle_fk,											
						(case when vedet.id_item is not null then
							item.nombre
						when vedet.id_sucursal_producto is not null then
							cig.desc_ingas
						when vedet.id_formula is not null then
							form.nombre
						end) as concepto,
                        vedet.cantidad::numeric,   
                        vedet.precio,
                        vedet.precio*vedet.cantidad,
                        um.codigo,
                        cig.nandina,
                        vedet.bruto,
                        vedet.ley,
                        vedet.kg_fino,
                        vedet.descripcion,
                        umcig.codigo as unidad_concepto,
                        sum(vedet.precio*vedet.cantidad) OVER (PARTITION BY vedet.descripcion) as precio_grupo
						from vef.tventa_detalle vedet						
						left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
						left join vef.tformula form on form.id_formula = vedet.id_formula
						left join alm.titem item on item.id_item = vedet.id_item
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
                        left join param.tunidad_medida um on um.id_unidad_medida = vedet.id_unidad_medida
				        left join param.tunidad_medida umcig on umcig.id_unidad_medida = cig.id_unidad_medida			        
                       where  id_venta = '||v_parametros.id_venta::varchar || '
                       order by vedet.descripcion,vedet.id_venta_detalle asc';
				        			        
                      
			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VENDESREP_SEL'
 	#DESCRIPCION:   Reporte Descripciones de Recibo o Factura
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VENDESREP_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='
                        select												
						vd.valor_label,
						td.columna,
						td.fila,
						vd.valor
						from vef.tvalor_descripcion vd						
						inner join vef.ttipo_descripcion td on td.id_tipo_descripcion = vd.id_tipo_descripcion
								        
                       where  vd.id_venta = '||v_parametros.id_venta::varchar||'
                       order by td.columna,td.fila asc';
			
			
			--Devuelve la respuesta
			return v_consulta;
						
		end;
        
    /*********************************    
 	#TRANSACCION:  'VF_VENETR_SEL'
 	#DESCRIPCION:	Consulta de datos para facturas en ETR
 	#AUTOR:		rensi	
 	#FECHA:		25-09-2018 05:58:00
	***********************************/

	ELSEIF(p_transaccion='VF_VENETR_SEL')then
     				
    	begin
        	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN             
            	v_historico =  v_parametros.historico;            
            ELSE            
            	v_historico = 'no';            
            END IF;
        	
            --obtener funcionario del usuario
            select f.id_funcionario into v_id_funcionario_usuario
            from segu.tusuario u
            inner join segu.tpersona p on p.id_persona = u.id_persona
            inner join orga.tfuncionario f on f.id_persona = p.id_persona
            where u.id_usuario = p_id_usuario;
            
            if (v_id_funcionario_usuario is null) then
            	v_id_funcionario_usuario = -1;
            end if;
            
            select coalesce(pxp.list(su.id_sucursal::text),'-1') into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;
                
                if (v_parametros.tipo_usuario = 'vendedor') then
                  v_filtro = ' (ven.id_usuario_reg='||p_id_usuario::varchar||') and ';
                elsif (v_parametros.tipo_usuario = 'cajero') THEN
                  v_filtro = ' (ewf.id_funcionario='||v_id_funcionario_usuario::varchar||') and ';
                ELSE
                  v_filtro = ' 0 = 0 and ';
                end if;           
            else
            	v_filtro = ' 0 = 0 and ';
            end if; 
            
            
            if v_parametros.tipo_factura = 'pedido' then
               v_join_destino = '	inner join vef.vcliente clides on clides.id_cliente = ven.id_cliente_destino';
               v_columnas_destino = ' clides.nombre_factura as cliente_destino';
            else
               v_join_destino = '';
                v_columnas_destino = ' ''''::varchar as cliente_destino';
            end if;         
            
            
    		--Sentencia de la consulta
			v_consulta:='with forma_pago_temporal as(
					    	select count(*)as cantidad_forma_pago,vfp.id_venta,
					        	pxp.list(fp.id_forma_pago::text) as id_forma_pago, pxp.list(fp.nombre) as forma_pago,
                                sum(monto_transaccion) as monto_transaccion,pxp.list(vfp.numero_tarjeta) as numero_tarjeta,
                                pxp.list(vfp.codigo_tarjeta) as codigo_tarjeta,pxp.list(vfp.tipo_tarjeta) as tipo_tarjeta
					        from vef.tventa_forma_pago vfp
					        inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
					        group by vfp.id_venta        
					    ),
					    medico_usuario as(
					    	select (med.id_medico || ''_medico'')::varchar as id_medico_usuario,med.nombre_completo::varchar as nombre
					        from vef.vmedico med
					      union all
					      select (usu.id_usuario || ''_usuario'')::varchar as id_medico_usuario,usu.desc_persona::varchar as nombre
					      from segu.vusuario usu

					    )
			
						select
						' || v_select || ',
						ven.id_proveedor,
						ven.id_sucursal,
						ven.id_proceso_wf,
						ven.id_estado_wf,
						ven.estado_reg,
						ven.correlativo_venta,
						ven.a_cuenta,
						ven.total_venta,
						ven.fecha_estimada_entrega,
						ven.usuario_ai,
						ven.fecha_reg,
						ven.id_usuario_reg,
						ven.id_usuario_ai,
						ven.id_usuario_mod,
						ven.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        ven.estado,
                        cli.desc_proveedor as nombre_factura,
                        suc.nombre as nombre_sucursal,
                        cli.nit,
                        puve.id_punto_venta,
                        puve.nombre as nombre_punto_venta,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::integer
                        else
                        	forpa.id_forma_pago::integer
                        end) as id_forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''DIVIDIDO''::varchar
                        else
                        	forpa.forma_pago::varchar
                        end) as forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::numeric
                        else
                        	forpa.monto_transaccion::numeric
                        end) as monto_forma_pago,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.numero_tarjeta::varchar
                        end) as numero_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.codigo_tarjeta::varchar
                        end) as codigo_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.tipo_tarjeta::varchar
                        end) as tipo_tarjeta,
                        ven.porcentaje_descuento,
                        ven.id_vendedor_medico,
                        ven.comision,
                        ven.observaciones,
                        ven.fecha,
                        ven.nro_factura,
                        ven.excento,
                        ven.cod_control,
                        
                        
                        ven.id_moneda,
                        ven.total_venta_msuc,
                        ven.transporte_fob,
                        ven.seguros_fob,
                        ven.otros_fob,
                        ven.transporte_cif,
                        ven.seguros_cif,
                        ven.otros_cif,
                        ven.tipo_cambio_venta,
                        mon.moneda as desc_moneda,
                        ven.valor_bruto,
                        ven.descripcion_bulto,
                        ven.contabilizable,
                        to_char(ven.hora_estimada_entrega,''HH24:MI'')::varchar,
                        mu.nombre as vendedor_medico,
                        ven.forma_pedido,
                        con.numero as contrato_numero,
                        con.objeto,
                        ven.id_cliente_destino,
                        '||v_columnas_destino||',
                        
                         ven.id_contrato,
                         con.numero::varchar as desc_contrato,
                         ven.id_centro_costo,                         
                         (cc.codigo_cc||'' ''||cc.descripcion_tcc)::varchar as desc_centro_costo,
                         ven.codigo_aplicacion::varchar,
                         suc.formato_comprobante,
                         ven.tipo_factura
                        	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg						
				        inner join param.vproveedor cli on cli.id_proveedor  = ven.id_proveedor
                        '||v_join_destino||'
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join forma_pago_temporal forpa on forpa.id_venta = ven.id_venta
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
						left join medico_usuario mu on mu.id_medico_usuario = ven.id_vendedor_medico
                        left join leg.tcontrato con on con.id_contrato = ven.id_contrato                       
                        left join param.vcentro_costo cc on cc.id_centro_costo = ven.id_centro_costo
                        ' || v_join || '
                        where ven.ncd = ''no'' AND  ven.estado_reg = ''activo'' and ' || v_filtro;
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            
           --  raise exception '%', v_consulta;
            raise notice  'CONSULTA.... %',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VENETR_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_VENETR_CONT')then

		begin
        	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN             
            	v_historico =  v_parametros.historico;            
            ELSE            
            	v_historico = 'no';            
            END IF;
        	--obtener funcionario del usuario
            select f.id_funcionario into v_id_funcionario_usuario
            from segu.tusuario u
            inner join segu.tpersona p on p.id_persona = u.id_persona
            inner join orga.tfuncionario f on f.id_persona = p.id_persona
            where u.id_usuario = p_id_usuario;
            
            if (v_id_funcionario_usuario is null) then
            	v_id_funcionario_usuario = -1;
            end if;
            
        select coalesce(pxp.list(su.id_sucursal::text),'-1') into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;
            	
                if (v_parametros.tipo_usuario = 'vendedor') then
                  v_filtro = ' (ven.id_usuario_reg='||p_id_usuario::varchar||') and ';
                elsif (v_parametros.tipo_usuario = 'cajero') THEN
                  v_filtro = ' (ewf.id_funcionario='||v_id_funcionario_usuario::varchar||') and ';
                ELSE
                  v_filtro = ' 0 = 0 and ';
                end if;
           
            else
            	v_filtro = ' 0 = 0 and ';
            end if;
            
            if v_parametros.tipo_factura = 'pedido' then
               v_join_destino = '	inner join vef.vcliente clides on clides.id_cliente = ven.id_cliente_destino';
            else
               v_join_destino = '';
            end if;
            
			--Sentencia de la consulta de conteo de registros
			v_consulta:='
                      with medico_usuario as(
                                      select (med.id_medico || ''_medico'')::varchar as id_medico_usuario,med.nombre_completo::varchar as nombre
                                      from vef.vmedico med
                                    union all
                                    select (usu.id_usuario || ''_usuario'')::varchar as id_medico_usuario,usu.desc_persona::varchar as nombre
                                    from segu.vusuario usu

                                  )
            		select count(' || v_select || ')
					    from vef.tventa ven
					    inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg 
					    inner join param.vproveedor cli on cli.id_proveedor  = ven.id_proveedor
                        '||v_join_destino||'
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
						left join medico_usuario mu on mu.id_medico_usuario = ven.id_vendedor_medico
                        left join leg.tcontrato con on con.id_contrato = ven.id_contrato                       
                        left join param.vcentro_costo cc on cc.id_centro_costo = ven.id_centro_costo
                        ' || v_join || '
                        where ven.ncd = ''no'' AND  ven.estado_reg = ''activo'' and ' || v_filtro;
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    
    /*********************************    
 	#TRANSACCION:  'VF_VENNCETR_SEL'
 	#DESCRIPCION:	Consulta de datos para notas de credito en ETR
 	#AUTOR:		rensi	
 	#FECHA:		08-10-2018 05:58:00
	***********************************/

	ELSEIF(p_transaccion='VF_VENNCETR_SEL')then
     				
    	begin
        	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN             
            	v_historico =  v_parametros.historico;            
            ELSE            
            	v_historico = 'no';            
            END IF;
        	
            --obtener funcionario del usuario
            select f.id_funcionario into v_id_funcionario_usuario
            from segu.tusuario u
            inner join segu.tpersona p on p.id_persona = u.id_persona
            inner join orga.tfuncionario f on f.id_persona = p.id_persona
            where u.id_usuario = p_id_usuario;
            
            if (v_id_funcionario_usuario is null) then
            	v_id_funcionario_usuario = -1;
            end if;
            
            select coalesce(pxp.list(su.id_sucursal::text),'-1') into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;
                
                if (v_parametros.tipo_usuario = 'vendedor') then
                  v_filtro = ' (ven.id_usuario_reg='||p_id_usuario::varchar||') and ';
                elsif (v_parametros.tipo_usuario = 'cajero') THEN
                  v_filtro = ' (ewf.id_funcionario='||v_id_funcionario_usuario::varchar||') and ';
                ELSE
                  v_filtro = ' 0 = 0 and ';
                end if;           
            else
            	v_filtro = ' 0 = 0 and ';
            end if; 
            
            
            if v_parametros.tipo_factura = 'pedido' then
               v_join_destino = '	inner join vef.vcliente clides on clides.id_cliente = ven.id_cliente_destino';
               v_columnas_destino = ' clides.nombre_factura as cliente_destino';
            else
               v_join_destino = '';
                v_columnas_destino = ' ''''::varchar as cliente_destino';
            end if;         
            
            
    		--Sentencia de la consulta
			v_consulta:='with forma_pago_temporal as(
					    	select count(*)as cantidad_forma_pago,vfp.id_venta,
					        	pxp.list(fp.id_forma_pago::text) as id_forma_pago, pxp.list(fp.nombre) as forma_pago,
                                sum(monto_transaccion) as monto_transaccion,pxp.list(vfp.numero_tarjeta) as numero_tarjeta,
                                pxp.list(vfp.codigo_tarjeta) as codigo_tarjeta,pxp.list(vfp.tipo_tarjeta) as tipo_tarjeta
					        from vef.tventa_forma_pago vfp
					        inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
					        group by vfp.id_venta        
					    ),
					    medico_usuario as(
					    	select (med.id_medico || ''_medico'')::varchar as id_medico_usuario,med.nombre_completo::varchar as nombre
					        from vef.vmedico med
					      union all
					      select (usu.id_usuario || ''_usuario'')::varchar as id_medico_usuario,usu.desc_persona::varchar as nombre
					      from segu.vusuario usu

					    )
			
						select
						' || v_select || ',
						ven.id_proveedor,
						ven.id_sucursal,
						ven.id_proceso_wf,
						ven.id_estado_wf,
						ven.estado_reg,
						ven.correlativo_venta,
						ven.a_cuenta,
						ven.total_venta,
						ven.fecha_estimada_entrega,
						ven.usuario_ai,
						ven.fecha_reg,
						ven.id_usuario_reg,
						ven.id_usuario_ai,
						ven.id_usuario_mod,
						ven.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        ven.estado,
                        cli.desc_proveedor as nombre_factura,
                        suc.nombre as nombre_sucursal,
                        cli.nit,
                        puve.id_punto_venta,
                        puve.nombre as nombre_punto_venta,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::integer
                        else
                        	forpa.id_forma_pago::integer
                        end) as id_forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''DIVIDIDO''::varchar
                        else
                        	forpa.forma_pago::varchar
                        end) as forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::numeric
                        else
                        	forpa.monto_transaccion::numeric
                        end) as monto_forma_pago,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.numero_tarjeta::varchar
                        end) as numero_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.codigo_tarjeta::varchar
                        end) as codigo_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.tipo_tarjeta::varchar
                        end) as tipo_tarjeta,
                        ven.porcentaje_descuento,
                        ven.id_vendedor_medico,
                        ven.comision,
                        ven.observaciones,
                        ven.fecha,
                        ven.nro_factura,
                        ven.excento,
                        ven.cod_control,
                        
                        
                        ven.id_moneda,
                        ven.total_venta_msuc,
                        ven.transporte_fob,
                        ven.seguros_fob,
                        ven.otros_fob,
                        ven.transporte_cif,
                        ven.seguros_cif,
                        ven.otros_cif,
                        ven.tipo_cambio_venta,
                        mon.moneda as desc_moneda,
                        ven.valor_bruto,
                        ven.descripcion_bulto,
                        ven.contabilizable,
                        to_char(ven.hora_estimada_entrega,''HH24:MI'')::varchar,
                        mu.nombre as vendedor_medico,
                        ven.forma_pedido,
                        con.numero as contrato_numero,
                        con.objeto,
                        ven.id_cliente_destino,
                        '||v_columnas_destino||',
                        
                         ven.id_contrato,
                         con.numero::varchar as desc_contrato,
                         ven.id_centro_costo,                         
                         (cc.codigo_cc||'' ''||cc.descripcion_tcc)::varchar as desc_centro_costo,
                         ven.codigo_aplicacion::varchar,
                         
                         ven.id_venta_fk, 
                         vo.nro_factura  as nro_factura_vo,
                         vo.id_dosificacion as id_dosificacion_vo,
                         vodos.nroaut as nroaut_vo,
                         vo.total_venta as total_venta_vo,
                         suc.formato_comprobante,
                         ven.tipo_factura
                        	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg						
				        inner join param.vproveedor cli on cli.id_proveedor  = ven.id_proveedor
                        '||v_join_destino||'
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join forma_pago_temporal forpa on forpa.id_venta = ven.id_venta
                        inner join vef.tventa  vo on vo.id_venta = ven.id_venta_fk
                        inner join vef.tdosificacion vodos on vodos.id_dosificacion = vo.id_dosificacion
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
						left join medico_usuario mu on mu.id_medico_usuario = ven.id_vendedor_medico
                        left join leg.tcontrato con on con.id_contrato = ven.id_contrato                       
                        left join param.vcentro_costo cc on cc.id_centro_costo = ven.id_centro_costo
                        ' || v_join || '
                        where ven.ncd = ''si'' AND ven.estado_reg = ''activo'' and ' || v_filtro;
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            
           --  raise exception '%', v_consulta;
            raise notice  'CONSULTA.... %',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VENNCETR_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00  
	***********************************/

	elsif(p_transaccion='VF_VENNCETR_CONT')then

		begin
        	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN             
            	v_historico =  v_parametros.historico;            
            ELSE            
            	v_historico = 'no';            
            END IF;
        	--obtener funcionario del usuario
            select f.id_funcionario into v_id_funcionario_usuario
            from segu.tusuario u
            inner join segu.tpersona p on p.id_persona = u.id_persona
            inner join orga.tfuncionario f on f.id_persona = p.id_persona
            where u.id_usuario = p_id_usuario;
            
            if (v_id_funcionario_usuario is null) then
            	v_id_funcionario_usuario = -1;
            end if;
            
        select coalesce(pxp.list(su.id_sucursal::text),'-1') into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;
            	
                if (v_parametros.tipo_usuario = 'vendedor') then
                  v_filtro = ' (ven.id_usuario_reg='||p_id_usuario::varchar||') and ';
                elsif (v_parametros.tipo_usuario = 'cajero') THEN
                  v_filtro = ' (ewf.id_funcionario='||v_id_funcionario_usuario::varchar||') and ';
                ELSE
                  v_filtro = ' 0 = 0 and ';
                end if;
           
            else
            	v_filtro = ' 0 = 0 and ';
            end if;
            
            if v_parametros.tipo_factura = 'pedido' then
               v_join_destino = '	inner join vef.vcliente clides on clides.id_cliente = ven.id_cliente_destino';
            else
               v_join_destino = '';
            end if;
            
			--Sentencia de la consulta de conteo de registros
			v_consulta:='
                      with medico_usuario as(
                                      select (med.id_medico || ''_medico'')::varchar as id_medico_usuario,med.nombre_completo::varchar as nombre
                                      from vef.vmedico med
                                    union all
                                    select (usu.id_usuario || ''_usuario'')::varchar as id_medico_usuario,usu.desc_persona::varchar as nombre
                                    from segu.vusuario usu

                                  )
            		select count(' || v_select || ')
					    from vef.tventa ven
					    inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg 
					    inner join param.vproveedor cli on cli.id_proveedor  = ven.id_proveedor
                        '||v_join_destino||'
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
						left join medico_usuario mu on mu.id_medico_usuario = ven.id_vendedor_medico
                        left join leg.tcontrato con on con.id_contrato = ven.id_contrato                       
                        left join param.vcentro_costo cc on cc.id_centro_costo = ven.id_centro_costo
                        ' || v_join || '
                        where ven.ncd = ''si''  AND ven.estado_reg = ''activo'' and ' || v_filtro;
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end; 
        
             
    
	/*********************************    
 	#TRANSACCION:  'VF_FACTCMB_SEL'
 	#DESCRIPCION:	Listado de facturas validadas para combos
 	#AUTOR:		rac	
 	#FECHA:		09-10-2018 05:58:00  
	***********************************/

	elsif(p_transaccion='VF_FACTCMB_SEL')then

		begin
        
             v_consulta='select
                          ven.id_venta,
                          ven.id_proveedor,
                          ven.id_sucursal,
                          ven.total_venta,
                          ven.estado,
                          cli.desc_proveedor as nombre_factura,                      
                          cli.nit,
                          ven.id_moneda,
                          ven.total_venta_msuc,                       
                          ven.tipo_cambio_venta,
                          mon.moneda as desc_moneda,
                          con.numero as contrato_numero,
                          con.objeto,
                          ven.id_contrato,
                          con.numero::varchar as desc_contrato,
                          ven.id_centro_costo,                         
                          (cc.codigo_cc||'' ''||cc.descripcion_tcc)::varchar as desc_centro_costo,
                          ven.codigo_aplicacion::varchar,
                          ven.fecha,
                          ven.nro_factura,
                          dos.nroaut,
                          ven.observaciones
                     
                        	
						from vef.tventa ven						 				
				        inner join param.vproveedor cli on cli.id_proveedor  = ven.id_proveedor                        
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join vef.tdosificacion dos on dos.id_dosificacion = ven.id_dosificacion
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        left join leg.tcontrato con on con.id_contrato = ven.id_contrato                       
                        left join param.vcentro_costo cc on cc.id_centro_costo = ven.id_centro_costo
                       
                        where ven.estado_reg = ''activo'' 
                         AND ven.estado = ''finalizado''  AND ven.ncd = ''no'' and ';
        	
                              
            --Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            
            raise notice '%',v_consulta;

			--Devuelve la respuesta
			return v_consulta;

		end;
        
    /*********************************    
 	#TRANSACCION:  'VF_FACTCMB_CONT'
 	#DESCRIPCION:	Listado de facturas validadas para combos
 	#AUTOR:		rac	
 	#FECHA:		09-10-2018 05:58:00  
	***********************************/

	elsif(p_transaccion='VF_FACTCMB_CONT')then

		begin  
        
          v_consulta='select                        
                          count(ven.id_venta)
						from vef.tventa ven						 				
				        inner join param.vproveedor cli on cli.id_proveedor  = ven.id_proveedor                        
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join vef.tdosificacion dos on dos.id_dosificacion = ven.id_dosificacion
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        left join leg.tcontrato con on con.id_contrato = ven.id_contrato                       
                        left join param.vcentro_costo cc on cc.id_centro_costo = ven.id_centro_costo                       
                        where ven.estado_reg = ''activo'' 
                          AND ven.estado = ''finalizado''  AND ven.ncd = ''no'' and ';
        	
                              
            --Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
              
            --Devuelve la respuesta
			return v_consulta;

		end;
      
     
      /*********************************    
 	#TRANSACCION:  'VF_VENEMISOR_SEL'
 	#DESCRIPCION:	Consulta para listar facturas pendientes de emision 
 	#AUTOR:		rensi	
 	#FECHA:		08-10-2018 05:58:00
	***********************************/

	ELSEIF(p_transaccion='VF_VENEMISOR_SEL')then
     				
    	begin
        	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN             
            	v_historico =  v_parametros.historico;            
            ELSE            
            	v_historico = 'no';            
            END IF;
        	
            --obtener funcionario del usuario
            select f.id_funcionario into v_id_funcionario_usuario
            from segu.tusuario u
            inner join segu.tpersona p on p.id_persona = u.id_persona
            inner join orga.tfuncionario f on f.id_persona = p.id_persona
            where u.id_usuario = p_id_usuario;
            
            if (v_id_funcionario_usuario is null) then
            	v_id_funcionario_usuario = -1;
            end if;
            
            select coalesce(pxp.list(su.id_sucursal::text),'-1') into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;                
               v_filtro = ' (ewf.id_funcionario='||v_id_funcionario_usuario::varchar||') and ';
            else
            	v_filtro = ' 0 = 0 and ';
            end if; 
            
            
          
            v_join_destino = '';
            v_columnas_destino = ' ''''::varchar as cliente_destino';
              
            
            
    		--Sentencia de la consulta
			v_consulta:='with forma_pago_temporal as(
					    	select count(*)as cantidad_forma_pago,vfp.id_venta,
					        	pxp.list(fp.id_forma_pago::text) as id_forma_pago, pxp.list(fp.nombre) as forma_pago,
                                sum(monto_transaccion) as monto_transaccion,pxp.list(vfp.numero_tarjeta) as numero_tarjeta,
                                pxp.list(vfp.codigo_tarjeta) as codigo_tarjeta,pxp.list(vfp.tipo_tarjeta) as tipo_tarjeta
					        from vef.tventa_forma_pago vfp
					        inner join vef.tforma_pago fp on fp.id_forma_pago = vfp.id_forma_pago
					        group by vfp.id_venta        
					    ),
					    medico_usuario as(
					    	select (med.id_medico || ''_medico'')::varchar as id_medico_usuario,med.nombre_completo::varchar as nombre
					        from vef.vmedico med
					      union all
					      select (usu.id_usuario || ''_usuario'')::varchar as id_medico_usuario,usu.desc_persona::varchar as nombre
					      from segu.vusuario usu

					    )
			
						select
						' || v_select || ',
						ven.id_proveedor,
						ven.id_sucursal,
						ven.id_proceso_wf,
						ven.id_estado_wf,
						ven.estado_reg,
						ven.correlativo_venta,
						ven.a_cuenta,
						ven.total_venta,
						ven.fecha_estimada_entrega,
						ven.usuario_ai,
						ven.fecha_reg,
						ven.id_usuario_reg,
						ven.id_usuario_ai,
						ven.id_usuario_mod,
						ven.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        ven.estado,
                        cli.desc_proveedor as nombre_factura,
                        suc.nombre as nombre_sucursal,
                        cli.nit,
                        puve.id_punto_venta,
                        puve.nombre as nombre_punto_venta,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::integer
                        else
                        	forpa.id_forma_pago::integer
                        end) as id_forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''DIVIDIDO''::varchar
                        else
                        	forpa.forma_pago::varchar
                        end) as forma_pago,
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	0::numeric
                        else
                        	forpa.monto_transaccion::numeric
                        end) as monto_forma_pago,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.numero_tarjeta::varchar
                        end) as numero_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.codigo_tarjeta::varchar
                        end) as codigo_tarjeta,
                        
                        (case when (forpa.cantidad_forma_pago > 1) then
                        	''''::varchar
                        else
                        	forpa.tipo_tarjeta::varchar
                        end) as tipo_tarjeta,
                        ven.porcentaje_descuento,
                        ven.id_vendedor_medico,
                        ven.comision,
                        ven.observaciones,
                        ven.fecha,
                        ven.nro_factura,
                        ven.excento,
                        ven.cod_control,
                        
                        
                        ven.id_moneda,
                        ven.total_venta_msuc,
                        ven.transporte_fob,
                        ven.seguros_fob,
                        ven.otros_fob,
                        ven.transporte_cif,
                        ven.seguros_cif,
                        ven.otros_cif,
                        ven.tipo_cambio_venta,
                        mon.moneda as desc_moneda,
                        ven.valor_bruto,
                        ven.descripcion_bulto,
                        ven.contabilizable,
                        to_char(ven.hora_estimada_entrega,''HH24:MI'')::varchar,
                        mu.nombre as vendedor_medico,
                        ven.forma_pedido,
                        con.numero as contrato_numero,
                        con.objeto,
                        ven.id_cliente_destino,
                        '||v_columnas_destino||',
                        
                         ven.id_contrato,
                         con.numero::varchar as desc_contrato,
                         ven.id_centro_costo,                         
                         (cc.codigo_cc||'' ''||cc.descripcion_tcc)::varchar as desc_centro_costo,
                         ven.codigo_aplicacion::varchar,
                         
                         ven.id_venta_fk, 
                         vo.nro_factura  as nro_factura_vo,
                         vo.id_dosificacion as id_dosificacion_vo,
                         vodos.nroaut as nroaut_vo,
                         vo.total_venta as total_venta_vo,
                         suc.formato_comprobante,
                         ven.tipo_factura
                        	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg						
				        inner join param.vproveedor cli on cli.id_proveedor  = ven.id_proveedor
                        '||v_join_destino||'
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        inner join forma_pago_temporal forpa on forpa.id_venta = ven.id_venta
                        left join vef.tventa  vo on vo.id_venta = ven.id_venta_fk
                        left join vef.tdosificacion vodos on vodos.id_dosificacion = vo.id_dosificacion
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
						left join medico_usuario mu on mu.id_medico_usuario = ven.id_vendedor_medico
                        left join leg.tcontrato con on con.id_contrato = ven.id_contrato                       
                        left join param.vcentro_costo cc on cc.id_centro_costo = ven.id_centro_costo
                        ' || v_join || '
                        where ven.estado in (''emision'',''finalizado'',''anulado'') AND    ven.estado_reg = ''activo'' and ' || v_filtro;
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            
           --  raise exception '%', v_consulta;
            raise notice  'CONSULTA.... %',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VENEMISOR_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00  
	***********************************/

	elsif(p_transaccion='VF_VENEMISOR_CONT')then

		begin
        	IF  pxp.f_existe_parametro(p_tabla,'historico') THEN             
            	v_historico =  v_parametros.historico;            
            ELSE            
            	v_historico = 'no';            
            END IF;
        	--obtener funcionario del usuario
            select f.id_funcionario into v_id_funcionario_usuario
            from segu.tusuario u
            inner join segu.tpersona p on p.id_persona = u.id_persona
            inner join orga.tfuncionario f on f.id_persona = p.id_persona
            where u.id_usuario = p_id_usuario;
            
            if (v_id_funcionario_usuario is null) then
            	v_id_funcionario_usuario = -1;
            end if;
            
        select coalesce(pxp.list(su.id_sucursal::text),'-1') into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;
            	
                 v_filtro = ' (ewf.id_funcionario='||v_id_funcionario_usuario::varchar||') and ';
           
            else
            	v_filtro = ' 0 = 0 and ';
            end if;
            
          
               v_join_destino = '';
            
            
			--Sentencia de la consulta de conteo de registros
			v_consulta:='
                      with medico_usuario as(
                                      select (med.id_medico || ''_medico'')::varchar as id_medico_usuario,med.nombre_completo::varchar as nombre
                                      from vef.vmedico med
                                    union all
                                    select (usu.id_usuario || ''_usuario'')::varchar as id_medico_usuario,usu.desc_persona::varchar as nombre
                                    from segu.vusuario usu

                                  )
            		select count(' || v_select || ')
					    from vef.tventa ven
					    inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg 
					    inner join param.vproveedor cli on cli.id_proveedor  = ven.id_proveedor
                        '||v_join_destino||'
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        left join vef.tpunto_venta puve on puve.id_punto_venta = ven.id_punto_venta
                        left join param.tmoneda mon on mon.id_moneda = ven.id_moneda
                        left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
						left join medico_usuario mu on mu.id_medico_usuario = ven.id_vendedor_medico
                        left join leg.tcontrato con on con.id_contrato = ven.id_contrato                       
                        left join param.vcentro_costo cc on cc.id_centro_costo = ven.id_centro_costo
                        ' || v_join || '
                         where ven.estado in (''emision'',''finalizado'',''anulado'') AND    ven.estado_reg = ''activo'' and ' || v_filtro;
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end; 
          
        				
	else
					     
		raise exception 'Transaccion inexistente';
					         
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
