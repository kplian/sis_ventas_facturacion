<?php
/**
*@package pXP
*@file gen-ValorDescripcion.php
*@author  (admin)
*@date 23-04-2016 14:24:45
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ValorDescripcion=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.ValorDescripcion.superclass.constructor.call(this,config);
		this.init();
		this.bloquearMenus();
        
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_valor_descripcion'
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
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_tipo_Descripcion'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'valor_label',
				fieldLabel: 'Atributo',
				allowBlank: false,
				anchor: '80%',
				gwidth: 150,
				maxLength:300
			},
				type:'TextField',
				filters: { pfiltro:'vald.valor_label',type:'string' },
				id_grupo:1,
				grid:true,
				egrid:true,
				form:false
		},
		{
			config:{
				name: 'valor',
				fieldLabel: 'valor',
				allowBlank: false,
				anchor: '80%',
				gwidth: 150,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'vald.valor',type:'string'},
				id_grupo:1,
				grid:true,
				egrid: true,
				form:false
		},
		{
			config:{
				name: 'obs',
				fieldLabel: 'obs',
				allowBlank: true,
				anchor: '80%',
				gwidth: 200,
				maxLength:400
			},
				type:'TextArea',
				filters:{pfiltro:'vald.obs',type:'string'},
				id_grupo:1,
				egrid: true,
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
				filters:{pfiltro:'vald.estado_reg',type:'string'},
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
				filters:{pfiltro:'vald.fecha_reg',type:'date'},
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
				filters:{pfiltro:'vald.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'vald.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'vald.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Valores',
	ActSave:'../../sis_ventas_facturacion/control/ValorDescripcion/insertarValorDescripcion',
	ActDel:'../../sis_ventas_facturacion/control/ValorDescripcion/eliminarValorDescripcion',
	ActList:'../../sis_ventas_facturacion/control/ValorDescripcion/listarValorDescripcion',
	id_store:'id_valor_descripcion',
	fields: [
		{name:'id_valor_descripcion', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'valor', type: 'string'},
		{name:'id_tipo_descripcion', type: 'numeric'},
		{name:'obs', type: 'string'},
		{name:'id_venta', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'}, 'obs_tipo','codigo','nombre','valor_label'
		
	],
	sortInfo:{
		field: 'id_valor_descripcion',
		direction: 'ASC'
	},
	bdel: true,
	bedit: false,
	bsave: true,
	bnew: false,
	onReloadPage:function(m)
    {
        this.maestro=m;                     
        this.store.baseParams={id_venta:this.maestro.id_venta};
        this.load({params:{start:0, limit:50}});            
    }
})
</script>
		
		