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
        
        Phx.vista.FormVenta.superclass.constructor.call(this,config);
        this.init();    
        this.iniciarEventos();
        //this.iniciarEventosDetalle();
        //this.onNew();
                
    },
    iniciarEventos : function () {
        this.Cmp.id_sucursal.store.load({params:{start:0,limit:this.tam_pag}, 
           callback : function (r) {
                if (r.length == 1 ) {                       
                    this.Cmp.id_sucursal.setValue(r[0].data.id_sucursal);
                    this.detCmp.id_item.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
                    this.detCmp.id_sucursal_producto.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
                    this.Cmp.id_sucursal.fireEvent('select', r[0]);
                }    
                                
            }, scope : this
        });
        this.detCmp.tipo.on('select',function(c,r,i) {
            this.cambiarCombo(r.data.field1);
        },this);
        
        this.detCmp.id_item.on('select',function(c,r,i) {
            this.detCmp.precio_unitario.setValue(Number(r.data.precio_ref));
            this.detCmp.precio_total.setValue(Number(r.data.precio_ref) * Number(this.detCmp.cantidad.getValue()));
        },this);
        
        this.detCmp.id_formula.on('select',function(c,r,i) {
            if (r.data.precio == '' || r.data.precio == undefined) {
                alert('La formula seleccionada no tiene ningun detalle y no puede ser utilizada');
                this.detCmp.id_formula.reset();
            } else {
                this.detCmp.precio_unitario.setValue(Number(r.data.precio));
                this.detCmp.precio_total.setValue(Number(r.data.precio) * Number(this.detCmp.cantidad.getValue()));
            }
            
        },this);
        
        this.detCmp.id_sucursal_producto.on('select',function(c,r,i) {
            this.detCmp.precio_unitario.setValue(Number(r.data.precio));
            this.detCmp.precio_total.setValue(Number(r.data.precio) * Number(this.detCmp.cantidad.getValue()));
        },this);
        
        this.detCmp.cantidad.on('keyup',function() {  
            this.detCmp.precio_total.setValue(Number(this.detCmp.precio_unitario.getValue()) * Number(this.detCmp.cantidad.getValue()));
        },this);
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
                            store:['producto_terminado','formula', 'servicio']
                    }),
                    'id_item': new Ext.form.ComboBox({
                                            name : 'id_item',
                                            fieldLabel : 'Item',
                                            allowBlank : false,
                                            msgTarget: 'title',
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
                                                fields : ['id_item', 'nombre', 'codigo', 'precio_ref', 'codigo_unidad'],
                                                remoteSort : true,
                                                baseParams : {
                                                    par_filtro : 'item.nombre#item.codigo#cla.nombre'
                                                }
                                            }),
                                            valueField : 'id_item',
                                            displayField : 'nombre',                                           
                                            qtip:'Seleccione un item',
                                            tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre}</p><p>Código: {codigo}</p><p>Precio.: {precio_ref}</p></div></tpl>',
                                            hiddenName : 'id_item',
                                            forceSelection : true,
                                            typeAhead : false,
                                            triggerAction : 'all',
                                            lazyRender : true,
                                            mode : 'remote',
                                            pageSize : 10,
                                            queryDelay : 1000,                
                                            minChars : 2,                                        
                                            resizable: true,
                                            disabled:true
                                         }),
                    
                    
                   'id_formula': new Ext.form.TrigguerCombo({
                                            name : 'id_formula',
                                            fieldLabel : 'Formula',
                                            allowBlank : false,
                                            emptyText : 'Elija una formula...',
                                            store : new Ext.data.JsonStore({
                                                url : '../../sis_ventas_facturacion/control/Formula/listarFormula',
                                                id : 'id_formula',
                                                root : 'datos',
                                                sortInfo : {
                                                    field : 'nombre',
                                                    direction : 'ASC'
                                                },
                                                totalProperty : 'total',
                                                fields : ['id_formula', 'nombre', 'descripcion', 'precio'],
                                                remoteSort : true,
                                                baseParams : {
                                                    par_filtro : 'form.nombre#form.descripcion'
                                                }
                                            }),
                                            valueField : 'id_formula',
                                            displayField : 'nombre',
                                            gdisplayField : 'nombre_formula',
                                            tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre}</p><p>Descripción: {descripcion}</p><p>Precio.: {precio}</p></div></tpl>',
                                            hiddenName : 'id_formula',
                                            forceSelection : true,
                                            typeAhead : false,
                                            triggerAction : 'all',
                                            lazyRender : true,
                                            mode : 'remote',
                                            pageSize : 10,
                                            queryDelay : 1000,
                                            anchor : '100%',
                                            gwidth : 200,
                                            minChars : 2,
                                            turl : '../../../sis_ventas_facturacion/vista/formula/Formula.php',  
                                            tasignacion : true,           
                                            tname : 'id_formula',
                                            ttitle : 'Formula',
                                            tdata : {},
                                            tcls : 'Formula',
                                            pid : this.idContenedor,                                            
                                            resizable: true,
                                            disabled:true
                                         }),
                    'id_sucursal_producto': new Ext.form.ComboBox({
                                            name: 'id_sucursal_producto',
                                            fieldLabel: 'Servicio',
                                            allowBlank: false,
                                            emptyText: 'Servicios...',
                                            store: new Ext.data.JsonStore({
                                                url: '../../sis_ventas_facturacion/control/SucursalProducto/listarSucursalProducto',
                                                id: 'id_sucursal_producto',
                                                root: 'datos',
                                                sortInfo: {
                                                    field: 'nombre',
                                                    direction: 'ASC'
                                                },
                                                totalProperty: 'total',
                                                fields: ['id_sucursal_producto', 'nombre_producto', 'precio'],
                                                remoteSort: true,
                                                baseParams: {par_filtro: 'sprod.nombre_producto'}
                                            }),
                                            valueField: 'id_sucursal_producto',
                                            displayField: 'nombre_producto',
                                            gdisplayField: 'nombre_producto',
                                            hiddenName: 'id_sucursal_producto',
                                            forceSelection: true,
                                            tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre_producto}</p><p>Precio.: {precio}</p></div></tpl>',
                                            typeAhead: false,
                                            triggerAction: 'all',
                                            lazyRender: true,
                                            mode: 'remote',
                                            pageSize: 15,
                                            queryDelay: 1000,
                                            anchor: '100%',
                                            gwidth: 150,
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
                                }),
                    
                    'sw_porcentaje_formula': new Ext.form.ComboBox({
                            name: 'sw_porcentaje_formula',
                            fieldLabel: 'Comisión',
                            allowBlank:false,
                            emptyText:'Aplicar Comisión...',                
                            triggerAction: 'all',
                            lazyRender:true,
                            mode: 'local',
                            gwidth: 150,
                            store:['no','si'],
                            disabled :true
                    }),
                    
              }
            
            
    }, 
    
    cambiarCombo : function (tipo) {
        if (tipo == 'formula') {
            this.detCmp.id_formula.setDisabled(false);            
            this.detCmp.id_formula.allowBlank = false;
            
            this.detCmp.id_sucursal_producto.setDisabled(true); 
            this.detCmp.id_sucursal_producto.allowBlank = true;
            this.detCmp.id_sucursal_producto.reset();
            
            this.detCmp.id_item.setDisabled(true); 
            this.detCmp.id_item.allowBlank = true;
            this.detCmp.id_item.reset();
            
            this.detCmp.sw_porcentaje_formula.setDisabled(false);
            this.detCmp.sw_porcentaje_formula.setValue('si');
        } else if (tipo == 'producto_terminado') {
            this.detCmp.id_formula.setDisabled(true);            
            this.detCmp.id_formula.allowBlank = true;
            this.detCmp.id_formula.reset();
            
            this.detCmp.id_sucursal_producto.setDisabled(true); 
            this.detCmp.id_sucursal_producto.allowBlank = true;
            this.detCmp.id_sucursal_producto.reset();
            
            this.detCmp.id_item.setDisabled(false); 
            this.detCmp.id_item.allowBlank = false;            
            
            this.detCmp.sw_porcentaje_formula.setDisabled(true);
            this.detCmp.sw_porcentaje_formula.setValue('no');
        } else {
            this.detCmp.id_formula.setDisabled(true);            
            this.detCmp.id_formula.allowBlank = true;
            this.detCmp.id_formula.reset();
            
            this.detCmp.id_sucursal_producto.setDisabled(false); 
            this.detCmp.id_sucursal_producto.allowBlank = false;            
            
            this.detCmp.id_item.setDisabled(true); 
            this.detCmp.id_item.allowBlank = true;  
            this.detCmp.id_sucursal_producto.reset();          
            
            this.detCmp.sw_porcentaje_formula.setDisabled(true);
            this.detCmp.sw_porcentaje_formula.setValue('no');
        }
    },
    
    onCancelAdd: function(re,save){
        if(this.sw_init_add){
            this.mestore.remove(this.mestore.getAt(0));
        }
        
        this.sw_init_add = false;
        //this.evaluaGrilla();
    },
    onUpdateRegister: function(){
        this.sw_init_add = false;
    },
    
    onAfterEdit:function(re, o, rec, num){
        //set descriptins values ...  in combos boxs
        
        var cmb_rec = this.detCmp['id_item'].store.getById(rec.get('id_item'));
        if(cmb_rec) {
            
            rec.set('nombre_item', cmb_rec.get('nombre')); 
        }
        
        cmb_rec = this.detCmp['id_formula'].store.getById(rec.get('id_formula'));
        if(cmb_rec) {
            
            rec.set('nombre_formula', cmb_rec.get('nombre')); 
        }
        
        cmb_rec = this.detCmp['id_sucursal_producto'].store.getById(rec.get('id_sucursal_producto'));
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
                        name: 'id_item',
                        type: 'int'
                    }, {
                        name: 'id_sucursal_producto',
                        type: 'int'
                    }, {
                        name: 'id_formula',
                        type: 'int'
                    }, {
                        name: 'tipo',
                        type: 'string'
                    }, {
                        name: 'sw_porcentaje_formula',
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
                        {name:'id_item', type: 'numeric'},
                        {name:'nombre_item', type: 'string'},
                        {name:'nombre_formula', type: 'string'},
                        {name:'nombre_producto', type: 'string'},
                        {name:'id_sucursal_producto', type: 'numeric'},
                        {name:'id_formula', type: 'numeric'},
                        {name:'tipo', type: 'string'},
                        {name:'estado_reg', type: 'string'},
                        {name:'cantidad', type: 'numeric'},
                        {name:'precio', type: 'numeric'},
                        {name:'precio_total', type: 'numeric'},
                        {name:'sw_porcentaje_formula', type: 'string'},
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
                    baseParams: {dir:'ASC',sort:'id_formula_detalle',limit:'50',start:'0'}
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
                                 var e = new Items({
                                    id_item: undefined,
                                    cantidad: 1,
                                    precio_unitario:0,
                                    precio_total:0});
                                
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
                            //this.evaluaGrilla();
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
                        header: 'Fórmula',
                        dataIndex: 'id_formula',
                        width: 200,
                        sortable: false,
                        renderer:function(value, p, record){return String.format('{0}', record.data['nombre_formula']);},
                        editor: this.detCmp.id_formula 
                    },
                    
                      
                    {
                        header: 'Item',
                        dataIndex: 'id_item',
                        width: 200,
                        sortable: false,
                        renderer:function(value, p, record){return String.format('{0}', record.data['nombre_item']);},
                        editor: this.detCmp.id_item 
                    }, 
                    {
                        header: 'Servicio',
                        dataIndex: 'id_sucursal_producto',
                        width: 170,
                        sortable: false,
                        renderer:function(value, p, record){return String.format('{0}', record.data['nombre_producto']);},
                        editor: this.detCmp.id_sucursal_producto 
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
                    },
                    {
                        header: 'Comisión',
                        dataIndex: 'sw_porcentaje_formula',
                        width: 75,
                        sortable: false,                        
                        editor: this.detCmp.sw_porcentaje_formula 
                    }]
                });
    },
    onInitAdd : function (r, i) {
        this.detCmp.id_formula.setDisabled(true);            
        this.detCmp.id_sucursal_producto.setDisabled(true); 
        this.detCmp.id_item.setDisabled(true); 
        this.detCmp.sw_porcentaje_formula.setDisabled(true);
        
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
                                 
                              ]
                          },
                            this.megrid
                         ]
                 }];
        
        
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
             Phx.vista.FormVenta.superclass.onSubmit.call(this,o);
        }
        else{
            alert('no tiene ningun elemento en la formula')
        }
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
                    fields : ['id_cliente', 'nombres', 'primer_apellido', 'segundo_apellido'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'cli.nombres#cli.primer_apellido#cli.segundo_apellido'
                    }
                }),
                valueField : 'id_cliente',
                displayField : 'primer_apellido',                
                hiddenName : 'id_cliente',
                forceSelection : true,
                typeAhead : false,
                tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombres} {primer_apellido} {segundo_apellido}</p> </div></tpl>',
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
                name: 'a_cuenta',
                fieldLabel: 'A cuenta',
                allowBlank: false,
                anchor: '80%',                
                maxLength:5
            },
                type:'NumberField',                
                id_grupo:0,                
                form:true
        },
        
        {
            config:{
                name: 'fecha_estimada_entrega',
                fieldLabel: 'Fecha de Entrega Estimada',
                allowBlank: false,              
                gwidth: 150,
                            format: 'd/m/Y', 
                            renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
                type:'DateField',
                filters:{pfiltro:'ven.fecha_estimada_entrega',type:'date'},
                id_grupo:0,
                grid:true,
                form:true
        },
        {
            config: {
                name: 'id_sucursal',
                fieldLabel: 'Sucursal',
                allowBlank: false,
                emptyText: 'Elija una Suc...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_ventas_facturacion/control/Sucursal/listarSucursal',
                    id: 'is_sucursal',
                    root: 'datos',
                    sortInfo: {
                        field: 'nombre',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_sucursal', 'nombre', 'codigo'],
                    remoteSort: true,
                    baseParams: {par_filtro: 'suc.nombre#suc.codigo'}
                }),
                valueField: 'id_sucursal',
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
          
        
    ],
    title: 'Formulario Venta'
})    
</script>