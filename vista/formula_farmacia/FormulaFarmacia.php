<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (fprudencio)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormulaFarmacia = {
    require:'../../../sis_ventas_facturacion/vista/formula/Formula.php',
	requireclase:'Phx.vista.Formula',
	title:'Venta',
	nombreVista: 'FormulaFarmacia',
	formUrl : '../../../sis_ventas_facturacion/vista/formula_farmacia/FormFormulaFarmacia.php',
	formClass : 'FormFormulaFarmacia',
	constructor: function(config) {	
		Phx.vista.FormulaFarmacia.superclass.constructor.call(this,config);		
   },
   successGetVariables :function (response,request) {     				  		
  		this.addElements();
		Phx.vista.FormulaFarmacia.superclass.successGetVariables.call(this,response,request); 
		this.formUrl = '../../../sis_ventas_facturacion/vista/formula_farmacia/FormFormulaFarmacia.php',
        this.formClass = 'FormFormulaFarmacia'; 
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
                gdisplayField : 'desc_medico',
                hiddenName : 'id_medico',
                tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombres} {primer_apellido} {segundo_apellido}</p> </div></tpl>',
                forceSelection : true,
                typeAhead : false,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,
                turl:'../../../sis_ventas_facturacion/vista/medico/Medico.php',
                ttitle:'Medicos',
                // tconfig:{width:1800,height:500},
                tdata:{},
                tcls:'Medico',
                gwidth : 150,
                minChars : 2,
                renderer: function(value, p, record){                    
                    return String.format('{0}', record.data['desc_medico']);
                }
            },
            type : 'TrigguerCombo',
            id_grupo : 0,
            filters : {
                pfiltro : 'med.nombre_completo',
                type : 'string'
            },
            grid : true,
            form : true,
            bottom_filter: true
        });
		
	this.Atributos.push({
			config:{
				name: 'cantidad',
				fieldLabel: 'Cantidad',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'NumberField',
				filters:{pfiltro:'form.cantidad',type:'numeric'},
				id_grupo:1,
				grid:true,
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
                displayField : 'descripcion',
                gdisplayField : 'desc_unidad_medida',
                hiddenName : 'id_unidad_medida',
                forceSelection : true,
                typeAhead : false,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,
                gwidth : 130,
                minChars : 2,
                renderer: function(value, p, record){                    
                    return String.format('{0}', record.data['desc_unidad_medida']);
                }
            },
            type : 'ComboBox',
            id_grupo : 0,
            filters : {
                pfiltro : 'ume.descripcion#ume.codigo',
                type : 'string'
            },
            grid : true,
            form : true
        });
  }
   
	
};
</script>
