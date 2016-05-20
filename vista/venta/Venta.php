<?php
/**
*@package pXP
*@file gen-Venta.php
*@author  (admin)
*@date 01-06-2015 05:58:00
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Venta=Ext.extend(Phx.gridInterfaz,{

	formUrl: '../../../sis_ventas_facturacion/vista/venta/FormVenta.php',
	formClass : 'FormVenta',
    tipo_factura: 'recibo',
    nombreVista: 'Venta',
	
	constructor:function(config) {
		
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
		var respuesta = JSON.parse(response.responseText);
		
   		if('datos' in respuesta){
		    this.variables_globales = respuesta.datos;
		}
		
		if (this.variables_globales.vef_tiene_punto_venta === 'true') {
			this.Atributos.push({
	            config: {
	                name: 'id_punto_venta',
	                fieldLabel: 'Punto de Venta',
	                allowBlank: false,
	                emptyText: 'Elija un Pun...',
	                store: new Ext.data.JsonStore({
	                    url: '../../sis_ventas_facturacion/control/PuntoVenta/listarPuntoVenta',
	                    id: 'id_punto_venta',
	                    root: 'datos',
	                    sortInfo: {
	                        field: 'nombre',
	                        direction: 'ASC'
	                    },
	                    totalProperty: 'total',
	                    fields: ['id_punto_venta', 'nombre', 'codigo'],
	                    remoteSort: true,
	                    baseParams: {par_filtro: 'puve.nombre#puve.codigo'}
	                }),
	                valueField: 'id_punto_venta',
	                displayField: 'nombre',
	                gdisplayField: 'nombre_punto_venta',
	                hiddenName: 'id_punto_venta',
	                forceSelection: true,
	                typeAhead: false,
	                triggerAction: 'all',
	                lazyRender: true,
	                mode: 'remote',
	                pageSize: 15,
	                queryDelay: 1000,               
	                gwidth: 150,
	                minChars: 2,
	                renderer : function(value, p, record) {
	                    return String.format('{0}', record.data['nombre_punto_venta']);
	                }
	            },
	            type: 'ComboBox',
	            id_grupo: 0,
	            filters: {pfiltro: 'puve.nombre',type: 'string'},
	            grid: true,
	            form: false
	        });
		}
		this.seleccionarPuntoVentaSucursal();
		//llama al constructor de la clase padre
		Phx.vista.Venta.superclass.constructor.call(this,request.arguments);
				
		this.init();
		//this.load({params:{start:0, limit:this.tam_pag}});
	}	,
	seleccionarPuntoVentaSucursal : function () {
		var validado = false;
		var title;
		var value;
		if (this.variables_globales.vef_tiene_punto_venta === 'true') {
			title = 'Seleccione el punto de venta con el que trabajara';
			value = 'id_punto_venta';
			var storeCombo = new Ext.data.JsonStore({
	                    url: '../../sis_ventas_facturacion/control/PuntoVenta/listarPuntoVenta',
	                    id: 'id_punto_venta',
	                    root: 'datos',
	                    sortInfo: {
	                        field: 'nombre',
	                        direction: 'ASC'
	                    },
	                    totalProperty: 'total',
	                    fields: ['id_punto_venta', 'id_sucursal','nombre', 'codigo','habilitar_comisiones','formato_comprobante'],
	                    remoteSort: true,
	                    baseParams: {par_filtro: 'puve.nombre#puve.codigo'}
	        });
		} else {
			title = 'Seleccione la sucursal con la que trabajara';
			value = 'id_sucursal';
			var storeCombo = new Ext.data.JsonStore({
                    url: '../../sis_ventas_facturacion/control/Sucursal/listarSucursal',
                    id: 'id_sucursal',
                    root: 'datos',
                    sortInfo: {
                        field: 'nombre',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_sucursal', 'nombre', 'codigo','habilitar_comisiones','formato_comprobante'],
                    remoteSort: true,
                    baseParams: {filtro_usuario: 'si',par_filtro: 'suc.nombre#suc.codigo', nombreVista: this.nombreVista}
               });
		}		
	    
	    storeCombo.load({params:{start: 0, limit: this.tam_pag}, 
	           callback : function (r) {
	                if (r.length == 1 ) {   
	                	if (this.variables_globales.vef_tiene_punto_venta === 'true') {                    
	                    	this.variables_globales.id_punto_venta = r[0].data.id_punto_venta;
	                    	this.variables_globales.habilitar_comisiones = r[0].data.habilitar_comisiones;
	                    	this.variables_globales.formato_comprobante = r[0].data.formato_comprobante;
	                    	this.store.baseParams.id_punto_venta = this.variables_globales.id_punto_venta;
	                    } else {
	                    	this.variables_globales.id_sucursal = r[0].data.id_sucursal;
	                    	this.variables_globales.habilitar_comisiones = r[0].data.habilitar_comisiones;
	                    	this.variables_globales.formato_comprobante = r[0].data.formato_comprobante;
	                    	this.store.baseParams.id_sucursal = this.variables_globales.id_sucursal;
	                    }
	                    this.store.baseParams.tipo_factura = this.tipo_factura;
	                    this.load({params:{start:0, limit:this.tam_pag}});  	                    
	                } else {
	                	
	                	var combo2 = new Ext.form.ComboBox(
						    {
						        typeAhead: false,
						        fieldLabel: title,
						        allowBlank : false,						        
						        store: storeCombo,
						        mode: 'remote',
                				pageSize: 15,
						        triggerAction: 'all',
						        valueField : value,
                				displayField : 'nombre', 
						        forceSelection: true,
						        tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Codigo:</b> {codigo}</p><p><b>Nombre:</b> {nombre}</p></div></tpl>',
						        allowBlank : false,
						        anchor: '100%',
						        resizable : true
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
					            width: 400,
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
						                		this.variables_globales.habilitar_comisiones = combo2.getStore().getById(combo2.getValue()).data.habilitar_comisiones;
						                		this.variables_globales.formato_comprobante = combo2.getStore().getById(combo2.getValue()).data.formato_comprobante;
						                		VentanaInicio.close();
						                		
						                		if (this.variables_globales.vef_tiene_punto_venta === 'true') {                    
							                    	this.variables_globales.id_punto_venta = combo2.getValue();							                    	
							                    	this.variables_globales.id_sucursal = storeCombo.getById(combo2.getValue()).data.id_sucursal;
							                    	this.store.baseParams.id_punto_venta = this.variables_globales.id_punto_venta;
							                    } else {
							                    	this.variables_globales.id_sucursal = combo2.getValue();
							                    	this.store.baseParams.id_sucursal = this.variables_globales.id_sucursal;
							                    }
							                    
							                    this.store.baseParams.tipo_factura = this.tipo_factura;
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
	variables_globales : {
		vef_tiene_punto_venta : 'false',
		vef_tipo_venta_habilitado : 'producto_terminado,formula,servicio',
		habilitar_comisiones : 'no'
	},
	diagramGantt : function (){            
            var data=this.sm.getSelected().data.id_proceso_wf;
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
                params:{'id_proceso_wf':data},
                success:this.successExport,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });        
	},
	anular : function () {
		Phx.CP.loadingShow();
        var rec=this.sm.getSelected();
        
        Ext.Ajax.request({
            url:'../../sis_ventas_facturacion/control/Venta/anularVenta',
            params:{                    
                id_venta:  rec.data.id_venta
                },
            success:this.successSave,
            failure: this.conexionFailure,            
            timeout:this.timeout,
            scope:this
        });
	},	
	Atributos:[
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
                    name: 'id_proceso_wf'
            },
            type:'Field',
            form:true 
        },
        
        {
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_estado_wf'
            },
            type:'Field',
            form:true 
        },
        {
            config:{
                name: 'correlativo_venta',
                fieldLabel: 'Nro',              
                gwidth: 110
            },
                type:'TextField',
                filters:{pfiltro:'ven.correlativo_venta',type:'string'},              
                grid:true,
                form:false,
                bottom_filter: true
        },
        {
            config:{
                name: 'nombre_factura',
                fieldLabel: 'Cliente',              
                gwidth: 110
            },
                type:'TextField',
                filters : {pfiltro : 'cli.nombre_factura',type : 'string'},             
                grid:true,
                form:false,
                bottom_filter: true
        },
        {
            config:{
                name: 'total_venta',
                fieldLabel: 'Total Venta',
                allowBlank: false,
                anchor: '80%',
                gwidth: 120,
                maxLength:5,
                disabled:true
            },
                type:'NumberField',
                filters:{pfiltro:'ven.total_venta',type:'numeric'},
                id_grupo:1,
                grid:true,
                form:false
        },
        {
            config:{
                name: 'fecha',
                fieldLabel: 'Fecha Doc.',              
                gwidth: 110,
                format: 'd/m/Y', 
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
                type:'DateField',
                filters: { pfiltro:'ven.fecha', type:'date'},              
                grid:true,
                form:false
        },
		{
            config:{
                name: 'nombre_sucursal',
                fieldLabel: 'Sucursal',              
                gwidth: 110
            },
                type:'TextField',
                filters: { pfiltro: 'suc.nombre', type: 'string'},      
                grid: true,
                form: false,
                bottom_filter: true
        },
        
         {
            config:{
                name: 'forma_pago',
                fieldLabel: 'Forma de Pago',              
                gwidth: 110
            },
                type:'TextField',
                grid:true,
                form:false
        },
		
		
        
        {
			config:{
				name: 'observaciones',
				fieldLabel: 'Observaciones',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100
			},
				type:'TextArea',
				filters:{pfiltro:'ven.observaciones',type:'string'},
				id_grupo:0,
				grid:true,
				form:false
		},
        {
            config:{
                name: 'monto_forma_pago',
                fieldLabel: 'Importe Recibido',
                allowBlank: false,                
                gwidth: 120,
                maxLength:5,
                disabled:true
            },
                type:'NumberField',                
                id_grupo:1,
                grid:true,
                form:false
        },
        {
            config:{
                name: 'comision',
                fieldLabel: 'Comisión',                          
                gwidth: 120,
                maxLength:5,
                disabled:true
            },
                type:'NumberField', 
                grid:true
                
        },
        {
            config:{
                name: 'estado',
                fieldLabel: 'Estado',                
                gwidth: 100
            },
                type:'TextField',
                filters:{pfiltro:'ven.estado',type:'string'},                
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
                filters:{pfiltro:'ven.estado_reg',type:'string'},
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
				filters:{pfiltro:'ven.usuario_ai',type:'string'},
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
				filters:{pfiltro:'ven.fecha_reg',type:'date'},
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
				filters:{pfiltro:'ven.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'ven.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
            config:{
                name: 'nro_factura',
                fieldLabel: 'Nro Factura',              
                gwidth: 110
            },
                type:'TextField',
                filters:{pfiltro:'ven.nro_factura',type:'string'},              
                grid:true,
                form:false,
                bottom_filter: true
        },
        {
            config:{
                name: 'excento',
                fieldLabel: 'Imp Excento',              
                gwidth: 110
            },
                type:'TextField',
                filters:{pfiltro:'ven.excento',type:'numeric'},              
                grid:true,
                form:false
        },
        {
            config:{
                name: 'cod_control',
                fieldLabel: 'Codigo Control',              
                gwidth: 110
            },
                type:'TextField',
                filters:{pfiltro:'ven.cod_control',type:'string'},              
                grid:true,
                form:false
        },
	],
	tam_pag:50,	
	title:'Ventas',
	ActSave:'../../sis_ventas_facturacion/control/Venta/insertarVenta',
	ActDel:'../../sis_ventas_facturacion/control/Venta/eliminarVenta',
	ActList:'../../sis_ventas_facturacion/control/Venta/listarVenta',
	id_store:'id_venta',
	fields: [
		{name:'id_venta', type: 'numeric'},
		{name:'id_cliente', type: 'numeric'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_punto_venta', type: 'numeric'},
		{name:'id_proceso_wf', type: 'numeric'},
		{name:'id_forma_pago', type: 'numeric'},
		{name:'porcentaje_descuento', type: 'numeric'},
		{name:'id_vendedor_medico', type: 'string'},
		{name:'forma_pago', type: 'string'},
		{name:'numero_tarjeta', type: 'string'},
		{name:'observaciones', type: 'string'},
		{name:'codigo_tarjeta', type: 'string'},
		{name:'tipo_tarjeta', type: 'string'},
		{name:'id_estado_wf', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'nombre_factura', type: 'string'},
		{name:'nombre_sucursal', type: 'string'},
		{name:'nombre_punto_venta', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'correlativo_venta', type: 'string'},
		{name:'a_cuenta', type: 'numeric'},
		{name:'total_venta', type: 'numeric'},
		{name:'comision', type: 'numeric'},
		{name:'fecha_estimada_entrega', type: 'date',dateFormat:'Y-m-d'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'nit', type: 'string'},
		{name:'monto_forma_pago', type: 'numeric'},
		{name:'nro_factura', type: 'string'},
		{name:'cod_control', type: 'string'},
		{name:'fecha', type: 'date'},
		{name:'excento', type: 'numeric'},
		{name:'nroaut', type: 'numeric'},
		'id_moneda','total_venta_msuc','transporte_fob','seguros_fob',
		'otros_fob','transporte_cif','seguros_cif','otros_cif','tipo_cambio_venta','desc_moneda','valor_bruto','descripcion_bulto'
		
		
	],
	sortInfo:{
		field: 'id_venta',
		direction: 'DESC'
	},
	bdel:true,
	bsave:true,
	rowExpander: new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<br>',                
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha de Registro:&nbsp;&nbsp;</b> {fecha_reg:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha Ult. Modificación:&nbsp;&nbsp;</b> {fecha_mod:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Creado por:&nbsp;&nbsp;</b> {usr_reg}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Modificado por:&nbsp;&nbsp;</b> {usr_mod}</p><br>'
            )
    }),
    loadValoresIniciales:function()
    {
        this.Cmp.total_venta.setValue(0);          
        Phx.vista.Venta.superclass.loadValoresIniciales.call(this);        
    },
    openForm : function (tipo, record) {
    	var me = this;
           me.objSolForm = Phx.CP.loadWindows(this.formUrl,
                                    'Formulario de Venta',
                                    {
                                        modal:true,
                                        width:'90%',
                                        height:'100%'
                                    }, {data:{objPadre : me,
                                    		tipo_form : tipo,                                    		
                                    		datos_originales: record}
                                    }, 
                                    this.idContenedor,
                                    this.formClass,
                                    {
                                        config:[{
                                                  event:'successsave',
                                                  delegate: this.onSaveForm,
                                                  
                                                }],
                                        
                                        scope:this
                                     }); 
    },
    
    onButtonNew : function () {
        //abrir formulario de solicitud
        this.openForm('new');        
    },
    
    onButtonEdit : function () {
        //abrir formulario de solicitud
        this.openForm('edit', this.sm.getSelected());        
    },    
    
    arrayDefaultColumHidden:['estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod'],
    sigEstado:function(){                   
      var rec=this.sm.getSelected();
      this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
                                'Estado de Wf',
                                {
                                    modal:true,
                                    width:700,
                                    height:450
                                }, {data:{
                                       id_estado_wf:rec.data.id_estado_wf,
                                       id_proceso_wf:rec.data.id_proceso_wf
                                    }}, this.idContenedor,'FormEstadoWf',
                                {
                                    config:[{
                                              event:'beforesave',
                                              delegate: this.onSaveWizard,
                                              
                                            }],
                                    
                                    scope:this
                                 });        
               
     },
       
     onSaveWizard:function(wizard,resp){
        Phx.CP.loadingShow();
        
        Ext.Ajax.request({
            url:'../../sis_ventas_facturacion/control/Venta/siguienteEstadoVenta',
            params:{
                    
                id_proceso_wf_act:  resp.id_proceso_wf_act,
                id_estado_wf_act:   resp.id_estado_wf_act,
                id_tipo_estado:     resp.id_tipo_estado,
                id_funcionario_wf:  resp.id_funcionario_wf,
                id_depto_wf:        resp.id_depto_wf,
                obs:                resp.obs,
                json_procesos:      Ext.util.JSON.encode(resp.procesos)
                },
            success:this.successWizard,
            failure: this.failureWizard,
            argument:{wizard:wizard},
            timeout:this.timeout,
            scope:this
        });
    },
    
    failureWizard:function(resp1,resp2,resp3,resp4,resp5){ 
        var resp = resp1;// error conexion
        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        if (reg.ROOT.detalle.mensaje.indexOf('insuficientes')!=-1) {
            var mensaje = reg.ROOT.detalle.mensaje;
            mensaje = mensaje.replace(/#/g, "");
            mensaje = mensaje.replace("*", "");            
            mensaje = mensaje.replace("*", "");
            mensaje = mensaje.replace("{", "");
            mensaje = mensaje.replace("}", "");
            alert(mensaje);
            Phx.CP.loadingHide();
            
        } else {
            Phx.vista.Venta.superclass.conexionFailure.call(this,resp1,resp2,resp3,resp4,resp5);
        }
        
    }, 
    successWizard:function(resp){
        var rec=this.sm.getSelected();
        
        if (rec.data.estado == 'borrador' && this.tipo_factura != 'manual') {
            this.imprimirNota();
        }
        Phx.CP.loadingHide();
        resp.argument.wizard.panel.destroy()
        this.reload();
     },
     
     antEstado:function(){
         var rec=this.sm.getSelected();
            Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
            'Estado de Wf',
            {
                modal:true,
                width:450,
                height:250
            }, {data:rec.data}, this.idContenedor,'AntFormEstadoWf',
            {
                config:[{
                          event:'beforesave',
                          delegate: this.onAntEstado,
                        }
                        ],
               scope:this
             })
   },
   
   imprimirNota: function(){
		//Ext.Msg.confirm('Confirmación','¿Está seguro de Imprimir el Comprobante?',function(btn){
			
			var rec = this.sm.getSelected();
			var data = rec.data;
			if (data) {
				Phx.CP.loadingShow();
				Ext.Ajax.request({
						url : '../../sis_ventas_facturacion/control/Venta/reporteFacturaRecibo',
						params : {
							'id_venta' : data.id_venta,
							'formato_comprobante' : this.variables_globales.formato_comprobante
						},
						success : this.successExport,
						failure : this.conexionFailure,
						timeout : this.timeout,
						scope : this
					});
			}
	},
	successExport: function (resp) {

        Phx.CP.loadingHide();
        var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
		var objetoDatos = (objRes.ROOT == undefined)?objRes.datos:objRes.ROOT.datos;
        var wnd = window.open("about:blank", "", "_blank");
		wnd.document.write(objetoDatos.html);


    },
   
   onAntEstado:function(wizard,resp){
            Phx.CP.loadingShow(); 
            Ext.Ajax.request({ 
                url:'../../sis_ventas_facturacion/control/Venta/anteriorEstadoVenta',
                params:{
                        id_proceso_wf:resp.id_proceso_wf,
                        id_estado_wf:resp.id_estado_wf,  
                        obs:resp.obs
                 },
                argument:{wizard:wizard},  
                success:this.successEstadoSinc,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
           
     },
     
   successEstadoSinc:function(resp){
        Phx.CP.loadingHide();
        resp.argument.wizard.panel.destroy()
        this.reload();
     },
	}
)
</script>
		
		