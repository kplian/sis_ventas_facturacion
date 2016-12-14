<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (jrivera)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormVentaFarmacia = {
    require:'../../../sis_ventas_facturacion/vista/venta/FormVenta.php',
	requireclase:'Phx.vista.FormVenta',
	title:'Venta',
	nombreVista: 'FormVentaFarmacia',
	cantidadAllowDecimals: true,	
	constructor: function(config) {	
		
		this.addElements();
		this.tipoDetalleArray = [['servicio','Servicio'],['producto_terminado','Producto']];  
        Phx.vista.FormVentaFarmacia.superclass.constructor.call(this,config); 
        
        this.Cmp.nit.allowBlank = true; 
        if (this.data.tipo_form == 'edit') {
            if (this.data.datos_originales.data.estado == 'pendiente_entrega') {
                this.ocultarComponente(this.Cmp.a_cuenta);
                this.mostrarGrupo(2);
            } else {
                this.mostrarComponente(this.Cmp.a_cuenta);
                this.ocultarGrupo(2);
            }
        } else {
            this.mostrarComponente(this.Cmp.a_cuenta);
            this.ocultarGrupo(2);
        }
  },
  
  addElements : function () {
    
  	this.Atributos.push({
			config:{
				name: 'a_cuenta',
				fieldLabel: 'A cuenta',
				allowBlank: false,
				anchor: '80%',
				maxLength:5
			},
				type:'NumberField',
                valorInicial:'0',			
				id_grupo:0,				
				form:true
		});
		
	this.Atributos.push({
			config:{
				name: 'fecha_estimada_entrega',
				fieldLabel: 'Fecha de Entrega Estimada',
				allowBlank: true,				
				format: 'd/m/Y'
							
			},
				type:'DateField',				
				id_grupo:0,				
				form:true
		});
	
	this.Atributos.push({
			config:{
				name: 'hora_estimada_entrega',
				fieldLabel: 'Hora de Entrega Estimada',
				allowBlank: true,				
				format: 'H:i',
				minValue: '8:00 AM',
    			maxValue: '8:00 PM',
    			increment:30
							
			},
				type:'TimeField',				
				id_grupo:0,				
				form:true
		});
		
	this.Atributos.push({
            config: {
                name: 'id_vendedor_medico',
                fieldLabel: 'Vendedor/Medico',
                allowBlank: false,
                emptyText: 'Seleccione...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_ventas_facturacion/control/Medico/listarVendedorMedico',
                    id: 'id_vendedor_medico',
                    root: 'datos',
                    sortInfo: {
                        field: 'nombre',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_vendedor_medico', 'nombre_vendedor_medico', 'tipo'],
                    remoteSort: true,
                    baseParams: {par_filtro: 'todo.nombre'}
                }),
                valueField: 'id_vendedor_medico',
                displayField: 'nombre_vendedor_medico',
                gdisplayField: 'id_vendedor_medico',
                hiddenName: 'id_vendedor_medico',
                tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Tipo:</b> {tipo}</p><p><b>Nombre:</b> {nombre_vendedor_medico}</p></div></tpl>',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 15,
                queryDelay: 1000,               
                gwidth: 150,
                minChars: 2
            },
            type: 'ComboBox',
            id_grupo: 1,            
            form: true
        });
        
        this.Atributos.push({
            config:{
                name: 'porcentaje_descuento',
                fieldLabel: 'Porcentaje Descuento',
                allowBlank: false,
                anchor: '80%',
                maxLength:3,
                maxValue:100,
                minValue:0,
                allowDecimals:false
            },
                type:'NumberField',                          
                id_grupo:1,             
                form:true
        });
        
        this.Atributos.push({
			config:{
                name: 'forma_pedido',
                fieldLabel: 'Forma Pedido',
                allowBlank: false,
                anchor: '60%', 
                typeAhead: false,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
               // displayField: 'descestilo',
                store:['personal','telefono','whatsapp']
            },
            type:'ComboBox',            
            id_grupo:0,
            valorInicial : 'personal',            
            form:true
		});
  },
  onNew: function(){      
      this.accionFormulario = 'NEW'; 
      this.Cmp.porcentaje_descuento.setValue(0);
      this.Cmp.forma_pedido.setValue('personal');
  },
  
  buildComponentesDetalle: function(){
  	
      Phx.vista.FormVentaFarmacia.superclass.buildComponentesDetalle.call(this); 
      this.detCmp.id_producto = new Ext.form.TrigguerCombo({
        name: 'id_sucursal_producto',
        fieldLabel: 'Producto/Servicio',
        allowBlank: false,
        tinit:true,
	    tasignacion:false,
	    tname:'id_producto',
        tdisplayField:'nombre',   				
		turl:'../../../sis_ventas_facturacion/vista/sucursal_producto/SucursalProducto.php',
		ttitle:'Sucursal Producto',
		tconfig:{width:'80%',height:'90%'},
		tdata:{},
		tcls:'SucursalProducto',
		pid:this.idContenedor,
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
            fields: ['id_producto', 'tipo','nombre_producto','descripcion','medico','requiere_descripcion','precio','codigo_unidad_medida'],
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
        '<p><b>Nombre:</b> {nombre_producto}</p><p><b>Descripcion:</b> {descripcion} {codigo_unidad_medida}</p><p><b>Precio:</b> {precio}</p></div></tpl>'),
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
     });

      this.detCmp.unidad_medida = new Ext.form.TextField({
          name: 'unidad_medida',
          msgTarget: 'title',
          fieldLabel: 'Unidad',
          allowBlank: true,
          readOnly :true
      });
     
      this.detCmp.porcentaje_descuento = new Ext.form.NumberField({
            name: 'porcentaje_descuento',
            fieldLabel: 'Porcentaje Descuento',
            allowBlank: false,
            anchor: '80%',
            maxLength:3,
            maxValue:100,
            minValue:0,
                enableKeyEvents : true,
                allowDecimals : false
        });
        
                
        this.detCmp.id_vendedor_medico = new Ext.form.ComboBox({
            name: 'id_vendedor_medico',
            fieldLabel: 'Vendedor/Medico',
            allowBlank: false,
            emptyText: 'Seleccione...',
            store: new Ext.data.JsonStore({
                url: '../../sis_ventas_facturacion/control/Medico/listarVendedorMedico',
                id: 'id_vendedor_medico',
                root: 'datos',
                sortInfo: {
                    field: 'nombre',
                    direction: 'ASC'
                },
                totalProperty: 'total',
                fields: ['id_vendedor_medico', 'nombre_vendedor_medico', 'tipo'],
                remoteSort: true,
                baseParams: {par_filtro: 'todo.nombre'}
            }),
            valueField: 'id_vendedor_medico',
            displayField: 'nombre_vendedor_medico',
            gdisplayField: 'id_vendedor_medico',
            hiddenName: 'id_vendedor_medico',
            tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Tipo:</b> {tipo}</p><p><b>Nombre:</b> {nombre_vendedor_medico}</p></div></tpl>',
            forceSelection: true,
            typeAhead: false,
            triggerAction: 'all',
            lazyRender: true,
            mode: 'remote',
            pageSize: 15,
            queryDelay: 1000,               
            gwidth: 150,
            minChars: 2
        });
        
        this.detCmp.precio_total_sin_descuento = new Ext.form.NumberField({
                name: 'precio_total_sin_descuento',
                msgTarget: 'title',
                fieldLabel: 'Total',
                allowBlank: false,
                allowDecimals: false,
                maxLength:10,
                readOnly :true
        });

      this.detCmp.precio_total_sin_descuento = new Ext.form.NumberField({
          name: 'precio_total_sin_descuento',
          msgTarget: 'title',
          fieldLabel: 'Total',
          allowBlank: false,
          allowDecimals: false,
          maxLength:10,
          readOnly :true
      });
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
                    },{
                        name: 'porcentaje_descuento',
                        type: 'int'
                    },{
                        name: 'id_vendedor_medico',
                        type: 'int'
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
                         {name:'descripcion', type: 'string'},
                        {name:'precio_unitario', type: 'numeric'},
                        {name:'precio_sin_descuento', type: 'numeric'}, 
                        {name:'precio_total_sin_descuento', type: 'numeric'}, 
                        {name:'porcentaje_descuento', type: 'numeric'},
                        {name:'precio_total', type: 'numeric'}, 
                        {name:'id_vendedor_medico', type: 'string'},
                        {name:'nombre_vendedor_medico', type: 'string'},                             
                        {name:'id_usuario_ai', type: 'numeric'},
                        {name:'usuario_ai', type: 'string'},
                        {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
                        {name:'id_usuario_reg', type: 'numeric'},
                        {name:'id_usuario_mod', type: 'numeric'},
                        {name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
                        {name:'usr_reg', type: 'string'},
                        {name:'usr_mod', type: 'string'},
                        {name:'codigo_unidad_cig', type: 'string'},
                        
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
                        
                        text: '<i class="fa fa-plus-circle fa-lg"></i> Agregar Detalle',
                        scope: this,
                        width: '100',
                        handler: function() { 
                            
                            if(this.evaluaRequistos() === true){                               
                                var recTem = new Array();
						        recTem['id_vendedor_medico'] = this.Cmp.id_vendedor_medico.getValue();
						        recTem['nombre_vendedor_medico'] = this.Cmp.id_vendedor_medico.getRawValue();
						        
						        this.detCmp.id_vendedor_medico.store.add(new Ext.data.Record(this.arrayToObject(this.detCmp.id_vendedor_medico.store.fields.keys,recTem), this.Cmp.id_vendedor_medico.getValue()));
						        this.detCmp.id_vendedor_medico.store.commitChanges();
						        this.detCmp.id_vendedor_medico.modificado = true;
                                 var e = new Items({
                                    id_producto: undefined,                                    
                                    cantidad: 1,
                                    precio_unitario:0,
                                    precio_total_sin_descuento:0,
                                    precio_total:0,
                                    porcentaje_descuento : this.Cmp.porcentaje_descuento.getValue()});
                                
                                this.editorDetail.stopEditing();
                                this.mestore.insert(0, e);
                                
                                this.megrid.getView().refresh();
                                this.megrid.getSelectionModel().selectRow(0);
                                this.editorDetail.startEditing(0);
                                this.sw_init_add = true;
                                
                                this.detCmp.id_vendedor_medico.setValue(this.Cmp.id_vendedor_medico.getValue());
                                
                                if (this.detCmp.tipo.store.getTotalCount() == 1) {                                                              
                                    this.detCmp.tipo.setValue(this.detCmp.tipo.store.getAt(0).data.field1);
                                    this.detCmp.tipo.fireEvent('select',this.detCmp.tipo,this.detCmp.tipo.store.getAt(0),0)
                                    
                                }
                                this.bloqueaRequisitos(true);
                            } else {
                                
                            }
                                                       
                        }
                    },{
                        
                        text: '<i class="fa fa-plus-circle fa-lg"></i> Agregar Formula/Paquete',
                        scope:this,
                        handler: function(){
                        	if(this.evaluaRequistos() === true) { 
                        		this.editorDetail.stopEditing(); 
                            	this.armarFormularioFormula();
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
                        width: 280,
                        sortable: false,
                        renderer:function(value, p, record){return String.format('{0}', record.data['nombre_producto']);},
                        editor: this.detCmp.id_producto 
                    },
                        {
                            header: 'Unidad',
                            dataIndex: 'codigo_unidad_cig',
                            width: 110,
                            sortable: false,
                            editor: this.detCmp.unidad_medida
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
                        dataIndex: 'precio_total_sin_descuento',
                        align: 'right',
                        width: 85,
                        summaryType: 'sum',
                        format: '$0,0.00',
                        editor: this.detCmp.precio_total_sin_descuento  
                    },
                    {
                       
                        header: '% Descuento',
                        dataIndex: 'porcentaje_descuento',
                        align: 'right',
                        width: 85,
                        editor: this.detCmp.porcentaje_descuento 
                    },
                    {
                        xtype: 'numbercolumn',
                        header: 'Total Liquido',
                        dataIndex: 'precio_total',
                        align: 'right',
                        width: 85,
                        summaryType: 'sum',
                        format: '$0,0.00',
                        editor: this.detCmp.precio_total 
                    },
                    {
                        header: 'Vendedor/Medico',
                        dataIndex: 'id_vendedor_medico',
                        width: 150,
                        sortable: false,
                        renderer:function(value, p, record){return String.format('{0}', record.data['nombre_vendedor_medico']);},
                        editor: this.detCmp.id_vendedor_medico
                    }, 
                    ]
                });
    },
    onAfterEdit:function(re, o, rec, num){
        //set descriptins values ...  in combos boxs       
        Phx.vista.FormVentaFarmacia.superclass.onAfterEdit.call(this,re,o,rec,num);
        rec.set('nombre_vendedor_medico', this.detCmp.id_vendedor_medico.getRawValue());
                
    },    
    iniciarEventos : function () {
        this.detCmp.id_producto.tdata.formulario = 'venta';
        this.detCmp.id_producto.tdata.maestro = {	id_sucursal : this.data.objPadre.variables_globales.id_sucursal,
        									id_entidad : this.data.objPadre.variables_globales.id_entidad};
        this.Cmp.id_vendedor_medico.store.baseParams.id_sucursal = this.data.objPadre.variables_globales.id_sucursal;
        this.detCmp.id_vendedor_medico.store.baseParams.id_sucursal = this.data.objPadre.variables_globales.id_sucursal;
        
       this.detCmp.porcentaje_descuento.on('keyup',function() {  
            
            this.detCmp.precio_total_sin_descuento.setValue(this.roundTwo(Number(this.detCmp.precio_unitario.getValue()) * Number(this.detCmp.cantidad.getValue())));
            this.detCmp.precio_total.setValue(this.round(Number(this.detCmp.precio_total_sin_descuento.getValue()) - (Number(this.detCmp.precio_total_sin_descuento.getValue()) * Number(this.detCmp.porcentaje_descuento.getValue()) / Number(100))) );
        },this);
        Phx.vista.FormVentaFarmacia.superclass.iniciarEventos.call(this);
        this.detCmp.cantidad.on('keyup',function() {  
            this.detCmp.precio_total_sin_descuento.setValue(this.roundTwo(Number(this.detCmp.precio_unitario.getValue()) * Number(this.detCmp.cantidad.getValue())));
            this.detCmp.precio_total.setValue(this.round(Number(this.detCmp.precio_total_sin_descuento.getValue()) - (Number(this.detCmp.precio_total_sin_descuento.getValue()) * Number(this.detCmp.porcentaje_descuento.getValue()) / Number(100))));
        },this);
        
        
        
        this.detCmp.precio_unitario.on('keyup',function() {  
            this.detCmp.precio_total_sin_descuento.setValue(this.roundTwo(Number(this.detCmp.precio_unitario.getValue()) * Number(this.detCmp.cantidad.getValue())));
            this.detCmp.precio_total.setValue(this.round(Number(this.detCmp.precio_total_sin_descuento.getValue()) - (Number(this.detCmp.precio_total_sin_descuento.getValue()) * Number(this.detCmp.porcentaje_descuento.getValue()) / Number(100))));
        },this);
        
        this.detCmp.id_producto.on('select',function(c,r,i) {
            this.detCmp.unidad_medida.setValue(r.data.codigo_unidad_medida);
            this.detCmp.precio_unitario.setValue(Number(r.data.precio));
            this.detCmp.precio_total_sin_descuento.setValue(this.roundTwo(Number(this.detCmp.precio_unitario.getValue()) * Number(this.detCmp.cantidad.getValue())));
            this.detCmp.precio_total.setValue(this.round(Number(this.detCmp.precio_total_sin_descuento.getValue()) - (Number(this.detCmp.precio_total_sin_descuento.getValue()) * Number(this.detCmp.porcentaje_descuento.getValue()) / Number(100))));
        },this);
        
        if (this.data.tipo_form == 'edit') {
            
            this.Cmp.id_vendedor_medico.store.load({params:{start:0,limit:this.tam_pag}, 
                   callback : function (r) {             
                                 
                        this.Cmp.id_vendedor_medico.setValue(this.data.datos_originales.data.id_vendedor_medico);             
                                        
                    }, scope : this
                });
        }
    },
    evaluaRequistos: function(){
        
        //valida que todos los requistosprevios esten completos y habilita la adicion en el grid
        //solo validar que tenga sucursal o punto de venta
        if (this.Cmp.id_vendedor_medico.getValue() == '' || this.Cmp.id_vendedor_medico.getValue() == undefined) {
            this.Cmp.id_vendedor_medico.validate();
            return false;
        }
        if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') {
              if (this.Cmp.id_punto_venta.getValue() != '' && this.Cmp.id_punto_venta.getValue() != undefined) {
                  return true;
              } else {
                  return false;
              }
        } else {
              if (this.Cmp.id_sucursal.getValue() != '' && this.Cmp.id_sucursal.getValue() != undefined) {
                  
                  return true;
              } else {
                  
                  return false;
              }
        }    
    },
    bloqueaRequisitos: function(sw){
        Phx.vista.FormVentaFarmacia.superclass.bloqueaRequisitos.call(this,sw);      
        this.Cmp.id_vendedor_medico.setDisabled(sw);  
        this.Cmp.porcentaje_descuento.setDisabled(sw);        
    
    }, 
    onInitAdd : function (r, i) {  
    	      
        var record = this.megrid.store.getAt(i);
        var recTem = new Array();
        recTem['id_vendedor_medico'] = record.data['id_vendedor_medico'];
        recTem['nombre_vendedor_medico'] = record.data['nombre_vendedor_medico'];
        
        this.detCmp.id_vendedor_medico.store.add(new Ext.data.Record(this.arrayToObject(this.detCmp.id_vendedor_medico.store.fields.keys,recTem), record.data['id_vendedor_medico']));
        this.detCmp.id_vendedor_medico.store.commitChanges();
        this.detCmp.id_vendedor_medico.modificado = true;
        Phx.vista.FormVentaFarmacia.superclass.onInitAdd.call(this,r, i);      
        
    },
    armarFormularioFormula : function () {
    	var comboVendedor = new Ext.form.ComboBox( {
                name: 'id_vendedor_medico',
                fieldLabel: 'Vendedor/Medico',
                allowBlank: false,
                emptyText: 'Seleccione...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_ventas_facturacion/control/Medico/listarVendedorMedico',
                    id: 'id_vendedor_medico',
                    root: 'datos',
                    sortInfo: {
                        field: 'nombre',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_vendedor_medico', 'nombre_vendedor_medico', 'tipo'],
                    remoteSort: true,
                    baseParams: {par_filtro: 'todo.nombre'}
                }),
                valueField: 'id_vendedor_medico',
                displayField: 'nombre_vendedor_medico',
                gdisplayField: 'id_vendedor_medico',
                hiddenName: 'id_vendedor_medico',
                tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Tipo:</b> {tipo}</p><p><b>Nombre:</b> {nombre_vendedor_medico}</p></div></tpl>',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 15,
                queryDelay: 1000,               
                gwidth: 150,
                minChars: 2
            });
        var recTem = new Array();
        recTem['id_vendedor_medico'] = this.Cmp.id_vendedor_medico.getValue();
        recTem['nombre_vendedor_medico'] = this.Cmp.id_vendedor_medico.getRawValue();
        
        comboVendedor.store.add(new Ext.data.Record(this.arrayToObject(comboVendedor.store.fields.keys,recTem), this.Cmp.id_vendedor_medico.getValue()));
        comboVendedor.store.commitChanges(this.Cmp.id_vendedor_medico.getValue());
        
        comboVendedor.setValue(this.Cmp.id_vendedor_medico.getValue());
        
        
        var porcentajeDescuento = new Ext.form.NumberField({
                name: 'porcentaje_descuento',
                fieldLabel: 'Porcentaje Descuento',
                allowBlank: false,
                anchor: '80%',
                maxLength:3,
                maxValue:100,
                minValue:0,
                allowDecimals:false
        });
        porcentajeDescuento.setValue(this.Cmp.porcentaje_descuento.getValue());
    	var comboFormula = new Ext.form.TrigguerCombo(
						    {
						        typeAhead: false,
						        fieldLabel: 'Paquete / Formula',
						        allowBlank : false,	
						        tinit:true,
							    turl:'../../../sis_ventas_facturacion/vista/formula/Formula.php',
								ttitle:'Formula',
								tconfig:{width:'80%',height:'90%'},
								tdata:{},
								tcls:'Formula',						        
						        store: new Ext.data.JsonStore({
                                                url: '../../sis_ventas_facturacion/control/SucursalProducto/listarProductoServicioItem',
                                                id: 'id_producto',
                                                root: 'datos',
                                                sortInfo: {
                                                    field: 'nombre',
                                                    direction: 'ASC'
                                                },
                                                totalProperty: 'total',
                                                fields: ['id_producto', 'tipo','nombre_producto','descripcion','medico','requiere_descripcion','precio'],
                                                remoteSort: true,
                                                baseParams: {par_filtro: 'todo.nombre'}
                                            }),
                                valueField: 'id_producto',
                                displayField: 'nombre_producto',
                                gdisplayField: 'nombre_producto',
                                hiddenName: 'id_producto',
						        mode: 'remote',
                				pageSize: 15,
						        triggerAction: 'all',						         
						        forceSelection: true,
						        tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Nombre:</b> {nombre_producto}</p><p><b>Descripcion:</b> {descripcion}</p></div></tpl>',
						        allowBlank : false,
						        anchor: '100%'
						    });
		comboFormula.store.baseParams.tipo = 'formula';
		comboFormula.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
    	var formularioFormula = new Ext.form.FormPanel({				            
	            items: [comboVendedor,porcentajeDescuento,comboFormula],				            
	            padding: true,
	            bodyStyle:'padding:5px 5px 0',
	            border: false,
	            frame: false				            
	        });
	     
	     var params = {
                		'id_sucursal' : this.Cmp.id_sucursal.getValue()
                		               		
	                };
	     
	     if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') { 
	     	params.id_punto_venta = this.Cmp.id_punto_venta.getValue(); 
	     	comboFormula.store.baseParams.id_punto_venta = this.Cmp.id_punto_venta.getValue();
	     }
	     
						 
		 var VentanaFormula = new Ext.Window({
	            title: 'Agregar paquete/formula',
	            modal: true,
	            width: 350,
	            height: 200,
	            bodyStyle: 'padding:5px;',
	            layout: 'fit',
	            hidden: true,					            
	            buttons: [
	                {
		                text: '<i class="fa fa-check"></i> Aceptar',
		                handler: function () {
		                	if (formularioFormula.getForm().isValid()) {
		                		validado = true;	
		                		var nombre_formula = comboFormula.getRawValue(); 
		                		params.id_vendedor_medico = comboVendedor.getValue();
		                		params.id_formula = comboFormula.getValue();
                				params.porcentaje_descuento = porcentajeDescuento.getValue();              		
		                		
		                		Ext.Ajax.request({
					                url:'../../sis_ventas_facturacion/control/FormulaDetalle/listarFormulaDetalleParaInsercion',                
					                params: params,
					                success:this.successCargarFormula,
					                failure: this.conexionFailure,					                
					                timeout:this.timeout,
					                arguments : {'nombre_formula' : nombre_formula},
					                scope:this
					            });	  
					            VentanaFormula.close(); 
		                		 		
		                		
		                	}
		                },
						scope: this
	               }],
	            items: formularioFormula,
	            autoDestroy: true,
	            closeAction: 'close'
	        });
	      VentanaFormula.show();
    },
    
    successCargarFormula : function (response,request) {
    	var respuesta = JSON.parse(response.responseText);
    	var grillaRecord =  Ext.data.Record.create([
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
	        {name:'precio_total_sin_descuento', type: 'numeric'},
	        {name:'porcentaje_descuento', type: 'numeric'},
	        {name:'id_vendedor_medico', type: 'string'},
	        {name:'nombre_vendedor_medico', type: 'string'},
	        {name:'precio_total', type: 'numeric'},                        
	        {name:'id_usuario_ai', type: 'numeric'},
	        {name:'usuario_ai', type: 'string'},
	        {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
	        {name:'id_usuario_reg', type: 'numeric'},
	        {name:'id_usuario_mod', type: 'numeric'},
	        {name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
	        {name:'usr_reg', type: 'string'},
	        {name:'usr_mod', type: 'string'}
		]);
		
    	for (var i = 0; i < respuesta.datos.length; i++) {
    		var myNewRecord = new grillaRecord({
    			nombre_producto : respuesta.datos[i].nombre_producto,
    			descripcion : request.arguments.nombre_formula, 
    			id_producto : respuesta.datos[i].id_producto,
    			tipo : respuesta.datos[i].tipo,
    			cantidad : respuesta.datos[i].cantidad,
    			precio_unitario : respuesta.datos[i].precio_unitario,
    			precio_total: this.round(Number(respuesta.datos[i].precio_total)) ,
    			precio_total_sin_descuento : respuesta.datos[i].precio_total_sin_descuento,
    			porcentaje_descuento : respuesta.datos[i].porcentaje_descuento, 
    			id_vendedor_medico : respuesta.datos[i].id_vendedor_medico, 
    			nombre_vendedor_medico : respuesta.datos[i].nombre_vendedor_medico 			
    			
    		});
    		this.mestore.add(myNewRecord);
    	}
    	this.mestore.commitChanges();
    	
    },
    round: function(can) {    	
    	 return  Math.ceil(can);
    }  
	
};
</script>
