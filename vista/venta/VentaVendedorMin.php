<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (rarteaga)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.VentaVendedorMin = {    
    bsave:false,    
    require:'../../../sis_ventas_facturacion/vista/venta/VentaVendedor.php',
    requireclase:'Phx.vista.VentaVendedor',
    title:'Factura de Exportación',
    nombreVista: 'VentaVendedorMin',
    tipo_factura:'computarizadamin',
    
    
    formUrl: '../../../sis_ventas_facturacion/vista/venta/FormVentaMin.php',
	formClass:'FormVentaMin',
    
    constructor: function(config) {
        this.maestro=config.maestro;  
        Phx.vista.VentaVendedorMin.superclass.constructor.call(this,config);
               
         
    } ,
    arrayDefaultColumHidden:['estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod','excento','cod_control','nroaut'],
    rowExpander: new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<br>',   
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Código de Control:&nbsp;&nbsp;</b> {cod_control}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Nro Autorización:&nbsp;&nbsp;</b> {nroaut}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Importe Excento:&nbsp;&nbsp;</b> {excento}</p>',             
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha de Registro:&nbsp;&nbsp;</b> {fecha_reg:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha Ult. Modificación:&nbsp;&nbsp;</b> {fecha_mod:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Creado por:&nbsp;&nbsp;</b> {usr_reg}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Modificado por:&nbsp;&nbsp;</b> {usr_mod}</p><br>'
            )
    }),
    
    tabsouth:[
	     {
		   url:'../../../sis_ventas_facturacion/vista/valor_descripcion/ValorDescripcion.php',
		   title:'Atributos', 
		   height:'50%',
		   cls:'ValorDescripcion'
		 }
	
	   ],
    
    
};
</script>