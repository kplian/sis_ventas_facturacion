<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (rarteaga)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
* 		ISSUE 			Fecha				Autor				Descripcion
 * 		#1				19/11/2018			EGS					se aumento botones para subir y descargar plantillas para facturas en excel
 *		#2	EndeEtr		23/01/2019			EGS					se agrego reporte con lista de productos activos por puntos de venta
	 	#4	endeETR	 	21/02/2019			EGS					Se añadio la vista venta peajes
 *      #7  endeETR     31/10/2019          EGS                 Se agrega boton siguiente multiple
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.VentaVendedor = {    
    bsave:false,    
    require:'../../../sis_ventas_facturacion/vista/venta/Venta.php',
    requireclase:'Phx.vista.Venta',
    title:'Venta',
    nombreVista: 'VentaVendedor',
    grupoDateFin: [1],
    
    constructor: function(config) {
        this.maestro=config.maestro;  
        Phx.vista.VentaVendedor.superclass.constructor.call(this,config);        
    } ,
    arrayDefaultColumHidden:['estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod','nro_factura','excento','fecha','cod_control','nroaut'],
    successGetVariables :function (response,request) {   
    	Phx.vista.VentaVendedor.superclass.successGetVariables.call(this,response,request);  				  		
  		this.store.baseParams.pes_estado = 'borrador';        
		
		
		///#1 19/11/2018 EGS	
		if (this.nombreVista=='VentaVendedorETR'|| this.nombreVista=='VentaVendedorNCETR'|| this.nombreVista=='VentaVendedorPeajeETR' ) {//#4
			this.addButton('btnXls',
			{
				text: 'Subir Factura',
				iconCls: 'bchecklist',
				disabled: false	,
				handler: this.SubirArchivo,
				tooltip: '<b>Subir Archivo</b>'
			});
			//#2 Boton que agrega la lista de productos activos por punto de venta
			this.addButton('btnPlantExcel', {
				text : 'Plantilla y Pro.Activos ',
				iconCls : 'bprint',
				disabled : false,
				//handler : this.descargaPlantilla,
				tooltip : '<b>Descarga ejemplo de Plantilla Excel para subir Facturas y Productos Activos Por Punto de Venta</b><br/>',
				 menu: [{
			                    text: 'Plantilla',
			                    iconCls: 'bprint',
			                    argument: {
			                        'news': true,
			                        def: 'csv'
			                    },
			                    handler: this.descargaPlantilla,
			                    scope: this,
			                    
			                }, {
			                    text: 'Productos Activos Pv',
			                    iconCls: 'bprint',
			                    argument: {
			                        'news': true,
			                        def: 'pdf'
			                    },
			                    handler: this.imprimirProductoA,
			                    scope: this
			                }],
			});  
			
		}; 
			///#1 19/11/2018 EGS	      
        
        
        this.addButton('anular',{grupo:[],text:'Anular',iconCls: 'bdel',disabled:true,handler:this.anular,tooltip: '<b>Anular la venta</b>',hidden:true});
        this.addButton('sig_estado',{grupo:[0],text:'Siguiente',iconCls: 'badelante',disabled:true,handler:this.sigEstado,tooltip: '<b>Pasar al Siguiente Estado</b>'});
        this.addButton('sig_estado_multiple',{grupo:[0],text:'Siguiente Multiple',iconCls: 'badelante',disabled:true,handler:this.sigEstadoMultiple,tooltip: '<b>Pasar varios regitros al Siguiente Estado</b>'});//#7
        this.addButton('diagrama_gantt',{grupo:[0,1,2,3],text:'Gant',iconCls: 'bgantt',disabled:true,handler:this.diagramGantt,tooltip: '<b>Diagrama Gantt de la venta</b>'});
        this.addButton('btnImprimir',
            {   grupo:[0,1,2,3],
                text: 'Imprimir',
                iconCls: 'bpdf32',
                disabled: true,
                handler: this.elegirFormato,
                tooltip: '<b>Imprimir Formulario de Venta</b><br/>Imprime el formulario de la venta'
            }
        );
        
        this.campo_fecha = new Ext.form.DateField({
	        name: 'fecha_reg',
	        grupo: this.grupoDateFin,
			fieldLabel: 'Fecha',
			allowBlank: true,  //#123 ya no es obligatorio
			anchor: '80%',
			gwidth: 100,
			format: 'd/m/Y', 
			hidden : true
	    });
	    
		this.tbar.addField(this.campo_fecha);
		var datos_respuesta = JSON.parse(response.responseText);
    	var fecha_array = datos_respuesta.datos.fecha.split('/');
    	//this.campo_fecha.setValue(new Date(fecha_array[2],parseInt(fecha_array[1]) - 1,fecha_array[0])); //#123  por dejamos el valor incial, ya no es obligatoria para busquedas
        //this.campo_fecha.hide();
        
        this.finCons = true;
        
        this.campo_fecha.on('change',function(value){
    		this.store.baseParams.fecha = this.campo_fecha.getValue().dateFormat('d/m/Y');
    		this.load();
    	},this);
		  
  	},
  	gruposBarraTareas:[{name:'borrador',title:'<H1 align="center"><i class="fa fa-play"></i> En Registro</h1>',grupo:0, height:0},
  	                   {name:'emision',title:'<H1 align="center"><i class="fa fa-eye"></i> Pendientes</h1>',grupo:3,  height:0},
                       {name:'finalizado',title:'<H1 align="center"><i class="fa fa-thumbs-up"></i> Finalizados</h1>',grupo:1, height:0},
                       {name:'anulado',title:'<H1 align="center"><i class="fa fa-thumbs-down"></i> Anulados</h1>',grupo:2, height:0}
                       ],
    
    
    actualizarSegunTab: function(name, indice){
        if(this.finCons){
        	 if (name == 'finalizado' && this.campo_fecha.getValue()){
        	 	this.store.baseParams.fecha = this.campo_fecha.getValue().dateFormat('d/m/Y');;
        	 } else {
        	 	this.store.baseParams.fecha = '';
        	 }
             this.store.baseParams.pes_estado = name;
             this.store.baseParams.interfaz = 'vendedor';
             this.load({params:{start:0, limit:this.tam_pag}});
           }
    },
    beditGroups: [0],
    bdelGroups:  [0],
    bactGroups:  [0,1,2,3],
    btestGroups: [0],
    bexcelGroups: [0,1,2,3],  
         
    preparaMenu:function() {   
    	var rec = this.sm.getSelected();
        if (rec.data.estado == 'borrador') {              
              this.getBoton('sig_estado').enable();
              this.getBoton('sig_estado_multiple').enable();//#7
        } 
        
        if (rec.data.estado == 'finalizado') {              
              this.getBoton('anular').enable();
        } 
        this.getBoton('btnImprimir').enable();       
        this.getBoton('diagrama_gantt').enable(); 
        Phx.vista.VentaVendedor.superclass.preparaMenu.call(this);
    },
    liberaMenu:function() {   
    	this.getBoton('btnImprimir').disable(); 
        this.getBoton('diagrama_gantt').disable();
        this.getBoton('anular').disable();        
        this.getBoton('sig_estado').disable();
        this.getBoton('sig_estado_multiple').disable();//#7
        Phx.vista.VentaVendedor.superclass.liberaMenu.call(this);
    },
      imprimirProductoA : function() {
			//var rec = this.sm.getSelected();
			//var data = rec.data;
			//if (data) {
				//Phx.CP.loadingShow();
				Ext.Ajax.request({
					url : '../../sis_ventas_facturacion/control/ReportesVentas/listarProductoActivoPuntoV',
					params : {
						'id_proceso_wf' : 'data.id_proceso_wf'
					},
					success : this.successExport,
					failure : this.conexionFailure,
					timeout : this.timeout,
					scope : this
				});
			//}

		},
    
    
};
</script>
