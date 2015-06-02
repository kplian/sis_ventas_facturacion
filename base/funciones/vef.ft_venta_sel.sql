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

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
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
            
            select pxp.list(su.id_sucursal::text) into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;
            	v_filtro = '(ewf.id_funcionario='||v_id_funcionario_usuario::varchar||' or ven.id_sucursal in ('||v_sucursales||' )) and ';
            else
            	v_filtro = ' 0 = 0 and ';
            end if;          
            
            
    		--Sentencia de la consulta
			v_consulta:='select
						' || v_select || ',
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
                        suc.nombre	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
				        inner join vef.vcliente cli on cli.id_cliente = ven.id_cliente
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        ' || v_join || '
                        where  ' || v_filtro;
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

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
            
            select pxp.list(su.id_sucursal::text) into v_sucursales
            from vef.tsucursal_usuario su
            where su.id_usuario = p_id_usuario and su.estado_reg = 'activo';
            
            v_select = 'ven.id_venta';
            v_join = 'inner join wf.testado_wf ewf on ewf.id_estado_wf = ven.id_estado_wf';
            
            if p_administrador !=1 then
            	if (v_historico = 'si') then
                	v_select = 'distinct(ven.id_venta)';
                	v_join = 'inner join wf.testado_wf ewf on ewf.id_proceso_wf = ven.id_proceso_wf';
                end if;
            	v_filtro = '(ewf.id_funcionario='||v_id_funcionario_usuario::varchar||' or ven.id_sucursal in ('||v_sucursales||' )) and ';
            else
            	v_filtro = ' 0 = 0 and ';
            end if;
            
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(' || v_select || ')
					    from vef.tventa ven
					    inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
					    inner join vef.vcliente cli on cli.id_cliente = ven.id_cliente
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                        ' || v_join || '
                        where  ' || v_filtro;
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
	/*********************************    
 	#TRANSACCION:  'VF_NOTAVEND_SEL'
 	#DESCRIPCION:	lista el detalle de la nota de venta
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	ELSIF(p_transaccion='VF_NOTAVEND_SEL')then
     				
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
 	#TRANSACCION:  'VF_NOTAVEND_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_NOTAVEND_CONT')then

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
 	#TRANSACCION:  'VF_NOTVEN_SEL'
 	#DESCRIPCION:   Lista de la cabecera de la nota de venta
 	#AUTOR:		admin	
 	#FECHA:		01-06-2015 05:58:00
	***********************************/

	elsif(p_transaccion='VF_NOTVEN_SEL')then
     				
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
                        suc.nombre	
						from vef.tventa ven
						inner join segu.tusuario usu1 on usu1.id_usuario = ven.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ven.id_usuario_mod
				        inner join vef.vcliente cli on cli.id_cliente = ven.id_cliente
                        inner join vef.tsucursal suc on suc.id_sucursal = ven.id_sucursal
                       where  id_venta = '||v_parametros.id_venta::varchar;
			
			
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