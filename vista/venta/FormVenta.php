<?php
/**
*@package pXP
*@file    FormVenta.php
*@author  Jaime Rivera rojas 
*@date    30-01-2014
*@description permites subir archivos a la tabla de documento_sol
*/
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
Phx.vista.FormVenta=Ext.extend(Phx.frmInterfaz,{
    ActSave:'../../sis_ventas_facturacion/control/Venta/insertarVentaCompleta',
    tam_pag: 10,    
    layout: 'fit',
    tabEnter: true,
    autoScroll: false,
    breset: false,
    labelSubmit: '<i class="fa fa-check"></i> Guardar',
    storeFormaPago : false,
    constructor:function(config)
    {   
        Ext.apply(this,config);
        
        if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') {  
        	
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
	                    baseParams: {filtro_usuario: 'si',par_filtro: 'puve.nombre#puve.codigo'}
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
	            id_grupo: 1,
	            filters: {pfiltro: 'puve.nombre',type: 'string'},
	            grid: true,
	            form: true
	        });
		}
		this.tipoDetalleArray = this.data.objPadre.variables_globales.vef_tipo_venta_habilitado.split(",");
        this.addEvents('beforesave');
        this.addEvents('successsave');
        
        this.buildComponentesDetalle();
        this.buildDetailGrid();
        this.buildGrupos();
        
        Phx.vista.FormVenta.superclass.constructor.call(this,config);
        this.init();    
        this.iniciarEventos();
        
        //this.iniciarEventosDetalle();
        //this.onNew();
        
        if(this.data.tipo_form == 'new'){
        	this.onNew();
        }
        else{
        	this.onEdit();
        }
        
        if(this.data.readOnly===true){
        	for(var index in this.Cmp) { 
					if( this.Cmp[index].setReadOnly){
					    	 this.Cmp[index].setReadOnly(true);
					   }
			}
			
			this.megrid.getTopToolbar().disable();
					
        }
        if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') {          	
        	this.Cmp.id_sucursal.allowBlank = true;
        	this.Cmp.id_sucursal.setDisabled(true);
        }
                
    },
    
    buildComponentesDetalle: function(){
        this.detCmp = {
                    'tipo': new Ext.form.ComboBox({
                            name: 'tipo',
                            fieldLabel: 'Tipo detalle',
                            allowBlank:false,
                            emptyText:'Tipo...',
                            typeAhead: true,
                            triggerAction: 'all',
                            lazyRender:true,
                            mode: 'local',
                            gwidth: 150,
                            store:this.tipoDetalleArray
                    }),
                    
                    'id_producto': new Ext.form.ComboBox({
                                            name: 'id_producto',
                                            fieldLabel: 'Producto/Servicio',
                                            allowBlank: false,
                                            emptyText: 'Productos...',
                                            store: new Ext.data.JsonStore({
                                                url: '../../sis_ventas_facturacion/control/SucursalProducto/listarProductoServicioItem',
                                                id: 'id_producto',
                                                root: 'datos',
                                                sortInfo: {
                                                    field: 'nombre',
                                                    direction: 'ASC'
                                                },
                                                totalProperty: 'total',
                                                fields: ['id_producto', 'tipo','nombre_producto','descripcion','medico', 'precio'],
                                                remoteSort: true,
                                                baseParams: {par_filtro: 'todo.nombre'}
                                            }),
                                            valueField: 'id_producto',
                                            displayField: 'nombre_producto',
                                            gdisplayField: 'nombre_producto',
                                            hiddenName: 'id_producto',
                                            forceSelection: true,
                                            tpl : new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item">','<tpl if="tipo == \'formula\'">',
                                            '<p><b>Medico:</b> {medico}</p>','</tpl>',
                                            '<p><b>Nombre:</b> {nombre_producto}</p><p><b>Descripcion:</b> {descripcion}</p><p><b>Precio:</b> {precio}</p></div></tpl>'),
                                            typeAhead: false,
                                            triggerAction: 'all',
                                            lazyRender: true,
                                            mode: 'remote',
                                            resizable:true,
                                            pageSize: 15,
                                            queryDelay: 1000,
                                            anchor: '100%',
                                            width : 250,
                                            listWidth:'450',
                                            minChars: 2 ,
                                            disabled:true                                           
                                         }),
                                      
                    'cantidad': new Ext.form.NumberField({
                                        name: 'cantidad',
                                        msgTarget: 'title',
                                        fieldLabel: 'Cantidad',
                                        allowBlank: false,
                                        allowDecimals: false,
                                        maxLength:10,
                                        enableKeyEvents : true
                                        
                                }),
                    'precio_unitario': new Ext.form.NumberField({
                                        name: 'precio_unitario',
                                        msgTarget: 'title',
                                        fieldLabel: 'P/U',
                                        allowBlank: false,
                                        allowDecimals: false,
                                        maxLength:10,
                                        readOnly :true
                                }),
                    'precio_total': new Ext.form.NumberField({
                                        name: 'precio_total',
                                        msgTarget: 'title',
                                        fieldLabel: 'Total',
                                        allowBlank: false,
                                        allowDecimals: false,
                                        maxLength:10,
                                        readOnly :true
                                })
                    
              }
            
            
    }, 
    
    iniciarEventos : function () {
    	if (this.data.objPadre.variables_globales.vef_tiene_punto_venta != 'true') {  
	        this.Cmp.id_sucursal.store.load({params:{start:0,limit:this.tam_pag}, 
	           callback : function (r) {
	           		console.log(r.getById(this.data.objPadre.variables_globales.id_sucursal));
	           		this.Cmp.id_sucursal.setValue(this.data.objPadre.variables_globales.id_sucursal);
	           		this.detCmp.id_producto.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
	                this.Cmp.id_sucursal.fireEvent('select',this.Cmp.id_sucursal, this.Cmp.id_sucursal.store.getById(this.data.objPadre.variables_globales.id_sucursal));	                   
	                                
	            }, scope : this
	        });
	    }
        if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') {
	        this.Cmp.id_punto_venta.store.load({params:{start:0,limit:this.tam_pag}, 
	           callback : function (r) {
	           		console.log(this.Cmp.id_punto_venta.store.getById(this.data.objPadre.variables_globales.id_punto_venta));
	                this.Cmp.id_punto_venta.setValue(this.data.objPadre.variables_globales.id_punto_venta);
	           		this.detCmp.id_producto.store.baseParams.id_punto_venta = this.Cmp.id_punto_venta.getValue();
	                this.Cmp.id_punto_venta.fireEvent('select',this.Cmp.id_punto_venta, this.Cmp.id_punto_venta.store.getById(this.data.objPadre.variables_globales.id_punto_venta));   
	                                
	            }, scope : this
	        });
	    }
	    
	    this.Cmp.id_punto_venta.on('select',function(c,r,i) {
	    	if (this.accionFormulario != 'EDIT') {
            	this.Cmp.id_forma_pago.store.baseParams.defecto = 'si';
           }
            this.cargarFormaPago();
            
        },this);
        
        this.Cmp.id_forma_pago.on('select',function(c,r,i) {
            if (r.data.registrar_tarjeta == 'si' || r.data.registrar_cc == 'si') {
            	this.mostrarComponente(this.Cmp.numero_tarjeta);
            	this.Cmp.numero_tarjeta.allowBlank = false;
            	if (r.data.registrar_tarjeta == 'si') {
	            	this.mostrarComponente(this.Cmp.codigo_tarjeta);
	            	this.mostrarComponente(this.Cmp.tipo_tarjeta);
	            	
	            	this.Cmp.codigo_tarjeta.allowBlank = false;
	            	this.Cmp.tipo_tarjeta.allowBlank = false;
	            } else {
	            	this.Cmp.codigo_tarjeta.allowBlank = true;
            		this.Cmp.tipo_tarjeta.allowBlank = true;
            		this.ocultarComponente(this.Cmp.codigo_tarjeta);
            		this.ocultarComponente(this.Cmp.tipo_tarjeta);
            		this.Cmp.codigo_tarjeta.reset();
            		this.Cmp.tipo_tarjeta.reset();
	            }
            } else {
            	this.ocultarComponente(this.Cmp.numero_tarjeta);
            	this.ocultarComponente(this.Cmp.codigo_tarjeta);
            	this.ocultarComponente(this.Cmp.tipo_tarjeta);
            	this.Cmp.numero_tarjeta.allowBlank = true;
            	this.Cmp.codigo_tarjeta.allowBlank = true;
            	this.Cmp.tipo_tarjeta.allowBlank = true;
            	this.Cmp.numero_tarjeta.reset();
            	this.Cmp.codigo_tarjeta.reset();
            	this.Cmp.tipo_tarjeta.reset();
            }
        },this);
        
        this.Cmp.id_sucursal.on('select',function(c,r,i) {
        	if (this.accionFormulario != 'EDIT') {
            	this.Cmp.id_forma_pago.store.baseParams.defecto = 'si';
            }
            this.cargarFormaPago();
            
        },this);
        
        this.detCmp.tipo.on('select',function(c,r,i) {
            this.cambiarCombo(r.data.field1);
        },this);       
        
        this.Cmp.id_cliente.on('select',function(c,r,i) {
            this.Cmp.nit.setValue(r.data.nit);            
        },this);      
        
        
        this.detCmp.id_producto.on('select',function(c,r,i) {
            this.detCmp.precio_unitario.setValue(Number(r.data.precio));
            this.detCmp.precio_total.setValue(Number(r.data.precio) * Number(this.detCmp.cantidad.getValue()));
        },this);
        
        this.detCmp.cantidad.on('keyup',function() {  
            this.detCmp.precio_total.setValue(Number(this.detCmp.precio_unitario.getValue()) * Number(this.detCmp.cantidad.getValue()));
        },this);
    },    
    
    cambiarCombo : function (tipo) {
    	this.detCmp.id_producto.setDisabled(false);
    	this.detCmp.id_producto.store.baseParams.tipo = tipo;
    	if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') { 
    		this.detCmp.id_producto.store.baseParams.id_punto_venta = this.Cmp.id_punto_venta.getValue();
    	} else {
    		this.detCmp.id_sucursal.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
    	}
    	this.detCmp.id_producto.modificado = true;
    	this.detCmp.id_producto.reset();
    },
    cargarFormaPago : function () {
    	this.Cmp.id_forma_pago.store.baseParams.id_punto_venta = this.Cmp.id_punto_venta.getValue();
    	this.Cmp.id_forma_pago.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
    	
    	if (this.accionFormulario == 'EDIT' && this.Cmp.id_forma_pago.getValue() == '0') {
    		this.ocultarComponente(this.Cmp.numero_tarjeta);
        	this.ocultarComponente(this.Cmp.codigo_tarjeta);
        	this.ocultarComponente(this.Cmp.tipo_tarjeta);
        	this.Cmp.numero_tarjeta.allowBlank = false;
        	this.Cmp.codigo_tarjeta.allowBlank = false;
        	this.Cmp.tipo_tarjeta.allowBlank = false;
        	this.Cmp.numero_tarjeta.reset();
        	this.Cmp.codigo_tarjeta.reset();
        	this.Cmp.tipo_tarjeta.reset();        	
        	this.Cmp.monto_forma_pago.setDisabled(true);        	
        	this.Cmp.id_forma_pago.setDisabled(true);
        	this.Cmp.tipo_tarjeta.setDisabled(true);
      } else {
    	
	    	this.Cmp.id_forma_pago.store.load({params:{start:0,limit:this.tam_pag}, 
		           callback : function (r) {
		           		if (this.accionFormulario != 'EDIT') {
		           			if (r.length == 1 ) {                       
			                    this.Cmp.id_forma_pago.setValue(r[0].data.id_forma_pago); 
			                    this.Cmp.id_forma_pago.fireEvent('select', this.Cmp.id_forma_pago,r[0],0);
			                }
		           		} else {		           		
		           			this.Cmp.id_forma_pago.fireEvent('select', this.Cmp.id_forma_pago,this.Cmp.id_forma_pago.store.getById(this.Cmp.id_forma_pago.getValue()),0);
		           		}
		                
		                this.Cmp.id_forma_pago.store.baseParams.defecto = 'no';  
		                this.Cmp.id_forma_pago.modificado = true;
		                                
		            }, scope : this
		        });
		}
    },
    
    onInitAdd: function(){
    	if(this.data.readOnly===true){
    		return false
    	}
    	
    },
    
    onCancelAdd: function(re,save){
        if(this.sw_init_add){
            this.mestore.remove(this.mestore.getAt(0));
        }
        
        this.sw_init_add = false;
        this.evaluaGrilla();
    },
    
    
    onUpdateRegister: function(){
        this.sw_init_add = false;
    },
    
    onAfterEdit:function(re, o, rec, num){
        //set descriptins values ...  in combos boxs       
        
        cmb_rec = this.detCmp['id_producto'].store.getById(rec.get('id_producto'));
        if(cmb_rec) {
            
            rec.set('nombre_producto', cmb_rec.get('nombre_producto')); 
        }
                     
        
    },
    evaluaRequistos: function(){
    	//valida que todos los requistosprevios esten completos y habilita la adicion en el grid
     	var i = 0;
    	sw = true,
    	me =this;
    	while( i < me.Componentes.length) {
    		
    		if(me.Componentes[i] &&!me.Componentes[i].isValid()){
    		   sw = false;
    		   //i = this.Componentes.length;
    		}
    		i++;
    	}
    	return sw
    },
    bloqueaRequisitos: function(sw){
    	this.Cmp.id_sucursal.setDisabled(sw);    	
    	
    },    
    
    evaluaGrilla: function(){
    	//al eliminar si no quedan registros en la grilla desbloquea los requisitos en el maestro
    	var  count = this.mestore.getCount();
    	if(count == 0){
    		this.bloqueaRequisitos(false);
    	} 
    },
        
    buildDetailGrid: function(){
        
        //cantidad,detalle,peso,totalo
        var Items = Ext.data.Record.create([{
                        name: 'cantidad',
                        type: 'int'
                    }, {
                        name: 'id_producto',
                        type: 'int'
                    },{
                        name: 'tipo',
                        type: 'string'
                    }
                    ]);
        
        this.mestore = new Ext.data.JsonStore({
                    url: '../../sis_ventas_facturacion/control/VentaDetalle/listarVentaDetalle',
                    id: 'id_venta_detalle',
                    root: 'datos',
                    totalProperty: 'total',
                    fields: [
                        {name:'id_venta_detalle', type: 'numeric'},
                        {name:'id_venta', type: 'numeric'}, 
                        {name:'nombre_producto', type: 'string'},
                        {name:'id_producto', type: 'numeric'},
                        {name:'tipo', type: 'string'},
                        {name:'estado_reg', type: 'string'},
                        {name:'cantidad', type: 'numeric'},
                        {name:'precio', type: 'numeric'},
                        {name:'precio_total', type: 'numeric'},                        
                        {name:'id_usuario_ai', type: 'numeric'},
                        {name:'usuario_ai', type: 'string'},
                        {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
                        {name:'id_usuario_reg', type: 'numeric'},
                        {name:'id_usuario_mod', type: 'numeric'},
                        {name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
                        {name:'usr_reg', type: 'string'},
                        {name:'usr_mod', type: 'string'},
                        
                    ],
                    remoteSort: true,
                    baseParams: {dir:'ASC',sort:'id_venta_detalle',limit:'50',start:'0'}
                });
        
        this.editorDetail = new Ext.ux.grid.RowEditor({
                saveText: 'Aceptar',
                name: 'btn_editor'
               
            });  
        
        this.summary = new Ext.ux.grid.GridSummary();          
        
        this.editorDetail.on('beforeedit', this.onInitAdd , this);        
        //al cancelar la edicion
        this.editorDetail.on('canceledit', this.onCancelAdd , this);
        
        //al cancelar la edicion
        this.editorDetail.on('validateedit', this.onUpdateRegister, this);
        
        this.editorDetail.on('afteredit', this.onAfterEdit, this);        
        
        this.megrid = new Ext.grid.GridPanel({
                    layout: 'fit',
                    store:  this.mestore,
                    region: 'center',
                    split: true,
                    border: false,
                    plain: true,
                    //autoHeight: true,
                    plugins: [ this.editorDetail, this.summary],
                    stripeRows: true,
                    tbar: [{
                        /*iconCls: 'badd',*/
                        text: '<i class="fa fa-plus-circle fa-lg"></i> Agregar Detalle',
                        scope: this,
                        width: '100',
                        handler: function() { 
                        	
                        	if(this.evaluaRequistos() === true){                               
                                 var e = new Items({
                                    id_producto: undefined,                                    
                                    cantidad: 1,
                                    precio_unitario:0,
                                    precio_total:0});
                                
                                this.editorDetail.stopEditing();
                                this.mestore.insert(0, e);
                                
                                this.megrid.getView().refresh();
                                this.megrid.getSelectionModel().selectRow(0);
                                this.editorDetail.startEditing(0);
                                this.sw_init_add = true;
                                
                                if (this.detCmp.tipo.store.getTotalCount() == 1) {                                	                       		
	                        		this.detCmp.tipo.setValue(this.detCmp.tipo.store.getAt(0).data.field1);
	                        		this.detCmp.tipo.fireEvent('select',this.detCmp.tipo,this.detCmp.tipo.store.getAt(0),0)
	                        		
	                        	}
                                this.bloqueaRequisitos(true);
                            } else {
                            	
                            }
                                                       
                        }
                    },{
                        ref: '../removeBtn',
                        text: '<i class="fa fa-trash fa-lg"></i> Eliminar',
                        scope:this,
                        handler: function(){
                            this.editorDetail.stopEditing();
                            var s = this.megrid.getSelectionModel().getSelections();
                            for(var i = 0, r; r = s[i]; i++){
                                this.mestore.remove(r);
                            }
                            this.evaluaGrilla();
                        }
                    }],
            
                    columns: [
                    new Ext.grid.RowNumberer(),
                    {
                        header: 'Tipo',
                        dataIndex: 'tipo',
                        width: 90,
                        sortable: false,                        
                        editor: this.detCmp.tipo 
                    }, 
                    {
                        header: 'Producto/Servicio',
                        dataIndex: 'id_producto',
                        width: 350,
                        sortable: false,
                        renderer:function(value, p, record){return String.format('{0}', record.data['nombre_producto']);},
                        editor: this.detCmp.id_producto 
                    },                   
                    {
                       
                        header: 'Cantidad',
                        dataIndex: 'cantidad',
                        align: 'right',
                        width: 75,
                        summaryType: 'sum',
                        editor: this.detCmp.cantidad 
                    },
                    {
                       
                        header: 'P / Unit',
                        dataIndex: 'precio_unitario',
                        align: 'right',
                        width: 85,
                        summaryType: 'sum',
                        editor: this.detCmp.precio_unitario 
                    },
                    {
                        xtype: 'numbercolumn',
                        header: 'Total',
                        dataIndex: 'precio_total',
                        align: 'right',
                        width: 85,
                        summaryType: 'sum',
                        format: '$0,0.00',
                        editor: this.detCmp.precio_total 
                    }
                    ]
                });
    },
    onInitAdd : function (r, i) {                
        this.detCmp.id_producto.setDisabled(true);       
        record = this.megrid.store.getAt(i);
        //alert(record.data.tipo);
        if (record.data.tipo != '' && record.data.tipo != undefined) {
            //alert(this.detCmp.tipo.getValue());
            this.cambiarCombo(record.data.tipo);
        }
    },
    buildGrupos: function(){
        this.Grupos = [{
                        layout: 'border',
                        border: false,
                         frame:true,
                        items:[
                          {
                            xtype: 'fieldset',
                            border: false,
                            split: true,
                            layout: 'column',
                            region: 'north',
                            autoScroll: true,
                            autoHeight: true,
                            collapseFirst : false,
                            collapsible: true,
                            width: '100%',
                            //autoHeight: true,
                            padding: '0 0 0 10',
                            items:[
                                   {
                                    bodyStyle: 'padding-right:5px;',
                                   
                                    autoHeight: true,
                                    border: false,
                                    items:[
                                       {
                                        xtype: 'fieldset',
                                        frame: true,
                                        border: false,
                                        layout: 'form', 
                                        title: 'Datos Venta',
                                        width: '40%',
                                        
                                        //margins: '0 0 0 5',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 0,
                                        items: [],
                                     }]
                                 },
                                 {
                                  bodyStyle: 'padding-right:5px;',
                                
                                  border: false,
                                  autoHeight: true,
                                  items: [{
                                        xtype: 'fieldset',
                                        frame: true,
                                        layout: 'form',
                                        title: ' Datos Sucursal ',
                                        width: '33%',
                                        border: false,
                                        //margins: '0 0 0 5',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 1,
                                        items: [],
                                     }]
                                 },
                                 {
                                  bodyStyle: 'padding-right:5px;',
                                
                                  border: false,
                                  autoHeight: true,
                                  items: [{
                                        xtype: 'fieldset',
                                        frame: true,
                                        layout: 'form',
                                        title: ' Forma de Pago ',
                                        width: '33%',
                                        border: false,
                                        //margins: '0 0 0 5',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 2,
                                        items: [{
								             xtype:'button',
								             
								             text:'Dividir Forma de Pago',
								             handler: this.onDividirFormaPago,
								             scope:this,
								             //makes the button 24px high, there is also 'large' for this config
								             scale: 'medium'
								           }],
                                     }]
                                 },
                                 
                              ]
                          },
                            this.megrid
                         ]
                 }];
        
        
    },
    crearStoreFormaPago : function () {
    	this.storeFormaPago = new Ext.data.JsonStore({
    		url: '../../sis_ventas_facturacion/control/FormaPago/listarFormaPago',
			id: 'id_forma_pago',
			root: 'datos',
			sortInfo: {
				field: 'id_forma_pago',
				direction: 'ASC'
			},
			totalProperty: 'total',
			fields: [
	           {name: 'id_forma_pago',type: 'numeric'},
	           {name: 'nombre',      type: 'string'},
	           {name: 'valor',     type: 'numeric'},
	           {name: 'numero_tarjeta',     type: 'string'},
	           {name: 'codigo_tarjeta',     type: 'string'},
	           {name: 'registrar_tarjeta',     type: 'string'},
	           {name: 'registrar_cc',     type: 'string'},
	           {name: 'tipo_tarjeta',     type: 'string'}
	        ]
		});
		this.storeFormaPago.baseParams.id_punto_venta = this.Cmp.id_punto_venta.getValue();
		this.storeFormaPago.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
		this.storeFormaPago.baseParams.id_venta = this.Cmp.id_venta.getValue();
		this.storeFormaPago.load({params:{start:0,limit:100}});
    },
    onDividirFormaPago : function () {
    	if (!this.Cmp.id_sucursal.getValue() && !this.Cmp.id_punto_venta.getValue()) {
    		Ext.Msg.alert('ATENCION', 'Debe registrar la sucursal o el punto de venta para dividir la forma de pago');
    	} else {
	    	var wid = Ext.id();
	    	
		    if (!this.storeFormaPago) {
		    	this.crearStoreFormaPago();
			}
			
		    // create the Grid
		    var grid = new Ext.grid.EditorGridPanel({
		        store: this.storeFormaPago,
		        stateful: false,
		        margins: '3 3 3 0',
		        loadMask: true,
		        columns: [
		            {
		                header     : 'Forma de Pago',	 
		                flex     : 1,
		                width    : 280,               
		                dataIndex: 'nombre'
		            },
		            {
		                header     : 'Valor',
		                width    : 100,	                
		                dataIndex: 'valor',
		                align : 'right',
		                editable : true,
		                editor: new Ext.form.NumberField({
	                                        name: 'valor',                                        
	                                        fieldLabel: 'Cantidad',
	                                        allowBlank: false,
	                                        allowDecimals: true,
	                                        allowNegative: false,
	                                        maxLength:15                                       
	                                })
		            },
		            {
		                header     : 'Tipo Tarjeta',
		                width    : 125,	                
		                dataIndex: 'tipo_tarjeta',		                
		                editable : true,
		                editor: new Ext.form.ComboBox({
	                                        name: 'tipo_tarjeta',
							                fieldLabel: 'Tipo Tarjeta',
							                allowBlank: true,  
							                emptyText:'tipo...',
							                triggerAction: 'all',
							                lazyRender:true,
							                mode: 'local', 
							                displayField: 'text',
                							valueField: 'value',
							                store:new Ext.data.SimpleStore({
												data : [['VI', 'VISA'], ['AX', 'AMERICAN EXPRESS'],
														['DC', 'DINERS CLUB'],['CA', 'MASTER CARD'],
														['RE', 'RED ENLACE']],
												id : 'value',
												fields : ['value', 'text']
											})                                      
	                                })
		            },
		            {
		                header     : 'No Tarjeta / Cuenta Corriente',
		                width    : 170,	                
		                dataIndex: 'numero_tarjeta',		                
		                editable : true,
		                editor: new Ext.form.TextField({
	                                        name: 'numero_tarjeta',                                        
	                                        fieldLabel: 'Numero Tarjeta',
	                                        allowBlank: true,	                                        
	                                        maxLength:24,
	                                        minLength:15                                     
	                                })
		            },
		            {
		                header     : 'Codigo de Autorizacion',
		                width    : 130,	                
		                dataIndex: 'codigo_tarjeta',		                
		                editable : true,
		                editor: new Ext.form.TextField({
	                                        name: 'codigo_tarjeta',                                        
	                                        fieldLabel: 'Codigo de Autorizacion',
	                                        allowBlank: true,	                                        
	                                        maxLength:24,
	                                        minLength:15
	                                                                               
	                                })
		            }
		        ],
		        region:  'center',
		    });
		    
	        var win = new Ext.Window({            
	            id: wid,
	            layout:'fit',
	            width:820,
	            height:350,
	            modal:true,
	            items: grid,
	            title: 'Dividir Formas de Pago',
	            buttons: [{
	                text:'Guardar',
	                disabled:false,
	                scope : this,
	                handler : function () {
	                	this.storeFormaPago.commitChanges();
	                	var validado = true;
	                	for(var i = 0; i < this.storeFormaPago.getTotalCount() ;i++) {
	                		var fp = this.storeFormaPago.getAt(i);
	                		if (fp.data.valor != "0" && fp.data.registrar_tarjeta == 'si' && (fp.data.numero_tarjeta == '' || fp.data.codigo_tarjeta == '' || fp.data.tipo_tarjeta == '')) {
	                			validado = false;
	                			alert('La forma de pago ' + fp.data.nombre + ' requiere el tipo, numero de tarjeta y codigo de autorización')
	                		}
	                		
	                		if (fp.data.valor != "0" && fp.data.registrar_cc == 'si' && (fp.data.numero_tarjeta == '')) {
	                			validado = false;
	                			alert('La forma de pago ' + fp.data.nombre + ' requiere el código de cuenta corriente');
	                		}
	                	}
	                	if (validado) {
		                	this.Cmp.id_forma_pago.setValue(0);
		                	this.Cmp.monto_forma_pago.setValue(0);
		                	this.Cmp.monto_forma_pago.setDisabled(true);
		                	this.Cmp.id_forma_pago.setRawValue('DIVIDIDO');
		                	this.Cmp.id_forma_pago.setDisabled(true);
		                	this.ocultarComponente(this.Cmp.numero_tarjeta);
			            	this.ocultarComponente(this.Cmp.codigo_tarjeta);
			            	this.ocultarComponente(this.Cmp.tipo_tarjeta);
			            	this.Cmp.numero_tarjeta.allowBlank = false;
			            	this.Cmp.codigo_tarjeta.allowBlank = false;
			            	this.Cmp.tipo_tarjeta.allowBlank = false;
			            	this.Cmp.numero_tarjeta.reset();
			            	this.Cmp.codigo_tarjeta.reset();
			            	this.Cmp.tipo_tarjeta.reset();
		                	win.close();
		                }         	
	                	
	                }
	            }]
	        });
	        win.show();
	    }
        
    },
    loadValoresIniciales:function() 
    {                
       Phx.vista.FormVenta.superclass.loadValoresIniciales.call(this);
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
            config : {
                name : 'id_cliente',
                fieldLabel : 'Cliente',
                allowBlank : false,
                emptyText : 'Cliente...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_ventas_facturacion/control/Cliente/listarCliente',
                    id : 'id_cliente',
                    root : 'datos',
                    sortInfo : {
                        field : 'nombres',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_cliente', 'nombres', 'primer_apellido', 'segundo_apellido','nombre_factura','nit'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'cli.nombres#cli.primer_apellido#cli.segundo_apellido#nombre_factura#nit'
                    }
                }),
                valueField : 'id_cliente',
                displayField : 'nombre_factura',  
                gdisplayField : 'nombre_factura',              
                hiddenName : 'id_cliente',
                forceSelection : false,
                typeAhead : false,
                tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>NIT:</b> {nit}</p><p><b>Razon Social:</b> {nombre_factura}</p><p><b>Nombre:</b> {nombres} {primer_apellido} {segundo_apellido}</p> </div></tpl>',
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,
                turl:'../../../sis_ventas_facturacion/vista/cliente/Cliente.php',
                ttitle:'Clientes',
                // tconfig:{width:1800,height:500},
                tasignacion : true,           
                tname : 'id_cliente',
                tdata:{},
                tcls:'Cliente',
                gwidth : 170,
                minChars : 2
            },
            type : 'TrigguerCombo',
            id_grupo : 0,            
            form : true
        },
        {
            config:{
                name: 'nit',
                fieldLabel: 'NIT',
                allowBlank: false,
                anchor: '80%',                
                maxLength:20
            },
                type:'TextField',                
                id_grupo:0,                
                form:true,
                valorInicial:'0'
        },          
        {
            config: {
                name: 'id_sucursal',
                fieldLabel: 'Sucursal',
                allowBlank: false,
                emptyText: 'Elija una Suc...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_ventas_facturacion/control/Sucursal/listarSucursal',
                    id: 'id_sucursal',
                    root: 'datos',
                    sortInfo: {
                        field: 'nombre',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_sucursal', 'nombre', 'codigo'],
                    remoteSort: true,
                    baseParams: {filtro_usuario: 'si',par_filtro: 'suc.nombre#suc.codigo'}
                }),
                valueField: 'id_sucursal',
                gdisplayField : 'nombre_sucursal',
                displayField: 'nombre',                
                hiddenName: 'id_sucursal',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 15,
                queryDelay: 1000,                
                minChars: 2
            },
            type: 'ComboBox',
            id_grupo: 1,            
            form: true
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
                gwidth: 150,
                minChars: 2,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['forma_pago']);
                }
            },
            type: 'ComboBox',
            id_grupo: 2,
            grid: true,
            form: true
        },
        {
            config:{
                name: 'monto_forma_pago',
                fieldLabel: 'Importe Recibido',
                allowBlank: false,
                anchor: '80%',                
                maxLength:20,
                allowNegative:false,
                value:0
            },
                type:'NumberField',                
                id_grupo:2,                
                form:true,
                valorInicial:'0'
        },
        {
            config:{
                name: 'tipo_tarjeta',
                fieldLabel: 'Tipo Tarjeta',
                allowBlank: true,  
                emptyText:'tipo...',
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
                displayField: 'text',
                valueField: 'value',
                store:new Ext.data.SimpleStore({
					data : [['VI', 'VISA'], ['AX', 'AMERICAN EXPRESS'],
							['DC', 'DINERS CLUB'],['CA', 'MASTER CARD'],
							['RE', 'RED ENLACE']],
					id : 'value',
					fields : ['value', 'text']
				})
            },
            type:'ComboBox',            
            id_grupo:2,            
            form:true
        },        
        {
            config:{
                name: 'numero_tarjeta',
                fieldLabel: 'No Tarjeta/Cuenta Corriente',
                allowBlank: true,
                anchor: '80%',                
                maxLength:24,
	            minLength:15
                
            },
                type:'TextField',                
                id_grupo:2,                
                form:true
        },
        {
            config:{
                name: 'codigo_tarjeta',
                fieldLabel: 'Codigo de Autorización',
                allowBlank: true,
                anchor: '80%',                
                maxLength:20
                
            },
                type:'TextField',                
                id_grupo:2,                
                form:true
        }
           
          
        
    ],
    title: 'Formulario Venta',
    onEdit:function(){
        
    	this.accionFormulario = 'EDIT';    	
    	this.loadForm(this.data.datos_originales);    	
    	
        //load detalle de conceptos
        this.mestore.baseParams.id_venta = this.Cmp.id_venta.getValue();
        this.mestore.load();  
        this.crearStoreFormaPago();    	
        
    },    
    onNew: function(){    	
    	this.accionFormulario = 'NEW';
	},
    
    onSubmit: function(o) {
        //  validar formularios
        var arra = [], i, me = this;
        var formapa = [];
        for (i = 0; i < me.megrid.store.getCount(); i++) {
            var record = me.megrid.store.getAt(i);
            arra[i] = record.data;            
        } 
        if (me.storeFormaPago) {
	        for (i = 0; i < me.storeFormaPago.getCount(); i++) {
	            var record = me.storeFormaPago.getAt(i);
	            formapa[i] = record.data;            
	        }
	    }        
        
        me.argumentExtraSubmit = { 'json_new_records': JSON.stringify(arra, 
        				function replacer(key, value) {
                       		if (typeof value === 'string') {
                            	return String(value).replace(/&/g, "%26")
                            }
                            return value;
                        }),
                        'formas_pago' :  JSON.stringify(formapa, 
        				function replacer(key, value) {
                       		if (typeof value === 'string') {
                            	return String(value).replace(/&/g, "%26")
                            }
                            return value;
                        })};
        if( i > 0 &&  !this.editorDetail.isVisible()){
             Phx.vista.FormVenta.superclass.onSubmit.call(this,o);
        }
        else{
            alert('La venta no tiene registrado ningun detalle');
        }
    },    
    
    successSave:function(resp)
    {
    	var datos_respuesta = JSON.parse(resp.responseText);
    	Phx.CP.loadingHide();
    	if ('cambio' in datos_respuesta.ROOT.datos) {
    		Ext.Msg.show({
			   title:'DEVOLUCION',
			   msg: 'Debe devolver ' + datos_respuesta.ROOT.datos.cambio + ' al cliente',
			   buttons: Ext.Msg.OK,
			   fn: function () {			   	
		        Phx.CP.getPagina(this.idContenedorPadre).reload();
		        this.panel.close();
			   },
			   scope:this
			});
    		//Ext.Msg.alert('DEVOLUCION', 'Debe devolver ' + datos_respuesta.ROOT.datos.cambio + ' al cliente');
    	} else {
    		Phx.CP.getPagina(this.idContenedorPadre).reload();
		    this.panel.close();
    	}
        
    }, 
    
    
    
})    
</script>