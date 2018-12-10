<?php
/**
*@package pXP
*@file gen-ACTProveedorCuentaBancoCobro.php
*@author  (m.mamani)
*@date 22-11-2018 22:19:44
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTProveedorCuentaBancoCobro extends ACTbase{    
			
	function listarProveedorCuentaBancoCobro(){
		$this->objParam->defecto('ordenacion','id_proveedor_cuenta_banco_cobro');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODProveedorCuentaBancoCobro','listarProveedorCuentaBancoCobro');
		} else{
			$this->objFunc=$this->create('MODProveedorCuentaBancoCobro');
			
			$this->res=$this->objFunc->listarProveedorCuentaBancoCobro($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarProveedorCuentaBancoCobro(){
		$this->objFunc=$this->create('MODProveedorCuentaBancoCobro');	
		if($this->objParam->insertar('id_proveedor_cuenta_banco_cobro')){
			$this->res=$this->objFunc->insertarProveedorCuentaBancoCobro($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarProveedorCuentaBancoCobro($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarProveedorCuentaBancoCobro(){
			$this->objFunc=$this->create('MODProveedorCuentaBancoCobro');	
		$this->res=$this->objFunc->eliminarProveedorCuentaBancoCobro($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>