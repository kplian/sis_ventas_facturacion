<?php
/**
*@package pXP
*@file gen-ReporteVenta.php
*@author  (admin)
*@date 18-08-2015 15:57:09
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 * ISSUE				FECHA			AUTHOR		  DESCRIPCION
 * 1B				17/08/2018			EGS				se hizo cambios para cobros regularizados y retencion de garantias , se movio y se habilito columnas
 * 1C				20/09/2018			EGS				se aumento codigo para el campo id_contrato
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ReporteVenta = Ext.extend(Phx.gridInterfaz,{
	
    fheight: '80%',
    fwidth: '70%',
    tabEnter: true,
    tipoDoc: 'venta',
    regitrarDetalle: 'si',
    nombreVista: 'ReporteVenta',
    constructor:function(config){
    	this.maestro=config.maestro;
		//var me = this;
		//me.configurarAtributos(me);
		
			//llama al constructor de la clase padre
		Phx.vista.ReporteVenta.superclass.constructor.call(this,config);
        //Botón para Imprimir el Comprobante
	
                
            this.addButton('btnImprimirTodoFactura',{ 
       	    text: 'Reportes', 
       	    iconCls: 'blist', 
       	    disabled: false, 
       	    handler: this.imprimirReporteVenta, 
       	    tooltip: 'reporte de ventas'});
               ////EGS-F-17/08/2018///////////
            

		
		//this.iniciarEventos();
		this.init();
		this.obtenerVariableGlobal();
		//this.load({params:{start:0, limit:this.tam_pag}});
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
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_proceso_wf'
            },
            type:'Field',
            form:true 
        },
        
        {
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_estado_wf'
            },
            type:'Field',
            form:true 
        },
        {
            config:{
                name: 'fecha',
                fieldLabel: 'Fecha Doc.',              
                gwidth: 110,
                format: 'd/m/Y', 
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
                type:'DateField',
                filters: { pfiltro:'ven.fecha', type:'date'},   
                 bottom_filter: true,           
                grid:true,
                form:false
        },
        
         {
            config:{
                name: 'nro_tramite',
                fieldLabel: 'Nro',              
                gwidth: 110,
                renderer: function(value,c,r){  
                	
                	if (r.data.estado == 'anulado') {
                		return String.format('{0}', '<p><font color="red">' + value + '</font></p>');
                	} else {
                		return value;
                	}  
                    
                }
            },
                type:'TextField',
                filters:{pfiltro:'ven.nro_tramite',type:'string'},              
                grid:true,
                form:false,
                bottom_filter: true
        },
        {
            config:{
                name: 'correlativo_venta',
                fieldLabel: 'Nro',              
                gwidth: 110,
                renderer: function(value,c,r){  
                	
                	if (r.data.estado == 'anulado') {
                		return String.format('{0}', '<p><font color="red">' + value + '</font></p>');
                	} else {
                		return value;
                	}  
                    
                }
            },
                type:'TextField',
                filters:{pfiltro:'ven.correlativo_venta',type:'string'},              
                grid:false,
                form:false,
                bottom_filter: false
        },
        {
            config:{
                name: 'desc_proveedor',
                fieldLabel: 'Cliente',              
                gwidth: 110
            },
                type:'TextField',
                filters : {pfiltro : 'vpro.desc_proveedor',type : 'string'},             
                grid:true,
                form:false,
                bottom_filter: true
        },        
        {
            config:{
                name: 'cliente_destino',
                fieldLabel: 'Destino',
                gwidth: 110
            },
                type:'TextField',
                filters : {pfiltro : 'clides.nombre_factura',type : 'string'},             
                grid:false,
                form:false,
                bottom_filter: false
        },
        {
            config:{
                name: 'nro_factura',
                fieldLabel: 'Nro Factura',              
                gwidth: 110
            },
                type:'TextField',
                filters:{pfiltro:'ven.nro_factura',type:'string'},              
                grid:true,
                form:false,
                bottom_filter: true
        },        
        {
            config:{
                name: 'total_venta',
                fieldLabel: 'Total Venta',
                allowBlank: false,
                anchor: '80%',
                gwidth: 120,
                maxLength:5,
                disabled:true
            },
                type:'NumberField',
                filters:{pfiltro:'ven.total_venta',type:'numeric'},
                id_grupo:1,
                grid:true,
                form:false,
                bottom_filter: true
        },
        {
            config:{
                name: 'cod_control',
                fieldLabel: 'Codigo Control',              
                gwidth: 110
            },
                type:'TextField',
                filters:{pfiltro:'ven.cod_control',type:'string'},              
                grid:true,
                form:false
        },

		{
            config:{
                name: 'nombre_sucursal',
                fieldLabel: 'Sucursal',              
                gwidth: 110
            },
                type:'TextField',
                filters: { pfiltro: 'suc.nombre', type: 'string'},      
                grid: true,
                form: false,
                bottom_filter: false
        },
        
         {
            config:{
                name: 'forma_pago',
                fieldLabel: 'Forma de Pago',              
                gwidth: 110
            },
                type:'TextField',
                grid:true,
                form:false
        },
		
		
        
        {
			config:{
				name: 'observaciones',
				fieldLabel: 'Observaciones',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100
			},
				type:'TextArea',
				filters:{pfiltro:'ven.observaciones',type:'string'},
				id_grupo:0,
				grid:true,
				form:false
		},
        {
            config:{
                name: 'monto_forma_pago',
                fieldLabel: 'Importe Recibido',
                allowBlank: false,                
                gwidth: 120,
                maxLength:5,
                disabled:true
            },
                type:'NumberField',                
                id_grupo:1,
                grid:true,
                form:false
        },
        {
            config:{
                name: 'comision',
                fieldLabel: 'Comisión',                          
                gwidth: 120,
                maxLength:5,
                disabled:true
            },
                type:'NumberField', 
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
                bottom_filter: true,          
                grid:true,
                form:false
        },
        {
            config:{
                name: 'estado_reg',
                fieldLabel: 'Estado Reg.',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:10
            },
                type:'TextField',
                filters:{pfiltro:'ven.estado_reg',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
        },
		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'ven.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'ven.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'ven.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu2.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'ven.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},

        {
            config:{
                name: 'excento',
                fieldLabel: 'Imp Excento',              
                gwidth: 110
            },
                type:'TextField',
                filters:{pfiltro:'ven.excento',type:'numeric'},              
                grid:true,
                form:false
        },

	],
	tam_pag:50,	
	title:'Ventas y Notas',
	ActList:'../../sis_ventas_facturacion/control/ReportesVentas/reporteVentasGrid',
	id_store:'id_venta',
	fields: [
		{name:'id_venta', type: 'numeric'},
		{name:'id_cliente', type: 'numeric'},
		{name:'id_proveedor', type: 'numeric'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_punto_venta', type: 'numeric'},
		{name:'id_proceso_wf', type: 'numeric'},
		{name:'id_forma_pago', type: 'numeric'},
		{name:'porcentaje_descuento', type: 'numeric'},
		{name:'id_vendedor_medico', type: 'string'},
		{name:'forma_pago', type: 'string'},
		{name:'numero_tarjeta', type: 'string'},
		{name:'observaciones', type: 'string'},
		{name:'codigo_tarjeta', type: 'string'},
		{name:'tipo_tarjeta', type: 'string'},
		{name:'id_estado_wf', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'nombre_factura', type: 'string'},
		{name:'nombre_sucursal', type: 'string'},
		{name:'nombre_punto_venta', type: 'string'},
		{name:'forma_pedido', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'correlativo_venta', type: 'string'},
        {name:'contabilizable', type: 'string'},
		{name:'a_cuenta', type: 'numeric'},
		{name:'total_venta', type: 'numeric'},
		{name:'comision', type: 'numeric'},
		{name:'fecha_estimada_entrega', type: 'date',dateFormat:'Y-m-d'},
        {name:'hora_estimada_entrega', type: 'string'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'nit', type: 'string'},
		{name:'monto_forma_pago', type: 'numeric'},
		{name:'nro_factura', type: 'string'},
		{name:'cod_control', type: 'string'},
        {name:'vendedor_medico', type: 'string'},
		{name:'fecha', type: 'date',dateFormat:'Y-m-d'},
		{name:'excento', type: 'numeric'},
		{name:'nroaut', type: 'numeric'},
		'id_moneda','total_venta_msuc','transporte_fob','seguros_fob',
		'otros_fob','transporte_cif','seguros_cif','otros_cif',
		'tipo_cambio_venta','desc_moneda','valor_bruto',
		'descripcion_bulto','cliente_destino','id_cliente_destino',
		'id_contrato',  'desc_contrato',  
		'id_centro_costo', 
		'desc_centro_costo',
		'codigo_aplicacion',
		'id_venta_fk','nro_factura_vo','id_dosificacion_vo','nroaut_vo','total_venta_vo',
		

		{name:'nro_tramite', type: 'string'},
		{name:'desc_proveedor', type: 'string'},
		
		
	],
	sortInfo:{
		field: 'id_venta',
		direction: 'DESC'
	},

	
	obtenerVariableGlobal: function(){
		//Verifica que la fecha y la moneda hayan sido elegidos
		Phx.CP.loadingShow();
		Ext.Ajax.request({
				url:'../../sis_seguridad/control/Subsistema/obtenerVariableGlobal',
				params:{
					codigo: 'conta_libro_compras_detallado'  
				},
				success: function(resp){
					Phx.CP.loadingHide();
					var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
					
					if (reg.ROOT.error) {
						Ext.Msg.alert('Error','Error a recuperar la variable global')
					} else {
						if(reg.ROOT.datos.valor == 'no'){
							this.regitrarDetalle = 'no';
						}
					}
				},
				failure: this.conexionFailure,
				timeout: this.timeout,
				scope:this
			});
		
	},

	bdel: false,
	bsave: false,
	bnew: false,
	bedit: false,
	bexcel: false,
	
	
	//recibe los parametros del formulario 
	onReloadPage:function(param){
		
		var me = this;
		this.initFiltro(param);
	},
	
	initFiltro: function(param){
		this.store.baseParams=param;
		this.load( { params: { start:0, limit: this.tam_pag } });
	},
	
       
     preparaMenu:function(tb){
        Phx.vista.ReporteVenta.superclass.preparaMenu.call(this,tb)
        var data = this.getSelectedData();
        //this.getBoton('btnImprimirR').enable();
         //this.getBoton('btnImprimir').enable();
          //this.getBoton('btnImprimirTodoCliente').enable();
      	
		
    },
    
    liberaMenu:function(tb){
        Phx.vista.ReporteVenta.superclass.liberaMenu.call(this,tb);
        //this.getBoton('btnImprimirR').disable(); //desahabilita si no se escoge registro enble para q siempre este habilitado
      // this.getBoton('btnImprimir').disable();
        //this.getBoton('btnImprimirTodoCliente').enable();
        
                    
    },
    	imprimirReporte : function() {
			var rec = this.sm.getSelected();
			var data = rec.data;
			if (data) {
				Phx.CP.loadingShow();
				Ext.Ajax.request({
					url : '../../sis_cobros/control/CobroRecibo/cobroReporteFactura',
					params : {
						'id_doc_compra_venta' : data.id_doc_compra_venta,
						'razon_social':'',
						 'formato':'pdf'
					},
					success : this.successExport,
					failure : Phx.CP.conexionFailure,
					timeout : this.timeout,
					scope : this
				});
			}

		},
		
		imprimirReporteCliente: function() {
			var rec = this.sm.getSelected();
			var data = rec.data;
			if (data) {
				Phx.CP.loadingShow();
				Ext.Ajax.request({
					url : '../../sis_cobros/control/CobroRecibo/cobroReporteFactura',
					params : {
						'razon_social':data.razon_social,
					    'id_doc_compra_venta' :'',
					     'formato':'pdf'
					},
					success : this.successExport,
					failure : Phx.CP.conexionFailure,
					timeout : this.timeout,
					scope : this
				});
			}

		},
    	
 			imprimirReporteExcel: function() {
			var rec = this.sm.getSelected();
			var data = rec.data;
			if (data) {
				Phx.CP.loadingShow();
				Ext.Ajax.request({
					url : '../../sis_cobros/control/CobroRecibo/cobroReporteFactura',
					params : {
						'id_doc_compra_venta':data.id_doc_compra_venta,
					    'razon_social' :'',
					    'formato':'xls'
					},
					success : this.successExport,
					failure : Phx.CP.conexionFailure,
					timeout : this.timeout,
					scope : this
				});
			}

		},
			imprimirReporteClienteExcel: function() {
			var rec = this.sm.getSelected();
			var data = rec.data;
			if (data) {
				Phx.CP.loadingShow();
				Ext.Ajax.request({
					url : '../../sis_cobros/control/CobroRecibo/cobroReporteFactura',
					params : {
						'razon_social':data.razon_social,
					    'id_doc_compra_venta' :'',
					    'formato':'xls'
					},
					success : this.successExport,
					failure : Phx.CP.conexionFailure,
					timeout : this.timeout,
					scope : this
				});
			}

		},

	
		imprimirReporteVenta: function(){
		var data = this.getSelectedData();
		var win = Phx.CP.loadWindows(
			'../../../sis_ventas_facturacion/vista/reporte_resumen_ventas/ReporteResumenVentas.php',
			'Reporte', {
			    width: '40%',
			    height: '50%'
			},
			data,
			this.idContenedor,
			'ReporteResumenVentas'//clase de la vista
			);
		},
   
    	tabsouth: [{
		 url:'../../../sis_ventas_facturacion/vista/reporteVenta/Detalle.php',
          title:'Dertalle', 
          width:'100%',
          height:'50%',
          cls:'Detalle'
	}], 

	
  
    
})
</script>