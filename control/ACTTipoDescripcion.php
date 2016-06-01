<?php
/**
*@package pXP
*@file gen-ACTTipoDescripcion.php
*@author  (admin)
*@date 23-04-2016 02:03:14
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTTipoDescripcion extends ACTbase{    
			
	function listarTipoDescripcion(){
		$this->objParam->defecto('ordenacion','id_tipo_descripcion');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		
		if ($this->objParam->getParametro('id_sucursal') != '') {
            $this->objParam->addFiltro("tde.id_sucursal = ". $this->objParam->getParametro('id_sucursal'));
        } 
		
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTipoDescripcion','listarTipoDescripcion');
		} else{
			$this->objFunc=$this->create('MODTipoDescripcion');
			
			$this->res=$this->objFunc->listarTipoDescripcion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarTipoDescripcion(){
		$this->objFunc=$this->create('MODTipoDescripcion');	
		if($this->objParam->insertar('id_tipo_descripcion')){
			$this->res=$this->objFunc->insertarTipoDescripcion($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarTipoDescripcion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarTipoDescripcion(){
			$this->objFunc=$this->create('MODTipoDescripcion');	
		$this->res=$this->objFunc->eliminarTipoDescripcion($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>