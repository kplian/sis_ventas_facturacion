<?php
/**
*@package pXP
*@file gen-ACTSucursalAlmacen.php
*@author  (admin)
*@date 21-04-2015 07:33:41
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTSucursalAlmacen extends ACTbase{    
			
	function listarSucursalAlmacen(){
		$this->objParam->defecto('ordenacion','id_sucursal_almacen');

		$this->objParam->defecto('dir_ordenacion','asc');
        
        if ($this->objParam->getParametro('id_almacen') != '') {
            $this->objParam->addFiltro("sucalm.id_almacen = ". $this->objParam->getParametro('id_almacen'));
        }  
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODSucursalAlmacen','listarSucursalAlmacen');
		} else{
			$this->objFunc=$this->create('MODSucursalAlmacen');
			
			$this->res=$this->objFunc->listarSucursalAlmacen($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarSucursalAlmacen(){
		$this->objFunc=$this->create('MODSucursalAlmacen');	
		if($this->objParam->insertar('id_sucursal_almacen')){
			$this->res=$this->objFunc->insertarSucursalAlmacen($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarSucursalAlmacen($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarSucursalAlmacen(){
			$this->objFunc=$this->create('MODSucursalAlmacen');	
		$this->res=$this->objFunc->eliminarSucursalAlmacen($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>