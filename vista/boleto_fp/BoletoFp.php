<?php
/**
*@package pXP
*@file gen-BoletoFp.php
*@author  (jrivera)
*@date 26-11-2015 22:03:35
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.BoletoFp=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.BoletoFp.superclass.constructor.call(this,config);
		this.init();
		
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_boleto_fp'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_boleto'
			},
			type:'Field',
			form:true 
		},
		
		{
            config: {
                name: 'id_forma_pago',
                fieldLabel: 'Forma de Pago',
                allowBlank: false,
                emptyText: 'Forma de Pago...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_ventas_facturacion/control/FormaPago/listarFormaPago',
                    id: 'id_forma_pago',
                    root: 'datos',
                    sortInfo: {
                        field: 'nombre',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_forma_pago', 'nombre', 'desc_moneda','registrar_tarjeta','registrar_cc'],
                    remoteSort: true,
                    baseParams: {par_filtro: 'forpa.nombre#mon.codigo'}
                }),
                valueField: 'id_forma_pago',
                displayField: 'nombre',
                gdisplayField: 'forma_pago',
                hiddenName: 'id_forma_pago',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 15,
                queryDelay: 1000,               
                gwidth: 200,
                minChars: 2,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['forma_pago']);
                }
            },
            type: 'ComboBox',          
            grid: true,
            form: true
        },
        {
			config:{
				name: 'monto',
				fieldLabel: 'Monto',
				allowBlank: true,
				anchor: '80%',
				gwidth: 150,
				maxLength:1179650
			},
				type:'NumberField',
				filters:{pfiltro:'bolfp.monto',type:'numeric'},
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
				filters:{pfiltro:'bolfp.estado_reg',type:'string'},
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
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'bolfp.usuario_ai',type:'string'},
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
				filters:{pfiltro:'bolfp.fecha_reg',type:'date'},
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
				filters:{pfiltro:'bolfp.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'bolfp.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	onReloadPage:function(m, x){  		 
		this.maestro=m;
		this.store.baseParams.id_boleto = this.maestro.id_boleto;
		
		this.Cmp.id_forma_pago.store.baseParams.id_punto_venta = this.maestro.id_punto_venta;
		this.load({params:{start:0, limit:this.tam_pag}});
	},
	loadValoresIniciales: function() {    	
        this.Cmp.id_boleto.setValue(this.maestro.id_boleto);            
        Phx.vista.BoletoFp.superclass.loadValoresIniciales.call(this);
   },
	tam_pag:50,	
	title:'Boleto Forma de Pago',
	ActSave:'../../sis_ventas_facturacion/control/BoletoFp/insertarBoletoFp',
	ActDel:'../../sis_ventas_facturacion/control/BoletoFp/eliminarBoletoFp',
	ActList:'../../sis_ventas_facturacion/control/BoletoFp/listarBoletoFp',
	id_store:'id_boleto_fp',
	fields: [
		{name:'id_boleto_fp', type: 'numeric'},
		{name:'id_boleto', type: 'numeric'},
		{name:'id_forma_pago', type: 'numeric'},
		{name:'forma_pago', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'monto', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_boleto_fp',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	successSave: function(resp) {
        Phx.CP.getPagina(this.idContenedorPadre).reload();
        Phx.vista.BoletoFp.superclass.successSave.call(this,resp);
    },
    
    successDel: function(resp) {
        Phx.CP.getPagina(this.idContenedorPadre).reload();
        Phx.vista.BoletoFp.superclass.successDel.call(this,resp);
    },
	
	
	}
)
</script>
		
		