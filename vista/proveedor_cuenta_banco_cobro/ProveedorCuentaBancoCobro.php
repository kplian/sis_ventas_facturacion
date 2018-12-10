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
			//configuracion del componente
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
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_proveedor']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nombre',type: 'string'},
			grid: true,
			form: true
		},
        {
            config: {
                name: 'id_cuenta_bancaria',
                fieldLabel: 'Cuenta Bancaria',
                allowBlank: true,
                emptyText: 'Elija una opción...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_tesoreria/control/CuentaBancaria/listarCuentaBancaria',
                    id: 'id_cuenta_bancaria',
                    root: 'datos',
                    sortInfo: {
                        field: 'nro_cuenta',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_cuenta_bancaria', 'nro_cuenta', 'nombre_institucion'],
                    remoteSort: true,
                    baseParams: {par_filtro: 'nombre_institucion'}
                }),
                valueField: 'id_cuenta_bancaria',
                displayField: 'nro_cuenta',
                gdisplayField: 'nro_cuenta',
                hiddenName: 'id_cuenta_bancaria',
                forceSelection: true,
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
                    return String.format('{0}', record.data['nro_cuenta']);
                }
            },
            type: 'ComboBox',
            id_grupo: 0,
            filters: {pfiltro: 'movtip.nombre',type: 'string'},
            grid: true,
            form: true
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
           // valorInicial: 'no',
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
		{name:'estado_reg', type: 'string'},
		{name:'tipo', type: 'string'},
		{name:'id_cuenta_bancaria', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
        {name:'id_institucion', type: 'numeric'},
        {name:'desc_proveedor', type: 'string'},
        {name:'rotulo_comercial', type: 'string'},
        {name:'nro_cuenta', type: 'string'},
        {name:'denominacion', type: 'string'}
		
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


    },
    iniciarEvento:function () {

        //this.Cmp.id_proveedor.store.baseParams ={par_filtro: 'nombre_institucion'};
        //var f = this.Cmp.formulario.getValue();
    }
	}
)
</script>
		
		