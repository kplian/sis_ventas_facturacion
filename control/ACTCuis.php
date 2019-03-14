<?php
/**
*@package pXP
*@file gen-ACTCuis.php
*@author  (admin)
*@date 21-01-2019 15:18:39
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTCuis extends ACTbase{    
			
	function listarCuis(){
		$this->objParam->defecto('ordenacion','id_cuis');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCuis','listarCuis');
		} else{
			$this->objFunc=$this->create('MODCuis');
			
			$this->res=$this->objFunc->listarCuis($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarCuis(){
		$this->objFunc=$this->create('MODCuis');	
		if($this->objParam->insertar('id_cuis')){
			$this->res=$this->objFunc->insertarCuis($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCuis($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCuis(){
			$this->objFunc=$this->create('MODCuis');	
		$this->res=$this->objFunc->eliminarCuis($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>