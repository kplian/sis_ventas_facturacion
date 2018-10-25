<?php
/**
*@package pXP
*@file gen-CobroSimple.php
*@author  (admin)
*@date 31-12-2017 12:33:30
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Detalle=Ext.extend(Phx.gridInterfaz,{
	nombreVista: 'Detalle',
	constructor:function(config){
		this.maestro=config.maestro;

		//Historico
		this.historico = 'no';

    	//llama al constructor de la clase padre
		Phx.vista.Detalle.superclass.constructor.call(this,config);
		this.init();
		
		//Adicion de botones en la barra de herramientas
	     
      this.iniciarEventos();

		//this.load({params:{start:0, limit:this.tam_pag}});
		this.bloquearMenus();

	

	},
	
    	
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_cobro_simple'
			},
			type:'Field',
			form:true 
		},
	

		{
			config:{
				name: 'concepto',
				fieldLabel: 'Concepto',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				//filters:{pfiltro:'',type:'string'},
				id_grupo:1,
				grid:true,
				form:false,
				//bottom_filter:true
		},
		{
			config:{
				name: 'cantidad',
				fieldLabel: 'Cantidad',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'vedet.cantidad',type:'string'},
				id_grupo:1,
				grid:true,
				form:false,
				bottom_filter:true
		},
		{
			config:{
				name: 'precio_unitario',
				fieldLabel: 'Precio Unitario',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'vedet.precio',type:'string'},
				id_grupo:1,
				grid:true,
				form:false,
				bottom_filter:true
		},
		{
			config:{
				name: 'precio_total',
				fieldLabel: 'Precio Total',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				//filters:{pfiltro:'pagsim.nro_tramite',type:'string'},
				id_grupo:1,
				grid:false,
				form:false,
				//bottom_filter:true
		},
		{
			config:{
				name: 'unidad_medida',
				fieldLabel: 'unidad_medida',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				//filters:{pfiltro:'pagsim.nro_tramite',type:'string'},
				id_grupo:1,
				grid:false,
				form:false,
				//bottom_filter:true
		},
		{
			config:{
				name: 'nandina',
				fieldLabel: 'nandina',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				//filters:{pfiltro:'pagsim.nro_tramite',type:'string'},
				id_grupo:1,
				grid:false,
				form:false,
				//bottom_filter:true
		},
				{
			config:{
				name: 'bruto',
				fieldLabel: 'bruto',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'pagsim.nro_tramite',type:'string'},
				id_grupo:1,
				grid:false,
				form:false,
				bottom_filter:false
		},
				{
			config:{
				name: 'ley',
				fieldLabel: 'ley',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'pagsim.nro_tramite',type:'string'},
				id_grupo:1,
				grid:false,
				form:false,
				bottom_filter:false
		},
				{
			config:{
				name: 'kg_fino',
				fieldLabel: 'kg_fino',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'pagsim.nro_tramite',type:'string'},
				id_grupo:1,
				grid:false,
				form:false,
				bottom_filter:false
		},
				{
			config:{
				name: 'descripcion',
				fieldLabel: 'Descripcion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'vedet.descripcion',type:'string'},
				id_grupo:1,
				grid:true,
				form:false,
				bottom_filter:true
		},
				{
			config:{
				name: 'unidad_concepto',
				fieldLabel: 'unidad_concepto',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'pagsim.nro_tramite',type:'string'},
				id_grupo:1,
				grid:false,
				form:false,
				bottom_filter:false
		},
				{
			config:{
				name: 'precio_grupo',
				fieldLabel: 'precio_grupo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'pagsim.nro_tramite',type:'string'},
				id_grupo:1,
				grid:false,
				form:false,
				bottom_filter:false
		},
	],
	tam_pag:50,	
	title:'Pago Simple',
	
	ActList:'../../sis_ventas_facturacion/control/ReportesVentas/reporteVentaDetalle',
	id_store:'id_cobro_simple',
	fields: [
		{name:'id_cobro_simple', type: 'numeric'},
		{name:'concepto', type: 'string'},
		{name:'cantidad', type: 'numeric'},
		{name:'precio_unitario', type: 'numeric'},
		{name:'precio_total', type: 'numeric'},
		{name:'unidad_medida', type: 'string'},
		{name:'nandina', type: 'string'},
		{name:'bruto', type: 'string'},
		{name:'ley', type: 'string'},
		{name:'kg_fino', type: 'string'},
		{name:'descripcion', type: 'string'},
		{name:'unidad_cincepto', type: 'string'},
		{name:'precio_grupo', type: 'numeric'},
	
	],
	sortInfo:{
		field: 'id_cobro_simple',
		direction: 'DESC'
	},
	bdel:false,
	bsave:false,
	bnew:false,
	bedit:false,
	
	

   preparaMenu: function(n) {

		var data = this.getSelectedData();
		var tb = this.tbar;
		Phx.vista.Detalle.superclass.preparaMenu.call(this, n);
    
     
		return tb
	},

		
	liberaMenu: function() {
		var tb = Phx.vista.Detalle.superclass.liberaMenu.call(this);
		
	
                           
		return tb
	},

    
  

	onReloadPage: function (m) {
		//alert ('asda');
            this.maestro = m;
            this.store.baseParams = {id_venta: this.maestro.id_venta};
            this.load({params: {start: 0, limit: 50}})
        },
     loadValoresIniciales: function () {
            Phx.vista.Detalle.superclass.loadValoresIniciales.call(this);
            this.Cmp.id_venta.setValue(this.maestro.id_venta);
            
        }
    
    

})
</script>