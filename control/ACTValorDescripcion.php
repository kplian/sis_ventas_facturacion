<?php
/**
*@package pXP
*@file gen-ACTValorDescripcion.php
*@author  (admin)
*@date 23-04-2016 14:24:45
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTValorDescripcion extends ACTbase{    
			
	function listarValorDescripcion(){
		$this->objParam->defecto('ordenacion','id_valor_descripcion');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		
		if ($this->objParam->getParametro('id_venta') != '') {
            $this->objParam->addFiltro("vald.id_venta = ". $this->objParam->getParametro('id_venta'));
        } 
		
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODValorDescripcion','listarValorDescripcion');
		} else{
			$this->objFunc=$this->create('MODValorDescripcion');
			
			$this->res=$this->objFunc->listarValorDescripcion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarValorDescripcion(){
		$this->objFunc=$this->create('MODValorDescripcion');	
		if($this->objParam->insertar('id_valor_descripcion')){
			$this->res=$this->objFunc->insertarValorDescripcion($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarValorDescripcion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarValorDescripcion(){
			$this->objFunc=$this->create('MODValorDescripcion');	
		$this->res=$this->objFunc->eliminarValorDescripcion($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>