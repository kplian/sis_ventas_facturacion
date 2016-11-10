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
    Phx.vista.VentaAdministradorBoa = {
        require:'../../../sis_ventas_facturacion/vista/venta/VentaAdministrador.php',
        requireclase:'Phx.vista.VentaAdministrador',
        title:'Venta',
        nombreVista: 'VentaAdministradorBoa',



        constructor: function(config) {

            Phx.vista.VentaAdministradorBoa.superclass.constructor.call(this,config);

        },

       iniciarEventos :function () {

           this.addButton('verificar_relacion',{text:'Ventas mal Relacionadas',iconCls: 'bchecklist',disabled:false,handler:this.verificarRelacion,tooltip: '<b>Diagrama Gantt de la venta</b>'});

           this.addButton('contabilizable', {
                text: 'Volver Contabilizable',
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.setContabilizable,
                tooltip: '<b>Cambia una venta no contabilizable a contabilizable</b>'
            });

           Phx.vista.VentaAdministradorBoa.superclass.iniciarEventos.call(this);
       },
        preparaMenu:function()
        {   var rec = this.sm.getSelected();
            if (rec.data.contabilizable == 'no') {
                this.getBoton('contabilizable').enable();
            } else {
                this.getBoton('contabilizable').disable();
            }

            Phx.vista.VentaAdministradorBoa.superclass.preparaMenu.call(this);

        },
        liberaMenu:function()
        {   var rec = this.sm.getSelected();
            this.getBoton('contabilizable').disable();
            Phx.vista.VentaAdministradorBoa.superclass.liberaMenu.call(this);

        },
        setContabilizable : function () {
            var rec = this.sm.getSelected();
            Ext.Ajax.request({
                url:'../../sis_ventas_facturacion/control/Venta/setContabilizable',
                params:{
                    id_venta:  rec.data.id_venta
                },
                success:this.successSave,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
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
        }





    };
</script>
