<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (fprudencio)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite 
*dar el visto a solicitudes de compra
*
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.AnularVentaTab = {
    bsave:false,
    bedit:true,
    bdel:true,
    tipo_interfaz:'',
	swEstado: 'anulado',
	require: '../../../sis_ventas_facturacion/vista/venta/AnularVenta.php',
	requireclase: 'Phx.vista.AnularVenta',  
	title: 'Anular Venta',
	nombreVista: 'AnularVentaTab',
	gruposBarraTareas: [
		{
			name: 'finalizado',
			title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Finalizados</h1>',
			grupo: 0,
			height: 0
		},
		{
			name: 'anulados',
			title: '<H1 align="center"><i class="fa fa-eye"></i>Anulados</h1>',
			grupo: 1,
			height:   0
		}
	],
	
		
	//beditGroups: [1, 1],
	/*bactGroups: [0, 1],
	btestGroups: [0,0],
	bexcelGroups: [0, 1],
*/
ActList:'../../sis_ventas_facturacion/control/Venta/listarAnularVenta',
	
	//ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaExterna',
	//ActSave: '../../sis_correspondencia/control/Correspondencia/insertarCorrespondenciaExterna',
   constructor: function(config) {
	        
   
	      
	    Phx.vista.AnularVentaTab.superclass.constructor.call(this,config);
	    
	 
		
		this.init();  
		this.argumentExtraSubmit={'vista':'AnularVentaTab'};
        this.tipo_interfaz=config.tipo;
        this.store.baseParams = {'tipo': this.tipo,'estado': this.swEstado,'vista':'AnularVentaTab'};
        this.load({params: {start: 0, limit: 50}})
     	//this.iniciarEventos();
    
   },
  /* iniciarEventos(){
	  
   },*/
     
 
   	getParametrosFiltro: function () {
   	 	this.store.baseParams.estado = this.swEstado;
		
	},
	
	actualizarSegunTab: function (name, indice) {
			var data = this.getSelectedData();
       //alert (name);
         
		if (name=='finalizado'){
			this.swEstado = this.estado;
			
		}else{
		    this.swEstado = name;
			
		}
		this.getParametrosFiltro();
		this.load();
	

	},
	
	
    preparaMenu:function(n){
      	
      	Phx.vista.AnularVentaTab.superclass.preparaMenu.call(this,n);      	
		  var data = this.getSelectedData();

		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar
		
     
		 return tb
		
	},
	liberaMenu:function(){
        var tb = Phx.vista.AnularVentaTab.superclass.liberaMenu.call(this);
         // console.log('- aksdkshad');
        //console.log(tb);
       /* if(tb){
           
           this.getBoton('anular_factura').disable();
			        
        }*/
       return tb   
     }
	
	
};
</script>
