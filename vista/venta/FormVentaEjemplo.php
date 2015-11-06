<?php
/**
*@package pXP
*@file    FormCompraVenta.php
*@author  Rensi Arteaga Copari 
*@date    30-01-2014
*@description permites subir archivos a la tabla de documento_sol
*/
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
Phx.vista.FormCompraVenta=Ext.extend(Phx.frmInterfaz,{
    ActSave:'../../sis_contabilidad/control/DocCompraVenta/insertarDocCompleto',
    tam_pag: 10,
    tabEnter: true,
    //layoutType: 'wizard',
    layout: 'fit',
    autoScroll: false,
    breset: false,
   
    conceptos_eliminados: [],
    //labelSubmit: '<i class="fa fa-check"></i> Siguiente',
    constructor:function(config)
    {   
    	
    	Ext.apply(this,config);
    	//declaracion de eventos
        this.addEvents('beforesave');
        this.addEvents('successsave');
    	
    	this.buildComponentesDetalle();
        this.buildDetailGrid();
        this.buildGrupos();
        
        Phx.vista.FormCompraVenta.superclass.constructor.call(this,config);     
        
        
        this.init();    
        this.iniciarEventos();
        this.iniciarEventosDetalle();
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
    },
    buildComponentesDetalle: function(){
    	
    	me.detCmp = {
    		       
					'cantidad_sol': new Ext.form.NumberField({
										name: 'cantidad_sol',
										msgTarget: 'title',
						                fieldLabel: 'Cantidad',
						                allowBlank: false,
						                allowDecimals: false,
						                maxLength:10
								})				
			  }
    		
    		
    }, 
    //se pueden iniciar los eventos tanto del maestro como del detalle
    iniciarEventos: function(){
       
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
    	
    	var cmb_rec = this.detCmp['id_concepto_ingas'].store.getById(rec.get('id_concepto_ingas'));
    	if(cmb_rec){
    		rec.set('desc_concepto_ingas', cmb_rec.get('desc_ingas')); 
    	}
    	
    	var cmb_rec = this.detCmp['id_orden_trabajo'].store.getById(rec.get('id_orden_trabajo'));
    	if(cmb_rec){
    		rec.set('desc_orden_trabajo', cmb_rec.get('desc_orden')); 
    	}
    	
    	var cmb_rec = this.detCmp['id_centro_costo'].store.getById(rec.get('id_centro_costo'));
    	if(cmb_rec){
    		rec.set('desc_centro_costo', cmb_rec.get('codigo_cc')); 
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
    	this.Cmp.id_plantilla.setDisabled(sw);
    	
    	
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
                        name: 'cantidad_sol',
                        type: 'float'
                    }
                    ]);
        
        this.mestore = new Ext.data.JsonStore({
					url: '../../sis_contabilidad/control/DocConcepto/listarDocConcepto',
					id: 'id_doc_concepto',
					root: 'datos',
					totalProperty: 'total',
					fields: ['cantidad_sol'],remoteSort: true,
					baseParams: {dir:'ASC',sort:'id_doc_concepto',limit:'50',start:'0'}
				});
    	
    	this.editorDetail = new Ext.ux.grid.RowEditor({
                saveText: 'Aceptar',
                name: 'btn_editor'
               
            });
            
        this.summary = new Ext.ux.grid.GridSummary();
        // al iniciar la edicion
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
                    plugins: [ this.editorDetail, this.summary ],
                    stripeRows: true,
                    tbar: [{
                        /*iconCls: 'badd',*/
                        text: '<i class="fa fa-plus-circle fa-lg"></i> Agregar Concepto',
                        scope: this,
                        width: '100',
                        handler: function(){
                        	if(this.evaluaRequistos() === true){
                        		
	                        		 var e = new Items({
	                        		 	
		                                cantidad_sol: 1,
		                                
	                            });
	                            this.editorDetail.stopEditing();
	                            this.mestore.insert(0, e);
	                            this.megrid.getView().refresh();
	                            this.megrid.getSelectionModel().selectRow(0);
	                            this.editorDetail.startEditing(0);
	                            this.sw_init_add = true;
	                            
	                            this.bloqueaRequisitos(true);
                        	}
                        	else{
                        		//alert('Verifique los requisitos');
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
                                
                                console.log('al eliminar ...', r);
                                
                                // si se edita el documento y el concepto esta registrado, marcarlo para eliminar de la base
                                if(r.data.id_doc_concepto > 0){
                                	this.conceptos_eliminados.push(r.data.id_doc_concepto);
                                }
                                this.mestore.remove(r);
                            }
                            
                            
                            this.evaluaGrilla();
                        }
                    }],
            
                    columns: [
                    new Ext.grid.RowNumberer(),
                    
                    {
                       
                        header: 'Cantidad',
                        dataIndex: 'cantidad_sol',
                        align: 'center',
                        width: 50,
                        summaryType: 'sum',
                        editor: this.detCmp.cantidad_sol 
                    }]
                });
    },
    buildGrupos: function(){
    	this.Grupos = [{
    	           	    layout: 'border',
    	           	    border: false,
    	           	    frame:  true,
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
	                        collapseMode : 'mini',
	                        width: '100%',
	                        //autoHeight: true,
	                        padding: '0 0 0 10',
	    	                items:[
		    	                   {
							        bodyStyle: 'padding-right:5px;',
							        width: '33%',
							        autoHeight: true,
							        border: true,
							        items:[
			    	                   {
			                            xtype: 'fieldset',
			                            frame: true,
			                            border: false,
			                            layout: 'form',	
			                            title: 'Tipo',
			                            width: '100%',
			                            
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
       Phx.vista.FormCompraVenta.superclass.loadValoresIniciales.call(this);
    },
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_doc_compra_venta'
			},
			type:'Field',
			form:true 
		},		
		{
			config:{
				name: 'importe_pago_liquido',
				fieldLabel: 'Liquido Pagado',
				allowBlank: true,
				readOnly:true,
				anchor: '80%',
				gwidth: 100
			},
				type:'NumberField',
				id_grupo:0,
				form: true
		}
		
		
	],
	title: 'Frm solicitud',
	
    onEdit:function(){
        
    	this.accionFormulario = 'EDIT';
    	
    	this.loadForm(this.data.datosOriginales);    	
    	
        //load detalle de conceptos
        this.mestore.baseParams.id_doc_compra_venta = this.Cmp.id_doc_compra_venta.getValue();
        this.mestore.load()
        
        
        	
        
    },
    
    onNew: function(){
    	
    	this.accionFormulario = 'NEW'; 	
       
       
	},
   
    onSubmit: function(o) {
    	//  validar formularios
        var arra = [], total_det = 0.0, i, me = this;
        for (i = 0; i < me.megrid.store.getCount(); i++) {
    		record = me.megrid.store.getAt(i);
    		arra[i] = record.data;
    		total_det = total_det + (record.data.precio_total)*1
    		
		}
		
		//si tiene conceptos eliminados es necesari oincluirlos ...
		
		
		me.argumentExtraSubmit = { 'id_doc_conceto_elis': this.conceptos_eliminados.join(), 
		                           'json_new_records': JSON.stringify(arra, function replacer(key, value) {
   	    	           if (typeof value === 'string') {
							        return String(value).replace(/&/g, "%26")
							    }
							    return value;
							}) };
							
   	    if( i > 0 &&  !this.editorDetail.isVisible()){
   	    	
   	    	
   	    	console.log('doc', this.Cmp.importe_doc.getValue(), 'detalle', total_det);
   	    	
   	    	if (total_det*1 == this.Cmp.importe_doc.getValue()){
   	    		Phx.vista.FormCompraVenta.superclass.onSubmit.call(this, o, undefined, true);
   	    	}
   	    	else{
   	    		alert('El total del detalle no cuadra con el total del documento');
   	    	}
   	    	
   	    	
   	    }
   	    else{
   	    	alert('no tiene ningun concepto  en el documento')
   	    }
   	},
   
   
   	 successSave:function(resp)
    {
        Phx.CP.loadingHide();
        Phx.CP.getPagina(this.idContenedorPadre).reload();
        this.panel.close();
    }
    
})    
</script>