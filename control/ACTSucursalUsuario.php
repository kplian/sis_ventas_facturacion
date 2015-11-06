<?php
/**
*@package pXP
*@file gen-ACTSucursalUsuario.php
*@author  (admin)
*@date 21-04-2015 07:33:37
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTSucursalUsuario extends ACTbase{    
			
	function listarSucursalUsuario(){
		$this->objParam->defecto('ordenacion','id_sucursal_usuario');

		$this->objParam->defecto('dir_ordenacion','asc');
        
        if ($this->objParam->getParametro('id_sucursal') != '') {
            $this->objParam->addFiltro("sucusu.id_sucursal = ". $this->objParam->getParametro('id_sucursal'));
        }
		
		if ($this->objParam->getParametro('id_punto_venta') != '') {
            $this->objParam->addFiltro("sucusu.id_punto_venta = ". $this->objParam->getParametro('id_punto_venta'));
        }  
		  
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODSucursalUsuario','listarSucursalUsuario');
		} else{
			$this->objFunc=$this->create('MODSucursalUsuario');
			
			$this->res=$this->objFunc->listarSucursalUsuario($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarSucursalUsuario(){
		$this->objFunc=$this->create('MODSucursalUsuario');	
		if($this->objParam->insertar('id_sucursal_usuario')){
			$this->res=$this->objFunc->insertarSucursalUsuario($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarSucursalUsuario($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarSucursalUsuario(){
			$this->objFunc=$this->create('MODSucursalUsuario');	
		$this->res=$this->objFunc->eliminarSucursalUsuario($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>