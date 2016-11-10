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
Phx.vista.VentaFarmacia = {
    require:'../../../sis_ventas_facturacion/vista/venta/Venta.php',
	requireclase:'Phx.vista.Venta',
	title:'Venta',
	nombreVista: 'VentaFarmacia',
	
	constructor: function(config) {			
		Phx.vista.VentaFarmacia.superclass.constructor.call(this,config);
		 
        
  },
  
  addElements : function () {

      this.Atributos.push({
          config:{
              name: 'vendedor_medico',
              fieldLabel: 'Vendedor/Medico',
              allowBlank: false,
              anchor: '80%',
              gwidth: 120
          },
          type:'TextField',
          filters:{pfiltro:'mu.nombre',type:'string'},
          grid:true,
          form:false,
          bottom_filter: true
      });
  	this.Atributos.push({
			config:{
				name: 'a_cuenta',
				fieldLabel: 'A cuenta',
				allowBlank: false,
				anchor: '80%',
				gwidth: 120,
				maxLength:5
			},
				type:'NumberField',
				filters:{pfiltro:'ven.a_cuenta',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		});
	
	this.Atributos.push({
			config:{
				name: 'forma_pedido',
				fieldLabel: 'Forma Pedido',
				allowBlank: false,
				anchor: '80%',
				gwidth: 120,
				maxLength:5
			},
				type:'TextField',
				filters:{pfiltro:'ven.forma_pedido',type:'varchar'},
				id_grupo:1,
				grid:true,
				form:false
		});
		
	this.Atributos.push({
			config:{
				name: 'fecha_estimada_entrega',
				fieldLabel: 'Fecha de Entrega Estimada',
				allowBlank: false,				
				gwidth: 150,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'ven.fecha_estimada_entrega',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		});
  }
  
   
	
};
</script>
