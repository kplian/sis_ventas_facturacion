<?php
/**
*@package pXP
*@file gen-ProveedorCuentaBancoCobro.php
*@author  (m.mamani)
*@date 22-11-2018 22:19:44
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ProveedorCuentaBancoCobro=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.ProveedorCuentaBancoCobro.superclass.constructor.call(this,config);
		this.init();

		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_proveedor_cuenta_banco_cobro'
			},
			type:'Field',
			form:true 
		},
        {
            config: {
                name: 'id_institucion',
                fieldLabel: 'Institucion',
                tinit: true,
                allowBlank: false,
                origen: 'INSTITUCION',
                baseParams:{es_banco:'si'},
                gdisplayField: 'nombre_institucion',
                gwidth: 200,
                renderer:function (value, p, record){return String.format('{0}', record.data['desc_nombre']);}
            },
            type: 'ComboRec',
            id_grupo: 0,
            filters:{pfiltro:'ins.nombre',type:'string'},
            grid: true,
            form: true
        },
        {
            config:{
                name: 'nro_cuenta_bancario',
                fieldLabel: 'Nro Cuenta',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200,
                maxLength:50
            },
            type:'TextField',
            filters:{pfiltro:'pcc.nro_cuenta_bancario',type:'string'},
            id_grupo:1,
            bottom_filter: true,
            grid:true,
            form:true
        },
		{
			config: {
				name: 'id_proveedor',
				fieldLabel: 'Proveedor',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_parametros/control/Proveedor/listarProveedorCombos',
					id: 'id_proveedor',
					root: 'datos',
					sortInfo: {
						field: 'desc_proveedor',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_proveedor', 'desc_proveedor', 'nit'],
					remoteSort: true,
					baseParams: {par_filtro: 'desc_proveedor'}
				}),
				valueField: 'id_proveedor',
				displayField: 'desc_proveedor',
				gdisplayField: 'desc_proveedor',
				hiddenName: 'id_proveedor',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
                gwidth: 230,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_proveedor']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'pr.desc_proveedor',type: 'string'},
			grid: true,
			form: true
		},
        {
            config:{
                name: 'fecha_alta',
                fieldLabel: 'Fecha Alta',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            filters:{pfiltro:'pcc.fecha_alta',type:'date'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name:'id_moneda',
                origen:'MONEDA',
                allowBlank:true,
                fieldLabel:'Moneda',
                gdisplayField:'codigo_moneda',//mapea al store del grid
                gwidth:50,
                  renderer:function (value, p, record){return String.format('{0}', record.data['desc_moneda']);}
            },
            type:'ComboRec',
            id_grupo:1,
            filters:{
                pfiltro:'mo.codigo_internacional',
                type:'string'
            },
            grid:true,
            form:true
        },
        {
            config:{
                name: 'fecha_baja',
                fieldLabel: 'Fecha Baja',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            filters:{pfiltro:'pcc.fecha_baja',type:'date'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'tipo',
                fieldLabel: 'Tipo',
                allowBlank: true,
                anchor: '80%',
                gwidth: 50,
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                store:['bien','servicio']
            },
            type:'ComboBox',
            id_grupo:0,
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
				filters:{pfiltro:'pcc.estado_reg',type:'string'},
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
				filters:{pfiltro:'pcc.fecha_reg',type:'date'},
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
				filters:{pfiltro:'pcc.usuario_ai',type:'string'},
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
				filters:{pfiltro:'pcc.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'pcc.fecha_mod',type:'date'},
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
		}
	],
	tam_pag:50,	
	title:'Proveedor Cuenta Banco Cobro',
	ActSave:'../../sis_ventas_facturacion/control/ProveedorCuentaBancoCobro/insertarProveedorCuentaBancoCobro',
	ActDel:'../../sis_ventas_facturacion/control/ProveedorCuentaBancoCobro/eliminarProveedorCuentaBancoCobro',
	ActList:'../../sis_ventas_facturacion/control/ProveedorCuentaBancoCobro/listarProveedorCuentaBancoCobro',
	id_store:'id_proveedor_cuenta_banco_cobro',
	fields: [
		{name:'id_proveedor_cuenta_banco_cobro', type: 'numeric'},
		{name:'id_proveedor', type: 'numeric'},
        {name:'id_institucion', type: 'numeric'},
        {name:'id_moneda', type: 'numeric'},
        {name:'desc_proveedor', type: 'string'},
        {name:'tipo', type: 'string'},
        {name:'desc_nombre', type: 'string'},
        {name:'desc_moneda', type: 'string'},
        {name:'fecha_alta', type: 'date',dateFormat:'Y-m-d'},
        {name:'fecha_baja', type: 'date',dateFormat:'Y-m-d'},
        {name:'nro_cuenta_bancario', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'}
	],
	sortInfo:{
		field: 'id_proveedor_cuenta_banco_cobro',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
    onButtonNew:function(){
        Phx.vista.ProveedorCuentaBancoCobro.superclass.onButtonNew.call(this);
        this.iniciarEvento();
    }
	}
)
</script>
		
		