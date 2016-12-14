<?php
/**
*@package pXP
*@file gen-FormulaDetalle.php
*@author  (admin)
*@date 21-04-2015 13:16:56
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormulaDetalle=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.FormulaDetalle.superclass.constructor.call(this,config);
		this.init();
		//this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_formula_detalle'
			},
			type:'Field',
			form:true 
		},
		{
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_formula'
            },
            type:'Field',
            form:true 
        },
		{
            config : {
                name : 'id_item',
                fieldLabel : 'Item',
                allowBlank : false,
                emptyText : 'Elija un Item...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_almacenes/control/Item/listarItemNotBase',
                    id : 'id_item',
                    root : 'datos',
                    sortInfo : {
                        field : 'nombre',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_item', 'nombre', 'codigo', 'desc_clasificacion', 'codigo_unidad'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'item.nombre#item.codigo#cla.nombre'
                    }
                }),
                valueField : 'id_item',
                displayField : 'nombre',
                gdisplayField : 'nombre_item',
                tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre}</p><p>Código: {codigo}</p><p>Clasif.: {desc_clasificacion}</p></div></tpl>',
                hiddenName : 'id_item',
                forceSelection : true,
                typeAhead : false,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,
                anchor : '100%',
                gwidth : 250,
                minChars : 2,
                turl : '../../../sis_almacenes/vista/item/BuscarItem.php',
                tasignacion : true,
                tname : 'id_item',
                ttitle : 'Items',
                tdata : {},
                tcls : 'BuscarItem',
                pid : this.idContenedor,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['nombre_item']);
                },
                resizable: true
            },
            type : 'TrigguerCombo',
            id_grupo : 1,
            filters : {
                pfiltro : 'item.nombre',
                type : 'string'
            },
            grid : true,
            form : true,
            bottom_filter: true
        },
		
		{
			config:{
				name: 'cantidad',
				fieldLabel: 'cantidad',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				allowDecimals: true,
				decimalPrecision : 6,
				maxLength:1179650
			},
				type:'NumberField',
				filters:{pfiltro:'fordet.cantidad',type:'numeric'},
				id_grupo:1,
				grid:true,
				egrid:true,
				form:true
		},
		
		{
            config:{
                name: 'precio_unitario',
                fieldLabel: 'Precio Unitario',                
                gwidth: 120
            },
                type:'NumberField', 
                grid:true,
                form:false
        },
        
        {
            config:{
                name: 'precio',
                fieldLabel: 'Precio Total',                
                gwidth: 120
            },
                type:'NumberField', 
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
				filters:{pfiltro:'fordet.estado_reg',type:'string'},
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
				filters:{pfiltro:'fordet.fecha_reg',type:'date'},
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
				filters:{pfiltro:'fordet.usuario_ai',type:'string'},
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
				filters:{pfiltro:'fordet.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				filters:{pfiltro:'fordet.fecha_mod',type:'date'},
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
		}
	],
	tam_pag:50,	
	title:'Formula Detalle',
	ActSave:'../../sis_ventas_facturacion/control/FormulaDetalle/insertarFormulaDetalle',
	ActDel:'../../sis_ventas_facturacion/control/FormulaDetalle/eliminarFormulaDetalle',
	ActList:'../../sis_ventas_facturacion/control/FormulaDetalle/listarFormulaDetalle',
	id_store:'id_formula_detalle',
	fields: [
		{name:'id_formula_detalle', type: 'numeric'},
		{name:'id_item', type: 'numeric'},
		{name:'nombre_item', type: 'string'},
		{name:'id_formula', type: 'numeric'},
		{name:'precio_unitario', type: 'numeric'},
		{name:'precio', type: 'numeric'},
		{name:'cantidad', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_formula_detalle',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	onReloadPage : function(m) {
        this.maestro=m;
        this.Atributos[1].valorInicial = this.maestro.id_formula;
        this.store.baseParams={id_formula:this.maestro.id_formula};
        this.load({params:{start:0, limit:50}});
            
    },
	}
)
</script>
		
		