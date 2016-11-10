--------------- SQL ---------------

CREATE OR REPLACE FUNCTION vef.trig_tvalor_descripcion (
)
RETURNS trigger AS
$body$
DECLARE
     v_reg_pres_par 			record;
     v_reg_pres_par_new			record;
     v_reg						record;
     v_nombre					varchar;
    
BEGIN
   
   
   
    IF TG_OP = 'INSERT' THEN
    
         select 
           td.nombre
         into
           v_nombre
         from vef.ttipo_descripcion td
         where td.id_tipo_descripcion  =  NEW.id_tipo_descripcion;
    
          update vef.tvalor_descripcion v set
             valor_label = v_nombre
          where v.id_valor_descripcion = NEW.id_valor_descripcion;
   
   
     
         
   END IF;   
 
   
   RETURN NULL;
   
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;