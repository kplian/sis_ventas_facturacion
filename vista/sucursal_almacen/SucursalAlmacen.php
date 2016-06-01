<?php
/**
*@package pXP
*@file gen-SucursalAlmacen.php
*@author  (admin)
*@date 21-04-2015 07:33:41
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.SucursalAlmacen=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.SucursalAlmacen.superclass.constructor.call(this,config);
		this.init();
		var dataPadre = Phx.CP.getPagina(this.idContenedorPadre).getSelectedData();
        if(dataPadre){
            this.onEnablePanel(this, dataPadre);
        }
        else
        {
           this.bloquearMenus();
        }
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_sucursal_almacen'
			},
			type:'Field',
			form:true 
		},
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
                name: 'tipo_almacen',
                fieldLabel: 'Tipo de Almacen',
                allowBlank: false,                
                gwidth: 130,
                maxLength:15,
                emptyText:'tipo...',                   
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
               // displayField: 'descestilo',
                store:['ventas','produccion']
            },
            type:'ComboBox',
            //filters:{pfiltro:'promac.inicio',type:'string'},
            id_grupo:1,
            filters:{   
                         type: 'list',
                         pfiltro:'sucalm.tipo_almacen',
                         options: ['ventas','produccion']
                    },
            grid:true,
            form:true
        },      
        
        {
            config : {
                name : 'id_almacen',
                fieldLabel : 'Almacén',
                allowBlank : false,
                emptyText : 'Almacen...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_almacenes/control/Almacen/listarAlmacen',
                    id : 'id_almacen',
                    root : 'datos',
                    sortInfo : {
                        field : 'nombre',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_almacen', 'nombre'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'alm.nombre'
                    }
                }),
                valueField : 'id_almacen',
                displayField : 'nombre',
                gdisplayField : 'nombre_almacen',
                hiddenName : 'id_almacen',
                forceSelection : true,
                typeAhead : false,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,                
                gwidth : 150,
                minChars : 2,
                renderer: function(value, p, record){                    
                    return String.format('{0}', record.data['nombre_almacen']);
                }
            },
            type : 'ComboBox',
            id_grupo : 0,
            filters : {
                pfiltro : 'alm.nombre',
                type : 'string'
            },
            grid : true,
            form : true
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
				filters:{pfiltro:'sucalm.estado_reg',type:'string'},
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
				filters:{pfiltro:'sucalm.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				filters:{pfiltro:'sucalm.fecha_reg',type:'date'},
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
				filters:{pfiltro:'sucalm.usuario_ai',type:'string'},
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
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'sucalm.fecha_mod',type:'date'},
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
	title:'Almacenes',
	ActSave:'../../sis_ventas_facturacion/control/SucursalAlmacen/insertarSucursalAlmacen',
	ActDel:'../../sis_ventas_facturacion/control/SucursalAlmacen/eliminarSucursalAlmacen',
	ActList:'../../sis_ventas_facturacion/control/SucursalAlmacen/listarSucursalAlmacen',
	id_store:'id_sucursal_almacen',
	fields: [
		{name:'id_sucursal_almacen', type: 'numeric'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_almacen', type: 'numeric'},
		{name:'nombre_almacen', type: 'string'},
		{name:'tipo_almacen', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_sucursal_almacen',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	onReloadPage:function(m)
    {
        this.maestro=m;                     
        this.store.baseParams={id_sucursal:this.maestro.id_sucursal};
        this.load({params:{start:0, limit:50}});            
    },
    loadValoresIniciales:function()
    {
        Phx.vista.SucursalUsuario.superclass.loadValoresIniciales.call(this);
        this.getComponente('id_sucursal').setValue(this.maestro.id_sucursal);
              
    }
})
</script>
		
		