<?php
/**
*@package pXP
*@file    SubirArchivo.php
*@author  Manuel Guerra
*@date    22-03-2012
*@description permites subir archivos a la tabla de documento_sol
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.SubirArchivoFac=Ext.extend(Phx.frmInterfaz,{
    //ActSave:'../../sis_ventas_facturacion/control/SubirArchivoFac/SubirArchivoFac',
    
    ActSave:'../../sis_ventas_facturacion/control/SubirArchivoFac/SubirArchivoFactura',

    constructor:function(config)
    {   
        Phx.vista.SubirArchivoFac.superclass.constructor.call(this,config);
        this.init();    
        this.loadValoresIniciales();
        console.log('config',config);
        console.log('id_punto_venta',this.data.id_punto_venta);
        console.log('tipo factura',this.data.tipo_factura);
    },
    
    loadValoresIniciales:function()
    {        
        Phx.vista.SubirArchivoFac.superclass.loadValoresIniciales.call(this);
        this.getComponente('id_venta').setValue(this.id_venta); 
        this.getComponente('id_punto_venta').setValue(this.data.id_punto_venta);
        this.getComponente('tipo_factura').setValue(this.data.tipo_factura);          
    },
    
    successSave:function(resp)
    {
        Phx.CP.loadingHide();
        Phx.CP.getPagina(this.idContenedorPadre).reload();
        this.panel.close();
        this.ventanaEliminado();
        
    },
    
     ventanaEliminado : function(rec)
	{	 
		Phx.CP.loadWindows('../../../sis_ventas_facturacion/vista/venta/ExcelEliminado.php',
		'Datos Eliminados',
		{
			modal:true,
			width:500,
			height:400
		},
		{ data: {  //objPadre: me ,
			    maestro: this.maestro,
		 }},
		this.idContenedor,
		'TemporalData');
	},
                
    
    Atributos:[
		{
			config:{
				labelSeparator:'',
				inputType:'hidden',
				name: 'id_venta'
			},
			type:'Field',
			form:true
		},
		{
			config:{
				labelSeparator:'',
				inputType:'hidden',
				name: 'id_punto_venta'
			},
			type:'Field',
			form:true
		},
		{
			config:{
				labelSeparator:'',
				inputType:'hidden',
				name: 'tipo_factura'
			},
			type:'Field',
			form:true
		},
		
        {
            config:{
                fieldLabel: "Facturas (csv)",
                gwidth: 130,
                inputType: 'file',
                name: 'archivo',
                allowBlank: false,
                buttonText: '', 
                maxLength: 150,
                anchor:'100%'
            },
            type:'Field',
            form:true 
        }
    ],
    title:'Subir Archivo',    
    fileUpload:true
    
}
)    
</script>
