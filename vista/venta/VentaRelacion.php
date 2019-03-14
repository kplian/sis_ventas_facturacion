<?php
/**
 *@package pXP
 *@file gen-SistemaDist.php
 *@author  (rarteaga)
 *@date 20-09-2011 10:22:05
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.VentaRelacion = {
        bsave:false,
        bdel:false,
        require:'../../../sis_ventas_facturacion/vista/venta/Venta.php',
        requireclase:'Phx.vista.Venta',
        title:'Venta',
        nombreVista: 'VentaRelacion',
        bnew:false,
        bedit:false,
        tipo_usuario : 'cajero',

        constructor: function(config) {
            this.config = config;
            this.maestro=config.maestro;
            this.initButtons=[this.cmbTipoVenta,this.cmbGestion];

            Phx.vista.VentaRelacion.superclass.constructor.call(this,config);
            this.tipo_factura= '';
        } ,

        successGetVariables :function (response,request) {
            var respuesta = JSON.parse(response.responseText);

            if('datos' in respuesta){
                this.variables_globales = respuesta.datos;
            }

            if (this.variables_globales.vef_tiene_punto_venta === 'true') {
                this.Atributos.push({
                    config: {
                        name: 'id_punto_venta',
                        fieldLabel: 'Punto de Venta',
                        allowBlank: false,
                        emptyText: 'Elija un Pun...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_ventas_facturacion/control/PuntoVenta/listarPuntoVenta',
                            id: 'id_punto_venta',
                            root: 'datos',
                            sortInfo: {
                                field: 'nombre',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_punto_venta', 'nombre', 'codigo'],
                            remoteSort: true,
                            baseParams: {par_filtro: 'puve.nombre#puve.codigo'}
                        }),
                        valueField: 'id_punto_venta',
                        displayField: 'nombre',
                        gdisplayField: 'nombre_punto_venta',
                        hiddenName: 'id_punto_venta',
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'remote',
                        pageSize: 15,
                        queryDelay: 1000,
                        gwidth: 150,
                        minChars: 2,
                        renderer : function(value, p, record) {
                            return String.format('{0}', record.data['nombre_punto_venta']);
                        }
                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    filters: {pfiltro: 'puve.nombre',type: 'string'},
                    grid: true,
                    form: false
                });
            }

            //llama al constructor de la clase padre
            Phx.vista.Venta.superclass.constructor.call(this,request.arguments);

            this.init();
            //this.load({params:{start:0, limit:this.tam_pag}});
            this.iniciarEventos();
            this.store.baseParams.pes_estado = 'finalizado';

            this.addButton('diagrama_gantt',{text:'Gant',iconCls: 'bgantt',disabled:true,handler:this.diagramGantt,tooltip: '<b>Diagrama Gantt de la venta</b>'});
            this.addButton('verificar_relacion',{text:'Ventas mal Relacionadas',iconCls: 'bchecklist',disabled:false,handler:this.verificarRelacion,tooltip: '<b>Diagrama Gantt de la venta</b>'});

        },
        preparaMenu:function()
        {   var rec = this.sm.getSelected();
            this.getBoton('diagrama_gantt').enable();

            Phx.vista.VentaRelacion.superclass.preparaMenu.call(this);

        },
        onButtonAct:function(){
            if (this.cmbTipoVenta.getValue()) {
                Phx.vista.VentaRelacion.superclass.onButtonAct.call(this);

            } else {
                alert('Debe tener un tipo de venta seleccionado para poder actualizar la informacion');
            }


        },
        iniciarEventos : function () {
            this.cmbTipoVenta.on('select',function (c, r, i) {
                this.tipo_factura = r.data.codigo;
                this.seleccionarPuntoVentaSucursal();
                this.cmbTipoVenta.disable();
            }, this);

            this.cmbGestion.store.load({params:{start:0,limit:100},
                callback : function (r) {

                    var year = new Date().getFullYear();
                    var index = this.cmbGestion.store.find('gestion', year);
                    var id_gestion = this.cmbGestion.store.getAt(index).data.id_gestion;
                    this.cmbGestion.setValue(id_gestion);
                    this.cmbGestion.setRawValue(year);
                    this.cmbGestion.fireEvent('select',this.cmbGestion, this.cmbGestion.store.getById(id_gestion));
                    this.store.baseParams.id_gestion = id_gestion;

                }, scope : this
            });
        },
        verificarRelacion : function () {
            if (this.variables_globales.vef_tiene_punto_venta === 'true') {
                Ext.Ajax.request({
                    url: '../../sis_ventas_facturacion/control/Venta/verificarRelacion',
                    params: {
                        id_punto_venta: this.variables_globales.id_punto_venta,
                        tipo_factura : this.tipo_factura

                    },
                    success: this.successVerificarRelacion,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            } else {
                Ext.Ajax.request({
                    url: '../../sis_ventas_facturacion/control/Venta/verificarRelacion',
                    params: {
                        id_sucursal: this.variables_globales.id_sucursal,
                        tipo_factura : this.tipo_factura

                    },
                    success: this.successVerificarRelacion,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });

            }
        },
        successVerificarRelacion:function(resp){

            var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

            if (objRes.ROOT.datos.ventas) {
                Ext.Msg.alert('Atencion',"Las siguientes ventas no se encuentran bien relacionadas : <br>" + objRes.ROOT.datos.ventas).getDialog().setSize(350,300);
            } else {
                Ext.Msg.alert('Atencion',"No se encontraron ventas mal relacionadas").getDialog().setSize(350,300);
            }

            Phx.CP.loadingHide();
        },
        south:{
            url:'../../../sis_ventas_facturacion/vista/venta_detalle/VentaDetalle.php',
            title:'Venta Detalle',
            height:'50%',
            cls:'VentaDetalle',
            collapsed: true
        },
        liberaMenu:function()
        {
            this.getBoton('diagrama_gantt').disable();
            Phx.vista.VentaRelacion.superclass.liberaMenu.call(this);
        },
        cmbTipoVenta:new Ext.form.ComboBox({
            store: new Ext.data.JsonStore({

                url: '../../sis_ventas_facturacion/control/TipoVenta/listarTipoVenta',
                id: 'codigo',
                root: 'datos',
                sortInfo:{
                    field: 'nombre',
                    direction: 'ASC'
                },
                totalProperty: 'total',
                fields: [
                    {name:'codigo', type: 'string'},
                    {name:'nombre', type: 'string'}
                ],
                remoteSort: true,
                baseParams:{start:0,limit:10}
            }),
            displayField: 'nombre',
            valueField: 'codigo',
            typeAhead: false,
            mode: 'remote',
            triggerAction: 'all',
            emptyText:'Tipo Venta...',
            selectOnFocus:true,
            width:135,
            resizable : true
        }),
        cmbGestion : new Ext.form.ComboBox({
            store: new Ext.data.JsonStore({

                url: '../../sis_parametros/control/Gestion/listarGestion',
                id: 'id_gestion',
                root: 'datos',
                sortInfo:{
                    field: 'gestion',
                    direction: 'DESC'
                },
                totalProperty: 'total',
                fields: [
                    {name:'id_gestion'},
                    {name:'gestion', type: 'string'},
                    {name:'estado_reg', type: 'string'}
                ],
                remoteSort: true,
                baseParams:{start:0,limit:10}
            }),
            displayField: 'gestion',
            valueField: 'gestion',
            typeAhead: true,
            mode: 'remote',
            triggerAction: 'all',
            emptyText:'Gestión...',
            selectOnFocus:true,
            width:135
        })


    };
</script>
