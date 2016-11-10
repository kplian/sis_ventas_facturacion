<?php
/**
*@package pXP
*@file gen-ACTCliente.php
*@author  (admin)
*@date 20-04-2015 08:41:29
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTCliente extends ACTbase{    
			
	function listarCliente(){
		$this->objParam->defecto('ordenacion','id_cliente');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		if ($this->objParam->getParametro('nit') != '') {
            $this->objParam->addFiltro("cli.nit = ''". $this->objParam->getParametro('nit')."''");
        }  
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCliente','listarCliente');
		} else{
			$this->objFunc=$this->create('MODCliente');
			
			$this->res=$this->objFunc->listarCliente($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarCliente(){
		$this->objFunc=$this->create('MODCliente');

		if($this->objParam->insertar('id_cliente')){
			$this->res=$this->objFunc->insertarCliente($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCliente($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCliente(){
			$this->objFunc=$this->create('MODCliente');	
		$this->res=$this->objFunc->eliminarCliente($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>