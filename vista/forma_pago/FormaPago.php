<?php
/**
*@package pXP
*@file gen-FormaPago.php
*@author  (jrivera)
*@date 08-10-2015 13:29:06
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormaPago=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.FormaPago.superclass.constructor.call(this,config);
		this.init();
		
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_forma_pago'
			},
			type:'Field',
			form:true 
		},
		
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_entidad'
			},
			type:'Field',
			form:true 
		},
		
		{
			config:{
				name: 'codigo',
				fieldLabel: 'Codigo',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'forpa.codigo',type:'string'},
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
				gwidth: 200,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'forpa.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		
		{
            config:{
                name: 'defecto',
                fieldLabel: 'Forma de Pago por Defecto',
                allowBlank: false,               
                gwidth: 130,
                maxLength:15,
                emptyText:'tipo...',                   
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
               // displayField: 'descestilo',
                store:['si','no']
            },
            type:'ComboBox',
            //filters:{pfiltro:'promac.inicio',type:'string'},
            id_grupo:0,
            filters:{   
                         type: 'list',
                         pfiltro:'forpa.defecto',
                         options: ['si','no']
                    },
            grid:true,
            form:true
        }, 
        {
            config:{
                name: 'registrar_tarjeta',
                fieldLabel: 'Registrar Datos de Tarjeta',
                allowBlank: false,               
                gwidth: 130,
                maxLength:15,
                emptyText:'tipo...',                   
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
               // displayField: 'descestilo',
                store:['si','no']
            },
            type:'ComboBox',
            //filters:{pfiltro:'promac.inicio',type:'string'},
            id_grupo:0,
            filters:{   
                         type: 'list',
                         pfiltro:'forpa.registrar_tarjeta',
                         options: ['si','no']
                    },
            grid:true,
            form:true,
            valorInicial:'no'
        }, 
        
        {
            config:{
                name: 'registrar_tipo_tarjeta',
                fieldLabel: 'Registrar Tipo de Tarjeta',
                allowBlank: false,               
                gwidth: 130,
                maxLength:15,
                emptyText:'registrar...',                   
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
               // displayField: 'descestilo',
                store:['si','no']
            },
            type:'ComboBox',
            //filters:{pfiltro:'promac.inicio',type:'string'},
            id_grupo:0,
            filters:{   
                         type: 'list',
                         pfiltro:'forpa.registrar_tipo_tarjeta',
                         options: ['si','no']
                    },
            grid:true,
            form:true,
            valorInicial:'no'
        }, 
        {
            config:{
                name: 'registrar_cc',
                fieldLabel: 'Registrar Cuenta Corriente',
                allowBlank: false,               
                gwidth: 130,
                maxLength:15,
                emptyText:'tipo...',                   
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
               // displayField: 'descestilo',
                store:['si','no']
            },
            type:'ComboBox',
            //filters:{pfiltro:'promac.inicio',type:'string'},
            id_grupo:0,
            filters:{   
                         type: 'list',
                         pfiltro:'forpa.registrar_cc',
                         options: ['si','no']
                    },
            grid:true,
            form:true,
            valorInicial:'no'
        }, 
			
		{
            config:{
                name:'id_moneda',
                origen:'MONEDA',
                 allowBlank:false,
                fieldLabel:'Moneda',
                gdisplayField:'desc_moneda',//mapea al store del grid
                gwidth:50,
                 renderer:function (value, p, record){return String.format('{0}', record.data['desc_moneda']);}
             },
            type:'ComboRec',
            id_grupo:1,
            filters:{   
                pfiltro:'mon.codigo',
                type:'string'
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
				filters:{pfiltro:'forpa.estado_reg',type:'string'},
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
				filters:{pfiltro:'forpa.fecha_reg',type:'date'},
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
				filters:{pfiltro:'forpa.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'forpa.usuario_ai',type:'string'},
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
				filters:{pfiltro:'forpa.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Forma de Pago',
	ActSave:'../../sis_ventas_facturacion/control/FormaPago/insertarFormaPago',
	ActDel:'../../sis_ventas_facturacion/control/FormaPago/eliminarFormaPago',
	ActList:'../../sis_ventas_facturacion/control/FormaPago/listarFormaPago',
	id_store:'id_forma_pago',
	fields: [
		{name:'id_forma_pago', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'codigo', type: 'string'},
		{name:'defecto', type: 'string'},
		{name:'registrar_tarjeta', type: 'string'},
		{name:'registrar_cc', type: 'string'},
		{name:'nombre', type: 'string'},
		{name:'desc_moneda', type: 'string'},
		{name:'id_entidad', type: 'numeric'},
		{name:'id_moneda', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'registrar_tipo_tarjeta', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_forma_pago',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	onReloadPage:function(m)
    {
        this.maestro=m;         
		this.store.baseParams.id_entidad = this.maestro.id_entidad; 
        this.load({params:{start:0, limit:50}});            
    },
    loadValoresIniciales:function()
    {
        Phx.vista.FormaPago.superclass.loadValoresIniciales.call(this);        
		this.getComponente('id_entidad').setValue(this.maestro.id_entidad);
		 
    }
	}
)
</script>
		
		