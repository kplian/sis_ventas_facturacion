<?php
/**
*@package pXP
*@file    SubirArchivo.php
*@author  Rensi ARteaga Copari
*@date    27-03-2014
*@description permites subir archivos a la tabla de documento_sol
 ISSUE       FECHA       AUTHOR          DESCRIPCION
 #7          31/10/2019  EGS             CREACION
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormEstadoWfMultiple=Ext.extend(Phx.frmInterfaz,{

    constructor:function(config)
    {   this.maestro= config.data;
        Phx.vista.FormEstadoWfMultiple.superclass.constructor.call(this,config);

        this.init();
        this.iniciarEventos();
    },
    iniciarEventos: function(){
        console.log('this.maestro',this.maestro);
        this.Cmp.id_tipo_estado.store.baseParams.estados = this.maestro.id_tipo_estado;
        this.Cmp.id_funcionario_wf.store.baseParams.id_tipo_estado = this.maestro.id_tipo_estado;
        this.Cmp.id_funcionario_wf.store.baseParams.fecha = '';
        this.Cmp.id_funcionario_wf.store.baseParams.id_estado_wf = this.maestro.id_estado_wf;
        this.Cmp.data_json.setValue(this.maestro.data_json);
    },
    Atributos:[
        {
            //configuracion del componente
            config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'data_json'
            },
            type:'Field',
            form:true 
        },
    
        {
            config:{
                        name: 'id_tipo_estado',
                        hiddenName: 'id_tipo_estado',
                        fieldLabel: 'Siguiente Estado',
                        listWidth:280,
                        allowBlank: false,
                        emptyText:'Elija el estado siguiente',
                        store:new Ext.data.JsonStore(
                        {
                            url: '../../sis_workflow/control/TipoEstado/listarTipoEstado',
                            id: 'id_tipo_estado',
                            root:'datos',
                            sortInfo:{
                                field:'tipes.codigo',
                                direction:'ASC'
                            },
                            totalProperty:'total',
                            fields: ['id_tipo_estado','codigo_estado','nombre_estado','alerta','disparador','inicio','pedir_obs','tipo_asignacion','depto_asignacion'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams:{par_filtro:'tipes.nombre_estado#tipes.codigo'}
                        }),
                        valueField: 'id_tipo_estado',
                        displayField: 'codigo_estado',
                        forceSelection:true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender:true,
                        mode:'remote',
                        pageSize:50,
                        queryDelay:500,
                        anchor: '80%',
                        gwidth:220,
                        minChars:2,
                        tpl: '<tpl for="."><div class="x-combo-list-item"><p>{codigo_estado}</p>Prioridad: <strong>{nombre_estado}</strong> </div></tpl>'
                    
                    },
            type:'ComboBox',
            form:true
        },
        {
            config:{
                        name: 'id_funcionario_wf',
                        hiddenName: 'id_funcionario_wf',
                        fieldLabel: 'Funcionario Resp.',
                        allowBlank: false,
                        emptyText:'Elija un funcionario',
                        listWidth:280,
                        store:new Ext.data.JsonStore(
                        {
                            url: '../../sis_workflow/control/TipoEstado/listarFuncionarioWf',
                            id: 'id_funcionario',
                            root:'datos',
                            sortInfo:{
                                field:'prioridad',
                                direction:'ASC'
                            },
                            totalProperty:'total',
                            fields: ['id_funcionario','desc_funcionario','prioridad'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams:{par_filtro:'fun.desc_funcionario1'}
                        }),
                        valueField: 'id_funcionario',
                        displayField: 'desc_funcionario',
                        forceSelection:true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender:true,
                        mode:'remote',
                        pageSize:50,
                        queryDelay:500,
                        anchor: '80%',
                        minChars:2,
                        tpl: '<tpl for="."><div class="x-combo-list-item"><p>{desc_funcionario}</p>Prioridad: <strong>{prioridad}</strong> </div></tpl>'
                    
             },
            type:'ComboBox',
            form:true
        },
    ],
    ActSave:'../../sis_ventas_facturacion/control/Venta/siguienteEstadoMultiple',
    successSave:function(resp)
    {
        Phx.CP.loadingHide();
        Phx.CP.getPagina(this.idContenedorPadre).reload();
        this.panel.close();
    },
})
</script>
