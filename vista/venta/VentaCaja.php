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
Phx.vista.VentaCaja = {    
    bsave:false,    
    require:'../../../sis_ventas_facturacion/vista/venta/Venta.php',
    requireclase:'Phx.vista.Venta',
    title:'Venta',
    nombreVista: 'VentaCaja',
    bnew:false,
    bedit:true,
    bdel:true,
    tipo_factura: '',
    tipo_usuario : 'cajero',
    constructor: function(config) {
        this.maestro=config.maestro;  
        Phx.vista.VentaCaja.superclass.constructor.call(this,config);        
    } ,
    arrayDefaultColumHidden:['estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod','nro_factura','excento','fecha','cod_control','nroaut'],
    successGetVariables :function (response,request) {   
    	Phx.vista.VentaCaja.superclass.successGetVariables.call(this,response,request);  				  		
  		this.store.baseParams.pes_estado = 'caja';        
        
        
        this.addButton('anular',{grupo:[1],text:'Anular',iconCls: 'bdel',disabled:true,handler:this.anular,tooltip: '<b>Anular la venta</b>',hidden:true});
        this.addButton('sig_estado',{grupo:[0],text:'Siguiente',iconCls: 'badelante',disabled:true,handler:this.sigEstado,tooltip: '<b>Pasar al Siguiente Estado</b>'});
        this.addButton('diagrama_gantt',{grupo:[0,1],text:'Gant',iconCls: 'bgantt',disabled:true,handler:this.diagramGantt,tooltip: '<b>Diagrama Gantt de la venta</b>'});
        this.addButton('btnImprimir',
            {   grupo:[0,1],
                text: 'Imprimir',
                iconCls: 'bpdf32',
                disabled: true,
                handler: this.imprimirNota,
                tooltip: '<b>Imprimir Formulario de Venta</b><br/>Imprime el formulario de la venta'
            }
        );        
      
		      
        this.finCons = true;
               
		  
  	},
  	gruposBarraTareas:[{name:'caja',title:'<H1 align="center"><i class="fa fa-eye"></i> En Caja</h1>',grupo:0,height:0},
                       {name:'finalizado',title:'<H1 align="center"><i class="fa fa-eye"></i> Emitidas</h1>',grupo:1,height:0}
                       
                       ],
    
    
    actualizarSegunTab: function(name, indice){
        if(this.finCons) {        	 
             this.store.baseParams.pes_estado = name;
             this.store.baseParams.interfaz = 'caja';              
             this.load({params:{start:0, limit:this.tam_pag}});
        }
    },
    beditGroups: [0],
    bdelGroups:  [0],
    bactGroups:  [0,1,2],
    btestGroups: [0],
    bexcelGroups: [0,1,2],       
    preparaMenu:function()
    {   var rec = this.sm.getSelected();
        
        if (rec.data.estado == 'caja') {              
              this.getBoton('sig_estado').enable();
                          
        } 
        
        if (rec.data.estado == 'finalizado') {              
              this.getBoton('anular').enable();
                          
        } 
        this.getBoton('btnImprimir').enable();       
        this.getBoton('diagrama_gantt').enable(); 
        Phx.vista.VentaCaja.superclass.preparaMenu.call(this);
    },
    liberaMenu:function()
    {   this.getBoton('btnImprimir').disable(); 
        this.getBoton('diagrama_gantt').disable();
        this.getBoton('anular').disable();        
        this.getBoton('sig_estado').disable();        
        Phx.vista.VentaCaja.superclass.liberaMenu.call(this);
    }
    
    
};
</script>
