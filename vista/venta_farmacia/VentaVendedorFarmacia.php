<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (fprudencio)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.VentaVendedorFarmacia = {
    require:'../../../sis_ventas_facturacion/vista/venta/Venta.php',
	requireclase:'Phx.vista.Venta',
	title:'Venta',
	nombreVista: 'VentaVendedorFarmacia',
	formUrl : '../../../sis_ventas_facturacion/vista/venta_farmacia/FormVentaFarmacia.php',
	formClass : 'FormVentaFarmacia',
	constructor: function(config) {	
		Phx.vista.VentaVendedorFarmacia.superclass.constructor.call(this,config);		
   },
   successGetVariables :function (response,request) {     				  		
  		this.addElements();
		Phx.vista.VentaVendedorFarmacia.superclass.successGetVariables.call(this,response,request); 
		this.store.baseParams.pes_estado = 'borrador';        
        this.finCons = true;
        this.addButton('ant_estado',{grupo:[1,2,3,4],argument: {estado: 'anterior'},text:'Anterior',iconCls: 'batras',disabled:true,handler:this.antEstado,tooltip: '<b>Pasar al Anterior Estado</b>'});
        this.addButton('sig_estado',{grupo:[0,1,2],text:'Siguiente',iconCls: 'badelante',disabled:true,handler:this.sigEstado,tooltip: '<b>Pasar al Siguiente Estado</b>'});
        this.addButton('diagrama_gantt',{grupo:[0,1,2,3,4],text:'Gant',iconCls: 'bgantt',disabled:true,handler:this.diagramGantt,tooltip: '<b>Diagrama Gantt de la venta</b>'});
        this.addButton('btnImprimir',
            {   grupo:[0,1,2,3,4],
                text: 'Imprimir',
                iconCls: 'bpdf32',
                disabled: true,
                handler: this.imprimirNota,
                tooltip: '<b>Imprimir Formulario de Venta</b><br/>Imprime el formulario de la venta'
            }
        );
		this.formUrl = '../../../sis_ventas_facturacion/vista/venta_farmacia/FormVentaFarmacia.php';
        this.formClass = 'FormVentaFarmacia'; 
  },
  gruposBarraTareas:[{name:'borrador',title:'<H1 align="center"><i class="fa fa-eye"></i> En Registro</h1>',grupo:0,height:0},
                       {name:'proceso_elaboracion',title:'<H1 align="center"><i class="fa fa-eye"></i> En elaboración</h1>',grupo:1,height:0},
                       {name:'pendiente_entrega',title:'<H1 align="center"><i class="fa fa-eye"></i> Para Entrega</h1>',grupo:2,height:0},
                       {name:'entregado',title:'<H1 align="center"><i class="fa fa-eye"></i> Entregado</h1>',grupo:3,height:0},
                       {name:'descartado',title:'<H1 align="center"><i class="fa fa-eye"></i> Descartado</h1>',grupo:4,height:0}],
  actualizarSegunTab: function(name, indice){
        if(this.finCons){
        	 
             this.store.baseParams.pes_estado = name;
             this.load({params:{start:0, limit:this.tam_pag}});
           }
    },
  beditGroups: [0],
    bdelGroups:  [0],
    bactGroups:  [0,1,2,3,4],
    btestGroups: [0],
    bexcelGroups: [0,1,2,3,4],  
   addElements : function () {

       this.Atributos.push({
           config:{
               name: 'vendedor_medico',
               fieldLabel: 'Vendedor/Medico',
               allowBlank: false,
               anchor: '80%',
               gwidth: 120
           },
           type:'TextField',
           filters:{pfiltro:'mu.nombre',type:'string'},
           grid:true,
           form:false,
           bottom_filter: true
       });
  	this.Atributos.push({
			config:{
				name: 'a_cuenta',
				fieldLabel: 'A cuenta',
				allowBlank: false,
				anchor: '80%',
				gwidth: 120,
				maxLength:5
			},
				type:'NumberField',
				filters:{pfiltro:'ven.a_cuenta',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		});
	
	this.Atributos.push({
			config:{
				name: 'forma_pedido',
				fieldLabel: 'Forma Pedido',
				allowBlank: false,
				anchor: '80%',
				gwidth: 120,
				maxLength:5
			},
				type:'TextField',				
				id_grupo:1,
				grid:true,
				form:false
		});
		
	this.Atributos.push({
			config:{
				name: 'fecha_estimada_entrega',
				fieldLabel: 'Fecha de Entrega Estimada',
				allowBlank: false,				
				gwidth: 150,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'ven.fecha_estimada_entrega',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		});
  },
  preparaMenu:function()
    {   var rec = this.sm.getSelected();
        
        if (rec.data.estado == 'borrador') {
              this.getBoton('ant_estado').disable();
              this.getBoton('sig_estado').enable();
                          
        } else {
             this.getBoton('ant_estado').enable();
             this.getBoton('sig_estado').enable();
        }
               
        this.getBoton('diagrama_gantt').enable(); 
        this.getBoton('btnImprimir').enable();
        Phx.vista.VentaVendedorFarmacia.superclass.preparaMenu.call(this);
    },
    liberaMenu:function()
    {   
        this.getBoton('diagrama_gantt').disable();
        this.getBoton('ant_estado').disable();
        this.getBoton('sig_estado').disable();  
        this.getBoton('btnImprimir').disable();      
        Phx.vista.VentaVendedorFarmacia.superclass.liberaMenu.call(this);
    }
   
	
};
</script>
