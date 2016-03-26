<?php
/**
*@package pXP
*@file gen-ACTProcesoVenta.php
*@author  (jrivera)
*@date 22-03-2016 21:50:14
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTProcesoVenta extends ACTbase{    
			
	function listarProcesoVenta(){
		$this->objParam->defecto('ordenacion','id_proceso_venta');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODProcesoVenta','listarProcesoVenta');
		} else{
			$this->objFunc=$this->create('MODProcesoVenta');
			
			$this->res=$this->objFunc->listarProcesoVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarProcesoVenta(){
		$this->objFunc=$this->create('MODProcesoVenta');	
		if($this->objParam->insertar('id_proceso_venta')){
			$this->res=$this->objFunc->insertarProcesoVenta($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarProcesoVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarProcesoVenta(){
			$this->objFunc=$this->create('MODProcesoVenta');	
		$this->res=$this->objFunc->eliminarProcesoVenta($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>