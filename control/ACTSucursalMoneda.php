<?php
/**
*@package pXP
*@file gen-ACTSucursalMoneda.php
*@author  (admin)
*@date 22-09-2015 06:11:27
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTSucursalMoneda extends ACTbase{    
			
	function listarSucursalMoneda(){
		$this->objParam->defecto('ordenacion','id_sucursal_moneda');

        if ($this->objParam->getParametro('id_sucursal') != '') {
            $this->objParam->addFiltro(" sucmon.id_sucursal = " .  $this->objParam->getParametro('id_sucursal'));
        }
        
		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODSucursalMoneda','listarSucursalMoneda');
		} else{
			$this->objFunc=$this->create('MODSucursalMoneda');
			
			$this->res=$this->objFunc->listarSucursalMoneda($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarSucursalMoneda(){
		$this->objFunc=$this->create('MODSucursalMoneda');	
		if($this->objParam->insertar('id_sucursal_moneda')){
			$this->res=$this->objFunc->insertarSucursalMoneda($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarSucursalMoneda($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarSucursalMoneda(){
			$this->objFunc=$this->create('MODSucursalMoneda');	
		$this->res=$this->objFunc->eliminarSucursalMoneda($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>