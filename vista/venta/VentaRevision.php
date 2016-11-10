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
Phx.vista.VentaRevision = {    
    bsave:false,    
    require:'../../../sis_ventas_facturacion/vista/venta/Venta.php',
    requireclase:'Phx.vista.Venta',
    title:'Venta',
    nombreVista: 'VentaRevision',
    
    constructor: function(config) {
    	this.config = config;
        this.maestro=config.maestro;  
        
        
        
        Phx.vista.VentaRevision.superclass.constructor.call(this,config);
        
        
    } , 
    
    successGetVariables :function (response,request) {   
    	Phx.vista.VentaRevision.superclass.successGetVariables.call(this,response,request);  				  		
  		
        this.store.baseParams.pes_estado = this.config.estado_parametro;
        this.load({params:{start:0, limit:this.tam_pag}});        
        this.addButton('anular',{grupo:[1],text:'Anular',iconCls: 'bdel',disabled:true,handler:this.anular,tooltip: '<b>Anular la venta</b>',hidden:true});
        this.addButton('ant_estado',{argument: {estado: 'anterior'},text:'Anterior',iconCls: 'batras',disabled:true,handler:this.antEstado,tooltip: '<b>Pasar al Anterior Estado</b>'});
        this.addButton('sig_estado',{text:'Siguiente',iconCls: 'badelante',disabled:true,handler:this.sigEstado,tooltip: '<b>Pasar al Siguiente Estado</b>'});
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
        
        this.getBoton('ant_estado').enable();
        this.getBoton('sig_estado').enable();
        this.getBoton('diagrama_gantt').enable(); 
        Phx.vista.VentaRevision.superclass.preparaMenu.call(this);
        
    },
    liberaMenu:function()
    {   
        this.getBoton('diagrama_gantt').disable();
        this.getBoton('ant_estado').disable();
        this.getBoton('sig_estado').disable();        
        Phx.vista.VentaRevision.superclass.liberaMenu.call(this);
    },
    desBotoneshistorico:function(){
          this.getBoton('ant_estado').disable();          
          this.getBoton('sig_estado').disable();          
    },
    
    
};
</script>
