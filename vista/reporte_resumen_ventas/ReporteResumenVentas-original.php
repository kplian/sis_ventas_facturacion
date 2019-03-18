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
		Atributos : [
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
	                minChars: 2,
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
					name: 'fecha_desde',
					fieldLabel: 'Fecha Desde',
					allowBlank: false,				
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
					allowBlank: false,				
					format: 'd/m/Y'
								
				},
				type:'DateField',				
				id_grupo:0,				
				form:true
			}
		],
		title : 'Generar Reporte',
		ActSave : '../../sis_ventas_facturacion/control/ReportesVentas/reporteResumenVentasBoa',
		topBar : true,
		botones : false,
		labelSubmit : 'Imprimir',
		tooltipSubmit : '<b>Generar Reporte</b>',
		constructor : function(config) {
			Phx.vista.ReporteResumenVentas.superclass.constructor.call(this, config);
			this.init();
			this.Cmp.nivel.on('select',function(a,b,c) {
				if (b.data.field1 == 'sucursal') {
					this.Cmp.id_punto_venta.reset();
					this.Cmp.id_punto_venta.allowBlank = true;
					this.ocultarComponente(this.Cmp.id_punto_venta);
					this.Cmp.id_sucursal.allowBlank = false;
					this.mostrarComponente(this.Cmp.id_sucursal);
				} else {
					this.Cmp.id_sucursal.reset();
					this.Cmp.id_sucursal.allowBlank = true;
					this.ocultarComponente(this.Cmp.id_sucursal);
					this.Cmp.id_punto_venta.allowBlank = false;
					this.mostrarComponente(this.Cmp.id_punto_venta);
				}			
			},this);
			
			this.Cmp.fecha_desde.on('valid',function(){
				this.Cmp.fecha_hasta.setMinValue(this.Cmp.fecha_desde.getValue());
			},this);
			
			this.Cmp.fecha_hasta.on('valid',function(){
				this.Cmp.fecha_desde.setMaxValue(this.Cmp.fecha_hasta.getValue());
			},this);
			
			
		},
		
		tipo : 'reporte',
		clsSubmit : 'bprint',
		agregarArgsExtraSubmit: function() {
    		this.argumentExtraSubmit.sucursal = this.Cmp.id_sucursal.getRawValue();
    		this.argumentExtraSubmit.punto_venta = this.Cmp.id_punto_venta.getRawValue();
    		
    	},
})
</script>