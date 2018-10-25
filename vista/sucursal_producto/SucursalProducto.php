<?php
/**
*@package pXP
*@file gen-SucursalProducto.php
*@author  (admin)
*@date 21-04-2015 03:18:44
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 * Issue 					Fecha			Author				Descripcion					
 * #1					25/09/2018			EGS					Comentado por que no carga los conceptos de gasto al elegir por entidad
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.SucursalProducto=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
    	this.buildGrupos();
		Phx.vista.SucursalProducto.superclass.constructor.call(this,config);
		this.init();
		this.grid.getTopToolbar().disable();
        this.grid.getBottomToolbar().disable();
        this.iniciarEventos();

        
        this.addButton('addImagen', {
				text : 'Imagen',
				iconCls : 'bundo',
				disabled : false,
				handler : this.addImagen,
				tooltip : ' <b>Subir imagen</b>'
			});
			
        
        
        
		//this.load({params:{start:0, limit:this.tam_pag}})

        if (config.formulario == 'venta') {
        	this.onReloadPage(config.maestro);
        } else if (config.formulario == 'formula') {
        	this.load({params:{start:0, limit:50}});
        }
        

	},
	
	addImagen : function() {


			var rec = this.sm.getSelected();
			Phx.CP.loadWindows('../../../sis_parametros/vista/concepto_ingas/subirImagenConcepto.php', 'Subir', {
				modal : true,
				width : 500,
				height : 250
			}, {id_concepto_ingas: rec.data.id_concepto_ingas}, this.idContenedor, 'subirImagenConcepto')

			

	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_sucursal_producto',
					renderer:function (value, p, record){	
						//return  String.format('{0}',"<div style='text-align:center'><img src = ../../control/foto_persona/"+ record.data['foto']+"?"+record.data['nombre_foto']+hora_actual+" align='center' width='70' height='70'/></div>");
						if(record.data['tipo_producto'] =='servicio' ||record.data['tipo_producto'] =='producto'){
							var splittedArray = record.data['ruta_foto'].split('.');
							if (splittedArray[splittedArray.length - 1] != "") {
								return  String.format('{0}',"<div style='text-align:center'><img src = '"+ record.data['ruta_foto']+"' align='center' width='70' height='70'/></div>");
							} else {
								return  String.format('{0}',"<div style='text-align:center'><img src = '../../../lib/imagenes/noimagen2.jpg' align='center' width='70' height='70'/></div>");
							}
						}
						else{
							return '';
							
						}
						
						
					}
			
			},
			type:'Field',
			grid:true,
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
                    name:'tipo_producto',
                    fieldLabel:'Tipo de Producto',
                    allowBlank:false,
                    emptyText:'Tip...',                    
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',                    
                    store:['item_almacen','servicio', 'producto']
                    
                },
                type:'ComboBox',
                id_grupo:0,
                filters:{   
                         type: 'list',
                         options: ['item_almacen','servicio', 'producto'], 
                    },
                grid:true,
                form:true
            },
		
		{
            config : {
                name : 'id_item',
                fieldLabel : 'Item',
                allowBlank : false,
                emptyText : 'Elija un Item...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_almacenes/control/Item/listarItemNotBase',
                    id : 'id_item',
                    root : 'datos',
                    sortInfo : {
                        field : 'nombre',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_item', 'nombre', 'codigo', 'desc_clasificacion', 'codigo_unidad'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'item.nombre#item.codigo#cla.nombre'
                    }
                }),
                valueField : 'id_item',
                displayField : 'nombre',
                gdisplayField : 'nombre_item',
                tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre}</p><p>Código: {codigo}</p><p>Clasif.: {desc_clasificacion}</p></div></tpl>',
                hiddenName : 'id_item',
                forceSelection : true,
                typeAhead : false,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,
                anchor : '100%',
                gwidth : 250,
                minChars : 2,
                turl : '../../../sis_almacenes/vista/item/BuscarItem.php',
                tasignacion : true,
                tname : 'id_item',
                ttitle : 'Items',
                tdata : {},
                tcls : 'BuscarItem',
                pid : this.idContenedor,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['nombre_item']);
                },
                resizable: true
            },
            type : 'TrigguerCombo',
            id_grupo : 1,
            filters : {
                pfiltro : 'item.nombre',
                type : 'string'
            },
            grid : true,
            form : true
        },
        
                
        {
            config:{
                name:'nombre_producto',
                fieldLabel:'Nombre Producto/Servicio',
                allowBlank:true,                
                store: new Ext.data.JsonStore({
                         url: '../../sis_parametros/control/ConceptoIngas/listarConceptoIngas',
                         id: 'id_concepto_ingas',
                         root: 'datos',
                         sortInfo:{
                            field: 'desc_ingas',
                            direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_concepto_ingas','tipo','desc_ingas','movimiento','desc_partida','id_grupo_ots','filtro_ot','requiere_ot','descripcion_larga','ruta_foto','codigo','nandina'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'desc_ingas',movimiento:'recurso',start:0,limit:99999}
                    }),
                valueField: 'id_concepto_ingas',
                displayField: 'desc_ingas',
                gdisplayField:'nombre_producto',
                tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>{desc_ingas}</b></p><p>TIPO:{tipo}</p><p>MOVIMIENTO:{movimiento}</p></div></tpl>',
                hiddenName: 'id_concepto_ingas',
                forceSelection : false,
                typeAhead : false,
                hideTrigger : true,                
                lazyRender:true,
                mode:'remote',
                pageSize:0,
                queryDelay:1000,
                listWidth:600,
                resizable:true,
                anchor:'100%', 
                gwidth: 200,  
                minChars : 1,    
                renderer:function(value, p, record){return String.format('{0}', record.data['nombre_producto']);}
            },
            type:'ComboBox',
            id_grupo:2,
            filters:{   
                        filters:{pfiltro:'cig.desc_ingas',type:'string'},
                        type:'string'
                    },
            grid:true,
            form:true
        },
        
        
		{
			config:{
				name: 'descripcion_producto',
				fieldLabel: 'Descripcion Producto/Servicio',
				allowBlank: true,
				anchor: '100%',
				gwidth: 250
			},
				type:'TextArea',
				filters:{pfiltro:'sprod.descripcion_producto',type:'string'},
				id_grupo:2,
				grid:true,
				form:true
		},
		{
   			config:{
   				name:'id_unidad_medida',
   				tipo: 'All',
   				origen:'UNIDADMEDIDA',
   				allowBlank:true,
   				fieldLabel:'Unidad',
   				gdisplayField:'desc_unidad_medida',//mapea al store del grid
   				gwidth:200,
   				width: 350,
   				listWidth: 350,
   				//anchor: '80%',
	   			renderer:function (value, p, record){return String.format('{0}', record.data['desc_unidad_medida']);}
       	     },
   			type:'ComboRec',
   			id_grupo:2,
   			filters:{	
		        pfiltro:'um.codigo#um.descripcion',
				type:'string'
			},
   		   
   			grid:true,
   			form:true
	   	},
		{
			config:{
				name: 'nandina',
				fieldLabel: 'Nandina',
				qtip: 'Código de partida aduanera',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
			type:'TextField',
			filters:{pfiltro:'cig.nandina',type:'string'},
			id_grupo:2,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'codigo',
				fieldLabel: 'Código',
				qtip: 'Código propio',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength: 100
			},
			type:'TextField',
			filters:{pfiltro:'cig.codigo',type:'string'},
			id_grupo:2,
			grid:true,
			form:true
		},
		
		{
            config : {
                name : 'id_actividad_economica',
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
                gdisplayField : 'nombre_actividad',             
                hiddenName : 'id_actividad_economica',
                forceSelection : true,
                typeAhead : false,
                tpl:'<tpl for="."><div class="x-combo-list-item"><p>{codigo}</p><p>{nombre}</p><p>{descripcion}</p> </div></tpl>',
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,                
                gwidth : 170,
                minChars : 2,
                renderer:function(value, p, record){return String.format('{0}', record.data['nombre_actividad']);}
            },
            type : 'ComboBox',
            id_grupo : 2,
            filters:{pfiltro:'acteco.nombre',type:'string'},            
            form : true,
            grid:true
        }, 
        {
            config:{
                name: 'requiere_descripcion',
                fieldLabel: 'Requiere Descripción',
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
            id_grupo:2,
            filters:{   
                         type: 'list',
                         pfiltro:'sprod.requiere_descripcion',
                         options: ['si','no'],  
                    },
            grid:true,
            form:true
        },	
        
        {
            config:{
                name: 'contabilizable',
                fieldLabel: 'Contabilizable',
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
            id_grupo:2,
            filters:{   
                         type: 'list',
                         pfiltro:'sprod.contabilizable',
                         options: ['si','no'],  
                    },
            grid:true,
            form:true
        },
        
        {
            config:{
                name: 'excento',
                fieldLabel: 'Tiene Excento',
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
            id_grupo:2,
            filters:{   
                         type: 'list',
                         pfiltro:'sprod.excento',
                         options: ['si','no'],  
                    },
            grid:true,
            form:true
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
            id_grupo:0,
            filters:{   
                pfiltro:'mon.codigo',
                type:'string'
            },
            grid:true,
            form:true
          },  	
         
		{
			config:{
				name: 'precio',
				fieldLabel: 'Precio',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179650
			},
				type:'NumberField',
				filters:{pfiltro:'sprod.precio',type:'numeric'},
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
				filters:{pfiltro:'sprod.estado_reg',type:'string'},				
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
				filters:{pfiltro:'sprod.fecha_reg',type:'date'},
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
				filters:{pfiltro:'sprod.usuario_ai',type:'string'},
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
				filters:{pfiltro:'sprod.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'sprod.fecha_mod',type:'date'},
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
				grid:true,
				form:false
		}
	],
	
	onReloadPage : function(m) {
        this.maestro=m;
        this.Atributos[1].valorInicial = this.maestro.id_sucursal;
        this.store.baseParams={id_sucursal:this.maestro.id_sucursal};
        this.load({params:{start:0, limit:50}});
        
        /////Comentado por que no carga los conceptos de gasto al elegir
        //this.Cmp.nombre_producto.store.baseParams.id_entidad = this.maestro.id_entidad;
            
    },
    iniciarEventos :  function () {
        this.Cmp.tipo_producto.on('select',function (c,r,v) {
            if (r.data.field1 == 'item_almacen') {
                this.mostrarGrupo(1);
                this.allowBlankGrupo(1, false);
                this.ocultarGrupo(2);
                this.allowBlankGrupo(2, true);
                this.resetGroup(2)
                
            } else {
            	if (r.data.field1 == 'servicio') {
            		this.Cmp.nombre_producto.store.baseParams.tipo = 'Servicio';
            	} else {
            		this.Cmp.nombre_producto.store.baseParams.tipo = 'Bien';
            	}
            	this.Cmp.nombre_producto.reset();
            	this.Cmp.nombre_producto.modificado = true;
                this.mostrarGrupo(2);
                this.allowBlankGrupo(2, false);
                this.ocultarGrupo(1);
                this.allowBlankGrupo(1, true);
                this.resetGroup(1);
                
                
                
            }
             this.resetPanel();
        },this);
        
        this.Cmp.nombre_producto.on('select',function (c,r,v) {
        	this.Cmp.descripcion_producto.setValue(r.data.descripcion_larga);
        	this.Cmp.nandina.setValue(r.data.nandina);
        	this.Cmp.codigo.setValue(r.data.codigo);
        	
        	var ruta = '../../../lib/imagenes/noimagen2.jpg';
			if (r.data['ruta_foto'] && r.data['ruta_foto'] != "") {
        		ruta = r.data['ruta_foto'];
        	}        	
        	var plantilla = "<div style='text-align:center'><img src = '{0}' align='center' width='70' height='70'/></div>";
			this.panelResumen.update( String.format(plantilla,ruta));
        	
        	
        },this);
    },
	tam_pag:50,	
	title:'Productos',
	ActSave:'../../sis_ventas_facturacion/control/SucursalProducto/insertarSucursalProducto',
	ActDel:'../../sis_ventas_facturacion/control/SucursalProducto/eliminarSucursalProducto',
	ActList:'../../sis_ventas_facturacion/control/SucursalProducto/listarSucursalProducto',
	id_store:'id_sucursal_producto',
	fields: [
		{name:'id_sucursal_producto', type: 'numeric'},
		{name:'id_concepto_ingas', type: 'numeric'},
		{name:'id_actividad_economica', type: 'numeric'},
		{name:'id_moneda', type: 'numeric'},
		{name:'desc_moneda', type: 'string'},
		{name:'contabilizable', type: 'string'},
		{name:'excento', type: 'string'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_item', type: 'numeric'},
		{name:'nombre_item', type: 'string'},
		{name:'requiere_descripcion', type: 'string'},
		{name:'nombre_actividad', type: 'string'},
		{name:'descripcion_producto', type: 'string'},
		{name:'precio', type: 'numeric'},
		{name:'nombre_producto', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'tipo_producto', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},'nandina','id_unidad_medida',
		'desc_unidad_medida','ruta_foto','codigo'
		
	],
	sortInfo:{
		field: 'id_sucursal_producto',
		direction: 'DESC'
	},
	
	 buildGrupos: function(){ 
    	var me = this;
    	this.panelResumen = new Ext.Panel({  
    		    padding: '0 0 0 20',
    		    html: '',
    		    split: true, 
    		    layout:  'fit' });
    		    
    	me.Grupos =[
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
                                    title: 'Datos Generales',
                                    autoHeight: true,
                                    items: [],
                                    id_grupo:0
                                }]
                       }, {
                                bodyStyle: 'padding-left:5px;',
                                items: [{
                                    xtype: 'fieldset',
                                    title: 'Item',
                                    autoHeight: true,
                                    items: [],
                                    id_grupo:1
                                }]
                            }
                        , {
                            bodyStyle: 'padding-left:5px;',
                            items: [{
                                xtype: 'fieldset',
                                title: 'Producto Sucursal',
                                autoHeight: true,
                                items: [],
                                id_grupo:2
                            }]
                        },
                     {
                      bodyStyle: 'padding-right:5px;',
                      width: '40%',
                      border: true,
                      autoHeight: true,
				      items: [me.panelResumen]
                     }]
            }
        ];
        
    },
     
    resetPanel(){
     	this.panelResumen.update("");
     	
     }, 
        
    fheight:'80%',
    fwidth:'80%',
        
    onButtonNew:function() {                    
            Phx.vista.SucursalProducto.superclass.onButtonNew.call(this);
            this.ocultarGrupo(2);
            this.ocultarGrupo(1); 
            this.resetPanel();
                      
            
    },
    
    onButtonEdit:function() {                    
            Phx.vista.SucursalProducto.superclass.onButtonEdit.call(this);
            if (this.Cmp.tipo_producto.getValue() == 'item_almacen') {
                this.mostrarGrupo(1);
                this.allowBlankGrupo(1, false);
                this.ocultarGrupo(2);
                this.allowBlankGrupo(2, true);
                this.resetGroup(2)
                
            } else {
            	if (this.Cmp.tipo_producto.getValue() == 'servicio') {
            		this.Cmp.nombre_producto.store.baseParams.tipo = 'Servicio';
            	} else {
            		this.Cmp.nombre_producto.store.baseParams.tipo = 'Bien';
            	}
            	
            	this.Cmp.nombre_producto.modificado = true;
                this.mostrarGrupo(2);
                this.allowBlankGrupo(2, false);
                this.ocultarGrupo(1);
                this.allowBlankGrupo(1, true);
                this.resetGroup(1)
            }                      
            
    },
    
    preparaMenu: function (n) {		
		Phx.vista.SucursalProducto.superclass.preparaMenu.call(this, n);
		var record = this.getSelectedData();
		if(record['tipo_producto'] =='servicio' ||record['tipo_producto'] =='producto'){
		    this.getBoton('addImagen').enable();
		}else{
			this.getBoton('addImagen').disable();
		}
	
	},
	liberaMenu: function (n) {		
		Phx.vista.SucursalProducto.superclass.liberaMenu.call(this, n);
		this.getBoton('addImagen').disable();
		
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
    
    arrayDefaultColumHidden:['estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod'],
    
	}
)
</script>
		
		