<?php
/**
*@package pXP
*@file VentaEmisor.php
*@author  (rarteaga)
*@date 29/10/2018
*@description  Interface para emmisores de facturas 
 * 
 * */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.VentaEmisor = {    
    bsave:false,    
    require:'../../../sis_ventas_facturacion/vista/venta/Venta.php',
    ActList:'../../sis_ventas_facturacion/control/Venta/listarVentaEmisor',
    solicitarSucursal: false,
    requireclase:'Phx.vista.Venta',
    title:'Venta',
    nombreVista: 'VentaEmisor',
    bnew:false,
    bedit:false,
    bdel:true,
    tipo_factura: '',
    tipo_usuario : 'cajero',
    constructor: function(config) {
        this.maestro=config.maestro;  
        Phx.vista.VentaEmisor.superclass.constructor.call(this,config);        
    } ,
    arrayDefaultColumHidden:['estado_reg','usuario_ai','fecha_reg','fecha_mod','usr_reg','usr_mod','nro_factura','excento','fecha','cod_control','nroaut'],
    successGetVariables :function (response,request) {   
    	Phx.vista.VentaEmisor.superclass.successGetVariables.call(this,response,request);  				  		
  		this.store.baseParams.pes_estado = 'caja';        
        
        
        this.addButton('anular',{grupo:[1],text:'Anular',iconCls: 'bdel',disabled:true,handler:this.anular,tooltip: '<b>Anular la venta</b>',hidden:true});
        this.addButton('ant_estado',{argument: {estado: 'anterior'},text:'Anterior',iconCls: 'batras',disabled:true,handler:this.antEstado,tooltip: '<b>Pasar al Anterior Estado</b>'});
        this.addButton('sig_estado',{grupo:[0],text:'Siguiente',iconCls: 'badelante',disabled:true,handler:this.sigEstado,tooltip: '<b>Pasar al Siguiente Estado</b>'});
        this.addButton('diagrama_gantt',{grupo:[0,1],text:'Gant',iconCls: 'bgantt',disabled:true,handler:this.diagramGantt,tooltip: '<b>Diagrama Gantt de la venta</b>'});
        this.addButton('btnImprimir',
            {   grupo:[0,1,2],
                text: 'Imprimir',
                iconCls: 'bpdf32',
                disabled: true,
                handler: this.elegirFormato,
                tooltip: '<b>Imprimir Formulario de Venta</b><br/>Imprime el formulario de la venta'
            }
        );
        this.addBottoCarta();
        this.finCons = true; 
        //primera carga
		this.store.baseParams.pes_estado = 'emision';
    	this.load({params:{start:0, limit:this.tam_pag}});
               
		  
  	},
  	gruposBarraTareas:[{name:'emision',title:'<H1 align="center"><i class="fa fa-eye"></i> Pendiente de Emisión</h1>',grupo:0,height:0},
                       {name:'finalizado',title:'<H1 align="center"><i class="fa fa-thumbs-up"></i> Finalizados</h1>',grupo:1, height:0},
                       {name:'anulado',title:'<H1 align="center"><i class="fa fa-thumbs-down"></i> Anulados</h1>',grupo:2, height:0}
                       
                       ],
    
    
    actualizarSegunTab: function(name, indice){
        if(this.finCons) {        	 
             this.store.baseParams.pes_estado = name;
             this.store.baseParams.interfaz = 'emision';              
             this.load({params:{start:0, limit:this.tam_pag}});
        }
    },
    addBottoCarta: function() {
        this.menuAdq = new Ext.Toolbar.SplitButton({
            id: 'b-btnCarta-' + this.idContenedor,
            text: 'Cartas',
            disabled: true,
            grupo:[0,1,2],
            iconCls : 'bpdf32',
            handler:this.imprimirCarta,
            scope: this,
            menu:{
                items: [{
                    id:'b-btnCartaSN-' + this.idContenedor,
                    text: 'Carta SN',
                    iconCls : 'blist',
                    handler:this.imprimirCarta,
                    scope: this
                }, {
                    id:'b-btnCartaCN-' + this.idContenedor,
                    text: 'Carta CN',
                    iconCls : 'blist',
                    handler:this.imprimirCartaCn,
                    scope: this
                }
                ]}
        });
        this.tbar.add(this.menuAdq);
    },
    beditGroups: [0],
    bdelGroups:  [0],
    bactGroups:  [0,1,2],
    btestGroups: [0],
    bexcelGroups: [0,1,2],       
    preparaMenu:function()
    {   var rec = this.sm.getSelected();
        
        if (rec.data.estado == 'emision') {              
              this.getBoton('sig_estado').enable();
              this.getBoton('ant_estado').enable();
              
                          
        } 
        
        if (rec.data.estado == 'finalizado') {              
              this.getBoton('anular').enable();
              this.getBoton('ant_estado').disable();
              this.getBoton('sig_estado').disable();
                          
        } 
        this.getBoton('btnImprimir').enable();
        this.getBoton('btnCarta').enable();
        this.getBoton('diagrama_gantt').enable(); 
        Phx.vista.VentaEmisor.superclass.preparaMenu.call(this);
    },
    
    liberaMenu:function()
    {   this.getBoton('btnImprimir').disable();
        this.getBoton('btnCarta').disable();
        this.getBoton('diagrama_gantt').disable();
        this.getBoton('anular').disable();        
        this.getBoton('sig_estado').disable();
         this.getBoton('ant_estado').disable();        
        Phx.vista.VentaEmisor.superclass.liberaMenu.call(this);
   },
   
   elegirFormato: function(){   
   	var rec = this.sm.getSelected(),
		data = rec.data,
		me = this;
   	
   var formato_comprobante = data.formato_comprobante.split("-");   
                      
     formato_comprobante = formato_comprobante[0];     
     formato_comprobante = formato_comprobante.toUpperCase();
     
   	if (formato_comprobante == 'PDF') {
   		this.imprimirPdf();
   	} else{
   		this.imprimirNota();
   	 }

   },
   imprimirPdf : function() {
			var rec = this.sm.getSelected();
			var data = rec.data;
		
			console.log('nombreVista',this.nombreVista);
			me = this;			
			if (data) {
				Phx.CP.loadingShow();
				Ext.Ajax.request({
					url : '../../sis_ventas_facturacion/control/Venta/reporteFacturaReciboPdf',
					params : {
							'id_venta' : data.id_venta,
							'formato_comprobante' : data.formato_comprobante,
							'tipo_factura': data.tipo_factura,
							'nombre_vista': this.nombreVista
					},
					success : this.successExport,
					failure : this.conexionFailure,
					timeout : this.timeout,
					scope : this
				});
			}
	},
    imprimirCarta:function () {
        var rec = this.sm.getSelected();
        var data = rec.data;
        me = this;
        if (data) {
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url : '../../sis_ventas_facturacion/control/Venta/PlantillaCarta',
                params : {
                    'id_venta' : data.id_venta,
                    'formato_comprobante' : data.formato_comprobante,
                    'tipo_factura': data.tipo_factura,
                    'nombre_vista': this.nombreVista,
                    'tipo':'sn'
                },
                success : this.successExport,
                failure : this.conexionFailure,
                timeout : this.timeout,
                scope : this
            });
        }

    },
    imprimirCartaCn:function () {
        var rec = this.sm.getSelected();
        var data = rec.data;
        me = this;
        if (data) {
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url : '../../sis_ventas_facturacion/control/Venta/PlantillaCarta',
                params : {
                    'id_venta' : data.id_venta,
                    'formato_comprobante' : data.formato_comprobante,
                    'tipo_factura': data.tipo_factura,
                    'nombre_vista': this.nombreVista,
                    'tipo':'cn'
                },
                success : this.successExport,
                failure : this.conexionFailure,
                timeout : this.timeout,
                scope : this
            });
        }

    }
};
</script>
