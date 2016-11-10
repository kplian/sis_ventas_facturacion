<?php
/**
*@package pXP
*@file gen-Boleto.php
*@author  (jrivera)
*@date 26-11-2015 22:03:32
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Boleto=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
		Ext.Ajax.request({
                url:'../../sis_ventas_facturacion/control/Venta/getVariablesBasicas',                
                params: {'prueba':'uno'},
                success:this.successGetVariables,
                failure: this.conexionFailure,
                arguments:config,
                timeout:this.timeout,
                scope:this
            });	
    	
	},
	successGetVariables : function (response,request) {
		//llama al constructor de la clase padre
		Phx.vista.Boleto.superclass.constructor.call(this,request.arguments);
		
		var datos_respuesta = JSON.parse(response.responseText);
    	var fecha_array = datos_respuesta.datos.fecha.split('/');
    	
    	this.grid.getTopToolbar().doLayout();
    	    	
		this.init();
		this.iniciarEventos();
		this.seleccionarPuntoVentaSucursal();
		this.ultimo_valor = 930;
		
	},
	
	
	seleccionarPuntoVentaSucursal : function () {
		
			var storeCombo = new Ext.data.JsonStore({
	                    url: '../../sis_ventas_facturacion/control/PuntoVenta/listarPuntoVenta',
	                    id: 'id_punto_venta',
	                    root: 'datos',
	                    sortInfo: {
	                        field: 'nombre',
	                        direction: 'ASC'
	                    },
	                    totalProperty: 'total',
	                    fields: ['id_punto_venta', 'nombre', 'codigo','habilitar_comisiones'],
	                    remoteSort: true,
	                    baseParams: {par_filtro: 'puve.nombre#puve.codigo'}
	        });
			
	    
	    storeCombo.load({params:{start:0,limit:this.tam_pag}, 
	           callback : function (r) {
	                if (r.length == 1 ) {	                	                
	                    	this.id_punto_venta = r[0].data.id_punto_venta;	                    	
	                    	this.store.baseParams.id_punto_venta = r[0].data.id_punto_venta;
	                    	//this.store.baseParams.fecha = this.campo_fecha.getValue().dateFormat('d/m/Y');	                    
	                    	this.load({params:{start:0, limit:this.tam_pag}});  	                    
	                } else {
	                	
	                	var combo2 = new Ext.form.ComboBox(
						    {
						        typeAhead: false,
						        fieldLabel: 'Punto de Venta',
						        allowBlank : false,						        
						        store: storeCombo,
						        mode: 'remote',
                				pageSize: 15,
						        triggerAction: 'all',
						        valueField : 'id_punto_venta',
                				displayField : 'nombre', 
						        forceSelection: true,
						        tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Codigo:</b> {codigo}</p><p><b>Nombre:</b> {nombre}</p></div></tpl>',
						        allowBlank : false,
						        anchor: '100%'
						    });
						 
						 var formularioInicio = new Ext.form.FormPanel({				            
				            items: [combo2],				            
				            padding: true,
				            bodyStyle:'padding:5px 5px 0',
				            border: false,
				            frame: false				            
				        });
						 
						 var VentanaInicio = new Ext.Window({
					            title: 'Punto de Venta / Sucursal',
					            modal: true,
					            width: 300,
					            height: 160,
					            bodyStyle: 'padding:5px;',
					            layout: 'fit',
					            hidden: true,					            
					            buttons: [
					                {
						                text: '<i class="fa fa-check"></i> Aceptar',
						                handler: function () {
						                	if (formularioInicio.getForm().isValid()) {
						                		validado = true;						                		
						                		VentanaInicio.close(); 
							                    this.id_punto_venta  = combo2.getValue();
							                    this.store.baseParams.id_punto_venta = combo2.getValue();
							                    //this.store.baseParams.fecha = this.campo_fecha.getValue().dateFormat('d/m/Y');
							                    this.load({params:{start:0, limit:this.tam_pag}});
						                	}
						                },
										scope: this
					               }],
					            items: formularioInicio,
					            autoDestroy: true,
					            closeAction: 'close'
					        });
					      VentanaInicio.show();
					      VentanaInicio.on('beforeclose', function (){
					      	if (!validado) {
					      		alert('Debe seleccionar el punto de venta o sucursal de trabajo');
					      		return false;
					      	}
					      },this)
	                }
	                              
	            }, scope : this
	        });
	        
	    
		
	},
			
	Atributos:[
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
			config:{
				name: 'numero',
				fieldLabel: 'Número',
				allowBlank: false,
				anchor: '80%',
				gwidth: 150,
				maxLength:30
			},
				type:'TextField',
				filters:{pfiltro:'bol.numero',type:'string'},
				bottom_filter : true,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'ruta',
				fieldLabel: 'Ruta',
				allowBlank: false,
				anchor: '80%',
				gwidth: 150,
				maxLength:50
			},
				type:'TextArea',
				filters:{pfiltro:'bol.ruta',type:'string'},	
				bottom_filter : true,		
				grid:true,
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
				allowBlank: false,
				anchor: '80%',
				gwidth: 150,
				maxLength:1179650,
				enableKeyEvents : true
			},
				type:'NumberField',				
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
				filters:{pfiltro:'bol.estado_reg',type:'string'},
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
				filters:{pfiltro:'bol.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				filters:{pfiltro:'bol.fecha_reg',type:'date'},
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
				filters:{pfiltro:'bol.usuario_ai',type:'string'},
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
				filters:{pfiltro:'bol.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Boleto',
	ActSave:'../../sis_ventas_facturacion/control/Boleto/insertarBoleto',
	ActDel:'../../sis_ventas_facturacion/control/Boleto/eliminarBoleto',
	ActList:'../../sis_ventas_facturacion/control/Boleto/listarBoleto',
	id_store:'id_boleto',
	fields: [
		{name:'id_boleto', type: 'numeric'},
		{name:'id_punto_venta', type: 'numeric'},
		{name:'id_forma_pago', type: 'numeric'},
		{name:'forma_pago', type: 'string'},
		{name:'numero', type: 'string'},
		{name:'ruta', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'monto', type: 'numeric'},
		{name:'fecha', type: 'date',dateFormat:'Y-m-d'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_boleto',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	iniciarEventos : function() {
		this.Cmp.monto.on('keyup',function(a , e) {  
            if (e.getKey() == e.ENTER) {
            	a.argument = {'news': true,
		                        def: 'reset'}
            	this.onSubmit(a,e);
            }
        },this);
	},
	loadValoresIniciales: function() {    	
        this.Cmp.numero.setValue(this.ultimo_valor);
        this.Cmp.id_punto_venta.setValue(this.id_punto_venta);
               
        Phx.vista.Boleto.superclass.loadValoresIniciales.call(this);
   },
   onButtonNew: function() {
   		this.nuevo = true;
   		Phx.vista.Boleto.superclass.onButtonNew.call(this);
   		this.Cmp.id_forma_pago.store.baseParams.defecto = 'si';
   		this.Cmp.id_forma_pago.store.baseParams.id_punto_venta = this.id_punto_venta;
   		this.Cmp.id_forma_pago.allowBlank = false;
		this.mostrarComponente(this.Cmp.id_forma_pago);
		
		this.Cmp.monto.allowBlank = true;
		this.mostrarComponente(this.Cmp.monto);
		
   		this.Cmp.id_forma_pago.store.load({params:{start:0,limit:this.tam_pag}, 
		           callback : function (r) {
		           		
	           			if (r.length == 1 ) {                       
		                    this.Cmp.id_forma_pago.setValue(r[0].data.id_forma_pago); 
		                    this.Cmp.id_forma_pago.fireEvent('select', this.Cmp.id_forma_pago,r[0],0);
		                }
		           		
		                
		                this.Cmp.id_forma_pago.store.baseParams.defecto = 'no';  
		                this.Cmp.id_forma_pago.modificado = true;
		                                
		            }, scope : this
		        });
   },
   onButtonEdit: function() {
   		this.nuevo = false;
   		Phx.vista.Boleto.superclass.onButtonEdit.call(this);
   		if (this.sm.getSelected().data.forma_pago =='DIVIDIDO') {
   			this.Cmp.id_forma_pago.reset();
   			this.Cmp.id_forma_pago.allowBlank = true;
   			this.ocultarComponente(this.Cmp.id_forma_pago);
   			
   			this.Cmp.monto.allowBlank = true;
   			this.ocultarComponente(this.Cmp.monto);
   		} else {   			
   			this.Cmp.id_forma_pago.allowBlank = false;
   			this.mostrarComponente(this.Cmp.id_forma_pago);
   			
   			this.Cmp.monto.allowBlank = true;
   			this.mostrarComponente(this.Cmp.monto);
   		}
   },
   onSubmit: function(o, x, force) {
   		
   		if (this.nuevo) {
   			this.ultimo_valor = parseInt(this.Cmp.numero.getValue()) + 1;
   		}
   		Phx.vista.Boleto.superclass.onSubmit.call(this,o, x, force);
   },
   east : {
            url : '../../../sis_ventas_facturacion/vista/boleto_fp/BoletoFp.php',
            title : 'Formas de Pago',
            width : '35%',
            cls : 'BoletoFp'
       },
   
	
	
    }
	
)
</script>
		
		