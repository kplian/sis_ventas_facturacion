<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (rarteaga)
*@date 08-10-2018
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 *   HISTORIAL DE MODIFICACIONES:

 ISSUE            FECHA:		      AUTOR               DESCRIPCION
 #0              08-10-2018           RAC                 Creacion 
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.VentaVendedorNCETR = {    
    bsave: false,    
    require: '../../../sis_ventas_facturacion/vista/venta/VentaVendedor.php',   
    requireclase: 'Phx.vista.VentaVendedor',
    ActList:'../../sis_ventas_facturacion/control/Venta/listarVentaNCETR',
    title: 'Venta',
    nombreVista: 'VentaVendedorNCETR',
    tipo_factura:'notacredito',
    formUrl: '../../../sis_ventas_facturacion/vista/venta/FormVentaNCETR.php',
    formClass : 'FormVentaNCETR',
    
    constructor: function(config) {
        this.maestro = config.maestro;  
        Phx.vista.VentaVendedorNCETR.superclass.constructor.call(this,config);
                
    } ,
    arrayDefaultColumHidden:['estado_reg','usuario_ai','fecha_reg','fecha_mod','usr_reg','usr_mod','excento','cod_control','nroaut'],
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
   
   SubirArchivo : function(rec)
	{	 console.log('id_punto_venta',this.variables_globales.id_punto_venta) ; 
		Phx.CP.loadWindows('../../../sis_ventas_facturacion/vista/venta/SubirArchivoFac.php',
		'Subir Facturas',
		{
			modal:true,
			width:450,
			height:150
		},
		{ data: {  //objPadre: me ,
			    maestro: this.maestro,
				id_punto_venta:this.variables_globales.id_punto_venta,
				tipo_factura:this.tipo_factura,
				nombreVista:this.nombreVista
		 }},
		this.idContenedor,
		'SubirArchivoFac');
	},	
	descargaPlantilla: function(){
		var     data  = "&extension=xlsx";
	            data += "&sistema=sis_ventas_facturacion";
	            data += "&clase=plantilla";
	            data += "&url=./../../../sis_ventas_facturacion/reportes/plantillaExcelNota.xlsx";
	            //return  String.format('{0}',"<div style='text-align:center'><a target=_blank href = '../../../lib/lib_control/CTOpenFile.php?"+ data+"' align='center' width='70' height='70'>Abrir</a></div>");
	            window.open('../../../lib/lib_control/CTOpenFile.php?' + data);
		
		
	},
    
    
};
</script>