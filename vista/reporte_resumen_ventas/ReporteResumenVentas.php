<?php
/**
 *@package pXP
 *@file    ItemEntRec.php
 *@author  RCM
 *@date    07/08/2013
 *@description Reporte Material Entregado/Recibido
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.ReporteResumenVentas = Ext.extend(Phx.frmInterfaz, {
		constructor : function(config) {
			Phx.vista.ReporteResumenVentas.superclass.constructor.call(this, config);
			this.init();
			this.iniciarEventos();
		
		},
		

		Atributos : [
		{
			config:{
				name:'tipo_reporte',
				fieldLabel:'Reporte de ',
				allowBlank:false,
				emptyText:'Reporte de',
				typeAhead: true,
				triggerAction: 'all',
				lazyRender:true,
				mode: 'local',
				valueField: 'tipo',
				//anchor: '100%',
				//gwidth: 100,
				width:250,
				store:new Ext.data.ArrayStore({
					fields: ['variable', 'valor'],
					data : [    
								['todo','TODO'],
								['sucursal','SUCURSAL'],
								['punto_venta','PUNTO DE VENTA']
							]
				}),
				valueField: 'variable',
				displayField: 'valor',
				/*
				listeners: {
					'afterrender': function(combo){			  
						combo.setValue('todo');
					}
				}*/
			},
			type:'ComboBox',
			form:true
		},
		
		{
			config : {
				name: 'nivel',
                fieldLabel: 'Nivel',
                allowBlank:false,
                emptyText:'Nivel...',
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                gwidth: 150,
                store:['sucursal','punto_venta']
			},
			type: 'ComboBox',
			id_grupo: 0,			
			form: true
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
                    baseParams: {tipo_usuario : 'todos',par_filtro: 'suc.nombre#suc.codigo'}
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
                width:250,
                queryDelay: 1000,                
                minChars: 2,
                resizable:true,
                hidden : true
            },
            type: 'ComboBox',
            id_grupo: 0,            
            form: true
        },  
        {
			config: {
	                name: 'id_punto_venta',
	                fieldLabel: 'Punto de Venta',
	                //allowBlank: false,
	                allowBlank: true,
	                
	                emptyText: 'Elija un Pun...',
	                store: new Ext.data.JsonStore({
	                    url: '../../sis_ventas_facturacion/control/PuntoVenta/listarPuntoVentaCombo',
	                    id: 'id_punto_venta',
	                    root: 'datos',
	                    sortInfo: {
	                        field: 'nombre',
	                        direction: 'ASC'
	                    },
	                    totalProperty: 'total',
	                    fields: ['id_punto_venta', 'nombre', 'codigo','id_sucursal'],
	                    remoteSort: true,
	                    baseParams: {tipo_usuario : 'todos',par_filtro: 'puve.nombre#puve.codigo'}
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
	                width:250,
	                resizable:true,
	                minChars: 1,
	                renderer : function(value, p, record) {
	                    return String.format('{0}', record.data['nombre_punto_venta']);
	                },
                	hidden : true
	            },
	            type: 'ComboBox',
	            id_grupo: 0,
	            filters: {pfiltro: 'puve.nombre',type: 'string'},
	            grid: true,
	            form: true
	       },
	        {
	   			config:{
	   				name : 'id_gestion',
	   				origen : 'GESTION',
	   				fieldLabel : 'Gestion',
	   				gdisplayField: 'desc_gestion',
	   				allowBlank : true,
	   				width: 150
	   			},
	   			type : 'ComboRec',
	   			id_grupo : 0,
	   			form : true
	   	   },
	       {
				config:{
					name: 'fecha_desde',
					fieldLabel: 'Fecha Desde',
					allowBlank: true,				
					format: 'd/m/Y'
								
				},
				type:'DateField',				
				id_grupo:0,				
				form:true
			},
			{
				config:{
					name: 'fecha_hasta',
					fieldLabel: 'Fecha Hasta',
					allowBlank: true,				
					format: 'd/m/Y'
								
				},
				type:'DateField',				
				id_grupo:0,				
				form:true
			},
			{
            config:{
                name: 'desc_proveedor',
                origen: 'PROVEEDOR',
                allowBlank: true,
                fieldLabel: 'Proveedor',
                anchor: '100%',
                gdisplayField: 'desc_proveedor',//mapea al store del grid
                hiddenValue: 'desc_proveedor',
                gwidth: 150,
                //baseParams: { 'filtrar_base': 'si' },
                renderer: function (value, p, record){return String.format('{0}', record.data['desc_proveedor']);}
             },
            type: 'ComboRec',
            id_grupo: 0,
            filters: { pfiltro:'pro.desc_proveedor',type:'string'},
           // grid: true,
            form: true,
            bottom_filter:false,
         }

		],
		title : 'Generar Reporte',
		ActSave : '../../sis_ventas_facturacion/control/ReportesVentas/reporteVentas',
		topBar : true,
		botones : false,
		labelSubmit : 'Imprimir',
		tooltipSubmit : '<b>Generar Reporte</b>',
		
		
		tipo : 'reporte',
		clsSubmit : 'bprint',

		
		agregarArgsExtraSubmit: function() {
			
			if (this.Cmp.tipo_reporte.getValue() == 'sucursal' && this.Cmp.id_sucursal.getValue() == '') {
				
				alert('Ingrese una sucursal Por Favor');
				
			};
    		this.argumentExtraSubmit.sucursal = this.Cmp.id_sucursal.getRawValue();
    		this.argumentExtraSubmit.punto_venta = this.Cmp.id_punto_venta.getRawValue();
    		
    	},
    	iniciarEventos: function(){
    			
			this.ocultarComponente(this.Cmp.nivel);
			

			this.Cmp.tipo_reporte.on('select',function(a,record,c) {
				console.log('record',record);
				
				
				
				if(record.data.variable == 'sucursal' ){
					
					this.Cmp.id_sucursal.reset();
					this.Cmp.id_punto_venta.reset();
						
					this.mostrarComponente(this.Cmp.id_sucursal);

					this.ocultarComponente(this.Cmp.id_punto_venta);
					this.Cmp.id_punto_venta.allowBlank=true;
					
					
				}
				else if(record.data.variable == 'punto_venta'){
					
					this.Cmp.id_sucursal.reset();
					this.Cmp.id_punto_venta.reset();
										
					this.mostrarComponente(this.Cmp.id_sucursal);
					this.mostrarComponente(this.Cmp.id_punto_venta);
					
					this.Cmp.id_punto_venta.disable(true);
					this.Cmp.id_punto_venta.allowBlank=false;
					
					this.Cmp.id_sucursal.on('select',function(a,rec,c) {

					this.Cmp.id_punto_venta.enable(true);
					
					this.Cmp.id_punto_venta.store.baseParams.id_sucursal=rec.data.id_sucursal;
					this.Cmp.id_punto_venta.store.load({params:{start:0,limit:this.tam_pag}, 
					               callback : function (r) {                        
					                    if (r.length > 0 ) {                        
	
					                       this.Cmp.id_punto_venta.setValue(r[0].data.id_punto_venta);

					                    }     
					                                    
					                }, scope : this
					            });
	
			   
					},this);

				}
				else{
					this.Cmp.id_sucursal.reset();
					this.Cmp.id_punto_venta.reset();
					
					this.ocultarComponente(this.Cmp.id_sucursal);
					this.ocultarComponente(this.Cmp.id_punto_venta);
					this.Cmp.id_sucursal.allowBlank=true;
					this.Cmp.id_punto_venta.allowBlank=true;
				}
									
			},this);
						
			this.Cmp.fecha_desde.on('valid',function(){
				this.Cmp.fecha_hasta.setMinValue(this.Cmp.fecha_desde.getValue());
			},this);
			
			this.Cmp.fecha_hasta.on('valid',function(){
				this.Cmp.fecha_desde.setMaxValue(this.Cmp.fecha_hasta.getValue());
			},this);
			
			
    		
    	}
   
})
</script>