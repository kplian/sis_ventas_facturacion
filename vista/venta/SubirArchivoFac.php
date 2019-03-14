<?php
/**
*@package pXP
*@file    SubirArchivo.php
*@author  
*@date    
*@description permites subir archivos a la tabla de documento_sol
  ISSUE				FECHA		AUTOR				DESCRIPCION
 	#4	endeETR	 	21/02/2019	EGS					Se agrego a la condicion la vista de peaje
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.SubirArchivoFac=Ext.extend(Phx.frmInterfaz,{
    //ActSave:'../../sis_ventas_facturacion/control/SubirArchivoFac/SubirArchivoFac',
    
	
    
    constructor:function(config)
    {   
        Phx.vista.SubirArchivoFac.superclass.constructor.call(this,config);
        this.init();    
        this.loadValoresIniciales();
		
        console.log('config',config);
        console.log('id_punto_venta',this.data.id_punto_venta);
        console.log('tipo factura',this.data.tipo_factura);
      	console.log('nombreVista',this.data.nombreVista);

		this.iniciarEventos(); 
    },
    
   
    
     iniciarEventos:function (){
     	
	  if (this.data.nombreVista=='VentaVendedorETR' || this.data.nombreVista=='VentaVendedorPeajeETR') {//#4
		this.ActSave = '../../sis_ventas_facturacion/control/SubirArchivoFac/SubirArchivoFactura';
		
	  }
	  else if(this.data.nombreVista=='VentaVendedorNCETR'){
		this.ActSave = '../../sis_ventas_facturacion/control/SubirArchivoFac/SubirArchivoNota';
	  }
     },
     
     
     
    loadValoresIniciales:function()
    {        
        Phx.vista.SubirArchivoFac.superclass.loadValoresIniciales.call(this);
        this.getComponente('id_venta').setValue(this.id_venta); 
        this.getComponente('id_punto_venta').setValue(this.data.id_punto_venta);
        this.getComponente('tipo_factura').setValue(this.data.tipo_factura);  
        this.getComponente('nombreVista').setValue(this.data.nombreVista);          
        
    },
    
    successSave:function(resp)
    {	console.log('resp',resp);
        Phx.CP.loadingHide();
        Phx.CP.getPagina(this.idContenedorPadre).reload();
        this.panel.close();
        //verificamos si existe datos eliminados con errores por punto de venta 
        Ext.Ajax.request({
		url : '../../sis_ventas_facturacion/control/SubirArchivoFac/listarExcelEliminado',
		params : {
					start:'0',
					limit:'50',
					sort:'id_temporal_data',
					dir:'ASC',
					id_punto_venta : this.data.id_punto_venta
				},
		success : function(resp){
			 Phx.CP.loadingHide();
             var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
						console.log('reg.datos.length',reg.datos.length);
               			if (reg.datos.length >0) {
               				//si existe mostramos ventana
							this.ventanaEliminado();
					}	;
		},				
		failure : this.conexionFailure,
		timeout : this.timeout,
		scope : this
		});
		
        
        
    },
    

     ventanaEliminado : function(rec)
	{	 
		Phx.CP.loadWindows('../../../sis_ventas_facturacion/vista/venta/ExcelEliminado.php',
		'Datos Eliminados',
		{
			modal:true,
			width:1000,
			height:400
		},
		{ data: {  //objPadre: me ,
			    maestro: this.maestro,
			    id_punto_venta : this.data.id_punto_venta
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
				labelSeparator:'',
				inputType:'hidden',
				name: 'nombreVista'
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
