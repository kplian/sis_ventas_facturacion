<?php
/**
*@package pXP
*@file gen-ACTCuf.php
*@author  (admin)
*@date 21-01-2019 15:18:42
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTCuf extends ACTbase{    
			
	function listarCuf(){
		$this->objParam->defecto('ordenacion','id_cuf');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCuf','listarCuf');
		} else{
			$this->objFunc=$this->create('MODCuf');
			
			$this->res=$this->objFunc->listarCuf($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarCuf(){
		$this->objFunc=$this->create('MODCuf');	
		if($this->objParam->insertar('id_cuf')){
			$this->res=$this->objFunc->insertarCuf($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCuf($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCuf(){
			$this->objFunc=$this->create('MODCuf');	
		$this->res=$this->objFunc->eliminarCuf($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>