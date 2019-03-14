<?php
/**
*@package pXP
*@file    FormVentaNCETR.php
*@author  Jaime Rivera rojas 
*@date    30-01-2014
*@description permites subir archivos a la tabla de documento_sol
 *  *   HISTORIAL DE MODIFICACIONES:

 ISSUE            FECHA:		      AUTOR               DESCRIPCION
 #0              08-10-2018           RAC                 Creacion 
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormVentaNCETR=Ext.extend(Phx.frmInterfaz,{
    ActSave:'../../sis_ventas_facturacion/control/Venta/insertarVentaCompleta',
    tam_pag: 10,    
    layout: 'fit',
    tabEnter: true,
    autoScroll: false,
    breset: false,
    labelSubmit: '<i class="fa fa-check"></i> Guardar',
    storeFormaPago : false,
    fwidth : '9%',
    cantidadAllowDecimals: false,
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
	                tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Codigo:</b> {codigo}</p><p><b>Nombre:</b> {nombre}</p></div></tpl>',
	                forceSelection: true,
	                typeAhead: false,
	                triggerAction: 'all',
	                lazyRender: true,
	                mode: 'remote',
	                pageSize: 15,
	                queryDelay: 1000,               
	                gwidth: 150,
	                minChars: 2,
	                disabled:true,
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
		if (this.data.objPadre.variables_globales.habilitar_comisiones == 'si') {  
			this.Atributos.push({
		            config:{
		                name: 'comision',
		                fieldLabel: 'Comisión',
		                allowBlank: true,
		                anchor: '80%',                
		                maxLength:20,
		                allowNegative:false
		            },
		                type:'NumberField',                
		                id_grupo:0,                
		                form:true,
		                valorInicial:0
		      });
		}
		
		if (this.data.objPadre.tipo_factura == 'computarizada' || this.data.objPadre.tipo_factura == 'manual' || this.data.objPadre.tipo_factura == ''){
			this.Atributos.push({
		            config:{
		                name: 'excento',
		                fieldLabel: 'Excento',
		                allowBlank: false,
		                anchor: '80%',                
		                maxLength:20,
		                value : 0
		            },
		                type:'NumberField',                
		                id_grupo:2,                
		                form:true,
		                valorInicial:'0'
		      });
		      
		}
		
		if (this.data.objPadre.tipo_factura == 'manual' || this.data.objPadre.tipo_factura == 'computarizadaexpo'|| this.data.objPadre.tipo_factura == 'computarizadamin'|| this.data.objPadre.tipo_factura == 'computarizadaexpomin'|| this.data.objPadre.tipo_factura == 'pedido') {
			this.Atributos.push({
				config:{
					name: 'fecha',
					fieldLabel: 'Fecha Factura',
					allowBlank: false,
					anchor: '80%',					
					format: 'd/m/Y'
								
				},
					type:'DateField',					
					id_grupo:0,					
					form:true
			});
	  }		
	 if (this.data.objPadre.tipo_factura == 'manual') {	
			this.Atributos.push({
	            config: {
	                name: 'id_dosificacion',
	                fieldLabel: 'Dosificacion',
	                allowBlank: false,
	                emptyText: 'Elija un Dosi...',
	                store: new Ext.data.JsonStore({
	                    url: '../../sis_ventas_facturacion/control/Dosificacion/listarDosificacion',
	                    id: 'id_dosificacion',
	                    root: 'datos',
	                    sortInfo: {
	                        field: 'nroaut',
	                        direction: 'ASC'
	                    },
	                    totalProperty: 'total',
	                    fields: ['id_dosificacion', 'nroaut', 'desc_actividad_economica','inicial','final'],
	                    remoteSort: true,
	                    baseParams: {filtro_usuario: 'si',par_filtro: 'dos.nroaut'}
	                }),
	                valueField: 'id_dosificacion',
	                displayField: 'nroaut',	               
	                hiddenName: 'id_dosificacion',
	                tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Autorizacion:</b> {nroaut}</p><p><b>Actividad:</b> {desc_actividad_economica}</p></p><p><b>No Inicio:</b> {inicio}</p><p><b>No Final:</b> {final}</p></div></tpl>',
	                forceSelection: true,
	                typeAhead: false,
	                triggerAction: 'all',
	                lazyRender: true,
	                mode: 'remote',
	                pageSize: 15,
	                queryDelay: 1000,               
	                gwidth: 150,
	                minChars: 2,
	                disabled : true
	            },
	            type: 'ComboBox',
	            id_grupo: 0,	            
	            grid: false,
	            form: true
	        });
	        this.Atributos.push({
		            config:{
		                name: 'nro_factura',
		                fieldLabel: 'No Factura ',
		                allowBlank: false,
		                anchor: '80%',                
		                maxLength:20
		            },
		                type:'NumberField',                
		                id_grupo:0,                
		                form:true
		      });
		}
		if (!this.tipoDetalleArray) {			
		  this.tipoDetalleArray = this.data.objPadre.variables_globales.vef_tipo_venta_habilitado.split(",");
        }
        this.addEvents('beforesave');
        this.addEvents('successsave');
        
        this.buildComponentesDetalle();
        this.buildDetailGrid();
        this.buildGrupos();
        
        Phx.vista.FormVentaNCETR.superclass.constructor.call(this,config);
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
                                                fields: ['id_producto', 'tipo','nombre_producto','descripcion','medico','requiere_descripcion','precio','ruta_foto','codigo_unidad_medida'],
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
                                         }),
                    'descripcion': new Ext.form.TextField({
                            name: 'descripcion',
                            fieldLabel: 'Descripcion',                            
                            allowBlank:true,                            
                            gwidth: 150,
                            disabled : true
                    }),
                                      
                    'cantidad': new Ext.form.NumberField({
                                        name: 'cantidad',
                                        msgTarget: 'title',
                                        fieldLabel: 'Cantidad',
                                        allowBlank: false,
                                        allowDecimals: me.cantidadAllowDecimals,
                                        decimalPrecision : 6,
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
                                        maxLength:10,
                                        readOnly :true
                                })
                    
              }
            
            
    }, 
    
    iniciarEventosProducto:function(){
    	this.detCmp.id_producto.on('select',function(c,r,i) {
            this.detCmp.precio_unitario.setValue(Number(r.data.precio));            
            var tmp = this.roundTwo(Number(r.data.precio) * Number(this.detCmp.cantidad.getValue()))
            this.detCmp.precio_total.setValue(tmp);
        	
        	if (r.data.requiere_descripcion == 'si') {        		
        		this.habilitarDescripcion(true);
        	} else {
        		this.habilitarDescripcion(false);
        	}        	
        	// #123   cargar descripcion
        	this.detCmp.descripcion.setValue(r.data.descripcion);
        },this);
    	
    },
    
    
    iniciarEventos : function () {
    	
    	
        this.Cmp.id_sucursal.store.load({params:{start:0,limit:this.tam_pag}, 
           callback : function (r) {
           		
           		this.Cmp.id_sucursal.setValue(this.data.objPadre.variables_globales.id_sucursal);
           		if (this.data.objPadre.variables_globales.vef_tiene_punto_venta != 'true') {  
           			this.detCmp.id_producto.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
                }
                this.Cmp.id_sucursal.fireEvent('select',this.Cmp.id_sucursal, this.Cmp.id_sucursal.store.getById(this.data.objPadre.variables_globales.id_sucursal));	                   
                                
            }, scope : this
        });
	    
        if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') {
	        this.Cmp.id_punto_venta.store.load({params:{start:0,limit:this.tam_pag}, 
	           callback : function (r) {
	           		
	                this.Cmp.id_punto_venta.setValue(this.data.objPadre.variables_globales.id_punto_venta);
	           		this.detCmp.id_producto.store.baseParams.id_punto_venta = this.Cmp.id_punto_venta.getValue();
	                this.Cmp.id_punto_venta.fireEvent('select',this.Cmp.id_punto_venta, this.Cmp.id_punto_venta.store.getById(this.data.objPadre.variables_globales.id_punto_venta));   
	                                
	            }, scope : this
	        });
	    }
	    
	    if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') {  
    	    this.Cmp.id_punto_venta.on('select',function(c,r,i) {
    	    	if (this.accionFormulario != 'EDIT') {
                	this.Cmp.id_forma_pago.store.baseParams.defecto = 'si';
               }
                this.cargarFormaPago();
                
            },this);
        }
        
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
            if (this.data.objPadre.tipo_factura == 'manual') {   
            	this.Cmp.id_dosificacion.reset();         	
            	this.Cmp.id_dosificacion.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
            	this.Cmp.id_dosificacion.modificado = true;
            }
            this.cargarFormaPago();
            
        },this);
        
        
        if (this.data.objPadre.tipo_factura == 'manual') {
	        this.Cmp.fecha.on('blur',function(c) {
	        	
	            if (this.data.objPadre.tipo_factura == 'manual') {
	            	this.Cmp.id_dosificacion.reset();
	            	this.Cmp.id_dosificacion.modificado = true;
	            	this.Cmp.id_dosificacion.setDisabled(false);
	            	this.Cmp.id_dosificacion.store.baseParams.fecha = this.Cmp.fecha.getValue().format('d/m/Y');
	            	this.Cmp.id_dosificacion.store.baseParams.tipo = 'manual';
	            }
	            this.cargarFormaPago();
	            
	        },this);
	    }      
        
        
        this.detCmp.tipo.on('select',function(c,r,i) {
            this.cambiarCombo(r.data.field1);
        },this);  
        
          
        
        this.Cmp.id_proveedor.on('select',function(cmb,r,i) {
            if (r.data) {
                this.Cmp.nit.setValue(r.data.nit);
            } else {
                this.Cmp.nit.setValue(r.nit);
            } 
            
            this.Cmp.id_venta_fk.enable();
			this.Cmp.id_venta_fk.reset();
			this.Cmp.id_venta_fk.store.baseParams.filter = "[{\"type\":\"numeric\",\"comparison\":\"eq\", \"value\":\""+cmb.getValue()+"\",\"field\":\"VEN.id_proveedor\"}]";
			this.Cmp.id_venta_fk.modificado = true;
			//#123 reset grilla detalle
			this.resetGrillaDetalle()
			
                       
        },this); 
        
         this.Cmp.id_venta_fk.on('select',function(cmb,r,i) {
             this.detCmp.id_producto.store.baseParams.id_venta_fk = this.Cmp.id_venta_fk.getValue();
             //#123  reset grailla detalle  
             this.resetGrillaDetalle()         
        },this);     
        
       
        this.iniciarEventosProducto();
        
        this.detCmp.cantidad.on('keyup',function() {  
            this.detCmp.precio_total.setValue(this.roundTwo(Number(this.detCmp.precio_unitario.getValue()) * Number(this.detCmp.cantidad.getValue())));
        },this);
        
        this.detCmp.precio_unitario.on('keyup',function() {  
            this.detCmp.precio_total.setValue(this.roundTwo(Number(this.detCmp.precio_unitario.getValue()) * Number(this.detCmp.cantidad.getValue())));
        },this);
    }, 
    
    //#123  reset grailla detalle  
    resetGrillaDetalle: function(){
    	 console.log('resetear grilla del detalle....');
    	 this.editorDetail.stopEditing();
         var me = this;
         this.mestore.each(function(rec){
         	  me.mestore.remove(rec);
         });
         
    	
    },
    
    roundTwo: function(can){
    	 return  Math.round(can*Math.pow(10,2))/Math.pow(10,2);
    },
    
    habilitarDescripcion : function(opcion) {
    	
    	if(this.detCmp.descripcion){
	    	if (opcion) {
	    		this.detCmp.descripcion.setDisabled(false);   
	    		this.detCmp.descripcion.allowBlank = false; 		
	    	} else {
	    		this.detCmp.descripcion.setDisabled(true);
	    		this.detCmp.descripcion.allowBlank = true; 
	    		this.detCmp.descripcion.reset();
	    	}	
    	}
    	
    	console.log('opcion', opcion, this.detCmp.descripcion)
    		
    }, 
    
    cambiarCombo : function (tipo) {
    	this.detCmp.id_producto.setDisabled(false);
    	this.detCmp.id_producto.store.baseParams.tipo = tipo;
    	if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') { 
    		this.detCmp.id_producto.store.baseParams.id_punto_venta = this.Cmp.id_punto_venta.getValue();
    	} else {
    		this.detCmp.id_producto.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
    	}
    	this.detCmp.id_producto.store.baseParams.id_venta_fk = this.Cmp.id_venta_fk.getValue();
    	this.detCmp.id_producto.modificado = true;
    	this.detCmp.id_producto.reset();
    },
    cargarFormaPago : function () {
        if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') {  
    	    this.Cmp.id_forma_pago.store.baseParams.id_punto_venta = this.Cmp.id_punto_venta.getValue();
    		this.Cmp.id_forma_pago.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
    	} else {
    		this.Cmp.id_forma_pago.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
    	}
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
        
        var cmb_rec = this.detCmp['id_producto'].store.getById(rec.get('id_producto'));
        if(cmb_rec) {

            if (cmb_rec.get('codigo_unidad_medida')) {
                rec.set('nombre_producto', cmb_rec.get('nombre_producto') + ' (' + cmb_rec.get('codigo_unidad_medida') + ')');
            } else {
                rec.set('nombre_producto', cmb_rec.get('nombre_producto'));
            }
        }

                     
       
    },
    evaluaRequistos: function(){
    	//valida que todos los requistosprevios esten completos y habilita la adicion en el grid
     	//solo validar que tenga sucursal o punto de venta
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
                    },
                    {

                            text: '<i class="fa fa-plus-circle fa-lg"></i> Duplicar registro',
                            scope:this,
                            handler: function(){
                                if (this.megrid.getSelectionModel().getCount() == 0) {
                                    alert('Debe seleccionar un registro para duplicar');
                                } else if (this.megrid.getSelectionModel().getCount() > 1) {
                                    alert('Debe seleccionar un solo registro para duplicar');
                                } else {
                                    this.editorDetail.stopEditing();
                                    var s = this.megrid.getSelectionModel().getSelected();
                                    this.onDuplicateDetail(s);
                                    this.evaluaGrilla();
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
                        header: 'Descripción',
                        dataIndex: 'descripcion',
                        width: 300,
                        sortable: false,                        
                        editor: this.detCmp.descripcion 
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
    armarFormularioFormula : function () {
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
						        resizable:true,						         
						        forceSelection: true,
						        tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Nombre:</b> {nombre_producto}</p><p><b>Descripcion:</b> {descripcion}</p></div></tpl>',
						        allowBlank : false,
						        anchor: '100%'
						    });
		comboFormula.store.baseParams.tipo = 'formula';
		comboFormula.store.baseParams.id_sucursal = this.Cmp.id_sucursal.getValue();
    	var formularioFormula = new Ext.form.FormPanel({				            
	            items: [comboFormula],				            
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
	            width: 400,
	            height: 160,
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
		                		VentanaFormula.close(); 
		                		params.id_formula = comboFormula.getValue();   
		                		console.log(params);
		                		Ext.Ajax.request({
					                url:'../../sis_ventas_facturacion/control/FormulaDetalle/listarFormulaDetalleParaInsercion',                
					                params: params,
					                success:this.successCargarFormula,
					                failure: this.conexionFailure,					                
					                timeout:this.timeout,
					                arguments : {'nombre_formula' : nombre_formula},
					                scope:this
					            });	  
		                		//hacer ajax para obtener los datos a insertar en el detalle    		
		                		
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
    			precio_total: respuesta.datos[i].precio_total		
    			
    		});
    		this.mestore.add(myNewRecord);
    	}
    	this.mestore.commitChanges();
    	
    },
    onDuplicateDetail : function (r) {
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
        
        var myNewRecord = new grillaRecord({
            nombre_producto : r.data.nombre_producto,
            descripcion : r.data.descripcion,
            id_producto : r.data.id_producto,
            tipo : r.data.tipo,
            cantidad : r.data.cantidad,
            precio_unitario : r.data.precio_unitario,
            precio_total: r.data.precio_total

        });
        this.mestore.add(myNewRecord);

        this.mestore.commitChanges();
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
        
        if (record.data.tipo != '' && record.data.tipo != undefined) {
            
            this.cambiarCombo(record.data.tipo);
        }
        
        if (record.data.requiere_descripcion == 'si') {
            
            this.habilitarDescripcion(true);
        } else {
        	this.habilitarDescripcion(false);
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
                            autoHeight: true,
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
               {name: 'registrar_tipo_tarjeta',     type: 'string'},
	           {name: 'registrar_cc',     type: 'string'},
	           {name: 'tipo_tarjeta',     type: 'string'}
	        ]
		});
		if (this.data.objPadre.variables_globales.vef_tiene_punto_venta === 'true') {
		  this.storeFormaPago.baseParams.id_punto_venta = this.Cmp.id_punto_venta.getValue();
		}
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
	                		if (fp.data.valor > 0 && fp.data.registrar_tarjeta == 'si' && (fp.data.numero_tarjeta == '' || fp.data.codigo_tarjeta == '' || fp.data.tipo_tarjeta == '')) {
	                			validado = false;
	                			alert('La forma de pago ' + fp.data.nombre + ' requiere el tipo, numero de tarjeta y codigo de autorización')
	                		}
                            
                            if (fp.data.valor > 0 && fp.data.registrar_tipo_tarjeta == 'si' && fp.data.tipo_tarjeta == '') {
	                			validado = false;
	                			alert('La forma de pago ' + fp.data.nombre + ' requiere el tipo de tarjeta de credito')
	                		}
	                		
	                		if (fp.data.valor > 0 && fp.data.registrar_cc == 'si' && (fp.data.numero_tarjeta == '')) {
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
       Phx.vista.FormVentaNCETR.superclass.loadValoresIniciales.call(this);
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
			config:{
				name: 'id_proveedor',
				fieldLabel: 'Cliente',
				allowBlank: false,
				emptyText: 'Cliente ...',
				store: new Ext.data.JsonStore({

	    					url: '../../sis_parametros/control/Proveedor/listarProveedorCombos',
	    					id: 'id_proveedor',
	    					root: 'datos',
	    					sortInfo:{
	    						field: 'desc_proveedor',
	    						direction: 'ASC'
	    					},
	    					totalProperty: 'total',
	    					fields: ['id_proveedor','codigo','desc_proveedor','nit', 'desc_proveedor2'],
	    					// turn on remote sorting
	    					remoteSort: true,
	    					baseParams:{par_filtro:'codigo#desc_proveedor#nit'}
	    				}),
	    		tpl:'<tpl for=".">\
		                       <div class="x-combo-list-item"><p><b>Codigo: </b>{codigo}</p>\
		                      <p><b>Proveedor: </b>{desc_proveedor2}</p>\
		                      <p><b>Nit:</b>{nit}</p> \
		                     </div></tpl>',
        	    valueField: 'id_proveedor',
        	    displayField: 'desc_proveedor2',
        	    gdisplayField: 'nombre_factura',
        	    hiddenName: 'id_proveedor',
        	    triggerAction: 'all',
        	    //queryDelay:1000,
        	    pageSize:10,
				forceSelection: true,
				typeAhead: false,
				allowBlank: false,
				anchor: '100%',
				gwidth: 180,
				mode: 'remote',
				minChars:1,
				renderer: function(value,p,record){
                        if(record.data.estado=='anulado'){
                             return String.format('<b><font color="red">{0}</font></b>', record.data['desc_proveedor']);
                         }
                        else if(record.data.estado=='adjudicado'){
                             return String.format('<div title="Esta cotización tiene items adjudicados"><b><font color="green">{0}</font></b></div>', record.data['desc_proveedor']);
                        }
                        else{
                            return String.format('{0}', record.data['desc_proveedor']);
                        }}
			},	           			
			type:'ComboBox', 
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
            config : {
                name : 'id_cliente_destino',
                fieldLabel : 'Destino',
                allowBlank : false,
                emptyText : 'Destino...',
                qtip:'Cliente Destino',
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
                gdisplayField : 'cliente_destino',              
                hiddenName : 'id_cliente_destino',
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
            form : false
        },
         

        
        {
			config:{
				name: 'observaciones',
				fieldLabel: 'Observaciones',
				allowBlank: true,
				anchor: '80%'
				
			},
				type:'TextArea',
				id_grupo:0,				
				form:true
		},  
		{
	            config:{
	                name:'id_moneda',
	                origen:'MONEDA',
	                allowBlank:false,
	                fieldLabel:'Moneda',
	                gdisplayField:'desc_moneda',
	                gwidth:100,
				    anchor: '80%'
	             },
	            type:'ComboRec',
	            id_grupo:0,
	            form:false
	    },
        {
            config:{
                name: 'tipo_cambio_venta',
                fieldLabel: 'Tipo Cambio',
                allowBlank: false,
                allowNegative: false,
                anchor: '80%'
                
            },
                type:'NumberField',                
                id_grupo:0,                
                form:false,
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
                tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Codigo:</b> {codigo}</p><p><b>Nombre:</b> {nombre}</p></div></tpl>',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 15,
                queryDelay: 1000, 
                disabled:true,               
                minChars: 2
            },
            type: 'ComboBox',
            id_grupo: 1,            
            form: true
        },
        {
			config:{
				name: 'descripcion_bulto',
				fieldLabel: 'Bultos',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100
			},
				type:'TextArea',
				filters:{pfiltro:'ven.descripcion_bulto',type:'string'},
				id_grupo: 1,     
				grid:true,
				form:false
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
                tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombre}</p><p>Moneda:{desc_moneda}</p> </div></tpl>',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 15,
                queryDelay: 1000,               
                gwidth: 150,
                listWidth:450,
                resizable:true,
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
        },
        {
            config:{
                name: 'valor_bruto',
                fieldLabel: 'Valor Bruto',
                allowBlank: false,
                width: 150, 
                readOnly: true,               
                maxLength: 20
                
            },
                type:'NumberField',
                valorInicial: 0,                
                id_grupo: 3,                
                form: false
        },
        {
            config:{
                name: 'transporte_fob',
                fieldLabel: 'Trasporte FOB',
                allowBlank: false,
                width: 150,                 
                maxLength: 20
                
            },
                type:'NumberField',                
                id_grupo: 3,  
                valorInicial: 0,               
                form: false
        },
        {
            config:{
                name: 'seguros_fob',
                fieldLabel: 'Seguros FOB',
                allowBlank: false,
                width: 150,                
                maxLength:20
                
            },
                type:'NumberField',                
                id_grupo: 3,
                valorInicial: 0,                 
                form: false
        },
        {
            config:{
                name: 'otros_fob',
                fieldLabel: 'Otros FOB',
                allowBlank: false,
                width: 150,                 
                maxLength:20
                
            },
                type:'NumberField',                
                id_grupo: 3, 
                valorInicial: 0,                  
                form: false
        },
        {
            config:{
                name: 'total_fob',
                fieldLabel: 'TOTAL F.O.B',
                allowBlank: false,
                readOnly: true,
                width: 150,                
                maxLength:20
                
            },
                type:'NumberField',                
                id_grupo: 3, 
                                
                valorInicial: 0,               
                form: false
        },
        {
            config:{
                name: 'transporte_cif',
                fieldLabel: 'Trasporte CIF',
                allowBlank: false,
                width: 150,               
                maxLength: 20
                
            },
                type:'NumberField',                
                id_grupo: 4,                 
                valorInicial: 0,                
                form: false
        },
        {
            config:{
                name: 'seguros_cif',
                fieldLabel: 'Seguros CIF',
                allowBlank: false,
                width: 150,                
                maxLength:20
                
            },
                type:'NumberField',                
                id_grupo: 4,                 
                valorInicial: 0,               
                form: false
        },
        {
            config:{
                name: 'otros_cif',
                fieldLabel: 'Otros CIF',
                allowBlank: false,
                width: 150,               
                maxLength:20
                
            },
                type:'NumberField',                
                id_grupo: 4,                 
                valorInicial: 0,               
                form: false
        },
        {
            config:{
                name: 'total_cif',
                fieldLabel: 'TOTAL CIF',
                allowBlank: false,
                readOnly: true,
                width: 150,                
                maxLength:20
                
            },
                type:'NumberField',                
                id_grupo: 4,                 
                valorInicial: 0,               
                form: false
        },
        {
        	 config:{
						name:'codigo_aplicacion',
						qtip:'Aplicación para filtro prioritario, primero busca uan relación contable especifica para la aplicación definida si no la encuentra busca un relación contable sin aplicación',
						fieldLabel : 'Aplicación:',
						resizable:true,
						allowBlank:false,
		   				emptyText:'Seleccione un catálogo...',
		   				store: new Ext.data.JsonStore({
							url: '../../sis_parametros/control/Catalogo/listarCatalogoCombo',
							id: 'id_catalogo',
							root: 'datos',
							sortInfo:{
								field: 'orden',
								direction: 'ASC'
							},
							totalProperty: 'total',
							fields: ['id_catalogo','codigo','descripcion'],
							// turn on remote sorting
							remoteSort: true,
							baseParams: {par_filtro:'descripcion',catalogo_tipo:'tipo_credito_sobre_venta'}
						}),
	       			    enableMultiSelect:false,    				
						valueField: 'codigo',
		   				displayField: 'descripcion',
		   				gdisplayField: 'codigo_aplicacion',
		   				forceSelection:true,
		   				typeAhead: false,
		       			triggerAction: 'all',
		       			lazyRender:true,
		   				mode:'remote',
		   				pageSize:10,
		   				queryDelay:1000,
		   				anchor: '80%',
		   				minChars:2
		   		},
                type:'ComboBox',                
                id_grupo: 0,               
                form : true 
		 },
		 
		 {
        	 config:{
						name:'id_venta_fk', 
						qtip:'Factura sobre la que se generará la Nota de Crédito sobre Ventas',
						fieldLabel : 'Factura:',
						resizable:true,
						allowBlank:false,
		   				emptyText:'Factura...',
		   				store: new Ext.data.JsonStore({
							url: '../../sis_ventas_facturacion/control/Venta/listarVentaCombosETR',
							id: 'id_venta',
							root: 'datos',
							sortInfo:{
								field: 'ven.fecha',
								direction: 'DESC'
							},
							totalProperty: 'total',
							fields: ['id_venta','fecha','nro_factura','nroaut','observaciones','contrato_numero','objeto','nit','nombre_factura','total_venta_msuc','desc_moneda'],
							// turn on remote sorting
							remoteSort: true,
							baseParams: {par_filtro:'ven.nro_factura#dos.nroaut#ven.observaciones#ven.fecha#con.objeto#con.numero'}
						}),
						 tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Nombre:</b> {nombre_factura}</p><p><b>Nro:</b> {nro_factura}</p><p><b>Auto:</b> {nroaut}</p><p><b>Fecha:</b> {fecha}</p><p><b>Contrato:</b> {contrato_numero}</p><p><b>Obs:</b> {observaciones}</p><p><b>Importe</b>{total_venta_msuc} {desc_moneda}(s)</p></div></tpl>',
	       			    enableMultiSelect:false,    				
						valueField: 'id_venta',
		   				displayField: 'nro_factura',
		   				gdisplayField: 'nro_factura_vo',
		   				forceSelection:true,
		   				typeAhead: false,
		       			triggerAction: 'all',
		       			lazyRender:true,
		   				mode:'remote',
		   				pageSize:10,
		   				queryDelay:1000,
		   				anchor: '80%',
		   				minChars:2
		   		},
                type:'ComboBox',                
                id_grupo: 0,               
                form : true 
		 },
		   
        {
            config:{
                    name:'id_centro_costo',
                    origen:'CENTROCOSTO',
                    fieldLabel: 'Centro de Costos',                                 
                    url: '../../sis_parametros/control/CentroCosto/listarCentroCostoFiltradoXUsuaio',
                    emptyText : 'Centro Costo...',
                    allowBlank:false,
                    gdisplayField:'desc_centro_costo',//mapea al store del grid
                    gwidth:200,
                    baseParams:{'tipo_pres':'recurso'}
                },
            type:'ComboRec',
            id_grupo:0,
            filters:{pfiltro:'cc.codigo_cc',type:'string'},
            grid:true,
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
        
        // #123  definir filtro de proveedor 
        this.Cmp.id_venta_fk.store.baseParams.filter = "[{\"type\":\"numeric\",\"comparison\":\"eq\", \"value\":\""+this.Cmp.id_proveedor.getValue()+"\",\"field\":\"VEN.id_proveedor\"}]";
		this.Cmp.id_venta_fk.modificado = true;
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
                        }),
                        'tipo_factura':this.data.objPadre.tipo_factura};
        
        if( i > 0 &&  !this.editorDetail.isVisible()){
             Phx.vista.FormVentaNCETR.superclass.onSubmit.call(this,o);
        }
        else{
            alert('La venta no tiene registrado ningun detalle');
        }
    },    
    
    successSave:function(resp)
    {
    	
    	var datos_respuesta = JSON.parse(resp.responseText);
    	Phx.CP.loadingHide();
    	console.log(datos_respuesta.ROOT.datos);
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
        
    } 
    
    
})    
</script>