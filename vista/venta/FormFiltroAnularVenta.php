<?php
/**
*@package pXP
*@file    FormFiltroAnularVenta.php
*@author  Ana Maria Villegas Q.
*@date    01-02-2019
*@description vista de formulario, para el filtro de facturas a anular.
*/
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
Phx.vista.FormFiltroAnularVenta=Ext.extend(Phx.frmInterfaz,{
    constructor:function(config)
    {   
    	this.panelResumen = new Ext.Panel({html:''});
    	this.Grupos = [{

	                    xtype: 'fieldset',
	                    border: false,
	                    autoScroll: true,
	                    layout: 'form',
	                    items: [],
	                    id_grupo: 0
				               
				    },
				     this.panelResumen
				    ];
				    
        Phx.vista.FormFiltroAnularVenta.superclass.constructor.call(this,config);
        this.init(); 
        this.iniciarEventos();   
       
        if(config.detalle){
        	
			//cargar los valores para el filtro
			this.loadForm({data: config.detalle});
			var me = this;
			setTimeout(function(){
				me.onSubmit()
			}, 1000);
			
		}  
        
    },
    
    Atributos:[
           {
	   			config:{
	   				name : 'id_gestion',
	   				origen : 'GESTION',
	   				fieldLabel : 'Gestion',
	   				allowBlank : false,
	   				width: 150
	   			},
	   			type : 'ComboRec',
	   			id_grupo : 0,
	   			form : true
	   	   },
	   	   {
				config:{
					name: 'desde',
					fieldLabel: 'Desde',
					allowBlank: true,
					format: 'd/m/Y',
					width: 150
				},
				type: 'DateField',
				id_grupo: 0,
				form: true
		  },
		  {
				config:{
					name: 'hasta',
					fieldLabel: 'Hasta',
					allowBlank: true,
					format: 'd/m/Y',
					width: 150
				},
				type: 'DateField',
				id_grupo: 0,
				form: true
		  }

    ],
    labelSubmit: '<i class="fa fa-check"></i> Aplicar Filtro',
    east: {
          url: '../../../sis_ventas_facturacion/vista/venta/AnularVenta.php',
          title: undefined, 
          width: '70%',
          cls: 'AnularVenta'
         },
    title: 'Filtro de Anulacion',
    // Funcion guardar del formulario
    onSubmit: function(o) {
    	var me = this;
    	if (me.form.getForm().isValid()) {

             var parametros = me.getValForm()
             
             console.log('parametros ....', parametros);
             if (this.Cmp.desde.getValue()> this.Cmp.hasta.getValue()){
				
				alert("La fecha 'Desde' debe ser menor a la fecha 'Hasta'")
			} else {
				
			this.onEnablePanel(this.idContenedor + '-east', parametros);
			
          }
           
                    
        }

    },
    iniciarEventos:function(){
    	
    	this.Cmp.id_gestion.on('select', function(cmb, rec, ind){
			 this.Cmp.desde.setValue('01/01/'+rec.data.gestion);
			 this.Cmp.hasta.setValue('31/12/'+rec.data.gestion);
			 
    	},this);
    	
    }
    
    
})    
</script>