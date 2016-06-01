--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.f_check_cliente (
  p_id_usuario integer,
  p_nit varchar,
  p_desc_cliente varchar
)
RETURNS integer AS
$body$
/**************************************************************************
 FUNCION: 		vef.f_check_cliente
 DESCRIPCION:   verifica si existe el proveedor, si existe retorna el ID, si no lo creea
 AUTOR: 	    KPLIAN (rac)	
 FECHA:	        12/02/2016
 COMENTARIOS:	
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:		
 FECHA:		
 ***************************************************************************/
DECLARE


    v_resp                      varchar;
    v_nombre_funcion            text;
    v_mensaje_error             text;
    v_reg    		            record;
    
BEGIN
    v_nombre_funcion:='vef.f_check_cliente';
    
    
    -- si tenemos un nit buscamos si existe un cliente
    IF trim(p_nit) !='' and p_nit is not null THEN
        
        select 
          *
        into
         v_reg
        from vef.tcliente p
        where trim(p.nit) = trim(p_nit)
         limit  1 offset 0 ;
        
      IF  v_reg.id_cliente is not null THEN
         return v_reg.id_cliente;
      END IF;
    
    ELSE
          --si no tenemos nit buscamos por el nombre del cliente  
            select 
              *
            into
             v_reg
            from vef.vcliente c
            where trim(lower(c.nombre_factura)) = trim(lower(p_desc_cliente))
            limit  1 offset 0 ;
            
          IF  v_reg.id_cliente is not null THEN
             return v_reg.id_cliente;
          END IF;
    
    END IF;
    
    --  si no tenemos el id_cliente ...  

    RETURN NULL;
    

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