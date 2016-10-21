CREATE OR REPLACE FUNCTION vef.f_trig_tdosificacion (
)
RETURNS trigger AS
$body$
/**************************************************************************
 SISTEMA DE VENTAS
***************************************************************************
 SCRIPT: 		trig_tdosificacion
 DESCRIPCIÓN: 	Valida informacion registrada en la dosificacion
                
 AUTOR: 		KPLIAN(jrr)
 FECHA:			18-09-2016
 COMENTARIOS:	
***************************************************************************
 HISTORIA DE MODIFICACIONES:
 
***************************************************************************/
--------------------------
-- CUERPO DE LA FUNCIÓN --
--------------------------

--**** DECLARACION DE VARIABLES DE LA FUNCIÓN (LOCALES) ****---


DECLARE

BEGIN

    IF TG_OP = 'INSERT' THEN
    	BEGIN

            if (exists (select 1 from vef.tdosificacion d
                where (d.fecha_inicio_emi, d.fecha_limite) overlaps (NEW.fecha_inicio_emi,NEW.fecha_limite)
                and d.id_sucursal = NEW.id_sucursal and 
                d.id_activida_economica && NEW.id_activida_economica)) then
                raise exception 'Ya existe otra dosificacion que coincide con la sucursal, actividad economica y las fechas seleccionadas';
            end if; 
                   
		END;
     
   ELSIF TG_OP = 'UPDATE' THEN

        BEGIN
        
            if (exists (select 1 from vef.tdosificacion d
                where (d.fecha_inicio_emi, d.fecha_limite) overlaps (NEW.fecha_inicio_emi,NEW.fecha_limite)
                and d.id_sucursal = NEW.id_sucursal and 
                d.id_activida_economica && NEW.id_activida_economica and 
                d.id_dosificacion != NEW.id_dosificacion)) then
                raise exception 'Ya existe otra dosificacion que coincide con la sucursal, actividad economica y las fechas seleccionadas';
            end if; 


        END;
   end if;

  
   RETURN NEW;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;