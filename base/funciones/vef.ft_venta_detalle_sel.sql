CREATE OR REPLACE FUNCTION vef.ft_venta_detalle_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Ventas
 FUNCION: 		vef.ft_venta_detalle_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'vef.tventa_detalle'
 AUTOR: 		 (admin)
 FECHA:	        01-06-2015 09:21:07
 COMENTARIOS:	
  ***************************************************************************
   HISTORIAL DE MODIFICACIONES:

 ISSUE            FECHA:		      AUTOR               DESCRIPCION
 #0              01-06-2015        JRR                 Creacion 
 #123            10/10/2018        RAC                se adiciona id_venta_detalle_fx para notas de credito sobre ventas	
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'vef.ft_venta_detalle_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'VF_VEDET_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	if(p_transaccion='VF_VEDET_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
                            vedet.id_venta_detalle,
                            vedet.id_venta,
                            (case when vedet.id_item is not null then
                                vedet.id_item
                            when vedet.id_sucursal_producto is not null then
                                vedet.id_sucursal_producto
                            when vedet.id_formula is not null then
                                vedet.id_formula
                            when vedet.id_venta_detalle_fk is not null then    --#123
                                vedet.id_venta_detalle_fk
                            end) as id_producto,
                            vedet.tipo,
                            vedet.estado_reg,
                            vedet.cantidad,
                            vedet.precio_sin_descuento,						
                            vedet.id_usuario_ai,
                            vedet.usuario_ai,
                            vedet.fecha_reg,
                            vedet.id_usuario_reg,
                            vedet.id_usuario_mod,
                            vedet.fecha_mod,
                            usu1.cuenta as usr_reg,
                            usu2.cuenta as usr_mod,
                            round(vedet.precio*vedet.cantidad,2) as precio_total,						
                            (case when vedet.id_item is not null then
                                item.codigo  || '' - '' ||  item.nombre
                            when vedet.id_sucursal_producto is not null then
                                cig.desc_ingas
                            when vedet.id_venta_detalle_fk is not null then  --#123
                                cigvo.desc_ingas
                            when vedet.id_formula is not null then
                                form.nombre
                            end)::varchar as nombre_producto,
                            vedet.porcentaje_descuento,                       
                            (vedet.precio_sin_descuento * vedet.cantidad)::numeric,
                            (case when vedet.id_medico is not null then
                                vedet.id_medico || ''_medico''
                             when vedet.id_vendedor is not null then
                                vedet.id_vendedor || ''_usuario''
                             else
                                NULL
                             end)::varchar as id_vendedor_medico,
                             (case when vedet.id_medico is not null then
                                med.nombre_completo
                             when vedet.id_vendedor is not null then
                                ven.desc_persona
                             else
                                NULL
                             end)::varchar as nombre_vendedor_medico,
                             (case when vedet.id_sucursal_producto is not null then
                                sprod.requiere_descripcion
                              when vedet.id_venta_detalle_fk is not null then  --#123
                                spvo.requiere_descripcion 
                            else
                                ''no''::varchar
                            end) as requiere_descripcion,
                            vedet.descripcion,
                            vedet.bruto,
                            vedet.ley,
                            vedet.kg_fino,
                            um.id_unidad_medida,
                            um.codigo as codigo_unidad_medida,

                            COALESCE(cig.ruta_foto,'''')::varchar as ruta_foto,

                            umcig.codigo as codigo_unidad_cig
						from vef.tventa_detalle vedet
						inner join segu.tusuario usu1 on usu1.id_usuario = vedet.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = vedet.id_usuario_mod
						left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
						left join vef.tformula form on form.id_formula = vedet.id_formula
						left join alm.titem item on item.id_item = vedet.id_item
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
				        left join vef.vmedico med on med.id_medico = vedet.id_medico
                        left join segu.vusuario ven on ven.id_usuario = vedet.id_vendedor
                        left join param.tunidad_medida um on um.id_unidad_medida = vedet.id_unidad_medida
                        left join param.tunidad_medida umcig on umcig.id_unidad_medida = cig.id_unidad_medida
                        left join vef.tventa_detalle vdfo  ON vdfo.id_venta_detalle = vedet.id_venta_detalle_fk    --#123
                        left join vef.tsucursal_producto spvo on spvo.id_sucursal_producto = vdfo.id_sucursal_producto  --#123
                        left join param.tconcepto_ingas cigvo on cigvo.id_concepto_ingas = spvo.id_concepto_ingas --#123
                        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            raise notice  '...%...', v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VEDET_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	elsif(p_transaccion='VF_VEDET_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(vedet.id_venta_detalle)
					    from vef.tventa_detalle vedet
					    inner join segu.tusuario usu1 on usu1.id_usuario = vedet.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = vedet.id_usuario_mod
						left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
						left join vef.tformula form on form.id_formula = vedet.id_formula
						left join alm.titem item on item.id_item = vedet.id_item
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
					    left join vef.tmedico med on med.id_medico = vedet.id_medico
                        left join segu.vusuario ven on ven.id_usuario = vedet.id_vendedor
                        left join param.tunidad_medida umcig on umcig.id_unidad_medida = cig.id_unidad_medida
                        where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
        
    /*********************************    
 	#TRANSACCION:  'VF_VEDETVB_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	elseif(p_transaccion='VF_VEDETVB_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
                            vedet.id_venta_detalle,
                            vedet.id_venta,
                            (case when vedet.id_item is not null then
                                vedet.id_item
                            when vedet.id_sucursal_producto is not null then
                                vedet.id_sucursal_producto
                            when vedet.id_formula is not null then
                                vedet.id_formula
                            end) as id_producto,
                            vedet.tipo,
                            vedet.estado_reg,
                            vedet.cantidad,
                            vedet.precio_sin_descuento,						
                            vedet.id_usuario_ai,
                            vedet.usuario_ai,
                            vedet.fecha_reg,
                            vedet.id_usuario_reg,
                            vedet.id_usuario_mod,
                            vedet.fecha_mod,
                            usu1.cuenta as usr_reg,
                            usu2.cuenta as usr_mod,
                            round(vedet.precio*vedet.cantidad,2) as precio_total,						
                            (case when vedet.id_item is not null then
                                item.codigo  || '' - '' ||  item.nombre
                            when vedet.id_sucursal_producto is not null then
                                cig.desc_ingas
                            when vedet.id_formula is not null then
                                form.nombre
                            end)::varchar as nombre_producto,
                            vedet.porcentaje_descuento,                       
                            (vedet.precio_sin_descuento * vedet.cantidad)::numeric,
                            (case when vedet.id_medico is not null then
                                vedet.id_medico || ''_medico''
                             when vedet.id_vendedor is not null then
                                vedet.id_vendedor || ''_usuario''
                             else
                                NULL
                             end)::varchar as id_vendedor_medico,
                             (case when vedet.id_medico is not null then
                                med.nombre_completo
                             when vedet.id_vendedor is not null then
                                ven.desc_persona
                             else
                                NULL
                             end)::varchar as nombre_vendedor_medico,
                             (case when vedet.id_sucursal_producto is not null then
                                sprod.requiere_descripcion
                            else
                                ''no''::varchar
                            end) as requiere_descripcion,
                            vedet.descripcion,
                            vedet.bruto,
                            vedet.ley,
                            vedet.kg_fino,
                            um.id_unidad_medida,
                            um.codigo as codigo_unidad_medida,
                            COALESCE(cig.ruta_foto,'''')::varchar as ruta_foto,
                            vedet.estado,
                            vedet.obs,
                            vedet.serie
						from vef.tventa_detalle vedet
						inner join segu.tusuario usu1 on usu1.id_usuario = vedet.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = vedet.id_usuario_mod
						left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
						left join vef.tformula form on form.id_formula = vedet.id_formula
						left join alm.titem item on item.id_item = vedet.id_item
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
				        left join vef.vmedico med on med.id_medico = vedet.id_medico
                        left join segu.vusuario ven on ven.id_usuario = vedet.id_vendedor
                        left join param.tunidad_medida um on um.id_unidad_medida = vedet.id_unidad_medida 
                        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'VF_VEDETVB_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 09:21:07
	***********************************/

	elsif(p_transaccion='VF_VEDETVB_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_venta_detalle)
					    from vef.tventa_detalle vedet
					    inner join segu.tusuario usu1 on usu1.id_usuario = vedet.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = vedet.id_usuario_mod
						left join vef.tsucursal_producto sprod on sprod.id_sucursal_producto = vedet.id_sucursal_producto
						left join vef.tformula form on form.id_formula = vedet.id_formula
						left join alm.titem item on item.id_item = vedet.id_item
                        left join param.tconcepto_ingas cig on cig.id_concepto_ingas = sprod.id_concepto_ingas
					    left join vef.tmedico med on med.id_medico = vedet.id_medico
                        left join segu.vusuario ven on ven.id_usuario = vedet.id_vendedor
                        where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;    
    
    /*********************************    
    #TRANSACCION:  'VF_PEDDETCLI_SEL'
    #DESCRIPCION:   Consulta del detalle de productos por pedido por cliente
    #AUTOR:         rcm   
    #FECHA:         13/11/2016
    ***********************************/

    elseif(p_transaccion='VF_PEDDETCLI_SEL')then
                    
        begin
            --Sentencia de la consulta
            v_consulta:='select
                        vent.id_venta, vent.fecha, cli.nombre_completo,
                        pro.producto, vdet.cantidad, vent.id_estado_wf, te.nombre_estado as estado_gral,
                        vdet.estado
                        from vef.tventa_detalle vdet
                        inner join vef.tventa vent
                        on vent.id_venta = vdet.id_venta
                        inner join vef.vcliente cli
                        on cli.id_cliente = vent.id_cliente_destino
                        inner join vef.vproducto pro
                        on pro.id_sucursal_producto = vdet.id_sucursal_producto
                        inner join wf.testado_wf ef
                        on ef.id_estado_wf = vent.id_estado_wf
                        inner join wf.ttipo_estado te
                        on te.id_tipo_estado = ef.id_tipo_estado
                        where ';

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