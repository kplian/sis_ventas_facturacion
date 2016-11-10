<?php
/**
*@package pXP
*@file gen-PuntoVenta.php
*@author  (jrivera)
*@date 07-10-2015 21:02:00
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.PuntoVenta=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.PuntoVenta.superclass.constructor.call(this,config);
		this.init();
		this.store.baseParams.id_sucursal = this.maestro.id_sucursal; 
		this.load({params:{start:0, limit:this.tam_pag}});
		this.addButton('btnProductos',
            {
                text: 'Productos',
                iconCls: 'blist',
                disabled: true,                
                handler: this.onButtonProductos,
                tooltip: 'Productos por Punto de Venta'                
            }
        );
	},
	onButtonProductos : function() {
        var rec = {maestro: this.sm.getSelected().data};
                              
            Phx.CP.loadWindows('../../../sis_ventas_facturacion/vista/punto_venta_producto/PuntoVentaProducto.php',
                    'Productos por punto de venta',
                    {
                        width:800,
                        height:'80%'
                    },
                    rec,
                    this.idContenedor,
                    'PuntoVentaProducto');
    },
			
	Atributos:[
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
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_sucursal'
			},
			type:'Field',
			form:true 
		},
		{
            config:{
                name: 'codigo',
                fieldLabel: 'Código',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:20
            },
                type:'TextField',
                filters:{pfiltro:'puve.codigo',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },
			
		{
			config:{
				name: 'nombre',
				fieldLabel: 'Nombre',
				allowBlank: false,
				anchor: '80%',
				gwidth: 150,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'puve.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'descripcion',
				fieldLabel: 'Descripción',
				allowBlank: true,
				anchor: '80%',
				gwidth: 200,
				maxLength:500
			},
				type:'TextField',
				filters:{pfiltro:'puve.descripcion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config : {
				name : 'tipo',
				fieldLabel : 'Tipo',
				anchor : '90%',
				tinit : false,
				allowBlank : false,
				origen : 'CATALOGO',
				gdisplayField : 'tipo',
				gwidth : 200,
				anchor : '100%',
				valueField: 'codigo',
				baseParams : {
					cod_subsistema : 'VEF',
					catalogo_tipo : 'tipo_punto_venta'
				}
			},
			type : 'ComboRec',
			id_grupo : 0,
			filters : {
				pfiltro : 'puve.tipo',
				type : 'string'
			},
			grid : true,
			form : true
		},
		{
            config:{
                name: 'habilitar_comisiones',
                fieldLabel: 'Habilitar Comisiones',
                allowBlank: false,
                anchor: '80%',
                gwidth: 130,
                maxLength:2,
                emptyText:'si/no...',                   
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
               // displayField: 'descestilo',
                store:['si','no']
            },
            type:'ComboBox',
            //filters:{pfiltro:'promac.inicio',type:'string'},
            id_grupo:1,
            filters:{   
                         type: 'list',
                         pfiltro:'puve.habilitar_comisiones',
                         options: ['si','no'],  
                    },
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
				filters:{pfiltro:'puve.estado_reg',type:'string'},
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
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'puve.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'puve.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'puve.usuario_ai',type:'string'},
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
				filters:{pfiltro:'puve.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Punto de Venta',
	ActSave:'../../sis_ventas_facturacion/control/PuntoVenta/insertarPuntoVenta',
	ActDel:'../../sis_ventas_facturacion/control/PuntoVenta/eliminarPuntoVenta',
	ActList:'../../sis_ventas_facturacion/control/PuntoVenta/listarPuntoVenta',
	id_store:'id_punto_venta',
	fields: [
		{name:'id_punto_venta', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'tipo', type: 'string'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'nombre', type: 'string'},
		{name:'codigo', type: 'string'},
		{name:'habilitar_comisiones', type: 'string'},
		{name:'descripcion', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_punto_venta',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	loadValoresIniciales:function()
    {
    	this.Cmp.id_sucursal.setValue(this.maestro.id_sucursal);       
        Phx.vista.PuntoVenta.superclass.loadValoresIniciales.call(this);        
    },
    east:
    { 
              url:'../../../sis_ventas_facturacion/vista/sucursal_usuario/SucursalUsuario.php',
              title:'Usuarios', 
              width:'40%',
              cls:'SucursalUsuario'
    },
	
	preparaMenu:function()
    {   
        this.getBoton('btnProductos').enable();        
        Phx.vista.PuntoVenta.superclass.preparaMenu.call(this);
    },
    
    liberaMenu:function()
    {   
        this.getBoton('btnProductos').disable();         
        Phx.vista.PuntoVenta.superclass.liberaMenu.call(this);
    }
   }
)
</script>
		
		