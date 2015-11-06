<?php
/**
*@package pXP
*@file gen-PuntoVentaProducto.php
*@author  (jrivera)
*@date 07-10-2015 21:02:03
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.PuntoVentaProducto=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.PuntoVentaProducto.superclass.constructor.call(this,config);
		this.init();
		this.store.baseParams.id_punto_venta = this.maestro.id_punto_venta;
		this.load({params:{start:0, limit:this.tam_pag}});
		this.Cmp.id_sucursal_producto.store.baseParams.id_sucursal = this.maestro.id_sucursal;
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_punto_venta_producto'
			},
			type:'Field',
			form:true 
		},
		
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_punto_venta'
			},
			type:'Field',
			form:true 
		},
		
		{
			config: {
				name: 'id_sucursal_producto',
				fieldLabel: 'Producto',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_ventas_facturacion/control/SucursalProducto/listarSucursalProducto',
					id: 'id_sucursal_producto',
					root: 'datos',
					sortInfo: {
						field: 'id_sucursal_producto',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_sucursal_producto', 'nombre_producto', 'descripcion_producto'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo', tipo:'producto,servicio'}
				}),
				valueField: 'id_sucursal_producto',
				displayField: 'nombre_producto',
				gdisplayField: 'nombre_producto',
				hiddenName: 'id_sucursal_producto',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 200,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['nombre_producto']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'cig.desc_cingas',type: 'string'},
			grid: true,
			form: true
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
				filters:{pfiltro:'puvepro.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
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
				filters:{pfiltro:'puvepro.usuario_ai',type:'string'},
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
				filters:{pfiltro:'puvepro.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
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
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'puvepro.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				grid:true,
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
				filters:{pfiltro:'puvepro.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Punto de Venta Producto',
	ActSave:'../../sis_ventas_facturacion/control/PuntoVentaProducto/insertarPuntoVentaProducto',
	ActDel:'../../sis_ventas_facturacion/control/PuntoVentaProducto/eliminarPuntoVentaProducto',
	ActList:'../../sis_ventas_facturacion/control/PuntoVentaProducto/listarPuntoVentaProducto',
	id_store:'id_punto_venta_producto',
	fields: [
		{name:'id_punto_venta_producto', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'id_sucursal_producto', type: 'numeric'},
		{name:'id_punto_venta', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'nombre_producto', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_punto_venta_producto',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	loadValoresIniciales:function()
    {
        this.Cmp.id_punto_venta.setValue(this.maestro.id_punto_venta);             
        Phx.vista.PuntoVentaProducto.superclass.loadValoresIniciales.call(this);
    }
    
	}
)
</script>
		
		