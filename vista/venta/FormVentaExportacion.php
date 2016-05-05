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
Phx.vista.FormVentaExportacion = {
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
	    this.Atributos[this.getIndAtributo('total_cif')].form=true; 	    
	    this.Atributos[this.getIndAtributo('observaciones')].config.fieldLabel='ICOTERM y Puerto Destino'; 
	    this.Atributos[this.getIndAtributo('observaciones')].type = 'TextField';
	    
	    
		      
	    Phx.vista.FormVentaExportacion.superclass.constructor.call(this,config);
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
    
    onAfterEdit:function(re, o, rec, num){
        //set descriptins values ...  in combos boxs       
        var cmb_rec = this.detCmp['id_producto'].store.getById(rec.get('id_producto'));
        if(cmb_rec) {
            
            rec.set('nombre_producto', cmb_rec.get('nombre_producto')); 
        }
                     
       var tmp = this.summary.getData()
       this.Cmp.valor_bruto.setValue(tmp.precio_total);
       this.Cmp.valor_bruto.fireEvent('change',this.Cmp.valor_bruto)
       
       
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
    
	
};
</script>