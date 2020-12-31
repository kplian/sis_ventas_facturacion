/********************************************I-DAUP-AUTOR-SCHEMA-0-31/02/2019********************************************/
--SHEMA : Esquema (CONTA) contabilidad         AUTHOR:Siglas del autor de los scripts' dataupdate000001.txt
/********************************************F-DAUP-AUTOR-SCHEMA-0-31/02/2019********************************************/


/********************************************I-DAUP-MGM-VEF-0-31/12/2020********************************************/

--rollback
---UPDATE vef.tventa_detalle SET descripcion='ARRENDAMIENTO OFICINAS UBICADAS EN LA CALLE COLOMBIA Nº O-0655 ENTRE FALSURI Y SUIPACHA, ZONA NOROESTE, COCHABAMBA. SEGÚN CONTRATO Nº 16/2020, POR EL MES DE DICIEMBRE/202020' WHERE id_venta_detalle =2105;
--commit
UPDATE vef.tventa_detalle SET descripcion='ARRENDAMIENTO OFICINAS UBICADAS EN LA CALLE COLOMBIA Nº O-0655 ENTRE FALSURI Y SUIPACHA, ZONA NOROESTE, COCHABAMBA. SEGÚN CONTRATO Nº 16/2020, POR EL MES DE DICIEMBRE/2020' WHERE id_venta_detalle =2105;
/********************************************F-DAUP-MGM-VEF-0-31/12/2020********************************************/