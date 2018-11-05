<?php
/**
*@package pXP
*@file gen-SucursalUsuario.php
*@author  (admin)
*@date 21-04-2015 07:33:37
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.SucursalUsuario=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.SucursalUsuario.superclass.constructor.call(this,config);
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
					name: 'id_sucursal_usuario'
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
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_punto_venta'
            },
            type:'Field',
            form:true 
        },
        {
            config:{
                name: 'tipo_usuario',
                fieldLabel: 'Tipo de Usuario',
                allowBlank: false,               
                gwidth: 130,
                maxLength:15,
                emptyText:'tipo...',                   
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',                                  
               // displayField: 'descestilo',
                store:['vendedor','administrador','cajero','emisor'] //#123 se agrega usuario emisor
            },
            type:'ComboBox',
            //filters:{pfiltro:'promac.inicio',type:'string'},
            id_grupo:0,
            filters:{   
                         type: 'list',
                         pfiltro:'ucusu.tipo_usuario',
                         options: ['vendedor','administrador','cajero','emisor']  //#123 se agrega usuario emisor
                    },
            grid:true,
            form:true
        },      
		{
            config : {
                name : 'id_usuario',
                fieldLabel : 'Usuario',
                allowBlank : false,
                emptyText : 'Usuario...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_seguridad/control/Usuario/listarUsuario',
                    id : 'id_usuario',
                    root : 'datos',
                    sortInfo : {
                        field : 'cuenta',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_usuario', 'cuenta', 'desc_person'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'USUARI.cuenta#PERSON.nombre_completo2'
                    }
                }),
                tpl : '<tpl for="."><div class="x-combo-list-item"><p>Cuenta: {cuenta}</p><p>Nombre: {desc_person}</p></div></tpl>',
                valueField : 'id_usuario',
                displayField : 'cuenta',
                gdisplayField : 'cuenta',
                hiddenName : 'id_usuario',
                forceSelection : true,
                typeAhead : true,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,                
                minChars : 2,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['cuenta']);
                },
            },
            type : 'ComboBox',
            id_grupo : 0,
            filters : {
                pfiltro : 'usu.cuenta',
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
				filters:{pfiltro:'sucusu.estado_reg',type:'string'},
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
				filters:{pfiltro:'sucusu.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'sucusu.fecha_reg',type:'date'},
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
				filters:{pfiltro:'sucusu.usuario_ai',type:'string'},
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
				filters:{pfiltro:'sucusu.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Usuarios',
	ActSave:'../../sis_ventas_facturacion/control/SucursalUsuario/insertarSucursalUsuario',
	ActDel:'../../sis_ventas_facturacion/control/SucursalUsuario/eliminarSucursalUsuario',
	ActList:'../../sis_ventas_facturacion/control/SucursalUsuario/listarSucursalUsuario',
	id_store:'id_sucursal_usuario',
	fields: [
		{name:'id_sucursal_usuario', type: 'numeric'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_punto_venta', type: 'numeric'},
		{name:'id_usuario', type: 'numeric'},
		{name:'cuenta', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'tipo_usuario', type: 'string'},
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
		field: 'id_sucursal_usuario',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	onReloadPage:function(m)
    {
        this.maestro=m; 
        if(this.maestro.hasOwnProperty('id_punto_venta')){
		    this.store.baseParams.id_punto_venta = this.maestro.id_punto_venta;
		} else if (this.maestro.hasOwnProperty('id_sucursal')){
		    this.store.baseParams.id_sucursal = this.maestro.id_sucursal;
		} 
		
		                   
        
        
        this.load({params:{start:0, limit:50}});            
    },
    loadValoresIniciales:function()
    {
        Phx.vista.SucursalUsuario.superclass.loadValoresIniciales.call(this);
        if(this.maestro.hasOwnProperty('id_punto_venta')){
		    this.getComponente('id_punto_venta').setValue(this.maestro.id_punto_venta);
		
		} else if (this.maestro.hasOwnProperty('id_sucursal')){
		    this.getComponente('id_sucursal').setValue(this.maestro.id_sucursal);
		}  
        
              
    }
	}
)
</script>
		
		