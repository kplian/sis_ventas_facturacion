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
    require:'../../../sis_ventas_farmacia/vista/venta/Venta.php',
    requireclase:'Phx.vista.Venta',
    title:'Venta',
    nombreVista: 'VentaRevision',
    
    constructor: function(config) {
        this.maestro=config.maestro;  
        this.historico = 'no';
        
        this.tbarItems = ['-',{
            text: 'Histórico',
            enableToggle: true,
            pressed: false,
            toggleHandler: function(btn, pressed) {
               
                if(pressed){
                    this.historico = 'si';
                    this.desBotoneshistorico();
                }
                else{
                   this.historico = 'no' 
                }
                
                this.store.baseParams.historico = this.historico;
                this.onButtonAct();
             },
            scope: this
           }];
        Phx.vista.VentaRevision.superclass.constructor.call(this,config);
        this.store.baseParams.historico = this.historico;
        this.store.baseParams.pes_estado = config.estado_parametro;
        this.load({params:{start:0, limit:this.tam_pag}});        
        
        this.addButton('ant_estado',{argument: {estado: 'anterior'},text:'Anterior',iconCls: 'batras',disabled:true,handler:this.antEstado,tooltip: '<b>Pasar al Anterior Estado</b>'});
        this.addButton('sig_estado',{text:'Siguiente',iconCls: 'badelante',disabled:true,handler:this.sigEstado,tooltip: '<b>Pasar al Siguiente Estado</b>'});
        this.addButton('diagrama_gantt',{text:'Gant',iconCls: 'bgantt',disabled:true,handler:this.diagramGantt,tooltip: '<b>Diagrama Gantt de la venta</b>'});
        
        
    } ,        
    preparaMenu:function()
    {   var rec = this.sm.getSelected();
        
        this.getBoton('ant_estado').enable();
        this.getBoton('sig_estado').enable();
        this.getBoton('diagrama_gantt').enable(); 
        Phx.vista.VentaRevision.superclass.preparaMenu.call(this);
        if(this.historico == 'si'){
             this.desBotoneshistorico();
        }
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
