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
Phx.vista.VentaAdministrador = {    
    bsave:false,  
    bdel:false,     
    require:'../../../sis_ventas_facturacion/vista/venta/Venta.php',
    requireclase:'Phx.vista.Venta',
    title:'Venta',
    nombreVista: 'VentaAdministrador',
    
    constructor: function(config) {
    	this.config = config;
        this.maestro=config.maestro;       
        Phx.vista.VentaAdministrador.superclass.constructor.call(this,config);        
        
    } , 
    
    successGetVariables :function (response,request) {   
    	Phx.vista.VentaAdministrador.superclass.successGetVariables.call(this,response,request);  				  		
  		
        this.store.baseParams.pes_estado = this.config.estado_parametro;
              
        this.addButton('anular',{text:'Anular',iconCls: 'bdel',disabled:true,handler:this.anular,tooltip: '<b>Anular la venta</b>'});
        this.addButton('ant_estado',{argument: {estado: 'anterior'},text:'Anterior',iconCls: 'batras',disabled:true,handler:this.antEstado,tooltip: '<b>Pasar al Anterior Estado</b>'});
        this.addButton('diagrama_gantt',{text:'Gant',iconCls: 'bgantt',disabled:true,handler:this.diagramGantt,tooltip: '<b>Diagrama Gantt de la venta</b>'});
        this.addButton('btnImprimir',
            {   
                text: 'Imprimir',
                iconCls: 'bpdf32',
                disabled: true,
                handler: this.imprimirNota,
                tooltip: '<b>Imprimir Formulario de Venta</b><br/>Imprime el formulario de la venta'
            }
        );	  
  	},       
    preparaMenu:function()
    {   var rec = this.sm.getSelected();
        
         
        if (rec.data.estado != 'anulado') {
        	this.getBoton('anular').enable();  
        	this.getBoton('btnImprimir').enable(); 
        	this.getBoton('ant_estado').enable();
        }    
        this.getBoton('diagrama_gantt').enable(); 
         
        Phx.vista.VentaAdministrador.superclass.preparaMenu.call(this);
        
    },
    liberaMenu:function()
    {   
        this.getBoton('diagrama_gantt').disable();
        this.getBoton('ant_estado').disable();
        this.getBoton('anular').disable();         
        this.getBoton('btnImprimir').disable();       
        Phx.vista.VentaAdministrador.superclass.liberaMenu.call(this);
    }
    
    
};
</script>
