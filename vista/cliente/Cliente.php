<?php
/**
*@package pXP
*@file gen-Cliente.php
*@author  (admin)
*@date 20-04-2015 08:52:44
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Cliente=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Cliente.superclass.constructor.call(this,config);
		this.init();
		this.iniciarEventos();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_cliente'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'nombre_factura',
				fieldLabel: 'Nombre Factura',
				allowBlank: false,
				anchor: '100%',
				gwidth: 170,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'cli.nombre_factura',type:'string'},
				id_grupo:2,
				grid:true,
				form:true,
                bottom_filter: true
		},
		{
			config:{
				name: 'nombres',
				fieldLabel: 'Nombres',
				allowBlank: true,
				anchor: '100%',
				gwidth: 200,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'cli.nombres',type:'string'},
				id_grupo:0,
				grid:true,
				form:true,
				bottom_filter: true
		},
		{
            config:{
                name: 'primer_apellido',
                fieldLabel: 'Primer Apellido',
                allowBlank: true,
                anchor: '100%',
                gwidth: 250,
                maxLength:100,
                enableKeyEvents:true
            },
                type:'TextField',
                filters:{pfiltro:'cli.primer_apellido',type:'string'},
                id_grupo:0,
                grid:true,
                form:true,
                bottom_filter: true
        },
        {
            config:{
                name: 'segundo_apellido',
                fieldLabel: 'Segundo Apellido',
                allowBlank: true,
                anchor: '100%',
                gwidth: 200,
                maxLength:100
            },
                type:'TextField',
                filters:{pfiltro:'cli.segundo_apellido',type:'string'},
                id_grupo:0,
                grid:true,
                form:true,
                bottom_filter: true
        },
        {
            config:{
                name: 'correo',
                fieldLabel: 'Correo',
                allowBlank: true,
               anchor: '100%',
                gwidth: 100,
                vtype:'email',  
                maxLength:150
            },
                type:'TextField',
                filters:{pfiltro:'cli.correo',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter: true
        },
        {
            config:{
                name: 'otros_correos',
                fieldLabel: 'Otros Correos',
                allowBlank: true,
                anchor: '100%',
                gwidth: 100,
                maxLength:255
            },
                type:'TextField',
                filters:{pfiltro:'cli.otros_correos',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },
        {
            config:{
                name: 'telefono_celular',
                fieldLabel: 'Telefono Celular',
                allowBlank: true,
                anchor: '100%',
                gwidth: 100,
                maxLength:20
            },
                type:'TextField',
                filters:{pfiltro:'cli.telefono_celular',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter: true
        },
        {
            config:{
                name: 'telefono_fijo',
                fieldLabel: 'Telefono Fijo',
                allowBlank: true,
                anchor: '100%',
                gwidth: 100,
                maxLength:20
            },
                type:'TextField',
                filters:{pfiltro:'cli.telefono_fijo',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter: true
        },
		{
			config:{
				name: 'otros_telefonos',
				fieldLabel: 'Otros Telefonos',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'cli.otros_telefonos',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		
		{
			config:{
				name: 'nit',
				fieldLabel: 'Nit',
				allowBlank: true,
				anchor: '100%',
				gwidth: 150,
				maxLength:25
			},
				type:'TextField',
				filters:{pfiltro:'cli.nit',type:'string'},
				id_grupo:2,
				grid:true,
				form:true,
                bottom_filter: true
		},
		
		{
			config:{
				name: 'direccion',
				fieldLabel: 'Dirección',
				allowBlank: true,
				anchor: '100%',
				gwidth: 150,
				maxLength:400
			},
				type:'TextArea',
				filters:{pfiltro:'cli.direccion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true,
                bottom_filter: true
		},
		
		{
			config:{
				name: 'lugar',
				fieldLabel: 'Lugar',
				qtip: 'para indicar la ciudad de recidencia del cliente, ejemplo, La Paz - Bolivia (esto facilitara as busquedas)',
	            allowBlank: true,
				anchor: '100%',
				gwidth: 150,
				maxLength:400
			},
			type:'TextArea',
			filters:{pfiltro:'cli.lugar',type:'string'},
			id_grupo:2,
			grid:true,
			form:true,
            bottom_filter: true
		},
		
		
		{
			config:{
				name: 'observaciones',
				fieldLabel: 'Observaciones',
				allowBlank: true,
				anchor: '100%',
				gwidth: 150,
				maxLength:400
			},
				type:'TextArea',
				filters:{pfiltro:'cli.observaciones',type:'string'},
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
				filters:{pfiltro:'cli.estado_reg',type:'string'},
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
				filters:{pfiltro:'cli.usuario_ai',type:'string'},
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
				filters:{pfiltro:'cli.fecha_reg',type:'date'},
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
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'cli.fecha_mod',type:'date'},
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
	title:'Cliente',
	ActSave:'../../sis_ventas_facturacion/control/Cliente/insertarCliente',
	ActDel:'../../sis_ventas_facturacion/control/Cliente/eliminarCliente',
	ActList:'../../sis_ventas_facturacion/control/Cliente/listarCliente',
	id_store:'id_cliente',
	fields: [
		{name:'id_cliente', type: 'numeric'},
		{name:'nombres', type: 'string'},
		{name:'otros_telefonos', type: 'string'},
		{name:'otros_correos', type: 'string'},
		{name:'direccion', type: 'string'},
		{name:'observaciones', type: 'string'},
		{name:'nit', type: 'string'},
		{name:'telefono_celular', type: 'string'},
		{name:'primer_apellido', type: 'string'},
		{name:'nombre_factura', type: 'string'},
		{name:'segundo_apellido', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'telefono_fijo', type: 'string'},
		{name:'correo', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},'direccion','lugar'
		
	],
	sortInfo:{
		field: 'id_cliente',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
	fheight: '65%',
	iniciarEventos : function() {		  
	   this.Cmp.primer_apellido.on('keyup',function(){
	       this.Cmp.nombre_factura.setValue(this.Cmp.primer_apellido.getValue());       
	   },this);
	    
	},
	rowExpander: new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<br>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Segundo Apellido:&nbsp;&nbsp;</b> {segundo_apellido}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Correo:&nbsp;&nbsp;</b> {correo}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Otros Correos:&nbsp;&nbsp;</b> {otros_correos}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Telefono Celular:&nbsp;&nbsp;</b> {telefono_celular}, <b>Telefono Fijo</b>: {telefono_fijo}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Otros Telefonos:&nbsp;&nbsp;</b> {otros_telefonos}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Nombre Factura:&nbsp;&nbsp;</b> {nombre_factura}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha de Registro:&nbsp;&nbsp;</b> {fecha_reg:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha Ult. Modificación:&nbsp;&nbsp;</b> {fecha_mod:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Creado por:&nbsp;&nbsp;</b> {usr_reg}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Modificado por:&nbsp;&nbsp;</b> {usr_mod}</p><br>'
            )
    }),
    
    arrayDefaultColumHidden:['otros_telefonos','otros_correos','telefono_celular','estado_reg','telefono_fijo','correo','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod','segundo_apellido'],

	Grupos: [
            {
                layout: 'column',
                border: false,
                // defaults are applied to all child items unless otherwise specified by child item
                defaults: {
                   // columnWidth: '.5',
                    border: false
                },            
                items: [{
                               
                                bodyStyle: 'padding-right:5px;',
                                items: [{
                                    xtype: 'fieldset',
                                    title: 'Datos Personales',
                                    autoHeight: true,
                                    items: [],
                                    id_grupo:0
                                }]
                            }, {
                                bodyStyle: 'padding-left:5px;',
                                items: [{
                                    xtype: 'fieldset',
                                    title: 'Datos de Contacto',
                                    autoHeight: true,
                                    items: [],
                                    id_grupo:1
                                }]
                            }
                        , {
                            bodyStyle: 'padding-left:5px;',
                            items: [{
                                xtype: 'fieldset',
                                title: 'Datos de Facturación',
                                autoHeight: true,
                                items: [],
                                id_grupo:2
                            }]
                        }]
            }
        ]
	}
)
</script>