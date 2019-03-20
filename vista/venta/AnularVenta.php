<?php
/**
*@package pXP
*@file gen-AnularVenta.php
*@author  (admin)
*@date 18-01-2019 14:57:46
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.AnularVenta=Ext.extend(Phx.gridInterfaz,{
	swEstado: 'finalizado',
	filtroiniciado:false,
gruposBarraTareas: [
		{
			name: 'finalizado',
			title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Finalizados</h1>',
			grupo: 0,
			height: 0
		},
		{
			name: 'anulado',
			title: '<H1 align="center"><i class="fa fa-eye"></i>Anulados</h1>',
			grupo: 1,
			height:   0
		}
	],
	constructor:function(config){
		this.maestro=config.maestro;
		
		//llama al constructor de la clase padre
		Phx.vista.AnularVenta.superclass.constructor.call(this,config);
		
		    this.grid.getTopToolbar().enable();
			this.grid.getBottomToolbar().disable();
			this.init();
			this.store.baseParams = {'estado': this.swEstado};
	    	this.addButton('anular_factura', {
	            text: 'Anular Factura',
	            iconCls: 'bupload',
	            disabled: true,
	            handler: this.BAnularFactura,
	            tooltip: '<b>Anular Factura</b><br/>Anular una factura finalizada.'
	        });
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
                name: 'correlativo_venta',
                fieldLabel: 'Nro',              
                gwidth: 110,
                renderer: function(value,c,r){  
                	
                	if (r.data.codigo_sin != '') {
                		return String.format('{0}', '<p><font color="blue">' + value + '</font></p>');
                	} else {
                		return value;
                	}  
                    
                }
            },
                type:'TextField',
                filters:{pfiltro:'ven.correlativo_venta',type:'string'},              
                grid:true,
                form:false,
                bottom_filter: true
        }, 
        {
            config:{
                name: 'nro_factura',
                fieldLabel: 'Nro Factura',              
                gwidth: 110
               
            },
                type:'TextField',
                filters:{pfiltro:'ven.nro_factura',type:'numeric'},              
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
                form:false
        },
         {
            config:{
                name: 'comision',
                fieldLabel: 'Comision',              
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
		}
		,
        {
            config:{
                name: 'codigo_sin',
                fieldLabel: 'Codigo SIN',              
                gwidth: 110
               
            },
                type:'TextField',
                filters:{pfiltro:'ven.codigo_sin',type:'string'},              
                grid:true,
                form:true,
                bottom_filter: true
        }
         , {	config : {
				name : 'codigo_motivo_anulacion',
				fieldLabel : 'Motivo Anulacion',
				allowBlank : false,
				emptyText : 'Motivo Anulacion...',
				store : new Ext.data.JsonStore({
					url : '../../sis_siat/control/MotivoAnulacion/listarMotivoAnulacion',
					id : 'codigo',
					root : 'datos',
					sortInfo : {
						field : 'codigo',
						direction : 'ASC'
					},
					totalProperty : 'total',
					fields : ['id_motivo_anulacion', 'codigo', 'descripcion'],
					// turn on remote sorting
					remoteSort : true,
					baseParams : {
						par_filtro : 'codigo#descripcion'
					}
				}),
				valueField : 'codigo',
				displayField : 'descripcion',
				gdisplayField : 'descripcion', //mapea al store del grid
				tpl : '<tpl for="."><div class="x-combo-list-item"><p>({codigo}) {descripcion}</p> </div></tpl>',
				hiddenName : 'codigo',
				forceSelection : true,
				typeAhead : true,
				triggerAction : 'all',
				lazyRender : true,
				mode : 'remote',
				pageSize : 10,
				queryDelay : 1000,
				width : 250,
				gwidth : 150,
				minChars : 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['descripcion']);
				}
			},
			type : 'ComboBox',
			id_grupo : 0,
			filters : {
				pfiltro : 'descripcion',
				type : 'string'
			},

			grid : false,
			form : true
		}
		,
		 {
			config:{
				name: 'motivo_anulacion',
				fieldLabel: 'Motivo Anulacion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 300
			},
				type:'TextArea',
				filters:{pfiltro:'ven.motivo_anulacion',type:'string'},
				id_grupo:0,
				grid:true,
				form:false
		},
		 
		{
			config : {
				name : 'fecha_sw_anular',
				fieldLabel : 'Anular Hasta',
				disabled : false,
				allowBlank : false,
				format : 'd-m-Y',
				width : 100,
				gwidth : 200,
				renderer : function(value, p, record) {
					return value ? value.dateFormat('d/m/Y H:i:s') : ''
				}
			},
			type : 'DateField',
			valorInicial : new Date(),
			filters : {
				pfiltro : 'vef.fecha_reg',
				type : 'date'
			},
			id_grupo : 1,

			grid : true,
			form : false,
			bottom_filter : true
		}
		
       
	],
	tam_pag:50,	
	title:'AnularVenta ',
	ActSave:'../../sis_ventas_facturacion/control/Venta/insertarVentaAnular',
	ActDel:'../../sis_siat/control/AnularVenta/eliminarAnularVenta',
	ActList:'../../sis_ventas_facturacion/control/Venta/listarAnularVenta',
	id_store:'id_venta',
	fields: [
	   {name:'id_venta', type: 'numeric'},
	   {name:'id_cliente', type: 'numeric'},
       {name:'id_sucursal', type: 'numeric'},
       {name:'id_proceso_wf', type: 'numeric'},
       {name:'id_estado_wf', type: 'numeric'},
       {name:'estado_reg', type: 'string'},
       {name:'correlativo_venta', type: 'string'},
       {name:'a_cuenta', type: 'string'},
       {name:'total_venta', type: 'numeric'},
       {name:'fecha_estimada', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
       {name:'usuario_ai', type: 'string'},
       {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
       {name:'id_usuario_reg', type: 'numeric'},
       {name:'id_usuario_ai', type: 'numeric'},
       {name:'id_usuario_mod', type: 'numeric'},
       {name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
       {name:'porcentaje_descuento', type: 'numeric'},
       {name:'id_vendedor_medico', type: 'numeric'},
       {name:'comision', type: 'numeric'},
       {name:'observaciones', type: 'string'},
       {name:'codigo_sin', type: 'string'},
       {name:'motivo_anulacion', type: 'string'},
	   {name:'nro_factura', type: 'integer'},
	   {name:'cod_control', type: 'varchar'},
	   {name:'fecha_sw_anular', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
       
	],
	sortInfo:{
		field: 'id_venta',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false, 
	bnew:false,
	bedit:false,
	bact:true,
	onReloadPage:function(param){
		//Se obtiene la gestión en función de la fecha del comprobante para filtrar partidas, cuentas, etc.
		var me = this;
		this.initFiltro(param);
	},
	initFiltro: function(param){
		this.filtroiniciado=true;
		this.store.baseParams=param;
		this.store.baseParams={estado:'finalizado'};
		this.load( { params: { start:0, limit: this.tam_pag } });
	},
	onButtonNew: function () {
            
             this.ocultarComponente(this.Cmp.estado_reg);
             Phx.vista.AnularVenta.superclass.onButtonNew.call(this);
            },
   
    BAnularFactura:function () {
			var rec = this.sm.getSelected();
			Phx.vista.AnularVenta.superclass.onButtonEdit.call(this);
		},
	successSave:function(resp){
		
		 var avd= Ext.util.JSON.decode(resp.responseText);
		 alert(avd.ROOT.datos.descripcion);
		 Phx.vista.AnularVenta.superclass.successSave.call(this,resp);
		
				

	},
	getParametrosFiltro: function () {
		
		
   	 	this.store.baseParams.estado = this.swEstado;
		
	},
	actualizarSegunTab: function (name, indice) {
		
		  if (this.filtroiniciado){
		  	
		    if (name=='finalizado'){
				this.swEstado = name;
				this.getBoton('act').show();
			}else{
			    this.swEstado = name;
			    this.getBoton('act').show();
			    this.getBoton('excel').show();
				
			}
			
		 	this.getParametrosFiltro();
			this.load();
          }
		
    }
	
   
	
		
})
</script>		