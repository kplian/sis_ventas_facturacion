<?php
/**
*@package pXP
*@file gen-TipoVenta.php
*@author  (jrivera)
*@date 22-03-2016 15:29:00
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.TipoVenta=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.TipoVenta.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_tipo_venta'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'codigo',
				fieldLabel: 'Código',
				allowBlank: false,
				anchor: '80%',
				gwidth: 150,
				maxLength:80
			},
				type:'TextField',
				filters:{pfiltro:'tipven.codigo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nombre',
				fieldLabel: 'Nombre',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'tipven.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
            config:{
                name: 'tipo_base',
                fieldLabel: 'Tipo Factura',
                allowBlank: false,
                anchor: '40%',
                gwidth: 130,
                maxLength:20,
                emptyText:'tipo...',                   
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                store:['recibo','manual','computarizada']
            },
            type:'ComboBox',            
            id_grupo:1,
            filters:{   
                         type: 'list',
                         pfiltro:'tipven.tipo_base',
                         options: ['recibo','manual','computarizada']
                    },
            grid:true,
            form:true
        },
		{
			config:{
				name: 'codigo_relacion_contable',
				fieldLabel: 'Código Relacion Contable',
				allowBlank: true,
				anchor: '80%',
				gwidth: 120,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'tipven.codigo_relacion_contable',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
	            config:{
	                name: 'id_plantilla',
	                fieldLabel: 'Tipo Documento',
	                allowBlank: false,
	                emptyText:'Elija una plantilla...',
	                store:new Ext.data.JsonStore(
	                {
	                    url: '../../sis_parametros/control/Plantilla/listarPlantilla',
	                    id: 'id_plantilla',
	                    root:'datos',
	                    sortInfo:{
	                        field:'desc_plantilla',
	                        direction:'ASC'
	                    },
	                    totalProperty:'total',
	                    fields: ['id_plantilla','nro_linea','desc_plantilla','tipo',
	                    'sw_tesoro', 'sw_compro','sw_monto_excento','sw_descuento',
	                    'sw_autorizacion','sw_codigo_control','tipo_plantilla','sw_nro_dui','sw_ice'],
	                    remoteSort: true,
	                    baseParams:{par_filtro:'plt.desc_plantilla',sw_compro:'si',sw_tesoro:'si'}
	                }),
	                tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_plantilla}</p></div></tpl>',
	                valueField: 'id_plantilla',
	                hiddenValue: 'id_plantilla',
	                displayField: 'desc_plantilla',
	                gdisplayField:'desc_plantilla',
	                listWidth:'280',
	                forceSelection:true,
	                typeAhead: false,
	                triggerAction: 'all',
	                lazyRender:true,
	                mode:'remote',
	                pageSize:20,
	                queryDelay:500,
	               
	                gwidth: 250,
	                minChars:2
	            },
	            type:'ComboBox',
	            filters:{pfiltro:'pla.desc_plantilla',type:'string'},
	            id_grupo: 0,
	            grid: true,
	            bottom_filter: true,
	            form: true
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
				filters:{pfiltro:'tipven.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'tipven.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'tipven.fecha_reg',type:'date'},
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
				filters:{pfiltro:'tipven.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
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
				filters:{pfiltro:'tipven.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Tipo de Venta',
	ActSave:'../../sis_ventas_facturacion/control/TipoVenta/insertarTipoVenta',
	ActDel:'../../sis_ventas_facturacion/control/TipoVenta/eliminarTipoVenta',
	ActList:'../../sis_ventas_facturacion/control/TipoVenta/listarTipoVenta',
	id_store:'id_tipo_venta',
	fields: [
		{name:'id_tipo_venta', type: 'numeric'},
		{name:'id_plantilla', type: 'numeric'},
		{name:'codigo_relacion_contable', type: 'string'},
		{name:'desc_plantilla', type: 'string'},
		{name:'nombre', type: 'string'},
		{name:'tipo_base', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'codigo', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_tipo_venta',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true
	}
)
</script>
		
		