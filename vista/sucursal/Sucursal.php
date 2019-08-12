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
    	this.initButtons=[this.cmbEntidad];
		Phx.vista.Sucursal.superclass.constructor.call(this,config);
		this.init();
		this.iniciarEventos();
		this.addButton('btnMonedas',
            {
                text: 'Monedas',
                iconCls: 'bmoney',
                disabled: true,                
                handler: this.onButtonMonedas,
                tooltip: 'Monedas'                
            }
        );
        
        this.addButton('btnDosificaciones',
            {
                text: 'Dosificaciones',
                iconCls: 'blist',
                disabled: true,                
                handler: this.onButtonDosificaciones,
                tooltip: 'Dosificaciones para la sucursal'                
            }
        );
        
        this.addButton('btnPVenta',
            {
                text: 'P. Venta',
                iconCls: 'blist',
                disabled: true,                
                handler: this.onButtonPVenta,
                tooltip: 'Puntos de Venta'                
            }
        );
		
	},
	onButtonMonedas : function() {
        var rec = {maestro: this.sm.getSelected().data};
                              
            Phx.CP.loadWindows('../../../sis_ventas_facturacion/vista/sucursal_moneda/SucursalMoneda.php',
                    'Monedas por sucursal',
                    {
                        width:600,
                        height:'80%'
                    },
                    rec,
                    this.idContenedor,
                    'SucursalMoneda');
    },
    
    onButtonDosificaciones : function() {
        var rec = {maestro: this.sm.getSelected().data};
                              
            Phx.CP.loadWindows('../../../sis_ventas_facturacion/vista/dosificacion/Dosificacion.php',
                    'Dosificaciones por sucursal',
                    {
                        width:800,
                        height:'80%'
                    },
                    rec,
                    this.idContenedor,
                    'Dosificacion');
    },
    
    onButtonPVenta : function() {
        var rec = {maestro: this.sm.getSelected().data};
                              
            Phx.CP.loadWindows('../../../sis_ventas_facturacion/vista/punto_venta/PuntoVenta.php',
                    'Puntos de Venta',
                    {
                        width:800,
                        height:'80%'
                    },
                    rec,
                    this.idContenedor,
                    'PuntoVenta');
    },
    
	cmbEntidad:new Ext.form.ComboBox({
            store: new Ext.data.JsonStore({

                url: '../../sis_parametros/control/Entidad/listarEntidad',
                id: 'id_entidad',
                root: 'datos',
                sortInfo:{
                    field: 'nombre',
                    direction: 'ASC'
                },
                totalProperty: 'total',
                fields: [
                    {name:'id_entidad'},
                    {name:'nombre', type: 'string'},
                    {name:'nit', type: 'string'}
                ],
                remoteSort: true,
                baseParams:{start:0,limit:10}
            }),
            displayField: 'nombre',
            valueField: 'id_entidad',
            typeAhead: false,
            mode: 'remote',
            triggerAction: 'all',
            emptyText:'Entidad...',
            selectOnFocus:true,
            width:135,
            resizable : true
        }),
			
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
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_entidad'
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
                name: 'nombre_comprobante',
                fieldLabel: 'Nombre en Comprobante',
                qtip:'El nombre de la sucursal tal como se mostrara en el comprobante de venta. Debe incluir el nombre de la empresa',
                allowBlank: true,
                anchor: '100%',
                gwidth: 230,
                maxLength:200
            },
                type:'TextField',
                filters:{pfiltro:'suc.nombre_comprobante',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },
        {
   			config:{
   				name:'id_depto',
   				 hiddenName: 'id_depto',
   				 //url: '../../sis_parametros/control/Depto/listarDeptoFiltradoXUsuario',
	   				origen:'DEPTO',
	   				allowBlank:false,
	   				fieldLabel: 'Depto',
	   				gdisplayField:'desc_depto',//dibuja el campo extra de la consulta al hacer un inner join con orra tabla
	   				width:250,
   			        gwidth:180,
	   				baseParams:{tipo_filtro:'DEPTO_UO',estado:'activo',codigo_subsistema:'VEF'},//parametros adicionales que se le pasan al store
	      			renderer:function (value, p, record){return String.format('{0}', record.data['nombre_depto']);}
   			},
   			//type:'TrigguerCombo',
   			type:'ComboRec',
   			id_grupo:0,
   			filters:{pfiltro:'depto.nombre',type:'string'},
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
                maxLength:100
            },
                type:'TextField',
                filters:{pfiltro:'suc.telefono',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },
        {
            config:{
                name: 'direccion',
                fieldLabel: 'Direccion',
                allowBlank: true,
                anchor: '100%',
                gwidth: 100,
                maxLength:255
            },
                type:'TextField',
                filters:{pfiltro:'suc.direccion',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },
        
        {
            config:{
                name: 'lugar',
                fieldLabel: 'Lugar',
                allowBlank: true,
                anchor: '100%',
                gwidth: 100,
                maxLength:255
            },
                type:'TextField',
                filters:{pfiltro:'suc.lugar',type:'string'},
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
				name: 'id_lugar',
				fieldLabel: 'Lugar',
				allowBlank: false,
				emptyText:'Lugar...',
				store:new Ext.data.JsonStore(
				{
					url: '../../sis_parametros/control/Lugar/listarLugar',
					id: 'id_lugar',
					root: 'datos',
					sortInfo:{
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_lugar','id_lugar_fk','codigo','nombre','tipo','sw_municipio','sw_impuesto','codigo_largo'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{par_filtro:'lug.nombre',tipo:'departamento'}
				}),
				valueField: 'id_lugar',
				displayField: 'nombre',
				gdisplayField:'nombre_lugar',
				hiddenName: 'id_lugar',
    			triggerAction: 'all',
    			lazyRender:true,
				mode:'remote',
				pageSize:50,
				queryDelay:500,
				anchor:"100%",
				gwidth:150,
				minChars:2,
				renderer:function (value, p, record){return String.format('{0}', record.data['nombre_lugar']);}
			},
			type:'ComboBox',
			filters:{pfiltro:'lug.nombre',type:'string'},
			id_grupo:0,
			grid:true,
			form:true
		},			
		
        {
            config:{
                name: 'plantilla_documento_factura',
                fieldLabel: 'Plantilla de documento Factura',
                allowBlank: true,
                anchor: '100%',
                gwidth: 230,
                maxLength:50
            },
                type:'TextField',                
                id_grupo:1,
                grid:true,
                form:true
        },
        
        {
            config:{
                name: 'plantilla_documento_recibo',
                fieldLabel: 'Plantilla de documento Recibo',
                allowBlank: true,
                anchor: '100%',
                gwidth: 230,
                maxLength:50
            },
                type:'TextField',                
                id_grupo:1,
                grid:true,
                form:true
        },
        
        {
            config:{
                name: 'formato_comprobante',
                fieldLabel: 'Formato Comprobante',
                qtip: 'Los formatos por defecto son:FACMEDIACAR (para un formato para imprimir con el navegador),pdf-FACMEDIACAR(formato para imprimir en pdf para el uso de cualquier formato en pdf siempre colocar el prefijo(pdf-)y luego el formato correspondiente)',
                allowBlank: true,
                anchor: '100%',
                gwidth: 230,
                maxLength:50
            },
                type:'TextField',                
                id_grupo:1,
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
                name: 'habilitar_comisiones',
                fieldLabel: 'Habilitar Comisiones',
                allowBlank: false,
                anchor: '80%',
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
                         pfiltro:'suc.habilitar_comisiones',
                         options: ['si','no'],  
                    },
            grid:true,
            form:true
        },
        {
            config:{
                name:'tipo_interfaz',
                fieldLabel:'Tipo Venta',
                allowBlank:true,
                emptyText:'Tipo...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_ventas_facturacion/control/TipoVenta/listarTipoVenta',
                    id: 'codigo',
                    root: 'datos',
                    sortInfo:{
                        field: 'codigo',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['codigo','nombre'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'codigo#nombre'}

                }),
                valueField: 'codigo',
                displayField: 'nombre',
                gdisplayField: 'tipo_interfaz',
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
                renderer:function(value, p, record){return String.format('{0}', record.data['tipo_interfaz']);}

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
	ActSave:'../../sis_ventas_facturacion/control/Sucursal/insertarSucursal',
	ActDel:'../../sis_ventas_facturacion/control/Sucursal/eliminarSucursal',
	ActList:'../../sis_ventas_facturacion/control/Sucursal/listarSucursal',
	id_store:'id_sucursal',
	south : {
            url : '../../../sis_ventas_facturacion/vista/sucursal_producto/SucursalProducto.php',
            title : 'Productos',
            height : '50%',
            cls : 'SucursalProducto'
        },
    tabeast:[
          { 
              url:'../../../sis_ventas_facturacion/vista/sucursal_usuario/SucursalUsuario.php',
              title:'Usuarios', 
              width:'50%',
              cls:'SucursalUsuario'
         },
          { 
          url: '../../../sis_ventas_facturacion/vista/sucursal_almacen/SucursalAlmacen.php',
          title: 'Almacenes', 
          height: '50%',
          cls: 'SucursalAlmacen'
         },
          { 
          url: '../../../sis_ventas_facturacion/vista/tipo_descripcion/TipoDescripcion.php',
          title: 'Tipos de Atributos', 
          height: '50%',
          cls: 'TipoDescripcion'
         }],
	fields: [
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_lugar', type: 'numeric'},
		{name:'id_depto', type: 'numeric'},
		{name:'id_entidad', type: 'numeric'},
		{name:'correo', type: 'string'},
		{name:'nombre_depto', type: 'string'},
		{name:'nombre', type: 'string'},
		{name:'nombre_comprobante', type: 'string'},
		{name:'nombre_lugar', type: 'string'},
		{name:'telefono', type: 'string'},
		{name:'direccion', type: 'string'},
		{name:'lugar', type: 'string'},
		{name:'tiene_precios_x_sucursal', type: 'string'},
		{name:'habilitar_comisiones', type: 'string'},
		{name:'plantilla_documento_factura', type: 'string'},
		{name:'plantilla_documento_recibo', type: 'string'},
		{name:'formato_comprobante', type: 'string'},		
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
		{name:'usr_mod', type: 'string'},'tipo_interfaz'
		
	],
	sortInfo:{
		field: 'id_sucursal',
		direction: 'ASC'
	},
	iniciarEventos : function () {
	    
	    this.cmbEntidad.store.load({params:{start:0,limit:this.tam_pag}, 
           callback : function (r) {
                if (r.length == 1 ) {                       
                    this.cmbEntidad.setValue(r[0].data.id_entidad);  
                    this.cmbEntidad.fireEvent('select', r[0]);                  
                }    
                                
            }, scope : this
        });
        this.cmbEntidad.on('select', function(c,r,i) {            
            this.store.baseParams.id_entidad = this.cmbEntidad.getValue();
            this.load({params:{start:0, limit:this.tam_pag}});
            
        } , this);
	},
	loadValoresIniciales:function()
    {
        this.Cmp.id_entidad.setValue(this.cmbEntidad.getValue());          
        Phx.vista.Sucursal.superclass.loadValoresIniciales.call(this);        
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
    preparaMenu:function()
    {   
        this.getBoton('btnMonedas').enable(); 
        this.getBoton('btnDosificaciones').enable(); 
        this.getBoton('btnPVenta').enable(); 
        Phx.vista.Sucursal.superclass.preparaMenu.call(this);
    },
    
    liberaMenu:function()
    {   
        this.getBoton('btnMonedas').disable(); 
        this.getBoton('btnDosificaciones').disable();
        this.getBoton('btnPVenta').disable();
        Phx.vista.Sucursal.superclass.liberaMenu.call(this);
    },
    
    arrayDefaultColumHidden:['correo','telefono','estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod','id_clasificaciones_para_formula','id_clasificaciones_para_venta'],
	}
)
</script>
		
		