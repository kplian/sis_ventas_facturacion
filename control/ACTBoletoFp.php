<?php
/**
*@package pXP
*@file gen-ACTBoletoFp.php
*@author  (jrivera)
*@date 26-11-2015 22:03:35
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTBoletoFp extends ACTbase{    
			
	function listarBoletoFp(){
		$this->objParam->defecto('ordenacion','id_boleto_fp');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('id_boleto') != '') {
                $this->objParam->addFiltro(" bolfp.id_boleto = " . $this->objParam->getParametro('id_boleto'));
        }
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODBoletoFp','listarBoletoFp');
		} else{
			$this->objFunc=$this->create('MODBoletoFp');
			
			$this->res=$this->objFunc->listarBoletoFp($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarBoletoFp(){
		$this->objFunc=$this->create('MODBoletoFp');	
		if($this->objParam->insertar('id_boleto_fp')){
			$this->res=$this->objFunc->insertarBoletoFp($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarBoletoFp($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarBoletoFp(){
			$this->objFunc=$this->create('MODBoletoFp');	
		$this->res=$this->objFunc->eliminarBoletoFp($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>