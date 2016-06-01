<?php
/**
*@package pXP
*@file gen-VentaDetalle.php
*@author  (admin)
*@date 01-06-2015 09:21:07
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.VentaDetalle=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
	    this.config = config;
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.VentaDetalle.superclass.constructor.call(this,config);
		this.init();
		this.iniciarEventos();		
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_venta_detalle'
			},
			type:'Field',
			form:true 
		},
		
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
            config:{
                name: 'tipo',
                fieldLabel: 'Tipo detalle',
                allowBlank:false,
                emptyText:'Tipo...',
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                gwidth: 120,
                store:['producto_terminado','formula', 'servicio']
            },
                type:'ComboBox',
                filters:{   
                         type: 'list',
                         options: ['producto_terminado','formula', 'servicio'], 
                    },
                id_grupo:1,
                grid:true,
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
                    fields : ['id_item', 'nombre', 'codigo', 'desc_clasificacion', 'codigo_unidad','precio_ref'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'item.nombre#item.codigo#cla.nombre'
                    }
                }),
                valueField : 'id_item',
                displayField : 'nombre',
                gdisplayField : 'nombre_item',
                tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre}</p><p>Código: {codigo}</p><p>Precio.: {precio_ref}</p></div></tpl>',
                hiddenName : 'id_item',
                forceSelection : true,
                typeAhead : false,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,
                anchor : '100%',
                gwidth : 220,
                minChars : 2,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['nombre_item']);
                },
                resizable: true
            },
            type : 'ComboBox',
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
			config: {
				name: 'id_sucursal_producto',
				fieldLabel: 'Servicio',
				allowBlank: false,
				emptyText: 'Servicios...',
				store: new Ext.data.JsonStore({
					url: '../../sis_ventas_farmacia/control/SucursalProducto/listarSucursalProducto',
					id: 'id_sucursal_producto',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_sucursal_producto', 'nombre_producto', 'precio'],
					remoteSort: true,
					baseParams: {par_filtro: 'sprod.nombre_producto'}
				}),
				valueField: 'id_sucursal_producto',
				displayField: 'nombre_producto',
				gdisplayField: 'nombre_producto',
				hiddenName: 'id_sucursal_producto',
				forceSelection: true,
				tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre_producto}</p><p>Precio.: {precio}</p></div></tpl>',
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['nombre_producto']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'sprod.nombre_producto',type: 'string'},
			grid: true,
			form: true,
            bottom_filter: true
		},
		{
            config : {
                name : 'id_formula',
                fieldLabel : 'Formula',
                allowBlank : false,
                emptyText : 'Elija una formula...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_ventas_farmacia/control/Formula/listarFormula',
                    id : 'id_formula',
                    root : 'datos',
                    sortInfo : {
                        field : 'nombre',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_formula', 'nombre', 'descripcion', 'precio'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'form.nombre#form.descripcion'
                    }
                }),
                valueField : 'id_formula',
                displayField : 'nombre',
                gdisplayField : 'nombre_formula',
                tpl : '<tpl for="."><div class="x-combo-list-item"><p>Nombre: {nombre}</p><p>Descripción: {descripcion}</p><p>Precio.: {precio}</p></div></tpl>',
                hiddenName : 'id_formula',
                forceSelection : true,
                typeAhead : false,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,
                anchor : '100%',
                gwidth : 220,
                minChars : 2,
                turl : '../../../sis_ventas_farmacia/vista/formula/Formula.php',  
                tasignacion : true,           
                tname : 'id_formula',
                ttitle : 'Formula',
                tdata : {},
                tcls : 'Formula',
                pid : this.idContenedor,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['nombre_formula']);
                },
                resizable: true
            },
            type : 'TrigguerCombo',
            id_grupo : 1,
            filters : {
                pfiltro : 'form.nombre',
                type : 'string'
            },
            grid : true,
            form : true,
            bottom_filter: true
        },
		
		
		{
			config:{
				name: 'cantidad',
				fieldLabel: 'Cantidad',
				allowBlank: false,
				anchor: '80%',
				gwidth: 80,
				maxLength:4
			},
				type:'NumberField',
				filters:{pfiltro:'vedet.cantidad',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		
		{
            config:{
                name: 'precio',
                fieldLabel: 'P / Unit',               
                gwidth: 90
            },
                type:'NumberField',                              
                grid:true,
                form:false
        },
		{
			config:{
				name: 'precio_total',
				fieldLabel: 'Total',				
				gwidth: 100
			},
				type:'NumberField',
				filters:{pfiltro:'vedet.precio',type:'numeric'},				
				grid:true,
				form:false
		},
		{
            config:{
                name: 'sw_porcentaje_formula',
                fieldLabel: 'Comisión',
                allowBlank:false,
                emptyText:'Aplicar Comisión...',                
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                gwidth: 70,
                store:['no','si'],
                disabled :true
            },
                type:'ComboBox',
                filters:{   
                         type: 'list',
                         options: ['no','si'], 
                    },
                id_grupo:1,
                grid:true,
                form:true
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
                filters:{pfiltro:'vedet.estado_reg',type:'string'},
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
				filters:{pfiltro:'vedet.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				filters:{pfiltro:'vedet.usuario_ai',type:'string'},
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
				filters:{pfiltro:'vedet.fecha_reg',type:'date'},
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
				filters:{pfiltro:'vedet.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Detalle de Venta',
	ActSave:'../../sis_ventas_farmacia/control/VentaDetalle/insertarVentaDetalle',
	ActDel:'../../sis_ventas_farmacia/control/VentaDetalle/eliminarVentaDetalle',
	ActList:'../../sis_ventas_farmacia/control/VentaDetalle/listarVentaDetalle',
	id_store:'id_venta_detalle',
	fields: [
		{name:'id_venta_detalle', type: 'numeric'},
		{name:'id_venta', type: 'numeric'},
		{name:'id_item', type: 'numeric'},
		{name:'nombre_item', type: 'string'},
		{name:'nombre_formula', type: 'string'},
		{name:'nombre_producto', type: 'string'},
		{name:'id_sucursal_producto', type: 'numeric'},
		{name:'id_formula', type: 'numeric'},
		{name:'tipo', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'cantidad', type: 'numeric'},
		{name:'precio', type: 'numeric'},
		{name:'precio_total', type: 'numeric'},
		{name:'sw_porcentaje_formula', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_venta_detalle',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	onReloadPage : function(m) {
        this.maestro=m;
        this.Atributos[1].valorInicial = this.maestro.id_venta;
        this.Cmp.id_item.store.baseParams.id_sucursal = this.maestro.id_sucursal;
        this.Cmp.id_sucursal_producto.store.baseParams.id_sucursal = this.maestro.id_sucursal;
        this.store.baseParams={id_venta:this.maestro.id_venta};
        this.load({params:{start:0, limit:50}});
            
    },
    successDel:function(resp){
        //console.log(resp)
        Phx.CP.loadingHide();
        Phx.CP.getPagina(this.config.idContenedorPadre).reload();
        //this.sm.fireEvent('rowdeselect',this.sm);
        this.reload();
        
    },

    // funcion que corre cuando se guarda con exito
    successSave:function(resp){
        Phx.vista.VentaDetalle.superclass.successSave.call(this,resp); 
        
        Phx.CP.getPagina(this.config.idContenedorPadre).reload();

    },
    loadValoresIniciales:function()
    {
        this.Cmp.id_venta.setValue(this.maestro.id_venta); 
        this.Cmp.sw_porcentaje_formula.setValue('no');    
        Phx.vista.VentaDetalle.superclass.loadValoresIniciales.call(this);        
    },
    onButtonNew:function(){
        //llamamos primero a la funcion new de la clase padre por que reseta el valor los componentes
        this.ocultarComponente(this.Cmp.id_formula);        
        
        this.ocultarComponente(this.Cmp.id_sucursal_producto);        
        
        this.ocultarComponente(this.Cmp.id_item);
        
        Phx.vista.VentaDetalle.superclass.onButtonNew.call(this);
    },
    
    cambiarCombo : function (tipo) {
        if (tipo == 'formula') {
            this.mostrarComponente(this.Cmp.id_formula);
            this.Cmp.id_formula.allowBlank = false;
            
            this.ocultarComponente(this.Cmp.id_sucursal_producto);
            this.Cmp.id_sucursal_producto.allowBlank = true;
            this.Cmp.id_sucursal_producto.reset();
            
            this.ocultarComponente(this.Cmp.id_item);
            this.Cmp.id_item.allowBlank = true;
            this.Cmp.id_item.reset();
            
            this.Cmp.sw_porcentaje_formula.setDisabled(false);
            this.Cmp.sw_porcentaje_formula.setValue('si');
        } else if (tipo == 'producto_terminado') {
            this.ocultarComponente(this.Cmp.id_formula);
            this.Cmp.id_formula.allowBlank = true;
            this.Cmp.id_formula.reset();
            
            this.ocultarComponente(this.Cmp.id_sucursal_producto);
            this.Cmp.id_sucursal_producto.allowBlank = true;
            this.Cmp.id_sucursal_producto.reset();
            
            this.mostrarComponente(this.Cmp.id_item);
            this.Cmp.id_item.allowBlank = false;
            
            this.Cmp.sw_porcentaje_formula.setDisabled(true);
            this.Cmp.sw_porcentaje_formula.setValue('no');
        } else {
            this.ocultarComponente(this.Cmp.id_formula);
            this.Cmp.id_formula.allowBlank = true;
            this.Cmp.id_formula.reset();
            
            this.mostrarComponente(this.Cmp.id_sucursal_producto);
            this.Cmp.id_sucursal_producto.allowBlank = false;
            
            this.ocultarComponente(this.Cmp.id_item);
            this.Cmp.id_item.allowBlank = true;
            this.Cmp.id_item.reset();
            
            this.Cmp.sw_porcentaje_formula.setDisabled(true);
            this.Cmp.sw_porcentaje_formula.setValue('no');
        }
    },
    iniciarEventos : function () {
        this.Cmp.tipo.on('select',function(c,r,i) {
            this.cambiarCombo(r.data.field1);
        },this);
        
        this.Cmp.id_formula.on('select',function(c,r,i) {
            if (r.data.precio == '' || r.data.precio == undefined) {
                alert('La formula seleccionada no tiene ningun detalle y no puede ser utilizada');
                this.Cmp.id_formula.reset();
            }
        },this);
    },
    onButtonEdit : function () {
        this.cambiarCombo(this.Cmp.tipo.getValue());
        Phx.vista.VentaDetalle.superclass.onButtonEdit.call(this);
        this.cambiarCombo(this.Cmp.tipo.getValue());
       
   },
   arrayDefaultColumHidden:['estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod'],
    
    preparaMenu:function()
    {   
        Phx.vista.VentaDetalle.superclass.preparaMenu.call(this);
        if (this.maestro.estado != 'borrador') {
            this.getBoton('save').disable();
            this.getBoton('new').disable();
            this.getBoton('edit').disable();
            this.getBoton('del').disable();
        }
    },
    
    liberaMenu:function()
    {   
           
        Phx.vista.VentaDetalle.superclass.liberaMenu.call(this);
        if (this.maestro.estado != 'borrador') {            
            this.getBoton('new').disable();
            this.getBoton('save').disable();            
        }
    }
    
	}
)
</script>
		
		