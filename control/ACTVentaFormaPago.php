<?php
/**
*@package pXP
*@file gen-ACTVentaFormaPago.php
*@author  (jrivera)
*@date 22-10-2015 14:49:46
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTVentaFormaPago extends ACTbase{    
			
	function listarVentaFormaPago(){
		$this->objParam->defecto('ordenacion','id_venta_forma_pago');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODVentaFormaPago','listarVentaFormaPago');
		} else{
			$this->objFunc=$this->create('MODVentaFormaPago');
			
			$this->res=$this->objFunc->listarVentaFormaPago($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarVentaFormaPago(){
		$this->objFunc=$this->create('MODVentaFormaPago');	
		if($this->objParam->insertar('id_venta_forma_pago')){
			$this->res=$this->objFunc->insertarVentaFormaPago($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarVentaFormaPago($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarVentaFormaPago(){
			$this->objFunc=$this->create('MODVentaFormaPago');	
		$this->res=$this->objFunc->eliminarVentaFormaPago($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>