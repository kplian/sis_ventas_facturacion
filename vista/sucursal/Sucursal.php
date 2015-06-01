<?php
/**
*@package pXP
*@file gen-Sucursal.php
*@author  (admin)
*@date 20-04-2015 15:07:50
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Sucursal=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Sucursal.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_sucursal'
			},
			type:'Field',
			form:true 
		},
		{
            config:{
                name: 'codigo',
                fieldLabel: 'Código',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:20
            },
                type:'TextField',
                filters:{pfiltro:'suc.codigo',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },
        
        {
            config:{
                name: 'nombre',
                fieldLabel: 'Nombre',
                allowBlank: true,
                anchor: '100%',
                gwidth: 230,
                maxLength:200
            },
                type:'TextField',
                filters:{pfiltro:'suc.nombre',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },
        {
            config:{
                name: 'telefono',
                fieldLabel: 'Telefono',
                allowBlank: true,
                anchor: '100%',
                gwidth: 100,
                maxLength:50
            },
                type:'TextField',
                filters:{pfiltro:'suc.telefono',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },
		{
			config:{
				name: 'correo',
				fieldLabel: 'Correo',
				allowBlank: true,
				anchor: '100%',
				vtype:'email',  
				gwidth: 100,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'suc.correo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
            config:{
                name: 'tiene_precios_x_sucursal',
                fieldLabel: 'Precios por Sucursal',
                allowBlank: false,
                anchor: '40%',
                gwidth: 130,
                maxLength:2,
                emptyText:'si/no...',                   
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
               // displayField: 'descestilo',
                store:['si','no']
            },
            type:'ComboBox',
            //filters:{pfiltro:'promac.inicio',type:'string'},
            id_grupo:1,
            filters:{   
                         type: 'list',
                         pfiltro:'tipes.tiene_precios_x_sucursal',
                         options: ['si','no'],  
                    },
            grid:true,
            form:true
        },				
		
        
        {
            config:{
                    name:'id_clasificaciones_para_formula',
                    fieldLabel:'Clasificaciones para Fórmula',
                    qtip:'En este campo se define que items de la clasificación se mostrarán para la elaboración de fórmulas',
                    tinit:false,
                    resizable:true,
                    tasignacion:false,
                    allowBlank:true,
                    store: new Ext.data.JsonStore({
                            url: '../../sis_almacenes/control/Clasificacion/listarClasificacion',
                            id: 'id_clasificacion',
                            root: 'datos',
                            sortInfo:{
                                field: 'nombre',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_clasificacion','codigo_largo','nombre'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams: {par_filtro:'nombre',primer_nivel :'si'}
                        }),
                    
                    valueField: 'id_clasificacion',
                    displayField: 'nombre',
                    gdisplayField: 'desc_clasificaciones_para_formula',
                    hiddenName: 'id_clasificaciones_para_formula',
                    forceSelection:true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode:'remote',
                    pageSize:10,
                    queryDelay:1000,
                    width:250,
                    enableMultiSelect:true,
                    minChars:2
                },
                type:'AwesomeCombo',
                id_grupo:0,
                grid:true,
                form:true
        },
        
        {
            config:{
                    name:'id_clasificaciones_para_venta',
                    fieldLabel:'Clasificaciones para Venta',
                    qtip:'En este campo se define que items de la clasificación se mostrarán para ventas',
                    tinit:false,
                    resizable:true,
                    tasignacion:false,
                    allowBlank:true,
                    store: new Ext.data.JsonStore({
                            url: '../../sis_almacenes/control/Clasificacion/listarClasificacion',
                            id: 'id_clasificacion',
                            root: 'datos',
                            sortInfo:{
                                field: 'nombre',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_clasificacion','codigo_largo','nombre'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams: {par_filtro:'nombre',primer_nivel :'si'}
                        }),
                    
                    valueField: 'id_clasificacion',
                    displayField: 'nombre',
                    gdisplayField: 'desc_clasificaciones_para_venta',
                    hiddenName: 'id_clasificaciones_para_venta',
                    forceSelection:true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode:'remote',
                    pageSize:10,
                    queryDelay:1000,
                    width:250,
                    enableMultiSelect:true,
                    minChars:2
                },
                type:'AwesomeCombo',
                id_grupo:0,
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
				filters:{pfiltro:'suc.estado_reg',type:'string'},
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
				filters:{pfiltro:'suc.id_usuario_ai',type:'numeric'},
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
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'suc.usuario_ai',type:'string'},
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
				filters:{pfiltro:'suc.fecha_reg',type:'date'},
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
				filters:{pfiltro:'suc.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Sucursal',
	ActSave:'../../sis_ventas_farmacia/control/Sucursal/insertarSucursal',
	ActDel:'../../sis_ventas_farmacia/control/Sucursal/eliminarSucursal',
	ActList:'../../sis_ventas_farmacia/control/Sucursal/listarSucursal',
	id_store:'id_sucursal',
	south : {
            url : '../../../sis_ventas_farmacia/vista/sucursal_producto/SucursalProducto.php',
            title : 'Productos',
            height : '50%',
            cls : 'SucursalProducto'
        },
    tabeast:[
          { 
              url:'../../../sis_ventas_farmacia/vista/sucursal_usuario/SucursalUsuario.php',
              title:'Usuarios', 
              width:'50%',
              cls:'SucursalUsuario'
         },
          { 
          url:'../../../sis_ventas_farmacia/vista/sucursal_almacen/SucursalAlmacen.php',
          title:'Almacenes', 
          height:'50%',
          cls:'SucursalAlmacen'
         }],
	fields: [
		{name:'id_sucursal', type: 'numeric'},
		{name:'correo', type: 'string'},
		{name:'nombre', type: 'string'},
		{name:'telefono', type: 'string'},
		{name:'tiene_precios_x_sucursal', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_clasificaciones_para_formula', type: 'string'},
		{name:'desc_clasificaciones_para_formula', type: 'string'},
		{name:'desc_clasificaciones_para_venta', type: 'string'},
		{name:'codigo', type: 'string'},
		{name:'id_clasificaciones_para_venta', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_sucursal',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	rowExpander: new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<br>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Correo:&nbsp;&nbsp;</b> {correo}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Telefono:&nbsp;&nbsp;</b> {telefono}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Clasificaciones para fórmula:&nbsp;&nbsp;</b> {desc_clasificaciones_para_formula}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Clasificaciones para venta:&nbsp;&nbsp;</b> {desc_clasificaciones_para_venta}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha de Registro:&nbsp;&nbsp;</b> {fecha_reg:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha Ult. Modificación:&nbsp;&nbsp;</b> {fecha_mod:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Creado por:&nbsp;&nbsp;</b> {usr_reg}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Modificado por:&nbsp;&nbsp;</b> {usr_mod}</p><br>'
            )
    }),
    
    arrayDefaultColumHidden:['correo','telefono','estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod','id_clasificaciones_para_formula','id_clasificaciones_para_venta'],
	}
)
</script>
		
		