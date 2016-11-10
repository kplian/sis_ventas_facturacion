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
	Phx.vista.ReporteXProducto = Ext.extend(Phx.frmInterfaz, {
		Atributos : [
		
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
                minChars: 2,
                width:250,
                resizable:true
            },
            type: 'ComboBox',
            id_grupo: 0,            
            form: true
        },  
        {
       			config:{
       				name:'id_productos',
       				fieldLabel:'Productos',
       				allowBlank:false,
       				emptyText:'Productos...',
       				store: new Ext.data.JsonStore({
                                                url: '../../sis_ventas_facturacion/control/SucursalProducto/listarSucursalProducto',
                                                id: 'id_sucursal_producto',
                                                root: 'datos',
                                                sortInfo: {
                                                    field: 'id_sucursal_producto',
                                                    direction: 'ASC'
                                                },
                                                totalProperty: 'total',
                                                fields: ['id_sucursal_producto', 'tipo_producto','nombre_producto'],
                                                remoteSort: true,
                                                baseParams: {par_filtro: 'nombre'}
                                            }),
                    valueField: 'id_sucursal_producto',
                    displayField: 'nombre_producto',
                    gdisplayField: 'nombre_producto',
                    hiddenName: 'id_sucursal_producto',
       				forceSelection:true,
       				typeAhead: false,
           			triggerAction: 'all',
           			lazyRender:true,
       				mode:'remote',
       				pageSize:10,
       				queryDelay:1000,
       				width:250,
       				minChars:2,
	       			enableMultiSelect:true,
	       			disabled:true,
                	resizable:true
       			
       			},
       			type:'AwesomeCombo',
       			id_grupo:0,       			
       			form:true
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
		ActSave : '../../sis_ventas_facturacion/control/ReportesVentas/reporteXProducto',
		topBar : true,
		botones : false,
		labelSubmit : 'Imprimir',
		tooltipSubmit : '<b>Generar Reporte</b>',
		constructor : function(config) {
			Phx.vista.ReporteXProducto.superclass.constructor.call(this, config);
			this.init();			
			
			this.Cmp.fecha_desde.on('valid',function(){
				this.Cmp.fecha_hasta.setMinValue(this.Cmp.fecha_desde.getValue());
			},this);
			
			this.Cmp.id_sucursal.on('select',function(c, r, i){
				this.Cmp.id_productos.store.baseParams.id_sucursal = r.data.id_sucursal;
				this.Cmp.id_productos.modificado = true;
				this.Cmp.id_productos.setDisabled(false);
			},this);
			
			this.Cmp.fecha_hasta.on('valid',function(){
				this.Cmp.fecha_desde.setMaxValue(this.Cmp.fecha_hasta.getValue());
			},this);
			
			
		},
		
		tipo : 'reporte',
		clsSubmit : 'bprint',
		agregarArgsExtraSubmit: function() {
    		this.argumentExtraSubmit.sucursal = this.Cmp.id_sucursal.getRawValue();   		
    		
    	},
})
</script>