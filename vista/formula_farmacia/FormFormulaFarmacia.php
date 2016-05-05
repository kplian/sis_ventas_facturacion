<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (jrivera)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormFormulaFarmacia = {
    require:'../../../sis_ventas_facturacion/vista/formula/FormFormula.php',
	requireclase:'Phx.vista.FormFormula',
	title:'Formula',
	nombreVista: 'FormFormulaFarmacia',
	
	constructor: function(config) {	
		this.addElements();		   
        Phx.vista.FormFormulaFarmacia.superclass.constructor.call(this,config);        
  },
  addElements : function () {
    
  	this.Atributos.push({
            config : {
                name : 'id_medico',
                fieldLabel : 'Medico',
                allowBlank : false,
                emptyText : 'Medico...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_ventas_facturacion/control/Medico/listarMedico',
                    id : 'id_medico',
                    root : 'datos',
                    sortInfo : {
                        field : 'nombres',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_medico', 'nombres', 'primer_apellido', 'segundo_apellido'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'med.nombres#med.primer_apellido#med.segundo_apellido'
                    }
                }),
                valueField : 'id_medico',
                displayField : 'primer_apellido',
                tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombres} {primer_apellido} {segundo_apellido}</p> </div></tpl>',
                hiddenName : 'id_medico',
                forceSelection : true,
                typeAhead : false,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                turl:'../../../sis_ventas_facturacion/vista/medico/Medico.php',
                ttitle:'Medicos',
                tasignacion : true,           
                tname : 'id_medico',
                // tconfig:{width:1800,height:500},
                tdata:{},
                tcls:'Medico',
                pageSize : 10,
                queryDelay : 1000,
                gwidth : 150,
                minChars : 2
            },
            type : 'TrigguerCombo',
            id_grupo : 0,            
            form : true
        });
		
	this.Atributos.push({
            config:{
                name: 'cantidad',
                fieldLabel: 'Cantidad',
                allowBlank: false,
                anchor: '80%',                
                maxLength:4
            },
                type:'NumberField',                
                id_grupo:1,               
                form:true
        });
	this.Atributos.push({
            config : {
                name : 'id_unidad_medida',
                fieldLabel : 'Unidad de Medida',
                allowBlank : false,
                emptyText : 'Unidad...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_parametros/control/UnidadMedida/listarUnidadMedida',
                    id : 'id_unidad_medida',
                    root : 'datos',
                    sortInfo : {
                        field : 'codigo',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_unidad_medida', 'codigo', 'descripcion'],
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'ume.nombre'
                    }
                }),
                valueField : 'id_unidad_medida',
                displayField : 'codigo',                
                hiddenName : 'id_unidad_medida',
                forceSelection : true,
                typeAhead : false,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,
                gwidth : 130,
                minChars : 2
            },
            type : 'ComboBox',
            id_grupo : 1,            
            form : true
        });
 },
 buildGrupos: function(){
        this.Grupos = [{
                        layout: 'border',
                        border: false,
                         frame:true,
                        items:[
                          {
                            xtype: 'fieldset',
                            border: false,
                            split: true,
                            layout: 'column',
                            region: 'north',
                            autoScroll: true,
                            autoHeight: true,
                            collapseFirst : false,
                            collapsible: true,
                            width: '100%',
                            //autoHeight: true,
                            padding: '0 0 0 10',
                            items:[
                                   {
                                    bodyStyle: 'padding-right:5px;',
                                   
                                    autoHeight: true,
                                    border: false,
                                    items:[
                                       {
                                        xtype: 'fieldset',
                                        frame: true,
                                        border: false,
                                        layout: 'form', 
                                        title: 'Tipo',
                                        width: '40%',
                                        
                                        //margins: '0 0 0 5',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 0,
                                        items: [],
                                     }]
                                 },
                                 {
                                  bodyStyle: 'padding-right:5px;',
                                
                                  border: false,
                                  autoHeight: true,
                                  items: [{
                                        xtype: 'fieldset',
                                        frame: true,
                                        layout: 'form',
                                        title: ' Datos básicos ',
                                        width: '33%',
                                        border: false,
                                        //margins: '0 0 0 5',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 1,
                                        items: [],
                                     }]
                                 },
                                 
                              ]
                          },
                            this.megrid
                         ]
                 }];
        
        
    },
    onEdit:function(){
        
    	this.accionFormulario = 'EDIT';  
    	var recTem = new Array();
        recTem['id_medico'] = this.data.datos_originales.data.id_medico;
        recTem['primer_apellido'] = this.data.datos_originales.data.desc_medico;
        
        this.Cmp.id_medico.store.add(new Ext.data.Record(this.arrayToObject(this.Cmp.id_medico.store.fields.keys,recTem), this.data.datos_originales.id_medico));
        this.Cmp.id_medico.store.commitChanges();
        this.Cmp.id_medico.modificado = true;  
        
        var recTem = new Array();
        recTem['id_unidad_medida'] = this.data.datos_originales.data.id_unidad_medida;
        recTem['codigo'] = this.data.datos_originales.data.desc_unidad_medida;
        
        this.Cmp.id_unidad_medida.store.add(new Ext.data.Record(this.arrayToObject(this.Cmp.id_unidad_medida.store.fields.keys,recTem), this.data.datos_originales.id_unidad_medida));
        this.Cmp.id_unidad_medida.store.commitChanges();
        this.Cmp.id_unidad_medida.modificado = true; 
        
        	
    	this.loadForm(this.data.datos_originales);    	
    	
        //load detalle de conceptos
        this.mestore.baseParams.id_formula = this.Cmp.id_formula.getValue();
        this.mestore.load();      	
        
    },    
};
</script>
