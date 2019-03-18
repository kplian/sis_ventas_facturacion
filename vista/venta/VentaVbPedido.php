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
Phx.vista.VentaVbPedido = {    
      
    require:'../../../sis_ventas_facturacion/vista/venta/Venta.php',
    requireclase:'Phx.vista.Venta',
    title:'Venta',
    nombreVista: 'VentaVbPedido',
    solicitarSucursal: false,
    ActList:'../../sis_ventas_facturacion/control/Venta/listarVenta',
    bsave:false, 
    bnew: false,
    bdel: false,
    bedit: false,
    tipo_factura:'pedido',
    
        
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
           
       
        
        Phx.vista.VentaVbPedido.superclass.constructor.call(this,config);
        
        
       
    } ,  
    
     successGetVariables: function(response,request){
     	
     	
     	Phx.vista.VentaVbPedido.superclass.successGetVariables.call(this,response,request);
     	console.log('..............',this.store)
        this.store.baseParams = { tipo_factura: this.tipo_factura,
        						  historico :  this.historico,
        	                      pes_estado : this.estado_parametro,
        	                      nombreVista: this.nombreVista} ;
        
        this.load({params:{start:0, limit:this.tam_pag}});        
        
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
        
        this.variables_globales.formato_comprobante="PEDIDOMEDIACAR";
        
     	
     },
     
     
          
    preparaMenu:function()
    {   var rec = this.sm.getSelected();
        
        this.getBoton('ant_estado').enable();        
        this.getBoton('diagrama_gantt').enable(); 
        this.getBoton('btnImprimir').enable(); 
        
        if (rec.data.estado == 'entregado' || rec.data.estado == 'anulado') {              
              this.getBoton('sig_estado').disable();
        } 
        else{
        	this.getBoton('sig_estado').enable();
        }
        
        Phx.vista.VentaVbPedido.superclass.preparaMenu.call(this);
        
        
        if(this.historico == 'si'){
             this.desBotoneshistorico();
        }
    },
    liberaMenu:function()
    {   
        this.getBoton('diagrama_gantt').disable();
        this.getBoton('ant_estado').disable();
        this.getBoton('sig_estado').disable(); 
        this.getBoton('btnImprimir').disable();       
        Phx.vista.VentaVbPedido.superclass.liberaMenu.call(this);
    },
    desBotoneshistorico:function(){
          this.getBoton('ant_estado').disable();          
          this.getBoton('sig_estado').disable();           
    },
	
	south:
          { 
          url:'../../../sis_ventas_facturacion/vista/venta_detalle/VentaDetalleVb.php',
          title:'Detalle', 
          height:'50%',
          cls:'VentaDetalleVb'
         }
    
    
};
</script>
