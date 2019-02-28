<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (rarteaga)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 ISSUE				FECHA		AUTOR				DESCRIPCION
 	#4	endeETR	 	21/02/2019	EGS					Se agregaron funciones y boton de subir archivos
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.VentaVendedorPeajeETR = {    
    bsave: false,    
    require: '../../../sis_ventas_facturacion/vista/venta/VentaVendedor.php',   
    requireclase: 'Phx.vista.VentaVendedor',
    ActList:'../../sis_ventas_facturacion/control/Venta/listarVentaETR',
    title: 'Venta',
    nombreVista: 'VentaVendedorPeajeETR',
    tipo_factura:'computarizadareg',
    formUrl: '../../../sis_ventas_facturacion/vista/venta/FormVentaETR.php',
    formClass : 'FormVentaETR',
    
    
    
    constructor: function(config) {
        this.maestro = config.maestro;  
        Phx.vista.VentaVendedorPeajeETR.superclass.constructor.call(this,config);
                
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
    
    // Funcion eliminar del toolbar
	onButtonDel:function(){
		if(confirm('¿Está seguro de anular  la factura?')){
			//recupera los registros seleccionados
			var filas=this.sm.getSelections(),
			    data= {},aux={};
			 
			
            //arma una matriz de los identificadores de registros que se van a eliminar
            this.agregarArgsExtraSubmit();
            
			for(var i=0;i<this.sm.getCount();i++){
				aux={};
				aux[this.id_store]=filas[i].data[this.id_store];
				
				data[i]=aux;
				data[i]._fila=this.store.indexOf(filas[i])+1
				//rac 22032012
				Ext.apply(data[i],this.argumentExtraSubmit);
			}
		
			Phx.CP.loadingShow();
			
			//llama el metodo en la capa de control para eliminación
			Ext.Ajax.request({
				url:this.ActDel,
				success:this.successDel,
				failure:this.conexionFailure,
				//params:this.id_store+"="+this.sm.getSelected().data[this.id_store],
				params:{_tipo:'matriz','row':Ext.util.JSON.encode(data)},
				//argument :{'foo':'xxx'},
				timeout:this.timeout,
				scope:this
			})
		}
	},
	
	SubirArchivo : function(rec)//#4
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
	
	descargaPlantilla: function(){//#4
		var     data  = "&extension=xlsx";
	            data += "&sistema=sis_ventas_facturacion";
	            data += "&clase=plantilla";
	            data += "&url=./../../../sis_ventas_facturacion/reportes/plantillaExcelFacturaPeaje.xlsx";
	            //return  String.format('{0}',"<div style='text-align:center'><a target=_blank href = '../../../lib/lib_control/CTOpenFile.php?"+ data+"' align='center' width='70' height='70'>Abrir</a></div>");
	            window.open('../../../lib/lib_control/CTOpenFile.php?' + data);
		
		
	},
    
    
};
</script>