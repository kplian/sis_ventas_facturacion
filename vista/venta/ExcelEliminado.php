<?php
/**
*@package pXP
*@file gen-TemporalData.php
*@author  (eddy.gutierrez)
*@date 06-11-2018 20:39:08
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 *  ISSUE				FECHA		AUTOR				DESCRIPCION
 	#4	endeETR	 	21/02/2019		EGS					Se agrego campos para punto de venta 
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.TemporalData=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config;
		console.log(config);
    	//llama al constructor de la clase padre
		Phx.vista.TemporalData.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag , id_punto_venta :this.maestro.data.id_punto_venta }})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_temporal_data'
			},
			type:'Field',
			form:true 
		},
		{//#4
			config:{
				name: 'nombre_punto_venta',
				fieldLabel: 'Punto de Venta',
				allowBlank: true,
				anchor: '80%',
				gwidth: 200,
				maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'dad.razon_social',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'razon_social',
				fieldLabel: 'Razon Social',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'dad.razon_social',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'dad.estado_reg',type:'string'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'nro',
				fieldLabel: 'Nro',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'dad.nro',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'total_detalle',
				fieldLabel: 'Total Detalle',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'dad.nro_factura',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'total_venta',
				fieldLabel: 'Total Venta',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'dad.nro_factura',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'total_detalle_usd',
				fieldLabel: 'Total Detalle USD',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100
				//maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'dad.nro_factura',type:'string'},
				id_grupo:1,
				grid:false,
				form:true
		},
		{
			config:{
				name: 'total_venta_usd',
				fieldLabel: 'Total Venta USD',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100
				//maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'dad.nro_factura',type:'string'},
				id_grupo:1,
				grid:false,
				form:true
		},
		{
			config:{
				name: 'error',
				fieldLabel: 'Error',
				allowBlank: true,
				anchor: '80%',
				gwidth: 300,
				maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'dad.error',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'dad.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'dad.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'dad.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu2.cuenta',type:'string'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'dad.fecha_mod',type:'date'},
				id_grupo:1,
				grid:false,
				form:false
		}
	],
	tam_pag:50,	
	title:'dad',
	/*
	ActSave:'../../sis_ventas_facturacion/control/TemporalData/insertarTemporalData',
	ActDel:'../../sis_ventas_facturacion/control/TemporalData/eliminarTemporalData',
	ActList:'../../sis_ventas_facturacion/control/TemporalData/listarTemporalData',*/
	
	ActList:'../../sis_ventas_facturacion/control/SubirArchivoFac/listarExcelEliminado',

	id_store:'id_temporal_data',
	fields: [
		{name:'id_temporal_data', type: 'numeric'},
		{name:'razon_social', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'nro_factura', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'total_venta', type: 'numeric'},
		{name:'total_detalle', type: 'numeric'},
		{name:'total_venta_usd', type: 'numeric'},
		{name:'total_detalle_usd', type: 'numeric'},
		{name:'nro', type: 'string'},
		{name:'error', type: 'string'},
		{name:'id_punto_venta', type: 'numeric'},//#4
		{name:'nombre_punto_venta', type: 'string'},//#4
		
		
	],
	sortInfo:{
		field: 'id_temporal_data',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	bnew:false,
	bedit:false
	}
)
</script>
		
		