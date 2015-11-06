<?php
/**
*@package pXP
*@file gen-ACTPuntoVentaProducto.php
*@author  (jrivera)
*@date 07-10-2015 21:02:03
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTPuntoVentaProducto extends ACTbase{    
			
	function listarPuntoVentaProducto(){
		$this->objParam->defecto('ordenacion','id_punto_venta_producto');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		if ($this->objParam->getParametro('id_punto_venta') != '') {
            $this->objParam->addFiltro("puvepro.id_punto_venta = ". $this->objParam->getParametro('id_punto_venta'));
        }   
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODPuntoVentaProducto','listarPuntoVentaProducto');
		} else{
			$this->objFunc=$this->create('MODPuntoVentaProducto');
			
			$this->res=$this->objFunc->listarPuntoVentaProducto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarPuntoVentaProducto(){
		$this->objFunc=$this->create('MODPuntoVentaProducto');	
		if($this->objParam->insertar('id_punto_venta_producto')){
			$this->res=$this->objFunc->insertarPuntoVentaProducto($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarPuntoVentaProducto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarPuntoVentaProducto(){
			$this->objFunc=$this->create('MODPuntoVentaProducto');	
		$this->res=$this->objFunc->eliminarPuntoVentaProducto($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>