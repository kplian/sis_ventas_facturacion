<?php
/**
*@package pXP
*@file gen-ACTBoleto.php
*@author  (jrivera)
*@date 26-11-2015 22:03:32
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTBoleto extends ACTbase{    
			
	function listarBoleto(){
		$this->objParam->defecto('ordenacion','id_boleto');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		if($this->objParam->getParametro('id_punto_venta') != '') {
                $this->objParam->addFiltro(" bol.id_punto_venta = " . $this->objParam->getParametro('id_punto_venta'));
        }
		
		if($this->objParam->getParametro('fecha') != '') {
                $this->objParam->addFiltro(" bol.fecha = ''" . $this->objParam->getParametro('fecha')."''");
        }
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODBoleto','listarBoleto');
		} else{
			$this->objFunc=$this->create('MODBoleto');
			
			$this->res=$this->objFunc->listarBoleto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarBoleto(){
		$this->objFunc=$this->create('MODBoleto');	
		if($this->objParam->insertar('id_boleto')){
			$this->res=$this->objFunc->insertarBoleto($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarBoleto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarBoleto(){
			$this->objFunc=$this->create('MODBoleto');	
		$this->res=$this->objFunc->eliminarBoleto($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>