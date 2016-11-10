<?php
/**
*@package pXP
*@file FormRendicion.php
*@author  Gonzalo Sarmiento 
*@date 16-02-2016
*@description Archivo con la interfaz de usuario que permite 
*ingresar el documento a rendir
*
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormVentaExportacionMin = {
	require:'../../../sis_ventas_facturacion/vista/venta/FormVenta.php',
	requireclase:'Phx.vista.FormVenta',
	mostrarFormaPago : false,	
	cantidadAllowDecimals: true,	
	constructor: function(config) {	
		
		
		this.Atributos[this.getIndAtributo('id_moneda')].form=true;
		this.Atributos[this.getIndAtributo('tipo_cambio_venta')].form=true; 
		
		this.Atributos[this.getIndAtributo('valor_bruto')].form=true; 
		this.Atributos[this.getIndAtributo('transporte_fob')].form=true; 
	    this.Atributos[this.getIndAtributo('seguros_fob')].form=true; 
	    this.Atributos[this.getIndAtributo('otros_fob')].form=true; 
	    this.Atributos[this.getIndAtributo('total_fob')].form=true; 
	    this.Atributos[this.getIndAtributo('transporte_cif')].form=true; 
	    this.Atributos[this.getIndAtributo('seguros_cif')].form=true; 
	    this.Atributos[this.getIndAtributo('otros_cif')].form=true; 
	    this.Atributos[this.getIndAtributo('otros_cif')].config.fieldLabel = 'Gastos Portuarios';
	    this.Atributos[this.getIndAtributo('total_cif')].form=true; 	    
	    this.Atributos[this.getIndAtributo('observaciones')].config.fieldLabel='ICOTERM y Puerto Destino'; 
	    this.Atributos[this.getIndAtributo('observaciones')].type = 'TextField';
	    this.Atributos[this.getIndAtributo('descripcion_bulto')].form=true; 
	    
	    
		      
	    Phx.vista.FormVentaExportacionMin.superclass.constructor.call(this,config);
	    if (this.accionFormulario != 'EDIT') {
		    this.Cmp.valor_bruto.setValue(0);
		    this.Cmp.transporte_fob.setValue(0);
		    this.Cmp.seguros_fob.setValue(0);
		    this.Cmp.otros_fob.setValue(0);
		    this.Cmp.total_fob.setValue(0);
		    this.Cmp.transporte_cif.setValue(0);
		    this.Cmp.seguros_cif.setValue(0);
		    this.Cmp.otros_cif.setValue(0);	    
		    this.Cmp.total_cif.setValue(0);
		    this.Cmp.fecha.setValue(new Date);
	    }
	    
	    this.eventosExtras();
	                   
    },
   buildGrupos: function(){
        this.Grupos = [{
                        layout: 'border',
                        border: false,
                         frame:true,
                        items:[
                          {
                            xtype: 'fieldset',
                            border: true,
                            split: true,
                            layout: 'column',
                            region: 'north',
                            autoScroll: true,
                            autoHeight: true,
                            height: 150,
                            collapseFirst : false,
                            collapsible: true,
                            width: '100%',                            
                            padding: '0 10 0 10',
                            items:[
                                   {
                                        xtype: 'fieldset',
                                        frame: true,
                                        border: false,
                                        layout: 'form', 
                                        title: 'Datos Venta',
                                        columnWidth: '33%',
                                        padding: '0 15 0 15',
                                        //bodyStyle:'padding:5px 5px 0',
                                        id_grupo: 0,
                                        items: [],
                                  },
                                 {
                                        xtype: 'fieldset',
                                        frame: true,
                                        border: false,
                                        layout: 'form',
                                        title: ' Datos Sucursal ',
                                        columnWidth: '33%',
                                        //margins: '0 10 0 10',
                                        padding: '0 15 0 15',
                                        bodyStyle:'padding:5px 5px 5px 10px',
                                        id_grupo: 1,
                                        items: [],
                                  },
                                  {
                                        xtype: 'fieldset',
                                        frame: true,
                                        border: false,
                                        layout: 'form',
                                        title: ' Forma de Pago ',
                                        columnWidth: '33%',
                                       //margins: '0 0 0 5',
                                        padding: '0 15 0 15',
                                        //bodyStyle: 'padding-left:10px;',
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
                            this.megrid,
                          {
                            xtype: 'fieldset',
                            border: false,
                            split: true,
                            layout: 'column',
                            region: 'south',
                            autoScroll: false,
                            autoHeight: false,
                            height: 200,
                            collapseFirst : false,
                            collapsible: true,
                            width: '100%', 
                            padding: '0 0 0 10',
                            items:[{
                                        xtype: 'fieldset',
                                        frame: true,
                                        autoScroll: false,
                                        autoHeight: false,
                                        //autoHeight: true,:
                                        border: false,
                                        layout: 'form', 
                                        title: 'Datos FOB',
                                        width: '50%',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 3,
                                        items: []
                                     
                                 },
                                 {
                                        xtype: 'fieldset',
                                        frame: true,
                                        autoScroll: false,
                                        autoHeight: false,
                                        //autoHeight: true,
                                        border: false,
                                        layout: 'form', 
                                        title: 'Datos CIF',
                                        width: '50%',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 4,
                                        items: []
                                   
                                 }]
                          }]
                 }];
        
        
    },
    
   
    
    eventosExtras: function(){
    	this.Cmp.valor_bruto.on('change',function(field){this.calcularTotalesExtras()} ,this);
        this.Cmp.transporte_fob.on('change',function(field){this.calcularTotalesExtras()} ,this);
        this.Cmp.seguros_fob.on('change',function(field){this.calcularTotalesExtras()} ,this); 
        this.Cmp.otros_fob.on('change',function(field){this.calcularTotalesExtras()} ,this);        
        this.Cmp.transporte_cif.on('change',function(field){this.calcularTotalesExtras()} ,this); 
        this.Cmp.seguros_cif.on('change',function(field){this.calcularTotalesExtras()} ,this); 
        this.Cmp.otros_cif.on('change',function(field){this.calcularTotalesExtras()} ,this);     
       	
    
    },
    
    calcularTotalesExtras: function(){
    	
    	var total_fob = 0, total_cif = 0;
    	total_fob = total_fob + this.Cmp.valor_bruto.getValue();
    	total_fob = total_fob + this.Cmp.transporte_fob.getValue();
    	total_fob = total_fob + this.Cmp.seguros_fob.getValue();
    	total_fob = total_fob + this.Cmp.otros_fob.getValue();
    	this.Cmp.total_fob.setValue(total_fob);
    	total_cif = total_cif + total_fob;
    	total_cif = total_cif + this.Cmp.transporte_cif.getValue();
    	total_cif = total_cif + this.Cmp.seguros_cif.getValue();
    	total_cif = total_cif + this.Cmp.otros_cif.getValue();
    	this.Cmp.total_cif.setValue(total_cif);
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
    	
    	if (this.accionFormulario != 'EDIT') {
    	    this.Cmp.id_sucursal.setDisabled(sw); 
    	}
    	this.Cmp.tipo_cambio_venta.setDisabled(sw);
    	this.Cmp.id_moneda.setDisabled(sw);   	
    	this.cargarDatosMaestro();
    	
    },
    
    cargarDatosMaestro: function(){
    	
        
        //cuando esta el la inteface de presupeustos no filtra por bienes o servicios
        this.detCmp.id_producto.store.baseParams.tipo_cambio_venta = this.Cmp.tipo_cambio_venta.getValue();
        this.detCmp.id_producto.store.baseParams.id_moneda = this.Cmp.id_moneda.getValue();
        this.detCmp.id_producto.modificado = true;
    	
    },
    
    onEdit:function(){
        var me = this;
    	this.accionFormulario = 'EDIT';    	
    	this.loadForm(me.data.datos_originales);    	
    	
        //load detalle de conceptos
        this.mestore.baseParams.id_venta = me.Cmp.id_venta.getValue();
       
        this.mestore.on('load',function(){
         	 var tmp = this.summary.getData()
             this.Cmp.valor_bruto.setValue(tmp.precio_total);
             this.Cmp.valor_bruto.fireEvent('change',this.Cmp.valor_bruto)
         },this);
         
        this.mestore.load();
        this.crearStoreFormaPago();  
        this.Cmp.id_moneda.disable();
        this.Cmp.tipo_cambio_venta.disable();
        this.Cmp.fecha.disable();  
        this.Cmp.id_sucursal.disable(); 	
        
    },
    
    buildComponentesDetalle: function(){
        var  me = this;
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
                                                fields: ['id_producto', 'tipo','nombre_producto','descripcion','medico','requiere_descripcion','precio','id_unidad_medida','codigo_unidad_medida'],
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
                                         
                     'id_unidad_medida': new Ext.form.ComboRec({            
                           
						   				name:'id_unidad_medida',
						   				valueField: 'id_unidad_medida',
						   				tipo: 'Masa',
						   				origen:'UNIDADMEDIDA',
						   				displayField: 'codigo',
						   				allowBlank:true,
						   				fieldLabel:'Unidad',
						   				gdisplayField: 'codigo_unidad_medida',
						   				width: 100,
						   				listWidth: 350
						   		       }),           
                                         
                                         
                   /* 'descripcion': new Ext.form.TextField({
                            name: 'descripcion',
                            fieldLabel: 'Descripcion',                            
                            allowBlank:true,                            
                            gwidth: 150,
                            disabled : true
                    }),*/
                    
                   /*  'bruto': new Ext.form.TextField({
                                        name: 'bruto',
                                        msgTarget: 'title',
                                        fieldLabel: 'Bruto',
                                        allowBlank: false,
                                        allowDecimals: true,
                                        maxLength:10,
                                        enableKeyEvents : true
                                        
                                }),*/
                                
                     
                     'ley': new Ext.form.TextField({
                                        name: 'ley',
                                        msgTarget: 'title',
                                        fieldLabel: 'Ley',
                                        allowBlank: false,
                                        allowDecimals: true,
                                        maxLength:10,
                                        enableKeyEvents : true
                                        
                                }),
                                
                     
                     'kg_fino': new Ext.form.TextField({
                                        name: 'kg_fino',
                                        msgTarget: 'title',
                                        fieldLabel: 'Kgs. Finos',
                                        allowBlank: false,
                                        allowDecimals: true,
                                        maxLength:10,
                                        enableKeyEvents : true
                                        
                                }),           
                                           
                                      
                    
                    'cantidad': new Ext.form.NumberField({
                                        name: 'cantidad',
                                        msgTarget: 'title',
                                        fieldLabel: 'Cantidad',
                                        decimalPrecision : 6,
                                        allowBlank: false,
                                        allowDecimals: me.cantidadAllowDecimals,
                                        maxLength:10,
                                        enableKeyEvents : true
                                        
                                }),
                    
                    'precio_unitario': new Ext.form.NumberField({
                                        name: 'precio_unitario',
                                        msgTarget: 'title',
                                        fieldLabel: 'P/U',
                                        allowBlank: false,
                                        allowDecimals: true,
                                        decimalPrecision : 6,
                                        enableKeyEvents : true
                                }),
                    
                    'precio_total': new Ext.form.NumberField({
                                        name: 'precio_total',
                                        msgTarget: 'title',
                                        fieldLabel: 'Total',
                                        allowBlank: false,
                                        allowDecimals: false,
                                        readOnly :true
                                })
                    
              }
            
            
    },
    
    onInitAdd : function (r, i) {  
    	if(this.data.readOnly===true){
    		return false
    	}      
    	
        this.detCmp.id_producto.setDisabled(true);       
        var record = this.megrid.store.getAt(i);
        var recTem = new Array();
        recTem['id_producto'] = record.data['id_producto'];
        recTem['nombre_producto'] = record.data['nombre_producto'];
        
       
        
        this.detCmp.id_producto.store.add(new Ext.data.Record(this.arrayToObject(this.detCmp.id_producto.store.fields.keys,recTem), record.data['id_producto']));
        this.detCmp.id_producto.store.commitChanges();
        this.detCmp.id_producto.modificado = true;
        
        
        var recUm = new Array();
        recUm['id_unidad_medida'] = record.data['id_unidad_medida'];
        recUm['codigo'] = record.data['codigo_unidad_medida'];
        
        
        this.detCmp.id_unidad_medida.store.add(new Ext.data.Record(this.arrayToObject(this.detCmp.id_unidad_medida.store.fields.keys,recUm), record.data['id_unidad_medida']));
        this.detCmp.id_unidad_medida.store.commitChanges();
        this.detCmp.id_unidad_medida.modificado = true;
        
        
        
        if (record.data.tipo != '' && record.data.tipo != undefined) {            
            this.cambiarCombo(record.data.tipo);
        }
        
        if (record.data.requiere_descripcion == 'si') {
            
            this.habilitarDescripcion(true);
        } else {
        	this.habilitarDescripcion(false);
        }
    },
     onAfterEdit:function(re, o, rec, num){
        //set descriptins values ...  in combos boxs       
        var cmb_rec = this.detCmp['id_producto'].store.getById(rec.get('id_producto'));
        if(cmb_rec) {
            
            rec.set('nombre_producto', cmb_rec.get('nombre_producto')); 
        }
        
       var cmb_um = this.detCmp['id_unidad_medida'].store.getById(rec.get('id_unidad_medida'));        
       
       if(cmb_um) {            
            rec.set('codigo_unidad_medida', cmb_um.get('codigo')); 
       }
                     
       var tmp = this.summary.getData()
       this.Cmp.valor_bruto.setValue(tmp.precio_total);
       this.Cmp.valor_bruto.fireEvent('change',this.Cmp.valor_bruto)
       
       
    },
    iniciarEventosProducto:function(){
    	this.detCmp.id_producto.on('select',function(c,r,i) {
    		
    		
            this.detCmp.precio_unitario.setValue(Number(r.data.precio));
            
            var tmp = this.roundTwo(Number(r.data.precio) * Number(this.detCmp.cantidad.getValue()))
            this.detCmp.precio_total.setValue(tmp);
            var recUm = new Array();
	        recUm['id_unidad_medida'] = r.data.id_unidad_medida;
	        recUm['codigo'] = r.data.codigo_unidad_medida;
	        
	        
	        this.detCmp.id_unidad_medida.store.add(new Ext.data.Record(this.arrayToObject(this.detCmp.id_unidad_medida.store.fields.keys,recUm), r.data['id_unidad_medida']));
	        this.detCmp.id_unidad_medida.store.commitChanges();
	        this.detCmp.id_unidad_medida.modificado = true;
	        
	        //rec.set('codigo_unidad_medida', cmb_um.get('codigo')); 
	        
            this.detCmp.id_unidad_medida.setValue(r.data.id_unidad_medida);
        	
        },this);
    	
    },
    
    onSubmit: function(o) {
        //  validar formularios
        var arra = [], i, me = this;
        var formapa = [];
        for (i = 0; i < me.megrid.store.getCount(); i++) {
            var record = me.megrid.store.getAt(i);
            arra[i] = record.data;  
            
            console.log('record.data',record.data)          
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
                        }),
                        'tipo_factura':this.data.objPadre.tipo_factura};
        
        if( i > 0 &&  !this.editorDetail.isVisible()){
             Phx.vista.FormVentaExportacionMin.superclass.onSubmit.call(this,o);
        }
        else{
            alert('La venta no tiene registrado ningun detalle');
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
                        {name:'descripcion', type: 'string'},
                        {name:'requiere_descripcion', type: 'string'},
                        {name:'estado_reg', type: 'string'},
                        {name:'cantidad', type: 'numeric'},
                        {name:'precio_unitario', type: 'numeric'},
                        {name:'precio_total', type: 'numeric'},                        
                        {name:'id_usuario_ai', type: 'numeric'},
                        {name:'usuario_ai', type: 'string'},
                        {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
                        {name:'id_usuario_reg', type: 'numeric'},
                        {name:'id_usuario_mod', type: 'numeric'},
                        {name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
                        {name:'usr_reg', type: 'string'},
                        {name:'usr_mod', type: 'string'},
                        {name:'bruto', type: 'numeric'},
                        {name:'ley', type: 'numeric'},
                        {name:'kg_fino', type: 'numeric'},
                        {name:'id_unidad_medida', type: 'numeric'},
                        {name:'codigo_unidad_medida', type: 'string'}
                        
                        
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
        
         //eventos de rror en los combos  
        this.detCmp.id_producto.store.on('exception', this.conexionFailure);
        this.detCmp.id_unidad_medida.store.on('exception', this.conexionFailure);            
        
        this.megrid = new Ext.grid.GridPanel({
                    layout: 'fit',
                    store:  this.mestore,
                    region: 'center',
                    split: true,
                    border: false,
                    loadMask : true,
                    plain: true,                    
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
                                    id_unidad_medida: undefined,                                    
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
                    }
                    ,{
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
                        header: 'Mineral',
                        dataIndex: 'id_producto',
                        width: 200,
                        sortable: false,
                        renderer:function(value, p, record){return String.format('{0}', record.data['nombre_producto']);},
                        editor: this.detCmp.id_producto 
                    },  
                    /*{
                        header: 'Descripción',
                        dataIndex: 'descripcion',
                        width: 200,
                        sortable: false,                        
                        editor: this.detCmp.descripcion 
                    },  */               
                    /*{
                       
                        header: 'Bruto',
                        dataIndex: 'bruto',
                        align: 'right',
                        width: 100,
                        editor: this.detCmp.bruto 
                    },  */               
                    {
                       
                        header: 'Ley Mineral',
                        dataIndex: 'ley',
                        align: 'right',
                        width: 100,
                        editor: this.detCmp.ley 
                    },                 
                    {
                       
                        header: 'Peso Fino [Kg]',
                        dataIndex: 'kg_fino',
                        align: 'right',
                        width: 100,
                        editor: this.detCmp.kg_fino 
                    },                 
                    {
                       
                        header: 'Peso Fino',
                        dataIndex: 'cantidad',
                        align: 'right',
                        width: 100,
                        summaryType: 'sum',
                        editor: this.detCmp.cantidad 
                    },
                    {
                        header: 'Unidad',
                        dataIndex: 'id_unidad_medida',
                        width: 80,
                        sortable: false,
                        renderer:function(value, p, record){
                        	console.log('>>>>>',record.data)
                        	return String.format('{0}', record.data['codigo_unidad_medida']);
                        	
                        	},
                        editor: this.detCmp.id_unidad_medida 
                    },
                    {
                       
                        header: 'Cotización Mineral',
                        dataIndex: 'precio_unitario',
                        align: 'right',
                        width: 120,
                        summaryType: 'sum',
                        editor: this.detCmp.precio_unitario 
                    },
                    {
                        xtype: 'numbercolumn',
                        header: 'Total',
                        dataIndex: 'precio_total',
                        align: 'right',
                        width: 120,
                        summaryType: 'sum',
                        format: '$0,0.00',
                        editor: this.detCmp.precio_total 
                    }]
                });
    }
	
};
</script>