<?php
/**
*@package pXP
*@file gen-ACTTemporalData.php
*@author  (eddy.gutierrez)
*@date 06-11-2018 20:39:08
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTTemporalData extends ACTbase{    
			
	function listarTemporalData(){
		$this->objParam->defecto('ordenacion','id_dato_temporal');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTemporalData','listarTemporalData');
		} else{
			$this->objFunc=$this->create('MODTemporalData');
			
			$this->res=$this->objFunc->listarTemporalData($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarTemporalData(){
		$this->objFunc=$this->create('MODTemporalData');	
		if($this->objParam->insertar('id_dato_temporal')){
			$this->res=$this->objFunc->insertarTemporalData($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarTemporalData($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarTemporalData(){
			$this->objFunc=$this->create('MODTemporalData');	
		$this->res=$this->objFunc->eliminarTemporalData($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>