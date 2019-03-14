<?php
/**
*@package pXP
*@file gen-VentaDetalle.php
*@author  (admin)
*@date 01-06-2015 09:21:07
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.VentaDetalleVb=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
	    this.config = config;
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.VentaDetalleVb.superclass.constructor.call(this,config);
		this.init();
		this.iniciarEventos();		
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_venta_detalle'
			},
			type:'Field',
			form:true 
		},
		
		{
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_venta'
            },
            type:'Field',
            form:false 
        },
        
        {
            config:{
                name: 'tipo',
                fieldLabel: 'Tipo detalle',
                allowBlank:false,
                emptyText:'Tipo...',
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                gwidth: 120,
                store:['producto_terminado','formula', 'servicio']
            },
                type:'ComboBox',
                filters:{   
                         type: 'list',
                         options: ['producto_terminado','formula', 'servicio'], 
                    },
                id_grupo:1,
                grid:true,
                form:false
        },
        		
		
		{
			config: {
				name: 'id_sucursal_producto',
				fieldLabel: 'Productos',
				allowBlank: false,
				emptyText: 'Servicios...',
				store: new Ext.data.JsonStore({
					url: '../../sis_ventas_farmacia/control/SucursalProducto/listarSucursalProducto',
					id: 'id_sucursal_producto',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_sucursal_producto', 'nombre_producto', 'precio'],
					remoteSort: true,
					baseParams: {par_filtro: 'sprod.nombre_producto'}
				}),
				valueField: 'id_sucursal_producto',
				displayField: 'nombre_producto',
				gdisplayField: 'nombre_producto',
				hiddenName: 'id_sucursal_producto',
				forceSelection: true,
				tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre_producto}</p><p>Precio.: {precio}</p></div></tpl>',
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['nombre_producto']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'sprod.nombre_producto',type: 'string'},
			grid: true,
			form: false,
            bottom_filter: true
		},
		
		{
			config:{
				name: 'descripcion',
				fieldLabel: 'Descripcion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 180,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'vedet.descripcion',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		
		
		{
			config:{
				name: 'cantidad',
				fieldLabel: 'Cantidad',
				allowBlank: false,
				anchor: '80%',
				gwidth: 80,
				maxLength:4
			},
				type:'NumberField',
				filters:{pfiltro:'vedet.cantidad',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:false
		},
		
		
		
		
		{
            config:{
                name: 'precio_unitario',
                fieldLabel: 'P / Unit',               
                gwidth: 90
            },
                type:'NumberField',                              
                grid:true,
                form:false
        },
		{
			config:{
				name: 'precio_total',
				fieldLabel: 'Total',				
				gwidth: 100
			},
				type:'NumberField',
				filters:{pfiltro:'vedet.precio',type:'numeric'},				
				grid:true,
				form:false
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
                filters:{pfiltro:'vedet.estado_reg',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
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
				filters:{pfiltro:'vedet.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config: {
				name: 'estado',
				fieldLabel: 'Estado',
				anchor: '100%',
				tinit: false,
				allowBlank: false,
				origen: 'CATALOGO',
				gdisplayField: 'forma_pago',
				gwidth: 100,
				baseParams:{
						cod_subsistema:'VEF',
						catalogo_tipo:'estado_detalle'
				},
				renderer:function (value, p, record){return String.format('{0}', record.data['estado']);}
			},
			type: 'ComboRec',
			id_grupo: 0,
			filters:{pfiltro:'vedet.estado',type:'string'},
			grid: true,
			form: true
		},
		
		
		{
			config:{
				name: 'serie',
				fieldLabel: 'Nº Serie',
				allowBlank: true,
				anchor: '80%',
				gwidth: 80,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'vedet.serie',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		
		{
			config:{
				name: 'obs',
				fieldLabel: 'Obs',
				allowBlank: true,
				anchor: '80%',
				gwidth: 80
			},
				type:'TextArea',
				filters:{pfiltro:'vedet.obs',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
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
				filters:{pfiltro:'vedet.usuario_ai',type:'string'},
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
				filters:{pfiltro:'vedet.fecha_reg',type:'date'},
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
				filters:{pfiltro:'vedet.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Detalle de Venta',
	ActSave:'../../sis_ventas_facturacion/control/VentaDetalle/actulizarVentaDetallePedido',
	ActDel:'../../sis_ventas_facturacion/control/VentaDetalle/eliminarVentaDetalle',
	ActList:'../../sis_ventas_facturacion/control/VentaDetalle/listarVentaDetalleVb',
	id_store:'id_venta_detalle',
	fields: [
		{name:'id_venta_detalle', type: 'numeric'},
		{name:'id_venta', type: 'numeric'},
		{name:'id_item', type: 'numeric'},
		{name:'nombre_item', type: 'string'},
		{name:'nombre_formula', type: 'string'},
		{name:'nombre_producto', type: 'string'},
		{name:'id_sucursal_producto', type: 'numeric'},
		{name:'id_formula', type: 'numeric'},
		{name:'tipo', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'cantidad', type: 'numeric'},
		{name:'precio_unitario', type: 'numeric'},
		{name:'precio_total', type: 'numeric'},
		{name:'sw_porcentaje_formula', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},'estado','obs','serie','descripcion'
		
		
		
	],
	sortInfo:{
		field: 'id_venta_detalle',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	bnew:false,
	bedit:true,
	
	
	onReloadPage : function(m) {
        this.maestro=m;        
        this.store.baseParams={id_venta:this.maestro.id_venta};
        this.load({params:{start:0, limit:50}});
            
    },
    

   
   arrayDefaultColumHidden:['estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod'],
   
  
    
  
    
	}
)
</script>
		
		