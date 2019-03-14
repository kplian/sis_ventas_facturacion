--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.f_crear_cod_cliente (
  p_id_cliente integer
)
RETURNS varchar AS
$body$
/**************************************************************************
 FUNCION: 		vef.f_crear_cod_cliente
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
    
  new_uid text;
  done bool;
  chars text[] := '{0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z}';
  result text := '';
  i integer := 0;
  length INTEGER:= 7;
    
BEGIN
    
    v_nombre_funcion:='vef.f_crear_cod_cliente';
    
    done := false;
    i := 0;
    length = 7;
    
    WHILE NOT done LOOP
        
        new_uid = '';     
        for i in 1..length loop
           new_uid := new_uid || chars[1+random()*(array_length(chars, 1)-1)];
        end loop;
         
        done := NOT exists(SELECT 1 FROM vef.tcliente WHERE codigo=new_uid);
    
    END LOOP;
    
    RETURN new_uid;
    
    

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