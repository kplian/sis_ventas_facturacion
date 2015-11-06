<?php
/**
*@package pXP
*@file ConceptoIngas.php
*@author  RCM
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.EntidadFormaPago = {
	require:'../../../sis_parametros/vista/entidad/Entidad.php',
	requireclase:'Phx.vista.Entidad',
	title:'Entidad',
	constructor: function(config) {
    	Phx.vista.EntidadFormaPago.superclass.constructor.call(this,config);    	
	}, 
	east:
		  { 
	          url:'../../../sis_ventas_facturacion/vista/forma_pago/FormaPago.php',
	          title:'Forma de Pago', 
	          width:'50%',
	          cls:'FormaPago'
   		 },
   bedit:false,
   bnew:false,
   bdel:false,
   bsave:false
};
</script>
