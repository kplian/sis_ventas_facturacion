<?php
/**
*@package pXP
*@file gen-AperturaCierreCaja.php
*@author  (jrivera)
*@date 07-07-2016 14:16:20
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.AperturaCierreCaja=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.AperturaCierreCaja.superclass.constructor.call(this,config);
		this.init();
		this.addButton('cerrar',{grupo:[0],text:'Cerrar Caja',iconCls: 'block',disabled:true,handler:this.cerrarCaja,tooltip: '<b>Cerrar la Caja seleccionada</b>'});
        this.addButton('reporte',{grupo:[0,1],text:'Reporte',iconCls: 'bpdf',disabled:true,handler:this.generarReporte,tooltip: '<b>Genera reporte de la caja</b>'});
		this.finCons = true;
		this.store.baseParams.pes_estado = 'abierto';    
		this.load({params:{start:0, limit:this.tam_pag}});
	},
	bactGroups:  [0,1],    
    bexcelGroups: [0,1], 
    bdel : true,
	gruposBarraTareas:[{name:'abierto',title:'<H1 align="center"><i class="fa fa-eye"></i> Abiertas</h1>',grupo:0,height:0},
                       {name:'cerrado',title:'<H1 align="center"><i class="fa fa-eye"></i> Cerradas</h1>',grupo:1,height:0}
                       
                       ],
    actualizarSegunTab: function(name, indice){
        if(this.finCons) {        	 
             this.store.baseParams.pes_estado = name;                          
             this.load({params:{start:0, limit:this.tam_pag}});
        }
    },
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_apertura_cierre_caja'
			},
			type:'Field',
			form:true 
		},
		{
            config:{
                name: 'fecha_apertura_cierre',
                fieldLabel: 'Fecha ',              
                gwidth: 110,
                format: 'd/m/Y', 
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
                type:'DateField',
                filters: { pfiltro:'apcie.fecha', type:'date'},              
                grid:true,
                form:false
        },
		{
            config: {
                name: 'id_sucursal',
                fieldLabel: 'Sucursal',
                allowBlank: true,
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
                    baseParams: {tipo_usuario: 'cajero',par_filtro: 'suc.nombre#suc.codigo'}
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
            id_grupo: 1,            
            form: true,
            grid:true
        },
		{
			config: {
	                name: 'id_punto_venta',
	                fieldLabel: 'Punto de Venta',
	                allowBlank: true,
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
	                    baseParams: {tipo_usuario: 'cajero',par_filtro: 'puve.nombre#puve.codigo'}
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
	                renderer : function(value, p, record) {
	                    return String.format('{0}', record.data['nombre_punto_venta']);
	                },
	                width:250,
                	resizable:true
	            },
	            type: 'ComboBox',
	            id_grupo: 1,
	            filters: {pfiltro: 'puve.nombre',type: 'string'},
	            grid: true,
	            form: true,
	            grid:true
	       },
	       
	       {
            config:{
                name: 'estado',
                fieldLabel: 'Estado',                
                gwidth: 100
            },
                type:'TextField',
                filters:{pfiltro:'ven.estado',type:'string'},                
                grid:true,
                form:false
        },
		
		{
			config:{
				name: 'monto_inicial',
				fieldLabel: 'Monto Inicial',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:4,
				allowDecimals: true,
				decimalPrecision : 2
			},
				type:'NumberField',
				filters:{pfiltro:'apcie.monto_inicial',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true,
				valorInicial :0.00
		},
		{
			config:{
				name: 'monto_inicial_moneda_extranjera',
				fieldLabel: 'Monto Inicial Moneda Extranjera',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:4,
				allowDecimals: true,
				decimalPrecision : 2
			},
				type:'NumberField',
				filters:{pfiltro:'apcie.monto_inicial_moneda_extranjera',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true,
				valorInicial :0.00
		},
		{
			config:{
				name: 'obs_apertura',
				fieldLabel: 'Obs. Apertura',
				allowBlank: true,
				anchor: '100%',
				gwidth: 200
			},
				type:'TextArea',
				filters:{pfiltro:'apcie.obs_apertura',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'obs_cierre',
				fieldLabel: 'Obs. Cierre',
				allowBlank: true,
				anchor: '100%',
				gwidth: 150
			},
				type:'TextArea',
				filters:{pfiltro:'apcie.obs_cierre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'arqueo_moneda_local',
				fieldLabel: 'Arqueo Moneda Local',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:4,
				allowDecimals: true,
				decimalPrecision : 2
			},
				type:'NumberField',
				filters:{pfiltro:'apcie.arqueo_moneda_local',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
				
		},
		{
			config:{
				name: 'arqueo_moneda_extranjera',
				fieldLabel: 'Arqueo Moneda Extranjera',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4,
				allowDecimals: true,
				decimalPrecision : 2
				
			},
				type:'NumberField',
				filters:{pfiltro:'apcie.arqueo_moneda_extranjera',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true,
				valorInicial :0.00
		},
		{
			config:{
				name: 'fecha_hora_cierre',
				fieldLabel: 'Fecha Hora Cierre',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',				
				id_grupo:1,
				grid:true,
				form:false
		},
		{
            config:{
                name: 'usr_reg',
                fieldLabel: 'Cajero',                
                gwidth: 100
            },
                type:'TextField',
                filters:{pfiltro:'ven.estado',type:'string'},                
                grid:true,
                form:false
        },
		
	],
	tam_pag:50,	
	title:'Apertura de Caja',
	ActSave:'../../sis_ventas_facturacion/control/AperturaCierreCaja/insertarAperturaCierreCaja',
	ActDel:'../../sis_ventas_facturacion/control/AperturaCierreCaja/eliminarAperturaCierreCaja',
	ActList:'../../sis_ventas_facturacion/control/AperturaCierreCaja/listarAperturaCierreCaja',
	id_store:'id_apertura_cierre_caja',
	fields: [
		{name:'id_apertura_cierre_caja', type: 'numeric'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_punto_venta', type: 'numeric'},
		{name:'id_usuario_cajero', type: 'numeric'},
		{name:'id_moneda', type: 'numeric'},
		{name:'obs_cierre', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'fecha_apertura_cierre', type: 'date',dateFormat:'Y-m-d'},
		{name:'monto_inicial', type: 'numeric'},
		{name:'arqueo_moneda_local', type: 'numeric'},
		{name:'arqueo_moneda_extranjera', type: 'numeric'},
		{name:'monto_inicial', type: 'numeric'},
		{name:'obs_apertura', type: 'string'},
		{name:'usr_reg', type: 'string'},
		{name:'fecha_hora_cierre', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'monto_inicial_moneda_extranjera', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'nombre_punto_venta', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_apertura_cierre_caja',
		direction: 'ASC'
	},	
	onSubmit: function(o, x, force) {
		if (!this.Cmp.id_punto_venta.getValue() && !this.Cmp.id_punto_venta.getValue()) {
			alert('Debe elegir un punto de venta o sucursal para abrir');	
		} else {
			Phx.vista.AperturaCierreCaja.superclass.onSubmit.call(this,o, x, force);
		}
	},
	onButtonNew: function() {
		this.ocultarComponente(this.Cmp.obs_cierre);
		this.ocultarComponente(this.Cmp.arqueo_moneda_local);
		this.ocultarComponente(this.Cmp.arqueo_moneda_extranjera);
		this.Cmp.arqueo_moneda_local.allowBlank = true;		
		this.mostrarComponente(this.Cmp.id_sucursal);
		this.mostrarComponente(this.Cmp.id_punto_venta);
		this.mostrarComponente(this.Cmp.monto_inicial);
		this.mostrarComponente(this.Cmp.monto_inicial_moneda_extranjera);
		this.mostrarComponente(this.Cmp.obs_apertura);
        this.Cmp.id_punto_venta.setDisabled(false);
        this.Cmp.id_sucursal.setDisabled(false);
		Phx.vista.AperturaCierreCaja.superclass.onButtonNew.call(this);
		
	},
	
	onButtonEdit: function() {
		this.ocultarComponente(this.Cmp.obs_cierre);
		this.ocultarComponente(this.Cmp.arqueo_moneda_local);
		this.ocultarComponente(this.Cmp.arqueo_moneda_extranjera);
		this.Cmp.arqueo_moneda_local.allowBlank = true;		
		this.mostrarComponente(this.Cmp.id_sucursal);
		this.mostrarComponente(this.Cmp.id_punto_venta);
		this.mostrarComponente(this.Cmp.monto_inicial);
		this.mostrarComponente(this.Cmp.monto_inicial_moneda_extranjera);
		this.mostrarComponente(this.Cmp.obs_apertura);
        this.Cmp.id_punto_venta.setDisabled(true);
        this.Cmp.id_sucursal.setDisabled(true);
		this.argumentExtraSubmit = {'accion' :'nada'};
		Phx.vista.AperturaCierreCaja.superclass.onButtonEdit.call(this);
		
	},
	
	cerrarCaja : function () {
		this.mostrarComponente(this.Cmp.obs_cierre);
		this.mostrarComponente(this.Cmp.arqueo_moneda_local);
		this.mostrarComponente(this.Cmp.arqueo_moneda_extranjera);
		this.Cmp.arqueo_moneda_local.allowBlank = false;		
		this.ocultarComponente(this.Cmp.id_sucursal);
		this.ocultarComponente(this.Cmp.id_punto_venta);
		this.ocultarComponente(this.Cmp.monto_inicial);
		this.ocultarComponente(this.Cmp.monto_inicial_moneda_extranjera);
		this.ocultarComponente(this.Cmp.obs_apertura);
		this.argumentExtraSubmit = {'accion' :'cerrar'};
		Phx.vista.AperturaCierreCaja.superclass.onButtonEdit.call(this);
	},

    generarReporte : function () {
        var data=this.sm.getSelected().data;
        Phx.CP.loadingShow();
        Ext.Ajax.request({
            url:'../../sis_ventas_facturacion/control/AperturaCierreCaja/reporteAperturaCierreCaja',
            params:{'id_apertura_cierre_caja' : data.id_apertura_cierre_caja},
            success:this.successExport,
            failure: this.conexionFailure,
            timeout:this.timeout,
            scope:this
        });
    },
	preparaMenu:function()
    {   var rec = this.sm.getSelected();
        
        if (rec.data.estado == 'abierto') {              
              this.getBoton('cerrar').enable();
              this.getBoton('reporte').enable();
              this.getBoton('edit').enable(); 
              this.getBoton('del').enable();                                       
        } 
        
        if (rec.data.estado == 'cerrado') {              
              this.getBoton('cerrar').disable();
            this.getBoton('reporte').disable();
              this.getBoton('edit').disable(); 
              this.getBoton('del').disable();                                
        } 
        
        Phx.vista.AperturaCierreCaja.superclass.preparaMenu.call(this);
    },
    liberaMenu:function()
    {   this.getBoton('cerrar').disable();      
        Phx.vista.AperturaCierreCaja.superclass.liberaMenu.call(this);
    }
	
	
	}
)
</script>
		
		