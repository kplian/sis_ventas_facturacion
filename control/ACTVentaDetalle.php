<?php
/**
*@package pXP
*@file gen-ACTVentaDetalle.php
*@author  (admin)
*@date 01-06-2015 09:21:07
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTVentaDetalle extends ACTbase{    
			
	function listarVentaDetalle(){
		$this->objParam->defecto('ordenacion','id_venta_detalle');

		$this->objParam->defecto('dir_ordenacion','asc');
        if ($this->objParam->getParametro('id_venta') != '') {
            $this->objParam->addFiltro("vedet.id_venta = ". $this->objParam->getParametro('id_venta'));
        }  
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODVentaDetalle','listarVentaDetalle');
		} else{
			$this->objFunc=$this->create('MODVentaDetalle');
			
			$this->res=$this->objFunc->listarVentaDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarVentaDetalleVb(){
		$this->objParam->defecto('ordenacion','id_venta_detalle');

		$this->objParam->defecto('dir_ordenacion','asc');
        if ($this->objParam->getParametro('id_venta') != '') {
            $this->objParam->addFiltro("vedet.id_venta = ". $this->objParam->getParametro('id_venta'));
        }  
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODVentaDetalle','listarVentaDetalleVb');
		} else{
			$this->objFunc=$this->create('MODVentaDetalle');
			
			$this->res=$this->objFunc->listarVentaDetalleVb($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarVentaDetalle(){
		$this->objFunc=$this->create('MODVentaDetalle');	
		if($this->objParam->insertar('id_venta_detalle')){
			$this->res=$this->objFunc->insertarVentaDetalle($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarVentaDetalle($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarVentaDetalle(){
		$this->objFunc=$this->create('MODVentaDetalle');	
		$this->res=$this->objFunc->eliminarVentaDetalle($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function actulizarVentaDetallePedido(){			
		$this->objFunc=$this->create('MODVentaDetalle');		
		$this->res=$this->objFunc->actulizarVentaDetallePedido($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarPedidoDetalleCliente(){
		$this->objParam->defecto('ordenacion','id_venta_detalle');

		$this->objParam->defecto('dir_ordenacion','asc');
        if ($this->objParam->getParametro('id_cliente') != '') {
            $this->objParam->addFiltro("vent.id_cliente = ". $this->objParam->getParametro('id_cliente'));
        }  
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODVentaDetalle','listarPedidoDetalleCliente');
		} else{
			$this->objFunc=$this->create('MODVentaDetalle');
			
			$this->res=$this->objFunc->listarPedidoDetalleCliente($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>
