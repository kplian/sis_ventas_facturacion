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
Phx.vista.VentaVendedorPedido = {    
    bsave:false,    
    require:'../../../sis_ventas_facturacion/vista/venta/VentaVendedor.php',
    requireclase:'Phx.vista.VentaVendedor',
    title:'Factura de Exportación',
    nombreVista: 'VentaVendedorPedido',
    tipo_factura:'pedido',   
    formUrl: '../../../sis_ventas_facturacion/vista/venta/FormVentaPedido.php',
	formClass:'FormVentaPedido',
	grupoDateFin: [2],
    constructor: function(config) {
        this.maestro=config.maestro;        
        this.Atributos[this.getIndAtributo('cliente_destino')].grid = true;
        Phx.vista.VentaVendedorPedido.superclass.constructor.call(this,config);
        
    } ,
    
    gruposBarraTareas:[{name:'borrador',title:'<H1 align="center"><i class="fa fa-eye"></i> En Registro</h1>',grupo:0,height:0},
                       {name:'pedido_en_proceso',title:'<H1 align="center"><i class="fa fa-paper-plane"></i> En Procesos</h1>',grupo:1,height:0},
                       {name:'pedido_finalizado',title:'<H1 align="center"><i class="fa fa-check"></i> Finalizados</h1>',grupo:2,height:0}
                       ],
                       
    actualizarSegunTab: function(name, indice){
        if(this.finCons){
        	 if (name == 'pedido_comprado'){
        	 	this.store.baseParams.fecha = this.campo_fecha.getValue().dateFormat('d/m/Y');;
        	 } else {
        	 	this.store.baseParams.fecha = '';
        	 }
             this.store.baseParams.pes_estado = name;
             this.load({params:{start:0, limit:this.tam_pag}});
           }
    },                   
    
    arrayDefaultColumHidden:['estado_reg','usuario_ai',
    'fecha_reg','fecha_mod','usr_reg','usr_mod','excento','cod_control','nroaut'],
    rowExpander: new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<br>',   
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Código de Control:&nbsp;&nbsp;</b> {cod_control}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Nro Autorización:&nbsp;&nbsp;</b> {nroaut}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Importe Excento:&nbsp;&nbsp;</b> {excento}</p>',             
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha de Registro:&nbsp;&nbsp;</b> {fecha_reg:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha Ult. Modificación:&nbsp;&nbsp;</b> {fecha_mod:date("d/m/Y")}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Creado por:&nbsp;&nbsp;</b> {usr_reg}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Modificado por:&nbsp;&nbsp;</b> {usr_mod}</p><br>'
            )
    }),
    preparaMenu:function()
    {   var rec = this.sm.getSelected();
        
        
        Phx.vista.VentaVendedorPedido.superclass.preparaMenu.call(this);
        if (rec.data.estado != 'entregado') {              
              this.getBoton('anular').enable();
                          
        } 
        
    },
    
    
    
};
</script>