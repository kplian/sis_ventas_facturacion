<?php
/**
*@package pXP
*@file gen-ACTTipoVenta.php
*@author  (jrivera)
*@date 22-03-2016 15:29:00
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTTipoVenta extends ACTbase{    
			
	function listarTipoVenta(){
		$this->objParam->defecto('ordenacion','id_tipo_venta');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTipoVenta','listarTipoVenta');
		} else{
			$this->objFunc=$this->create('MODTipoVenta');
			
			$this->res=$this->objFunc->listarTipoVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarTipoVenta(){
		$this->objFunc=$this->create('MODTipoVenta');	
		if($this->objParam->insertar('id_tipo_venta')){
			$this->res=$this->objFunc->insertarTipoVenta($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarTipoVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarTipoVenta(){
			$this->objFunc=$this->create('MODTipoVenta');	
		$this->res=$this->objFunc->eliminarTipoVenta($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>