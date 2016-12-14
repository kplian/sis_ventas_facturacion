<?php
/**
*@package pXP
*@file    FormSolicitud.php
*@author  Rensi Arteaga Copari 
*@date    30-01-2014
*@description permites subir archivos a la tabla de documento_sol
*/
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
Phx.vista.FormFormula=Ext.extend(Phx.frmInterfaz,{
    ActSave:'../../sis_ventas_facturacion/control/Formula/insertarFormulaCompleta',
    tam_pag: 10,
    //layoutType: 'wizard',
    layout: 'fit',
    autoScroll: false,
    breset: false,
    labelSubmit: '<i class="fa fa-check"></i> Guardar',
    
    constructor:function(config)
    {   
        
        this.addEvents('beforesave');
        this.addEvents('successsave');
        
        this.buildComponentesDetalle();
        
        this.buildDetailGrid();
        
        this.buildGrupos();
        console.log(config);
        if(config.data.tipo_form == 'edit') {
        	this.breset = true;
        	this.labelReset= '<i class="fa fa-check"></i> Guardar como Nuevo';
        	this.tooltipReset= '<b>Guarda la formula/paquete como si fuera un nuevo registro</b>';
        	this.iconReset= '../../../lib/imagenes/print.gif';
        	this.clsReset=  'bsave';
        }
        
        Phx.vista.FormFormula.superclass.constructor.call(this,config);
        this.init();    
        this.iniciarEventos();
        if(this.data.tipo_form == 'new'){
        	this.onNew();
        }
        else{
        	this.onEdit();
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
                            store:['producto_servicio','item']
                    }),
                   'id_producto': new Ext.form.TrigguerCombo({
                                            name: 'id_producto',
                                            fieldLabel: 'Producto/Servicio',
                                            allowBlank: false,
                                            emptyText: 'Productos...',
                                            tinit:true,
										    tasignacion:false,
										    tname:'id_producto',
									        tdisplayField:'nombre',   				
											turl:'../../../sis_ventas_facturacion/vista/sucursal_producto/SucursalProducto.php',
											ttitle:'Sucursal Producto',
											tconfig:{width:'80%',height:'90%'},
											tdata:{formulario : 'formula'},
											tcls:'SucursalProducto',		
                                            store: new Ext.data.JsonStore({
                                                url: '../../sis_ventas_facturacion/control/SucursalProducto/listarItemsFormula',
                                                id: 'id_producto',
                                                root: 'datos',
                                                sortInfo: {
                                                    field: 'nombre',
                                                    direction: 'ASC'
                                                },
                                                totalProperty: 'total',
                                                fields: ['id_producto', 'tipo','nombre_producto','descripcion','unidad_medida'],
                                                remoteSort: true,
                                                baseParams: {par_filtro: 'todo.nombre'}
                                            }),
                                            valueField: 'id_producto',
                                            displayField: 'nombre_producto',
                                            gdisplayField: 'nombre_producto',
                                            hiddenName: 'id_producto',
                                            forceSelection: true,
                                            tpl : new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item">',
                                            '<p><b>Nombre:</b> {nombre_producto}</p><p><b>Unidad:</b> {unidad_medida}</p><p><b>Descripcion:</b> {descripcion}</p></div></tpl>'),
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
                    'unidad_medida': new Ext.form.TextField({
                        name: 'unidad_medida',
                        msgTarget: 'title',
                        fieldLabel: 'Unidad Medida',
                        allowBlank: true,
                        readOnly :true
                    }),
                    'cantidad': new Ext.form.NumberField({
                                        name: 'cantidad',
                                        msgTarget: 'title',
                                        fieldLabel: 'Cantidad',
                                        allowBlank: false,
                                        allowDecimals: true,
                                        decimalPrecision : 6,
                                        maxLength:10,
                                        enableKeyEvents : true
                                        
                                }),
                    'precio_unitario': new Ext.form.NumberField({
                                        name: 'precio_unitario',
                                        msgTarget: 'title',
                                        fieldLabel: 'P/U',
                                        allowBlank: false,
                                        allowDecimals: true,
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
        
        this.detCmp.tipo.on('select',function(c,r,i) {
            this.cambiarCombo(r.data.field1);
        },this); 
        
        this.detCmp.id_producto.on('select',function(c,r,i) {
            this.detCmp.unidad_medida.setValue(r.data.unidad_medida);
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
    	this.detCmp.id_producto.modificado = true;
    	this.detCmp.id_producto.reset();
    },
    
    onNew: function(){      
      this.accionFormulario = 'NEW'; 
      
  	},
    
    
    onCancelAdd: function(re,save){
        if(this.sw_init_add){
            this.mestore.remove(this.mestore.getAt(0));
        }
        
        this.sw_init_add = false;        
    },
    onUpdateRegister: function(){
        this.sw_init_add = false;
    },
    
    onAfterEdit:function(re, o, rec, num){
        //set descriptins values ...  in combos boxs
        
        var cmb_rec = this.detCmp['id_producto'].store.getById(rec.get('id_producto'));
        if(cmb_rec) {
            
            rec.set('nombre_producto', cmb_rec.get('nombre_producto')); 
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
                    }
                    ]);
        
        this.mestore = new Ext.data.JsonStore({
                    url: '../../sis_ventas_facturacion/control/FormulaDetalle/listarFormulaDetalle',
                    id: 'id_formula_detalle',
                    root: 'datos',
                    totalProperty: 'total',
                    fields: ['id_formula_detalle','tipo','id_producto', 'cantidad',
                             'nombre_producto','precio_unitario','precio_total','unidad_medida'
                    ],remoteSort: true,
                    baseParams: {dir:'ASC',sort:'id_formula_detalle',limit:'100',start:'0'}
                });
        
        this.editorDetail = new Ext.ux.grid.RowEditor({
                saveText: 'Aceptar',
                name: 'btn_editor'
               
            });            
        
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
                    plugins: [ this.editorDetail],
                    stripeRows: true,
                    tbar: [{
                        /*iconCls: 'badd',*/
                        text: '<i class="fa fa-plus-circle fa-lg"></i> Agregar Componente',
                        scope: this,
                        width: '100',
                        handler: function() {                                
                                 var e = new Items({
                                    id_producto: undefined,
                                    cantidad: 0});
                                
                                this.editorDetail.stopEditing();
                                this.mestore.insert(0, e);
                                this.megrid.getView().refresh();
                                this.megrid.getSelectionModel().selectRow(0);
                                this.editorDetail.startEditing(0);
                                this.sw_init_add = true;
                                                       
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
                            header: 'Unidad Medida',
                            dataIndex: 'unidad_medida',
                            width: 130,
                            sortable: false,
                            editor: this.detCmp.unidad_medida
                        },
                        {
                       
                        header: 'Cantidad',
                        dataIndex: 'cantidad',
                        align: 'center',
                        width: 100,
                        align: 'right',
                        editor: this.detCmp.cantidad 
                    }]
                });
        
    },
    onInitAdd : function (r, i) {  
    	
        this.detCmp.id_producto.setDisabled(true);       
        var record = this.megrid.store.getAt(i);
        var recTem = new Array();
        recTem['id_producto'] = record.data['id_producto'];
        recTem['nombre_producto'] = record.data['nombre_producto'];
        
        this.detCmp.id_producto.store.add(new Ext.data.Record(this.arrayToObject(this.detCmp.id_producto.store.fields.keys,recTem), record.data['id_producto']));
        this.detCmp.id_producto.store.commitChanges();
        this.detCmp.id_producto.modificado = true;
        
        if (record.data.tipo != '' && record.data.tipo != undefined) {
            
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
                                        title: 'Tipo',
                                        width: '40%',
                                        
                                        //margins: '0 0 0 5',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 0,
                                        items: [],
                                     }]
                                 }
                                 
                                 
                              ]
                          },
                            this.megrid
                         ]
                 }];
        
        
    },
    loadValoresIniciales:function() 
    {                
       Phx.vista.FormVenta.superclass.loadValoresIniciales.call(this);
    },
    onReset:function(o){
    	// Show a dialog using config options:
		Ext.Msg.show({
		   title:'Guardar?',
		   msg: 'Esta seguro de guardar esta formula/paquete como una nueva?',
		   buttons: Ext.Msg.YESNO,
		   fn: function(a) {
		   		if (a == 'yes'){
		   			this.Cmp.id_formula.reset();
					this.onSubmit(o);
		   		}
		   },
		   
		   scope : this,
		   icon: Ext.MessageBox.QUESTION
		});
    	
	},
	
    onSubmit: function(o) {
        //  validar formularios
        var arra = [], i, me = this;
        for (i = 0; i < me.megrid.store.getCount(); i++) {
            record = me.megrid.store.getAt(i);
            arra[i] = record.data;            
        }        
        
        me.argumentExtraSubmit = { 'json_new_records': JSON.stringify(arra, function replacer(key, value) {
                       if (typeof value === 'string') {
                                    return String(value).replace(/&/g, "%26")
                                }
                                return value;
                            }) };
        if( i > 0 &&  !this.editorDetail.isVisible()){
             Phx.vista.FormFormula.superclass.onSubmit.call(this,o);
        }
        else{
            alert('no tiene ningun elemento en la formula')
        }
    },   
    
    onEdit:function(){
        
    	this.accionFormulario = 'EDIT';  
    	this.loadForm(this.data.datos_originales);    	
    	
        //load detalle de conceptos
        this.mestore.baseParams.id_formula = this.Cmp.id_formula.getValue();
        this.mestore.load();      	
        
    },    
    onNew: function(){    	
    	this.accionFormulario = 'NEW';
	}, 
    
    successSave:function(resp)
    {
        Phx.CP.loadingHide();
        Phx.CP.getPagina(this.idContenedorPadre).reload();
        this.panel.close();
    },        
    
    
    Atributos:[
        {
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_formula'
            },
            type:'Field',
            form:true 
        },
        
        {
            config:{
                name: 'nombre',
                fieldLabel: 'Nombre Formula',
                allowBlank: false,
                anchor: '100%',
                gwidth: 200,
                maxLength:200
            },
                type:'TextField',                
                id_grupo:0,                
                form:true
        },
        {
            config:{
                name: 'descripcion',
                fieldLabel: 'Descripcion Formula',
                allowBlank: true,
                anchor: '100%',
                gwidth: 250
            },
                type:'TextArea',                
                id_grupo:0,                
                form:true,
        }          
        
    ],
    title: 'Form Formula'
})    
</script>