<?php
/**
*@package pXP
*@file gen-ACTPuntoVenta.php
*@author  (jrivera)
*@date 07-10-2015 21:02:00
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTPuntoVenta extends ACTbase{    
			
	function listarPuntoVenta(){
		$this->objParam->defecto('ordenacion','id_punto_venta');
		if ($this->objParam->getParametro('id_sucursal') != '') {
            $this->objParam->addFiltro(" puve.id_sucursal = " .  $this->objParam->getParametro('id_sucursal'));
        }
		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODPuntoVenta','listarPuntoVenta');
		} else{
			$this->objFunc=$this->create('MODPuntoVenta');
			
			$this->res=$this->objFunc->listarPuntoVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarPuntoVenta(){
		$this->objFunc=$this->create('MODPuntoVenta');	
		if($this->objParam->insertar('id_punto_venta')){
			$this->res=$this->objFunc->insertarPuntoVenta($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarPuntoVenta($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarPuntoVenta(){
			$this->objFunc=$this->create('MODPuntoVenta');	
		$this->res=$this->objFunc->eliminarPuntoVenta($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>