<?php
/**
*@package pXP
*@file gen-ACTActividadEconomica.php
*@author  (jrivera)
*@date 06-10-2015 21:23:23
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTActividadEconomica extends ACTbase{    
			
	function listarActividadEconomica(){
		$this->objParam->defecto('ordenacion','id_actividad_economica');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODActividadEconomica','listarActividadEconomica');
		} else{
			$this->objFunc=$this->create('MODActividadEconomica');
			
			$this->res=$this->objFunc->listarActividadEconomica($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarActividadEconomica(){
		$this->objFunc=$this->create('MODActividadEconomica');	
		if($this->objParam->insertar('id_actividad_economica')){
			$this->res=$this->objFunc->insertarActividadEconomica($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarActividadEconomica($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarActividadEconomica(){
			$this->objFunc=$this->create('MODActividadEconomica');	
		$this->res=$this->objFunc->eliminarActividadEconomica($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>