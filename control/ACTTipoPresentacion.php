<?php
/**
*@package pXP
*@file gen-ACTTipoPresentacion.php
*@author  (admin)
*@date 21-04-2015 09:00:49
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTTipoPresentacion extends ACTbase{    
			
	function listarTipoPresentacion(){
		$this->objParam->defecto('ordenacion','id_tipo_presentacion');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTipoPresentacion','listarTipoPresentacion');
		} else{
			$this->objFunc=$this->create('MODTipoPresentacion');
			
			$this->res=$this->objFunc->listarTipoPresentacion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarTipoPresentacion(){
		$this->objFunc=$this->create('MODTipoPresentacion');	
		if($this->objParam->insertar('id_tipo_presentacion')){
			$this->res=$this->objFunc->insertarTipoPresentacion($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarTipoPresentacion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarTipoPresentacion(){
			$this->objFunc=$this->create('MODTipoPresentacion');	
		$this->res=$this->objFunc->eliminarTipoPresentacion($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>