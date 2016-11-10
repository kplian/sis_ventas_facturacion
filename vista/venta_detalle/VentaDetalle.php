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
Phx.vista.VentaDetalle=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
	    this.config = config;
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.VentaDetalle.superclass.constructor.call(this,config);
		this.init();
        this.grid.on('beforeedit',this.onbeforeedit,this);
        //this.grid.addListener('celldblclick', this.oncelldblclick,this);
	},
        onbeforeedit : function(parametros) {

        if (parametros.field == 'descripcion' && parametros.record.data.requiere_descripcion == 'no') {
            alert('No es posible registrar el numero de boleto para este concepto');
            return false;

        } else {
            return true;
        }

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
            form:true 
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
                form:true
        },

		{
			config: {
				name: 'id_sucursal_producto',
				fieldLabel: 'Servicio',
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
			form: true,
            bottom_filter: true
		},
        {
            config:{
                name: 'descripcion',
                fieldLabel: 'Boleto',
                allowBlank: true,
                anchor: '80%',
                gwidth: 120
            },
            type:'TextField',
            id_grupo:1,
            grid:true,
            egrid:true,
            form:true
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
				form:true
		},
		
		{
            config:{
                name: 'precio',
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
		}

	],
	tam_pag:50,	
	title:'Detalle de Venta',
	ActSave:'../../sis_ventas_facturacion/control/VentaDetalle/insertarVentaDetalle',
	ActDel:'../../sis_ventas_farmacia/control/VentaDetalle/eliminarVentaDetalle',
	ActList:'../../sis_ventas_facturacion/control/VentaDetalle/listarVentaDetalle',
	id_store:'id_venta_detalle',
	fields: [
		{name:'id_venta_detalle', type: 'numeric'},
		{name:'id_venta', type: 'numeric'},
		{name:'id_item', type: 'numeric'},
		{name:'nombre_item', type: 'string'},
        {name:'descripcion', type: 'string'},
		{name:'nombre_formula', type: 'string'},
		{name:'nombre_producto', type: 'string'},
		{name:'id_sucursal_producto', type: 'numeric'},
        {name:'requiere_descripcion', type: 'string'},
		{name:'id_formula', type: 'numeric'},
		{name:'tipo', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'cantidad', type: 'numeric'},
		{name:'precio', type: 'numeric'},
		{name:'precio_total', type: 'numeric'},
		{name:'sw_porcentaje_formula', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_venta_detalle',
		direction: 'ASC'
	},
	bdel:false,
    bnew:false,
    bedit:false,
	bsave:true,
	onReloadPage : function(m) {
        this.maestro=m;
        this.Atributos[1].valorInicial = this.maestro.id_venta;
        this.store.baseParams={id_venta:this.maestro.id_venta};
        this.load({params:{start:0, limit:50}});
            
    },

    
    preparaMenu:function()
    {
        var rec = this.sm.getSelected();
        if (rec.data.requiereDescripcion == 'si') {
            this.Cmp.descripcion.setDisabled(false);
        } else {
            this.Cmp.descripcion.setDisabled(true);
        }
        Phx.vista.VentaDetalle.superclass.preparaMenu.call(this);

    },
    
    liberaMenu:function()
    {
        Phx.vista.VentaDetalle.superclass.liberaMenu.call(this);

    }
    
	}
)
</script>
		
		