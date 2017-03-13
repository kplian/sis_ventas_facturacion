<?php
/**
*@package pXP
*@file gen-Dosificacion.php
*@author  (jrivera)
*@date 07-10-2015 13:00:56
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Dosificacion=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Dosificacion.superclass.constructor.call(this,config);
		this.init();
		this.iniciarEventos();
		this.store.baseParams.id_sucursal = this.maestro.id_sucursal; 
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_dosificacion'
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
				name: 'tipo',
				fieldLabel: 'Tipo Documento',
				allowBlank: false,
				anchor: '80%',
				gwidth: 120,
				triggerAction: 'all',
                lazyRender:true,
                mode: 'local',               
				store: new Ext.data.ArrayStore({
					        id: 0,
					        fields: [
					            'id',
					            'display'
					        ],
					        data: [['F', 'Factura'], ['N', 'Nota de Credito/Debito']]
			    }),
			    valueField: 'id',
			    displayField: 'display',
			    renderer:function (value, p, record){
			    	if (value == 'F') {
			    		return 'Factura';
			    	} else {
			    		return 'Nota de Credito/Debito'
			    	}
			    }
			},
			type:'ComboBox',
			filters:{ type: 'list',	       		  
	       		  options: ['F','N']
					},
			id_grupo:0,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'fecha_dosificacion',
				fieldLabel: 'Fecha de Dosificacion',
				allowBlank: false,
				anchor: '80%',
				gwidth: 120,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'dos.fecha_dosificacion',type:'date'},
				id_grupo:0,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nroaut',
				fieldLabel: 'No Autorizacion',
				allowBlank: false,
				anchor: '100%',
				gwidth: 120,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dos.nroaut',type:'string'},
				id_grupo:0,
				grid:true,
				form:true
		},
		
		{
			config:{
				name: 'fecha_limite',
				fieldLabel: 'Fecha Limite Emision',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'dos.fecha_limite',type:'date'},
				id_grupo:0,
				grid:true,
				form:true
		},
		{
            config : {
                name : 'id_activida_economica',
                fieldLabel : 'Actividad Economica',
                allowBlank : false,
                emptyText : 'Actividad...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_ventas_facturacion/control/ActividadEconomica/listarActividadEconomica',
                    id : 'id_actividad_economica',
                    root : 'datos',
                    sortInfo : {
                        field : 'codigo',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_actividad_economica', 'nombre', 'codigo', 'descripcion'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'acteco.codigo#acteco.nombre'
                    }
                }),
                valueField : 'id_actividad_economica',
                displayField : 'nombre',   
                //gdisplayField : 'desc_actividad_economica',             
                hiddenName : 'id_actividad_economica',
                forceSelection : true,
                typeAhead : false,
                //tpl:'<tpl for="."><div class="x-combo-list-item"><p>{codigo}</p><p>{nombre}</p><p>{descripcion}</p> </div></tpl>',
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,                
                gwidth : 170,
                minChars : 2,
                enableMultiSelect:true,
                renderer:function(value, p, record){return String.format('{0}', record.data['desc_actividad_economica']);}
            },
            type : 'AwesomeCombo',
            id_grupo : 0,                  
            form : true,
            grid:true
        },  
        {
                config:{
                    name:'tipo_generacion',
                    fieldLabel:'Tipo de Generacion',
                    allowBlank:false,
                    emptyText:'Tip...',                    
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',                    
                    store:['manual','computarizada']
                    
                },
                type:'ComboBox',
                id_grupo:0,
                filters:{   
                         type: 'list',
                         options: ['manual','computarizada'], 
                    },
                grid:true,
                form:true
        },	
		
		
		//INI ES COMPUTARIZADA
		{
			config:{
				name: 'llave',
				fieldLabel: 'Llave',
				allowBlank: false,
				anchor: '100%',
				gwidth: 120,
				maxLength:150
			},
				type:'TextArea',
				filters:{pfiltro:'dos.llave',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		
		{
			config:{
				name: 'fecha_inicio_emi',
				fieldLabel: 'Fecha inicio de Emis.',
				allowBlank: false,
				anchor: '80%',
				gwidth: 125,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'dos.fecha_inicio_emi',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		//FIN ES COMPUTARIZADA
		//INI ES MANUAL
		{
			config:{
				name: 'inicial',
				fieldLabel: 'No Inicial',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150,				
				allowDecimals:false,
				allowNegative:false
			},
				type:'TextField',
				filters:{pfiltro:'dos.inicial',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		
		{
			config:{
				name: 'final',
				fieldLabel: 'No Final',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150,
				allowDecimals:false,
				allowNegative:false
			},
				type:'NumberField',
				filters:{pfiltro:'dos.final',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		
		//FIN ES MANUAL
		{
			config:{
				name: 'nro_siguiente',
				fieldLabel: 'Nro Siguiente',				
				gwidth: 100
			},
				type:'NumberField',
				filters:{pfiltro:'dos.nro_siguiente',type:'numeric'},				
				grid:true,
				form:false
		},	
		
		
		
		
		{
			config:{
				name: 'glosa_impuestos',
				fieldLabel: 'Glosa Impuestos',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextArea',
				filters:{pfiltro:'dos.glosa_impuestos',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'glosa_empresa',
				fieldLabel: 'Glosa Empresa',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextArea',
				filters:{pfiltro:'dos.glosa_empresa',type:'string'},
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
				filters:{pfiltro:'dos.estado_reg',type:'string'},
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
				filters:{pfiltro:'dos.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				filters:{pfiltro:'dos.fecha_reg',type:'date'},
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
				filters:{pfiltro:'dos.usuario_ai',type:'string'},
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
				filters:{pfiltro:'dos.fecha_mod',type:'date'},
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
	title:'Dosificación',
	ActSave:'../../sis_ventas_facturacion/control/Dosificacion/insertarDosificacion',
	ActDel:'../../sis_ventas_facturacion/control/Dosificacion/eliminarDosificacion',
	ActList:'../../sis_ventas_facturacion/control/Dosificacion/listarDosificacion',
	id_store:'id_dosificacion',
	fields: [
		{name:'id_dosificacion', type: 'numeric'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'final', type: 'numeric'},
		{name:'tipo', type: 'string'},
		{name:'fecha_dosificacion', type: 'date',dateFormat:'Y-m-d'},
		{name:'nro_siguiente', type: 'numeric'},
		{name:'nroaut', type: 'string'},
		{name:'fecha_inicio_emi', type: 'date',dateFormat:'Y-m-d'},
		{name:'fecha_limite', type: 'date',dateFormat:'Y-m-d'},
		{name:'tipo_generacion', type: 'string'},
		{name:'glosa_impuestos', type: 'string'},
		{name:'id_activida_economica', type: 'string'},
		{name:'desc_actividad_economica', type: 'string'},
		{name:'llave', type: 'string'},
        {name:'llave_aux', type: 'string'},
		{name:'inicial', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'glosa_empresa', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_dosificacion',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
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
                                    title: 'Datos Básicos',
                                    autoHeight: true,
                                    items: [],
                                    id_grupo:0
                                }]
                            }
                        , {
                            bodyStyle: 'padding-left:5px;',
                            items: [{
                                xtype: 'fieldset',
                                title: 'Datos Adicionales',
                                autoHeight: true,
                                items: [],
                                id_grupo:1
                            }]
                        }]
            }
        ],
        
    fheight:'60%',
    fwidth:'60%',
    iniciarEventos :  function () {
        this.Cmp.tipo_generacion.on('select',function (c,r,v) {
            if (this.Cmp.tipo_generacion.getValue() == 'manual') {
               this.mostrarComponente(this.Cmp.inicial);
               this.Cmp.inicial.allowBlank = false;
               
               this.mostrarComponente(this.Cmp.final); 
               this.Cmp.final.allowBlank = false;
               
               this.ocultarComponente(this.Cmp.llave);
               this.Cmp.llave.allowBlank = true;
               this.Cmp.llave.reset();
               
               this.ocultarComponente(this.Cmp.fecha_inicio_emi); 
               this.Cmp.fecha_inicio_emi.allowBlank = true;
               this.Cmp.fecha_inicio_emi.reset();
               
                
            } else {            	
               this.ocultarComponente(this.Cmp.inicial);
               this.Cmp.inicial.allowBlank = true;
               this.Cmp.inicial.reset();
               
               this.ocultarComponente(this.Cmp.final); 
               this.Cmp.final.allowBlank = true;
               this.Cmp.final.reset();
               
               this.mostrarComponente(this.Cmp.llave);
               this.Cmp.llave.allowBlank = false;               
               
               this.mostrarComponente(this.Cmp.fecha_inicio_emi); 
               this.Cmp.fecha_inicio_emi.allowBlank = false;
               
            }
        },this); 
        
    },
    onButtonEdit:function() {                    
            Phx.vista.Dosificacion.superclass.onButtonEdit.call(this);
            this.Cmp.tipo_generacion.fireEvent('select');            
    },
    loadValoresIniciales:function()
    {
    	this.Cmp.id_sucursal.setValue(this.maestro.id_sucursal);       
        Phx.vista.Dosificacion.superclass.loadValoresIniciales.call(this);        
    },
    onSubmit : function(o) {

		Phx.vista.Dosificacion.superclass.onSubmit.call(this,o);
	},
    successSave:function(resp){
        var datos_respuesta = JSON.parse(resp.responseText);
        if (datos_respuesta.ROOT.datos.prueba) {
            Ext.Msg.alert('Atencion',datos_respuesta.ROOT.datos.prueba).getDialog().setSize(350,300);

        } 
        Phx.vista.Dosificacion.superclass.successSave.call(this,resp);


    }
    
    
	}
)
</script>
		
		