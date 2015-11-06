<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (jrivera)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormVentaFarmacia = {
    require:'../../../sis_ventas_facturacion/vista/venta/FormVenta.php',
	requireclase:'Phx.vista.FormVenta',
	title:'Venta',
	nombreVista: 'FormVentaFarmacia',
	
	constructor: function(config) {	
		this.addElements();    
        Phx.vista.FormVentaFarmacia.superclass.constructor.call(this,config);        
        
  },
  addElements : function () {
  	this.Atributos.push({
			config:{
				name: 'a_cuenta',
				fieldLabel: 'A cuenta',
				allowBlank: false,
				anchor: '80%',
				maxLength:5
			},
				type:'NumberField',				
				id_grupo:0,				
				form:true
		});
		
	this.Atributos.push({
			config:{
				name: 'fecha_estimada_entrega',
				fieldLabel: 'Fecha de Entrega Estimada',
				allowBlank: false,				
				format: 'd/m/Y'
							
			},
				type:'DateField',				
				id_grupo:0,				
				form:true
		});
  }
  
   
	
};
</script>
