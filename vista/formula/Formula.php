<?php
/**
*@package pXP
*@file gen-Formula.php
*@author  (admin)
*@date 21-04-2015 09:14:49
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Formula=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Formula.superclass.constructor.call(this,config);
		this.init();
		
		this.load({params:{start:0, limit:this.tam_pag}})
	},
	formUrl : '../../../sis_ventas_facturacion/vista/formula/FormFormula.php',
	formClass : 'FormFormula',
			
	Atributos:[
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
		
		
		 /*{
            config : {
                name : 'id_tipo_presentacion',
                fieldLabel : 'Tipo de Presentacion',
                allowBlank : false,
                emptyText : 'Tipo...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_ventas_facturacion/control/TipoPresentacion/listarTipoPresentacion',
                    id : 'id_tipo_presentacion',
                    root : 'datos',
                    sortInfo : {
                        field : 'nombre',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_tipo_presentacion', 'nombre'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'tipre.nombre'
                    }
                }),
                valueField : 'id_tipo_presentacion',
                displayField : 'nombre',
                gdisplayField : 'nombre_tipo_presentacion',
                hiddenName : 'id_tipo_presentacion',
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
                    return String.format('{0}', record.data['nombre_tipo_presentacion']);
                }
            },
            type : 'ComboBox',
            id_grupo : 0,
            filters : {
                pfiltro : 'tipre.nombre',
                type : 'string'
            },
            grid : true,
            form : true
        },*/
        
        
		
				
		{
			config:{
				name: 'nombre',
				fieldLabel: 'Nombre Formula',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'form.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true,
                bottom_filter: true
		},
		{
            config:{
                name: 'descripcion',
                fieldLabel: 'Descripcion Formula',
                allowBlank: true,
                anchor: '80%',
                gwidth: 250
            },
                type:'TextArea',
                filters:{pfiltro:'form.descripcion',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter: true
        },
        
		
        {
            config:{
                name: 'precio',
                fieldLabel: 'Precio Actual',                
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
				filters:{pfiltro:'form.estado_reg',type:'string'},
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
				filters:{pfiltro:'form.usuario_ai',type:'string'},
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
				filters:{pfiltro:'form.fecha_reg',type:'date'},
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
				filters:{pfiltro:'form.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'form.fecha_mod',type:'date'},
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
	title:'Fórmula',
	ActSave:'../../sis_ventas_facturacion/control/Formula/insertarFormula',
	ActDel:'../../sis_ventas_facturacion/control/Formula/eliminarFormula',
	ActList:'../../sis_ventas_facturacion/control/Formula/listarFormula',
	id_store:'id_formula',
	fields: [
		{name:'id_formula', type: 'numeric'},
		{name:'id_tipo_presentacion', type: 'numeric'},
		{name:'id_unidad_medida', type: 'numeric'},
		{name:'precio', type: 'numeric'},
		{name:'id_medico', type: 'numeric'},
		{name:'desc_medico', type: 'string'},
		{name:'desc_unidad_medida', type: 'string'},
		{name:'nombre', type: 'string'},
		{name:'cantidad', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'descripcion', type: 'string'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_formula',
		direction: 'DESC'
	},
	
    /*south : {
            url : '../../../sis_ventas_facturacion/vista/formula_detalle/FormulaDetalle.php',
            title : 'Detalle',
            height : '50%',
            cls : 'FormulaDetalle'
    },*/
    onButtonNew : function () {
        //abrir formulario de solicitud
        this.openForm('new');        
    },
    onButtonEdit : function () {
        //abrir formulario de solicitud
        this.openForm('edit', this.sm.getSelected());        
    }, 
    openForm : function (tipo, record) {
        //abrir formulario de solicitud
           var me = this;
           me.objSolForm = Phx.CP.loadWindows(this.formUrl,
                                    'Formulario de Solicitud de Compra',
                                    {
                                        modal:true,
                                        width:'60%',
                                        height:'90%'
                                    }, {data:{objPadre: me,
                                    		tipo_form : tipo,
                                    		datos_originales: record}
                                    }, 
                                    this.idContenedor,
                                    this.formClass,
                                    {
                                        config:[{
                                                  event:'successsave',
                                                  delegate: this.onSaveForm,
                                                  
                                                }],
                                        
                                        scope:this
                                     });      
    },
	
	bdel:true,
	bsave:true,
	rowExpander: new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<br>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Descripcion:&nbsp;&nbsp;</b> {descripcion}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha de Registro:&nbsp;&nbsp;</b> {fecha_reg:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha Ult. Modificación:&nbsp;&nbsp;</b> {fecha_mod:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Creado por:&nbsp;&nbsp;</b> {usr_reg}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Modificado por:&nbsp;&nbsp;</b> {usr_mod}</p><br>'
            )
    }),
    preparaMenu:function(n){
        var data = this.getSelectedData();
        var tb =this.tbar;
        Phx.vista.Formula.superclass.preparaMenu.call(this,n);
        
        return tb;
     }, 
     liberaMenu:function(){
        var tb = Phx.vista.Formula.superclass.liberaMenu.call(this);
        
        
       return tb;
   },
   
    
    arrayDefaultColumHidden:['estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod','descripcion'],
	}
)
</script>
		
		