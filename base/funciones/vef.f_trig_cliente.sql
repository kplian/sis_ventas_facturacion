--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.f_trig_cliente (
)
RETURNS trigger AS
$body$
/**************************************************************************
 SISTEMA ENDESIS - SISTEMA DE SEGURIDAD (SSS)
***************************************************************************
 SCRIPT: 		trisg_usuario
 DESCRIPCIÓN: 	genera codigo de cliente si esta habiitado
                de datos
 AUTOR: 		KPLIAN(rac)
 FECHA:			11-11-2016
 COMENTARIOS:	

***************************************************************************/

DECLARE
   v_codigo varchar;
   v_codigo_cliente varchar;
  
BEGIN

   IF TG_OP = 'INSERT' THEN
   
          v_codigo_cliente = pxp.f_get_variable_global('vef_codigo_cliente');
       
        IF v_codigo_cliente = 'true' THEN
   
         v_codigo = vef.f_crear_cod_cliente(8);
         
         update vef.tcliente set
           codigo = v_codigo 
         where id_cliente =  NEW.id_cliente;
       
       END IF;
 
   END IF;
   
   RETURN NULL;
  
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;