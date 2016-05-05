<?php
/**
*@package pXP
*@file FormRendicion.php
*@author  Gonzalo Sarmiento 
*@date 16-02-2016
*@description Archivo con la interfaz de usuario que permite 
*ingresar el documento a rendir
*
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormVentaMin = {
	require:'../../../sis_ventas_facturacion/vista/venta/FormVenta.php',
	requireclase:'Phx.vista.FormVenta',
	mostrarFormaPago : false,	
	cantidadAllowDecimals: true,	
	constructor: function(config) {	
		Phx.vista.FormVentaMin.superclass.constructor.call(this,config);
	}
	
};
</script>